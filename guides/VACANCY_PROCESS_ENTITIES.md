

# Vacancy Process Support Entities - Documentation

## Overview

This document describes the 6 additional entities created to support the **Vacancy Creation Process** with proper audit trails, version management, rejection tracking, and analytics capabilities.

These entities extend the basic Process Flow System to provide **domain-specific data storage** for the hiring workflow.

---

## Entity Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                   VACANCY CREATION PROCESS                   │
│                                                               │
│  ┌──────────────┐    ┌───────────────────┐    ┌──────────┐ │
│  │ ORGANIZATION │◄───│  VACANCY_DRAFT    │◄───│  TASK_   │ │
│  │   VACANCY    │    │  (versions 1,2,3) │    │ FLOW_    │ │
│  │  (final)     │    └───────────────────┘    │ INSTANCE │ │
│  └──────────────┘                              └──────────┘ │
│         ▲                                                     │
│         │                                                     │
│         ├──────────┬──────────────┬────────────┬────────────┤
│         │          │              │            │            │
│  ┌──────┴─────┐ ┌──┴──────────┐ ┌─┴─────────┐ ┌┴──────────┐│
│  │  APPROVAL  │ │  REJECTION  │ │ REVISION  │ │   TASK    ││
│  │  RECORD    │ │   REASON    │ │  HISTORY  │ │   DATA    ││
│  └────────────┘ └─────────────┘ └───────────┘ └───────────┘│
│         │                                            │        │
│         └────────────────┬───────────────────────────┘        │
│                          │                                    │
│                   ┌──────┴──────────┐                        │
│                   │  PUBLICATION    │                        │
│                   │    RECORD       │                        │
│                   └─────────────────┘                        │
└─────────────────────────────────────────────────────────────┘
```

---

## Entities

### 1. VACANCY_DRAFT

**Purpose:** Stores draft versions of vacancies before final approval

**Why Needed:**
- Currently, ORGANIZATION_VACANCY is updated in-place during drafting
- Rejections would overwrite draft data, losing revision history
- No way to track what changed between review cycles
- Can't revert to previous version

**Key Features:**
- **Version Control**: Each draft gets a sequential version number (1, 2, 3...)
- **Status Tracking**: DRAFT → SUBMITTED → UNDER_REVIEW → APPROVED/REJECTED
- **Snapshot Storage**: Stores complete snapshot of vacancy at each version
- **Current Flag**: Marks which version is currently active

**Schema:**

| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| vacancy_id | UUID | FK to ORGANIZATION_VACANCY |
| flow_instance_id | UUID | FK to TASK_FLOW_INSTANCE |
| organization_id | UUID | FK to ORGANIZATION |
| position_id | UUID | FK to POPULAR_ORGANIZATION_POSITION |
| created_by | UUID | FK to PERSON |
| **draft_version** | INTEGER | Version number (1, 2, 3...) |
| **draft_status** | ENUM | DRAFT, SUBMITTED, UNDER_REVIEW, APPROVED, REJECTED |
| **is_current_draft** | BOOLEAN | TRUE if this is the active version |
| title_draft | TEXT | Vacancy title in this draft |
| description_draft | TEXT | Description in this draft |
| requirements_draft | TEXT | Requirements in this draft |
| responsibilities_draft | TEXT | Responsibilities in this draft |
| number_of_openings_draft | INTEGER | Number of openings |
| opening_date_draft | DATE | Planned opening date |
| closing_date_draft | DATE | Planned closing date |
| min_salary_draft | NUMBER | Minimum salary |
| max_salary_draft | NUMBER | Maximum salary |
| employment_type_draft | ENUM | Full-time, Part-time, etc. |
| is_urgent_draft | BOOLEAN | Urgency flag |
| draft_notes | TEXT | Internal notes |
| last_modified_by | UUID | Who last modified |
| submitted_at | DATETIME | When submitted for review |
| approved_at | DATETIME | When approved |
| rejected_at | DATETIME | When rejected |

**Usage Example:**

```php
// Create initial draft (version 1)
$draftId = createVacancyDraft([
    'vacancy_id' => $vacancyId,
    'flow_instance_id' => $flowInstanceId,
    'organization_id' => $orgId,
    'position_id' => $positionId,
    'created_by' => $userId,
    'draft_version' => 1,
    'draft_status' => 'DRAFT',
    'is_current_draft' => true,
    'title_draft' => 'Senior Software Engineer',
    'max_salary_draft' => 120000,
    'min_salary_draft' => 90000,
    // ... other fields
]);

// After HR rejection, create version 2
$draftId2 = createVacancyDraft([
    'vacancy_id' => $vacancyId,
    'flow_instance_id' => $flowInstanceId,
    'draft_version' => 2,  // Increment version
    'draft_status' => 'DRAFT',
    'is_current_draft' => true,  // Mark as current
    'title_draft' => 'Senior Software Engineer',
    'max_salary_draft' => 100000,  // Revised salary
    'min_salary_draft' => 80000,   // Revised salary
    // ... other fields
]);

// Mark previous version as not current
updateVacancyDraft($draftId, ['is_current_draft' => false]);
```

**Queries:**

```sql
-- Get all draft versions for a vacancy
SELECT draft_version, draft_status, max_salary_draft, submitted_at, rejected_at
FROM vacancy_draft
WHERE vacancy_id = 'vacancy-uuid'
ORDER BY draft_version DESC;

-- Get current active draft
SELECT * FROM vacancy_draft
WHERE vacancy_id = 'vacancy-uuid' AND is_current_draft = 1;

-- Compare version 1 vs version 2
SELECT
    v1.max_salary_draft as v1_max_salary,
    v2.max_salary_draft as v2_max_salary,
    (v2.max_salary_draft - v1.max_salary_draft) as salary_change
FROM vacancy_draft v1
JOIN vacancy_draft v2 ON v1.vacancy_id = v2.vacancy_id
WHERE v1.vacancy_id = 'vacancy-uuid'
  AND v1.draft_version = 1
  AND v2.draft_version = 2;
```

---

### 2. VACANCY_APPROVAL_RECORD

**Purpose:** Tracks all approval decisions in the vacancy creation workflow

**Why Needed:**
- Process has 3 approval steps (HR, Finance, Dept Head)
- Current system only stores latest action in `task_instance.completion_action`
- No single view of "who approved what and when"
- Compliance requires immutable approval records

**Key Features:**
- **Approval Chain**: Records each step in the approval hierarchy
- **Sequence Tracking**: Knows order of approvals (1st, 2nd, 3rd)
- **Decision Capture**: Stores APPROVED, REJECTED, NEEDS_REVISION, etc.
- **Rationale Recording**: Why the decision was made
- **Compliance Support**: Immutable records with sign-off dates

**Schema:**

| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| flow_instance_id | UUID | FK to TASK_FLOW_INSTANCE |
| task_instance_id | UUID | FK to TASK_INSTANCE |
| vacancy_id | UUID | FK to ORGANIZATION_VACANCY |
| node_id | UUID | FK to PROCESS_NODE (which step) |
| approved_by | UUID | FK to PERSON |
| **approval_date** | DATETIME | When decision was made |
| **approval_action** | ENUM | APPROVED, REJECTED, NEEDS_REVISION, CONDITIONAL_APPROVAL |
| **approval_sequence** | INTEGER | Order in chain (1, 2, 3) |
| **approval_step_name** | TEXT | e.g., "HR Review", "Finance Approval" |
| decision_rationale | TEXT | Why this decision was made |
| approval_comments | TEXT | Additional comments |
| conditions_met | TEXT | Which conditions were satisfied |
| signed_off_date | DATETIME | Formal sign-off date |
| is_final_approval | BOOLEAN | TRUE if last approval in chain |
| approval_level | ENUM | MANAGER, DIRECTOR, EXECUTIVE, etc. |
| delegation_from | UUID | If delegated, original approver |

**Usage Example:**

```php
// Record HR approval (sequence 1)
createApprovalRecord([
    'flow_instance_id' => $flowInstanceId,
    'task_instance_id' => $hrTaskId,
    'vacancy_id' => $vacancyId,
    'node_id' => $hrReviewNodeId,
    'approved_by' => $hrManagerId,
    'approval_date' => date('Y-m-d H:i:s'),
    'approval_action' => 'APPROVED',
    'approval_sequence' => 1,
    'approval_step_name' => 'HR Review',
    'decision_rationale' => 'Vacancy details comply with policies',
    'approval_level' => 'MANAGER'
]);

// Record Finance approval (sequence 2)
createApprovalRecord([
    'flow_instance_id' => $flowInstanceId,
    'task_instance_id' => $financeTaskId,
    'vacancy_id' => $vacancyId,
    'node_id' => $financeApprovalNodeId,
    'approved_by' => $financeManagerId,
    'approval_date' => date('Y-m-d H:i:s'),
    'approval_action' => 'APPROVED',
    'approval_sequence' => 2,
    'approval_step_name' => 'Finance Approval',
    'decision_rationale' => 'Budget available for Q4',
    'approval_level' => 'DIRECTOR'
]);

// Record Department Head approval (sequence 3, final)
createApprovalRecord([
    // ...
    'approval_sequence' => 3,
    'is_final_approval' => true  // Last in chain
]);
```

**Queries:**

```sql
-- Get complete approval history for a vacancy
SELECT
    approval_sequence,
    approval_step_name,
    p.name as approver_name,
    approval_action,
    approval_date,
    decision_rationale
FROM vacancy_approval_record var
JOIN person p ON var.approved_by = p.id
WHERE var.vacancy_id = 'vacancy-uuid'
ORDER BY approval_sequence ASC;

-- Find all Finance rejections
SELECT var.*, v.title
FROM vacancy_approval_record var
JOIN organization_vacancy v ON var.vacancy_id = v.id
WHERE var.approval_step_name = 'Finance Approval'
  AND var.approval_action = 'REJECTED';

-- Average approval time by step
SELECT
    approval_step_name,
    COUNT(*) as approvals,
    ROUND(AVG(JULIANDAY(approval_date) - JULIANDAY(ti.created_at)) * 24, 1) as avg_hours
FROM vacancy_approval_record var
JOIN task_instance ti ON var.task_instance_id = ti.id
GROUP BY approval_step_name;
```

---

### 3. VACANCY_REJECTION_REASON

**Purpose:** Tracks rejection reasons and required revisions

**Why Needed:**
- When HR/Finance/Dept Head rejects, reason is only in unstructured `completion_comments`
- Can't analyze rejection patterns (e.g., "30% are salary-related")
- No way to track what revisions were requested
- Can't identify training needs or process improvements

**Key Features:**
- **Structured Reasons**: Predefined categories (COMPLIANCE_ISSUE, SALARY_CONCERNS, BUDGET_CONSTRAINT, etc.)
- **Revision Tracking**: JSON array of specific items to fix
- **Cycle Counting**: Track 1st, 2nd, 3rd rejection...
- **Deadline Management**: When revisions are due
- **Resolution Tracking**: When/how rejection was addressed

**Schema:**

| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| flow_instance_id | UUID | FK to TASK_FLOW_INSTANCE |
| task_instance_id | UUID | FK to TASK_INSTANCE |
| vacancy_id | UUID | FK to ORGANIZATION_VACANCY |
| draft_id | UUID | FK to VACANCY_DRAFT |
| rejected_by | UUID | FK to PERSON |
| **rejection_date** | DATETIME | When rejected |
| **rejection_reason** | ENUM | Primary reason code |
| **rejection_category** | ENUM | Content, Budget, Compliance, Strategic |
| **detailed_feedback** | TEXT | Full explanation |
| **required_revisions** | TEXT | JSON array of items to fix |
| **revision_deadline** | DATETIME | When revisions due |
| **is_critical** | BOOLEAN | Blocks progress if true |
| **revision_number** | INTEGER | 1st, 2nd, 3rd rejection... |
| rejected_at_step | TEXT | Which step rejected |
| resolved_date | DATETIME | When addressed |
| resolution_notes | TEXT | How resolved |

**Rejection Reasons (Enum):**
- `COMPLIANCE_ISSUE` - Legal or policy violation
- `SALARY_CONCERNS` - Salary out of range
- `MISSING_INFO` - Incomplete information
- `MARKET_ANALYSIS` - Needs market research
- `BUDGET_CONSTRAINT` - Budget not available
- `ROLE_CLARITY` - Role unclear or overlapping
- `OTHER` - Other reason

**Usage Example:**

```php
// Record HR rejection
createRejectionReason([
    'flow_instance_id' => $flowInstanceId,
    'task_instance_id' => $hrTaskId,
    'vacancy_id' => $vacancyId,
    'draft_id' => $draftId,
    'rejected_by' => $hrManagerId,
    'rejection_date' => date('Y-m-d H:i:s'),
    'rejection_reason' => 'SALARY_CONCERNS',
    'rejection_category' => 'Budget',
    'detailed_feedback' => 'Maximum salary of $120k exceeds market rate for this position by 20%',
    'required_revisions' => json_encode([
        'Reduce max_salary to $100k',
        'Provide market analysis justification if keeping $120k'
    ]),
    'revision_deadline' => date('Y-m-d H:i:s', strtotime('+48 hours')),
    'is_critical' => true,
    'revision_number' => 1,
    'rejected_at_step' => 'HR_REVIEW'
]);

// Mark as resolved
updateRejectionReason($rejectionId, [
    'resolved_date' => date('Y-m-d H:i:s'),
    'resolution_notes' => 'Reduced max_salary to $100k, added market analysis'
]);
```

**Queries:**

```sql
-- Rejection analytics: most common reasons
SELECT
    rejection_reason,
    COUNT(*) as count,
    ROUND(100.0 * COUNT(*) / (SELECT COUNT(*) FROM vacancy_rejection_reason), 1) as percentage
FROM vacancy_rejection_reason
GROUP BY rejection_reason
ORDER BY count DESC;

-- Find vacancies rejected multiple times
SELECT
    v.title,
    COUNT(*) as rejection_count,
    GROUP_CONCAT(DISTINCT vrr.rejection_reason) as reasons
FROM vacancy_rejection_reason vrr
JOIN organization_vacancy v ON vrr.vacancy_id = v.id
GROUP BY vrr.vacancy_id
HAVING rejection_count > 1
ORDER BY rejection_count DESC;

-- Average time to resolve rejections
SELECT
    rejection_reason,
    ROUND(AVG(JULIANDAY(resolved_date) - JULIANDAY(rejection_date)) * 24, 1) as avg_resolution_hours
FROM vacancy_rejection_reason
WHERE resolved_date IS NOT NULL
GROUP BY rejection_reason;

-- Critical unresolved rejections
SELECT
    v.title,
    vrr.rejection_reason,
    vrr.revision_deadline,
    ROUND((JULIANDAY('now') - JULIANDAY(vrr.revision_deadline)) * 24, 1) as hours_overdue
FROM vacancy_rejection_reason vrr
JOIN organization_vacancy v ON vrr.vacancy_id = v.id
WHERE vrr.is_critical = 1
  AND vrr.resolved_date IS NULL
  AND vrr.revision_deadline < datetime('now')
ORDER BY hours_overdue DESC;
```

---

### 4. VACANCY_REVISION_HISTORY

**Purpose:** Tracks field-level changes made to vacancy during the process

**Why Needed:**
- Auditors need to see exactly WHAT changed and WHY
- Can't currently answer "Show me all salary changes"
- No before/after comparison capability
- Compliance requirement for tracking modifications

**Key Features:**
- **Field-Level Tracking**: Records which specific field changed
- **Before/After Values**: Stores old and new values
- **Change Context**: Why it changed and who triggered it
- **Sequential Numbering**: Revision 1, 2, 3...
- **Triggered Events**: Links changes to approval/rejection events

**Schema:**

| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| vacancy_id | UUID | FK to ORGANIZATION_VACANCY |
| flow_instance_id | UUID | FK to TASK_FLOW_INSTANCE |
| draft_id | UUID | FK to VACANCY_DRAFT |
| changed_by | UUID | FK to PERSON |
| **revision_number** | INTEGER | Sequential revision |
| **changed_at** | DATETIME | When changed |
| **field_changed** | TEXT | Which field (e.g., "max_salary") |
| **field_type** | ENUM | text, number, date, enum, boolean |
| **old_value** | TEXT | Value before change |
| **new_value** | TEXT | Value after change |
| **change_reason** | TEXT | Why changed |
| **triggered_by** | TEXT | Event that caused change |
| **change_type** | ENUM | CREATE, UPDATE, DELETE |
| changed_at_step | TEXT | Which process step |

**Usage Example:**

```php
// After HR rejection, record salary change
createRevisionHistory([
    'vacancy_id' => $vacancyId,
    'flow_instance_id' => $flowInstanceId,
    'draft_id' => $draftId2,  // New draft version
    'changed_by' => $userId,
    'revision_number' => 1,
    'changed_at' => date('Y-m-d H:i:s'),
    'field_changed' => 'max_salary',
    'field_type' => 'number',
    'old_value' => '120000',
    'new_value' => '100000',
    'change_reason' => 'HR review found salary 20% above market rate',
    'triggered_by' => 'HR_REVIEW rejection',
    'change_type' => 'UPDATE',
    'changed_at_step' => 'DRAFT_VACANCY'
]);

// Record title clarification
createRevisionHistory([
    // ...
    'field_changed' => 'title',
    'field_type' => 'text',
    'old_value' => 'Software Engineer',
    'new_value' => 'Senior Software Engineer',
    'change_reason' => 'Department head requested seniority level in title',
    'triggered_by' => 'DEPT_HEAD_APPROVAL feedback'
]);
```

**Queries:**

```sql
-- Get complete revision history for a vacancy
SELECT
    revision_number,
    field_changed,
    old_value,
    new_value,
    p.name as changed_by_name,
    changed_at,
    change_reason
FROM vacancy_revision_history vrh
JOIN person p ON vrh.changed_by = p.id
WHERE vrh.vacancy_id = 'vacancy-uuid'
ORDER BY revision_number ASC;

-- Find all salary changes
SELECT
    v.title,
    vrh.old_value as old_salary,
    vrh.new_value as new_salary,
    vrh.change_reason,
    vrh.changed_at
FROM vacancy_revision_history vrh
JOIN organization_vacancy v ON vrh.vacancy_id = v.id
WHERE vrh.field_changed = 'max_salary';

-- Most frequently changed fields
SELECT
    field_changed,
    COUNT(*) as change_count
FROM vacancy_revision_history
GROUP BY field_changed
ORDER BY change_count DESC;

-- Revision activity by user
SELECT
    p.name,
    COUNT(*) as revisions_made
FROM vacancy_revision_history vrh
JOIN person p ON vrh.changed_by = p.id
GROUP BY vrh.changed_by
ORDER BY revisions_made DESC;
```

---

### 5. VACANCY_TASK_DATA

**Purpose:** Stores task-specific data (comments, attachments, decision details)

**Why Needed:**
- Each task type has unique data requirements:
  - **HR Review**: Compliance checks performed, market analysis
  - **Finance**: Cost center, budget code, allocation amount
  - **Dept Head**: Workstation assignment, reporting structure
- Current `task_instance.task_variables` is unstructured JSON
- No standardized way to store attachments or documents
- Hard to query task-specific data

**Key Features:**
- **Task Type Differentiation**: Different data per task type
- **Structured Storage**: Dedicated fields for common items
- **Attachment Support**: URLs to uploaded files
- **Decision Details**: JSON for complex decision data
- **Time Tracking**: Actual time spent on task
- **Action Items**: Follow-up tasks identified

**Schema:**

| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| task_instance_id | UUID | FK to TASK_INSTANCE |
| vacancy_id | UUID | FK to ORGANIZATION_VACANCY |
| flow_instance_id | UUID | FK to TASK_FLOW_INSTANCE |
| **task_type** | ENUM | DRAFT, HR_REVIEW, FINANCE_APPROVAL, etc. |
| **task_type_code** | TEXT | Standardized code |
| **task_comments** | TEXT | Detailed comments |
| **attachment_urls** | TEXT | JSON array of file URLs |
| **decision_details** | TEXT | JSON structured decision data |
| **documents_reviewed** | TEXT | List of documents |
| action_items | TEXT | Follow-up items |
| priority_flag | BOOLEAN | Escalation flag |
| time_spent_minutes | INTEGER | Actual time spent |
| resource_notes | TEXT | Resource allocation notes |
| **compliance_checks** | TEXT | JSON array (HR specific) |
| **market_analysis_notes** | TEXT | Market comparison (HR specific) |
| **cost_center** | TEXT | Cost center code (Finance specific) |
| **budget_code** | TEXT | Budget code (Finance specific) |
| **budget_allocation** | NUMBER | Amount allocated (Finance specific) |
| **workstation_assignment** | TEXT | Workstation (Dept Head specific) |
| **reporting_structure** | TEXT | Reporting hierarchy (Dept Head specific) |

**Usage Example:**

```php
// HR Review task data
createTaskData([
    'task_instance_id' => $hrTaskId,
    'vacancy_id' => $vacancyId,
    'flow_instance_id' => $flowInstanceId,
    'task_type' => 'HR_REVIEW',
    'task_type_code' => 'HR_REVIEW',
    'task_comments' => 'Reviewed job description for compliance with labor laws',
    'attachment_urls' => json_encode([
        '/uploads/market_analysis_2025.pdf',
        '/uploads/salary_survey.xlsx'
    ]),
    'documents_reviewed' => 'Equal Employment Opportunity Act, Labor Law Section 240',
    'compliance_checks' => json_encode([
        'Equal opportunity language - PASS',
        'No discriminatory requirements - PASS',
        'Salary range disclosure - PASS',
        'Benefits description - NEEDS UPDATE'
    ]),
    'market_analysis_notes' => 'Salary range aligned with 2025 Robert Half survey for similar roles',
    'time_spent_minutes' => 45
]);

// Finance Approval task data
createTaskData([
    'task_instance_id' => $financeTaskId,
    'vacancy_id' => $vacancyId,
    'task_type' => 'FINANCE_APPROVAL',
    'task_type_code' => 'FINANCE_APPROVAL',
    'task_comments' => 'Budget approved from Q4 headcount allocation',
    'cost_center' => 'CC-ENG-2025',
    'budget_code' => 'BG-HC-Q4-001',
    'budget_allocation' => 110000,  // Annual budget allocated
    'decision_details' => json_encode([
        'budget_source' => 'Q4 Headcount Budget',
        'remaining_budget' => 250000,
        'approved_amount' => 110000,
        'fiscal_year' => 2025
    ]),
    'time_spent_minutes' => 30
]);

// Department Head task data
createTaskData([
    'task_instance_id' => $deptHeadTaskId,
    'vacancy_id' => $vacancyId,
    'task_type' => 'DEPT_HEAD_APPROVAL',
    'task_type_code' => 'DEPT_HEAD_APPROVAL',
    'task_comments' => 'Approved for Engineering team, reports to Sarah Chen',
    'workstation_assignment' => 'Desk E-205, Building A',
    'reporting_structure' => 'Reports to: Sarah Chen (Engineering Manager), Dotted line to: Alex Kim (Tech Lead)',
    'action_items' => 'Reserve desk E-205, order laptop, set up accounts',
    'time_spent_minutes' => 20
]);
```

**Queries:**

```sql
-- Get all task data for a vacancy
SELECT
    task_type,
    task_comments,
    cost_center,
    budget_allocation,
    workstation_assignment
FROM vacancy_task_data
WHERE vacancy_id = 'vacancy-uuid'
ORDER BY created_at ASC;

-- Finance approvals with budget codes
SELECT
    v.title,
    vtd.cost_center,
    vtd.budget_code,
    vtd.budget_allocation
FROM vacancy_task_data vtd
JOIN organization_vacancy v ON vtd.vacancy_id = v.id
WHERE vtd.task_type = 'FINANCE_APPROVAL';

-- Average time spent by task type
SELECT
    task_type,
    ROUND(AVG(time_spent_minutes), 0) as avg_minutes
FROM vacancy_task_data
WHERE time_spent_minutes IS NOT NULL
GROUP BY task_type;

-- HR compliance check failures
SELECT
    v.title,
    vtd.compliance_checks,
    vtd.created_at
FROM vacancy_task_data vtd
JOIN organization_vacancy v ON vtd.vacancy_id = v.id
WHERE vtd.task_type = 'HR_REVIEW'
  AND vtd.compliance_checks LIKE '%FAIL%';
```

---

### 6. VACANCY_PUBLICATION_RECORD

**Purpose:** Tracks publication of vacancies to job boards and channels

**Why Needed:**
- Can't currently track WHERE vacancy was published
- No analytics on which channels get most applications
- No record of when vacancy went public (compliance)
- Can't answer "How many applications per board?"

**Key Features:**
- **Multi-Channel Tracking**: Internal, external, social media
- **Board-Specific Data**: LinkedIn, Indeed, Glassdoor URLs
- **Closure Tracking**: When/why vacancy closed
- **Analytics Support**: Application counts, view counts
- **Publication Status**: DRAFT, SCHEDULED, PUBLISHED, ARCHIVED

**Schema:**

| Field | Type | Description |
|-------|------|-------------|
| id | UUID | Primary key |
| vacancy_id | UUID | FK to ORGANIZATION_VACANCY |
| flow_instance_id | UUID | FK to TASK_FLOW_INSTANCE |
| task_instance_id | UUID | FK to TASK_INSTANCE |
| published_by | UUID | FK to PERSON |
| **published_date** | DATETIME | When published |
| **publication_status** | ENUM | DRAFT, SCHEDULED, PUBLISHED, ARCHIVED |
| **internal_board_published** | BOOLEAN | Published internally? |
| **internal_published_date** | DATETIME | When internal |
| internal_url | TEXT | Internal posting URL |
| **external_boards** | TEXT | JSON array of boards |
| **external_publish_date** | DATETIME | When external |
| posting_id_external | TEXT | Reference from job board |
| **social_media_published** | BOOLEAN | Shared on social? |
| social_media_date | DATETIME | When social |
| social_platforms | TEXT | JSON array (LinkedIn, Twitter) |
| **close_date** | DATETIME | When closed |
| closed_by | UUID | Who closed |
| **closure_reason** | ENUM | FILLED, CANCELLED, EXPIRED, ON_HOLD |
| **applications_count** | INTEGER | Total applications |
| **selected_candidate_count** | INTEGER | How many selected |
| **views_count** | INTEGER | Views on job boards |
| publication_notes | TEXT | Notes |

**Usage Example:**

```php
// Record publication
createPublicationRecord([
    'vacancy_id' => $vacancyId,
    'flow_instance_id' => $flowInstanceId,
    'task_instance_id' => $publishTaskId,
    'published_by' => $hrCoordinatorId,
    'published_date' => date('Y-m-d H:i:s'),
    'publication_status' => 'PUBLISHED',

    // Internal
    'internal_board_published' => true,
    'internal_published_date' => date('Y-m-d H:i:s'),
    'internal_url' => 'https://intranet.company.com/jobs/12345',

    // External
    'external_boards' => json_encode([
        ['board' => 'LinkedIn', 'url' => 'https://linkedin.com/jobs/view/987654'],
        ['board' => 'Indeed', 'url' => 'https://indeed.com/viewjob?jk=abc123'],
        ['board' => 'Glassdoor', 'url' => 'https://glassdoor.com/job/xyz789']
    ]),
    'external_publish_date' => date('Y-m-d H:i:s'),

    // Social
    'social_media_published' => true,
    'social_media_date' => date('Y-m-d H:i:s'),
    'social_platforms' => json_encode(['LinkedIn', 'Twitter']),

    'publication_notes' => 'Posted to 3 external boards and 2 social platforms'
]);

// Update with analytics
updatePublicationRecord($publicationId, [
    'applications_count' => 45,
    'views_count' => 1250
]);

// Record closure
updatePublicationRecord($publicationId, [
    'close_date' => date('Y-m-d H:i:s'),
    'closed_by' => $hrManagerId,
    'closure_reason' => 'FILLED',
    'selected_candidate_count' => 1
]);
```

**Queries:**

```sql
-- Publication analytics by channel
SELECT
    v.title,
    vpr.internal_board_published,
    vpr.external_boards,
    vpr.applications_count,
    vpr.views_count
FROM vacancy_publication_record vpr
JOIN organization_vacancy v ON vpr.vacancy_id = v.id
WHERE vpr.publication_status = 'PUBLISHED';

-- Which job boards are most effective?
SELECT
    json_extract(board.value, '$.board') as job_board,
    COUNT(*) as vacancies_posted,
    SUM(vpr.applications_count) as total_applications,
    ROUND(AVG(vpr.applications_count), 1) as avg_applications
FROM vacancy_publication_record vpr,
     json_each(vpr.external_boards) as board
GROUP BY job_board
ORDER BY avg_applications DESC;

-- Time to fill (publication to closure)
SELECT
    v.title,
    ROUND(JULIANDAY(vpr.close_date) - JULIANDAY(vpr.published_date), 0) as days_to_fill,
    vpr.closure_reason,
    vpr.applications_count
FROM vacancy_publication_record vpr
JOIN organization_vacancy v ON vpr.vacancy_id = v.id
WHERE vpr.close_date IS NOT NULL;

-- Open vacancies by publication age
SELECT
    v.title,
    vpr.published_date,
    ROUND(JULIANDAY('now') - JULIANDAY(vpr.published_date), 0) as days_open,
    vpr.applications_count
FROM vacancy_publication_record vpr
JOIN organization_vacancy v ON vpr.vacancy_id = v.id
WHERE vpr.close_date IS NULL
  AND vpr.publication_status = 'PUBLISHED'
ORDER BY days_open DESC;
```

---

## Installation

### Prerequisites

1. **Process Flow System** installed (`metadata/010-process_flow_system.sql`)
2. **Hiring Domain** installed (`metadata/009-hiring_domain.sql`)
3. **Vacancy Creation Process** defined (`metadata/processes/vacancy_creation_process.sql`)

### Installation Steps

```bash
# Step 1: Run the entity migration
sqlite3 database/v4l.sqlite < metadata/011-vacancy_process_entities.sql

# Step 2: Verify installation
sqlite3 database/v4l.sqlite "SELECT code, name FROM entity_definition WHERE code LIKE 'VACANCY%';"

# Expected output:
# VACANCY_DRAFT|Vacancy Draft
# VACANCY_APPROVAL_RECORD|Vacancy Approval Record
# VACANCY_REJECTION_REASON|Vacancy Rejection Reason
# VACANCY_REVISION_HISTORY|Vacancy Revision History
# VACANCY_TASK_DATA|Vacancy Task Data
# VACANCY_PUBLICATION_RECORD|Vacancy Publication Record
```

---

## Integration with Vacancy Creation Process

### Process Flow Integration

```
START
  ↓
DRAFT_VACANCY
  - Creates VACANCY_DRAFT (version 1)
  - Stores in draft_status = 'DRAFT'
  ↓
HR_REVIEW
  - If APPROVED:
    - Creates VACANCY_APPROVAL_RECORD (sequence 1)
    - Creates VACANCY_TASK_DATA (HR compliance checks)
    - Updates VACANCY_DRAFT status = 'APPROVED'
  - If REJECTED:
    - Creates VACANCY_REJECTION_REASON
    - Creates VACANCY_REVISION_HISTORY (field changes)
    - Loops back to DRAFT_VACANCY (creates version 2)
  ↓
BUDGET_CHECK (Decision)
  ↓
FINANCE_APPROVAL (if high budget)
  - If APPROVED:
    - Creates VACANCY_APPROVAL_RECORD (sequence 2)
    - Creates VACANCY_TASK_DATA (budget codes, cost center)
  - If REJECTED:
    - Creates VACANCY_REJECTION_REASON
    - Loops back
  ↓
DEPT_HEAD_APPROVAL
  - If APPROVED:
    - Creates VACANCY_APPROVAL_RECORD (sequence 3, final)
    - Creates VACANCY_TASK_DATA (workstation, reporting)
  - If REJECTED:
    - Creates VACANCY_REJECTION_REASON
    - Loops back
  ↓
PUBLISH_VACANCY
  - Creates VACANCY_PUBLICATION_RECORD
  - Updates ORGANIZATION_VACANCY status = 'OPEN'
  - Tracks publication channels
  ↓
END
```

### Modified ProcessEngine Integration

Update your `ProcessEngine.php` to create these entities:

```php
class ProcessEngine {

    public function completeTask($taskInstanceId, $userId, $action, $comments, $data) {
        $task = $this->getTaskInstance($taskInstanceId);
        $nodeCode = $task['node_code'];

        // Complete the task
        $this->taskManager->completeTask($taskInstanceId, $userId, $action, $comments, $data);

        // Create approval/rejection records
        if (in_array($nodeCode, ['HR_REVIEW', 'FINANCE_APPROVAL', 'DEPT_HEAD_APPROVAL'])) {
            if ($action === 'APPROVE') {
                $this->createApprovalRecord($task, $userId, $action, $comments);
                $this->createTaskData($task, $data);  // Store task-specific data
            } else if ($action === 'REJECT') {
                $this->createRejectionReason($task, $userId, $comments, $data);
            }
        }

        // Create publication record
        if ($nodeCode === 'PUBLISH_VACANCY' && $action === 'COMPLETE') {
            $this->createPublicationRecord($task, $userId, $data);
        }

        // Track field changes
        if (!empty($data['changed_fields'])) {
            foreach ($data['changed_fields'] as $field => $values) {
                $this->createRevisionHistory($task['vacancy_id'], $field, $values);
            }
        }

        // Continue process flow
        $this->moveToNextNode($taskInstanceId);
    }
}
```

---

## Analytics Queries

### 1. Process Efficiency Dashboard

```sql
-- Overview metrics
SELECT
    COUNT(DISTINCT fi.id) as total_processes,
    COUNT(DISTINCT CASE WHEN fi.status = 'RUNNING' THEN fi.id END) as active,
    COUNT(DISTINCT CASE WHEN fi.status = 'COMPLETED' THEN fi.id END) as completed,
    ROUND(AVG(JULIANDAY(fi.completed_at) - JULIANDAY(fi.started_at)), 1) as avg_days_to_complete
FROM task_flow_instance fi
WHERE fi.graph_id = 'VC000000-0000-4000-8000-000000000001';

-- Rejection rate by step
SELECT
    var.approval_step_name,
    COUNT(*) as total_decisions,
    SUM(CASE WHEN var.approval_action = 'REJECTED' THEN 1 ELSE 0 END) as rejections,
    ROUND(100.0 * SUM(CASE WHEN var.approval_action = 'REJECTED' THEN 1 ELSE 0 END) / COUNT(*), 1) as rejection_rate
FROM vacancy_approval_record var
GROUP BY var.approval_step_name;
```

### 2. Rejection Analysis

```sql
-- Top rejection reasons
SELECT
    rejection_reason,
    rejected_at_step,
    COUNT(*) as count
FROM vacancy_rejection_reason
GROUP BY rejection_reason, rejected_at_step
ORDER BY count DESC
LIMIT 10;

-- Vacancies with most revisions
SELECT
    v.title,
    MAX(vd.draft_version) as total_versions,
    COUNT(DISTINCT vrr.id) as rejection_count
FROM organization_vacancy v
LEFT JOIN vacancy_draft vd ON v.id = vd.vacancy_id
LEFT JOIN vacancy_rejection_reason vrr ON v.id = vrr.vacancy_id
GROUP BY v.id
HAVING rejection_count > 0
ORDER BY total_versions DESC;
```

### 3. Approver Performance

```sql
-- Average approval time by approver
SELECT
    p.name,
    var.approval_step_name,
    COUNT(*) as approvals,
    ROUND(AVG(JULIANDAY(var.approval_date) - JULIANDAY(ti.created_at)) * 24, 1) as avg_hours
FROM vacancy_approval_record var
JOIN person p ON var.approved_by = p.id
JOIN task_instance ti ON var.task_instance_id = ti.id
GROUP BY var.approved_by, var.approval_step_name
ORDER BY avg_hours ASC;
```

### 4. Publication Effectiveness

```sql
-- Applications per channel
SELECT
    json_extract(board.value, '$.board') as channel,
    COUNT(*) as vacancies,
    SUM(vpr.applications_count) as total_apps,
    ROUND(AVG(vpr.applications_count), 1) as avg_apps_per_vacancy
FROM vacancy_publication_record vpr,
     json_each(vpr.external_boards) as board
GROUP BY channel
ORDER BY avg_apps_per_vacancy DESC;
```

---

## Best Practices

### 1. Draft Management
- Create new draft version on each rejection
- Always mark only one draft as `is_current_draft = TRUE`
- Store complete snapshot in each draft version
- Never delete draft versions (audit trail)

### 2. Approval Recording
- Create approval record immediately after task completion
- Set correct `approval_sequence` for ordering
- Always capture `decision_rationale` for compliance
- Mark final approval with `is_final_approval = TRUE`

### 3. Rejection Handling
- Use structured `rejection_reason` enum values
- Always provide `required_revisions` JSON array
- Set `revision_deadline` for tracking
- Mark `is_critical` for blocking issues

### 4. Revision Tracking
- Record every field change, no matter how small
- Always store both `old_value` and `new_value`
- Include `change_reason` for audit trail
- Link to triggering event (`triggered_by`)

### 5. Task Data Storage
- Use task-type-specific fields when available
- Store complex data in `decision_details` JSON
- Always upload attachments to secure location
- Track `time_spent_minutes` for analytics

### 6. Publication Tracking
- Record all channels (internal, external, social)
- Update `applications_count` regularly
- Track `views_count` from job boards
- Always record `closure_reason` when closing

---

## Maintenance

### Archival Strategy

```sql
-- Archive completed flows older than 2 years
CREATE TABLE vacancy_draft_archive AS SELECT * FROM vacancy_draft WHERE 0;
CREATE TABLE vacancy_approval_record_archive AS SELECT * FROM vacancy_approval_record WHERE 0;
-- ... create archive tables for all entities

-- Move old data
INSERT INTO vacancy_draft_archive
SELECT vd.* FROM vacancy_draft vd
JOIN task_flow_instance fi ON vd.flow_instance_id = fi.id
WHERE fi.status = 'COMPLETED'
  AND fi.completed_at < date('now', '-2 years');

-- Delete from main table
DELETE FROM vacancy_draft
WHERE id IN (SELECT id FROM vacancy_draft_archive);
```

### Performance Optimization

```sql
-- Add indexes for common queries
CREATE INDEX IF NOT EXISTS idx_vacancy_draft_vacancy_current
ON vacancy_draft(vacancy_id, is_current_draft);

CREATE INDEX IF NOT EXISTS idx_approval_record_vacancy_sequence
ON vacancy_approval_record(vacancy_id, approval_sequence);

CREATE INDEX IF NOT EXISTS idx_rejection_reason_vacancy_step
ON vacancy_rejection_reason(vacancy_id, rejected_at_step);

CREATE INDEX IF NOT EXISTS idx_revision_history_vacancy_field
ON vacancy_revision_history(vacancy_id, field_changed);

CREATE INDEX IF NOT EXISTS idx_task_data_task_type
ON vacancy_task_data(task_instance_id, task_type);

CREATE INDEX IF NOT EXISTS idx_publication_status
ON vacancy_publication_record(vacancy_id, publication_status);
```

---

## Troubleshooting

### Issue: Draft versions not incrementing

**Symptom:** Multiple drafts with same version number

**Solution:**
```sql
-- Get next version number
SELECT COALESCE(MAX(draft_version), 0) + 1 as next_version
FROM vacancy_draft
WHERE vacancy_id = 'vacancy-uuid';
```

### Issue: Approval sequence out of order

**Symptom:** Approval records with wrong sequence

**Solution:**
```sql
-- Check approval sequence
SELECT approval_sequence, approval_step_name, approval_date
FROM vacancy_approval_record
WHERE vacancy_id = 'vacancy-uuid'
ORDER BY approval_sequence;

-- Should be:
-- 1, HR Review, ...
-- 2, Finance Approval, ...
-- 3, Department Head Approval, ...
```

### Issue: Missing rejection reasons

**Symptom:** Rejections without reason records

**Solution:**
```sql
-- Find rejections without reason records
SELECT ti.id, pn.node_name
FROM task_instance ti
JOIN process_node pn ON ti.node_id = pn.id
WHERE ti.completion_action = 'REJECT'
  AND ti.id NOT IN (SELECT task_instance_id FROM vacancy_rejection_reason);
```

---

## Summary

These 6 entities provide:

✅ **Complete Audit Trail** - Every action, decision, and change tracked
✅ **Version Management** - All draft versions preserved
✅ **Structured Data** - Queryable, analyzable information
✅ **Compliance Support** - Immutable records with rationales
✅ **Process Analytics** - Identify bottlenecks and patterns
✅ **Better UX** - Rich data for building intuitive interfaces

Install these entities to unlock the full potential of your Vacancy Creation Process!
