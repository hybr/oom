# Recruitment & Hiring Process - Architecture

> **📋 Process Type:** Complete Lifecycle Workflow
> **Domain:** Hiring & Vacancy
> **Complexity:** High (Multi-entity, Multi-stage, Conditional routing)

---

## Overview

A comprehensive end-to-end recruitment process that manages the complete hiring lifecycle from application receipt through employment contract creation. This process implements automated screening, multi-stage interviews, offer management, and workstation assignment using the Process Flow System.

**Process Code:** `RECRUITMENT_PROCESS`

---

## Process Scope

### What This Process Covers

```
Application Received → Screening → Interviews → Offer → Contract → Onboarding
```

**Stages:**
1. **Application Screening** - Initial CV/resume review
2. **Technical Assessment** - Skills evaluation
3. **Interview Process** - Multiple interview rounds
4. **Offer Management** - Offer creation, negotiation, acceptance
5. **Contract Creation** - Employment contract generation
6. **Workstation Assignment** - Desk allocation from vacancy
7. **Onboarding Preparation** - Setup for first day

### Entities Managed

| Entity | Purpose | Created By |
|--------|---------|------------|
| `VACANCY_APPLICATION` | Candidate application | Applicant (external trigger) |
| `APPLICATION_REVIEW` | Screening feedback | HR/Hiring Manager |
| `APPLICATION_INTERVIEW` | Interview scheduling & feedback | Interviewers |
| `JOB_OFFER` | Offer letter & terms | HR Manager |
| `EMPLOYMENT_CONTRACT` | Final employment record | System (on offer acceptance) |
| `ORGANIZATION_VACANCY_WORKSTATION` | Workspace assignment | HR/Facilities |

---

## Process Flow Diagram

```
START (Application Received)
  ↓
[Initial Screening] (HR Coordinator - REVIEWER, SLA: 24h)
  • Review VACANCY_APPLICATION
  • Create APPLICATION_REVIEW with rating and feedback
  • Check minimum qualifications
  ├─ REJECT → [Rejection Notification] → END
  └─ SHORTLIST → {Technical Assessment Decision}
                   ├─ Technical Position → [Technical Assessment]
                   └─ Non-Technical → [Schedule First Interview]

[Technical Assessment] (Technical Lead - ASSESSOR, SLA: 48h)
  • Send assessment link/test
  • Create APPLICATION_REVIEW (technical)
  • Evaluate technical skills
  ├─ FAIL → [Rejection Notification] → END
  └─ PASS → [Schedule First Interview]

[Schedule First Interview] (HR Coordinator - SCHEDULER, SLA: 24h)
  • Create APPLICATION_INTERVIEW (Stage 1)
  • Schedule with interviewer
  • Send calendar invite to candidate
  ↓
[First Interview] (Hiring Manager - INTERVIEWER, SLA: 72h)
  • Conduct interview
  • Update APPLICATION_INTERVIEW (feedback, rating, recommendation)
  ├─ REJECT → [Rejection Notification] → END
  ├─ MAYBE → [Manager Review Decision]
  └─ PROCEED → {Interview Stage Decision}
                 ├─ Multiple Stages Required → [Schedule Second Interview]
                 └─ Single Stage Only → [Reference Check]

[Schedule Second Interview] (HR Coordinator - SCHEDULER, SLA: 24h)
  • Create APPLICATION_INTERVIEW (Stage 2)
  • Schedule with technical panel/senior manager
  ↓
[Second Interview] (Technical Panel/Senior Manager - INTERVIEWER, SLA: 72h)
  • Conduct interview
  • Update APPLICATION_INTERVIEW
  ├─ REJECT → [Rejection Notification] → END
  └─ PROCEED → [Reference Check]

[Reference Check] (HR Coordinator - VERIFIER, SLA: 48h)
  • Verify employment history
  • Contact references
  • Background check (optional)
  ├─ CONCERNS → [Hiring Manager Review]
  └─ CLEAR → [Salary Negotiation]

[Salary Negotiation] (HR Manager - NEGOTIATOR, SLA: 48h)
  • Review salary expectations vs. vacancy budget
  • Prepare offer amount
  • Consider experience and market rate
  ├─ NEEDS_APPROVAL (Above budget) → [CFO Approval]
  └─ WITHIN_BUDGET → [Prepare Offer]

[CFO Approval] (CFO - APPROVER, SLA: 48h)
  • Review above-budget offer
  • Approve budget increase
  ├─ REJECTED → [Adjust Offer] → [Prepare Offer]
  └─ APPROVED → [Prepare Offer]

[Prepare Offer] (HR Manager - OFFER_CREATOR, SLA: 24h)
  • Create JOB_OFFER record
  • Generate offer letter document (MEDIA_FILE)
  • Include: salary, benefits, start date, terms
  • Link to MEDIA_FILE (offer letter PDF)
  ↓
[Send Offer] (HR Manager - OFFER_SENDER, SLA: 24h)
  • Update JOB_OFFER status to 'SENT'
  • Email offer letter to candidate
  • Set expiry date (usually 7 days)
  ↓
[Await Offer Response] (System - WAIT_STATE)
  • Wait for candidate response
  • Monitor expiry date
  ├─ ACCEPTED → [Generate Contract]
  ├─ REJECTED → [Offer Declined] → END
  ├─ COUNTER_OFFER → [Salary Renegotiation]
  └─ EXPIRED → [Offer Expired] → END

[Salary Renegotiation] (HR Manager - NEGOTIATOR, SLA: 24h)
  • Review counter-offer
  • Consult with hiring manager
  ├─ ACCEPT_COUNTER → [Update Offer] → [Send Offer]
  ├─ REJECT_COUNTER → [Send Final Offer] → [Await Offer Response]
  └─ NEEDS_APPROVAL → [CFO Approval]

[Generate Contract] (HR Manager - CONTRACT_CREATOR, SLA: 48h)
  • Create EMPLOYMENT_CONTRACT record
  • Link to accepted JOB_OFFER
  • Generate contract document (MEDIA_FILE)
  • Include: terms, salary, benefits, policies
  • Assign contract_number
  ↓
[Legal Review] (Legal Team - REVIEWER, SLA: 48h)
  • Review employment contract
  • Ensure compliance
  ├─ REVISIONS_NEEDED → [Update Contract] → [Legal Review]
  └─ APPROVED → [Send Contract for Signature]

[Send Contract for Signature] (HR Coordinator - SENDER, SLA: 24h)
  • Email contract to candidate
  • Request e-signature or physical signature
  ↓
[Await Contract Signature] (System - WAIT_STATE)
  • Wait for signed contract
  ├─ SIGNED → [Process Signed Contract]
  └─ DELAYED (>7 days) → [Follow-up Reminder] → [Await Contract Signature]

[Process Signed Contract] (HR Manager - PROCESSOR, SLA: 24h)
  • Update EMPLOYMENT_CONTRACT status to 'ACTIVE'
  • Store signed contract (MEDIA_FILE)
  • Update ORGANIZATION_VACANCY filled_positions++
  ↓
[Assign Workstation] (HR/Facilities - WORKSPACE_MANAGER, SLA: 24h)
  • Get available workstation from ORGANIZATION_VACANCY_WORKSTATION
  • Assign to new employee
  • Update assigned_to_applicant_id
  • Update is_occupied = true
  • Set EMPLOYMENT_CONTRACT.workstation_id
  ↓
[Setup IT Account] (IT Admin - SETUP, SLA: 48h)
  • Create email account
  • Setup system access
  • Order equipment
  ↓
[Prepare Onboarding] (HR Coordinator - ONBOARDING_PREP, SLA: 48h)
  • Create onboarding checklist
  • Schedule orientation
  • Assign buddy/mentor
  • Prepare welcome package
  ↓
END (Recruitment Complete - Employee Hired)
```

---

## Process Nodes

### Node Types Used

| Node Type | Count | Purpose |
|-----------|-------|---------|
| START | 1 | Application received |
| TASK | 18 | Manual steps requiring user action |
| DECISION | 5 | Conditional routing points |
| WAIT_STATE | 2 | Awaiting external input (offer, contract) |
| END | 5 | Multiple endpoints (hired, rejected, expired) |

---

## Position-Based Permissions

### Required Positions & Permissions

| Position | Permission Type | Nodes |
|----------|----------------|-------|
| **HR Coordinator** | REVIEWER | Initial Screening |
| **HR Coordinator** | SCHEDULER | Schedule First Interview, Schedule Second Interview |
| **HR Coordinator** | VERIFIER | Reference Check |
| **HR Coordinator** | SENDER | Send Contract for Signature |
| **HR Coordinator** | ONBOARDING_PREP | Prepare Onboarding |
| **Technical Lead** | ASSESSOR | Technical Assessment |
| **Hiring Manager** | INTERVIEWER | First Interview |
| **Hiring Manager** | REVIEWER | Manager Review Decision |
| **Technical Panel** | INTERVIEWER | Second Interview |
| **Senior Manager** | INTERVIEWER | Second Interview (alternate) |
| **HR Manager** | NEGOTIATOR | Salary Negotiation, Salary Renegotiation |
| **HR Manager** | OFFER_CREATOR | Prepare Offer |
| **HR Manager** | OFFER_SENDER | Send Offer |
| **HR Manager** | CONTRACT_CREATOR | Generate Contract |
| **HR Manager** | PROCESSOR | Process Signed Contract |
| **CFO** | APPROVER | CFO Approval |
| **Legal Team** | REVIEWER | Legal Review |
| **Facilities Manager** | WORKSPACE_MANAGER | Assign Workstation |
| **IT Admin** | SETUP | Setup IT Account |

### Position Resolution Chain

```
EMPLOYMENT_CONTRACT → JOB_OFFER → VACANCY_APPLICATION →
ORGANIZATION_VACANCY → POPULAR_ORGANIZATION_POSITION
```

For positions not yet hired:
```
ORGANIZATION_VACANCY → POPULAR_ORGANIZATION_POSITION
```

---

## Decision Nodes

### 1. Technical Assessment Decision

**Node:** `technical_assessment_decision`
**Condition:** Check if position requires technical skills

```json
{
  "condition_type": "AND",
  "conditions": [
    {
      "source_type": "entity_field",
      "source_table": "popular_organization_position",
      "field_name": "requires_technical_assessment",
      "operator": "EQ",
      "value": true
    }
  ]
}
```

**Edges:**
- `TRUE` → Technical Assessment
- `FALSE` → Schedule First Interview

---

### 2. Interview Stage Decision

**Node:** `interview_stage_decision`
**Condition:** Check if multiple interview stages are configured

```json
{
  "condition_type": "AND",
  "conditions": [
    {
      "source_type": "entity_field",
      "source_table": "organization_vacancy",
      "field_name": "requires_multiple_interviews",
      "operator": "EQ",
      "value": true
    }
  ]
}
```

**Edges:**
- `TRUE` → Schedule Second Interview
- `FALSE` → Reference Check

---

### 3. Salary Approval Decision

**Node:** `salary_approval_decision`
**Condition:** Offer salary exceeds vacancy max_salary

```json
{
  "condition_type": "AND",
  "conditions": [
    {
      "source_type": "task_variable",
      "variable_name": "negotiated_salary",
      "operator": "GT",
      "compare_to_field": {
        "source_table": "organization_vacancy",
        "field_name": "salary_max"
      }
    }
  ]
}
```

**Edges:**
- `TRUE` → CFO Approval Required
- `FALSE` → Prepare Offer

---

### 4. Offer Response Decision

**Node:** `offer_response_decision`
**Condition:** Check JOB_OFFER status

```json
{
  "condition_type": "OR",
  "conditions": [
    {
      "source_type": "entity_field",
      "source_table": "job_offer",
      "field_name": "status",
      "operator": "EQ",
      "value": "ACCEPTED"
    },
    {
      "source_type": "entity_field",
      "source_table": "job_offer",
      "field_name": "status",
      "operator": "EQ",
      "value": "REJECTED"
    },
    {
      "source_type": "entity_field",
      "source_table": "job_offer",
      "field_name": "status",
      "operator": "EQ",
      "value": "COUNTER_OFFER"
    },
    {
      "source_type": "entity_field",
      "source_table": "job_offer",
      "field_name": "status",
      "operator": "EQ",
      "value": "EXPIRED"
    }
  ]
}
```

**Edges:**
- `ACCEPTED` → Generate Contract
- `REJECTED` → Offer Declined (END)
- `COUNTER_OFFER` → Salary Renegotiation
- `EXPIRED` → Offer Expired (END)

---

### 5. Contract Signature Status

**Node:** `contract_signature_check`
**Condition:** Check if contract signed within deadline

```json
{
  "condition_type": "AND",
  "conditions": [
    {
      "source_type": "entity_field",
      "source_table": "employment_contract",
      "field_name": "status",
      "operator": "EQ",
      "value": "SIGNED"
    }
  ]
}
```

**Edges:**
- `TRUE` → Process Signed Contract
- `FALSE` (>7 days) → Follow-up Reminder → Loop back

---

## Entity Status Transitions

### VACANCY_APPLICATION

```
Application Received (External) → SUBMITTED
  ↓ (Initial Screening)
UNDER_REVIEW
  ↓ (Shortlisted)
SHORTLISTED
  ↓ (Interview Scheduled)
INTERVIEW_SCHEDULED
  ↓ (Interview Completed)
INTERVIEWED
  ↓ (Offer Made)
OFFER_MADE
  ↓ (Offer Accepted)
HIRED

Rejection paths:
  UNDER_REVIEW → REJECTED
  SHORTLISTED → REJECTED
  INTERVIEWED → REJECTED
  OFFER_MADE → REJECTED (declined)
```

### JOB_OFFER

```
DRAFT (Prepare Offer)
  ↓
SENT (Send Offer)
  ↓
{ACCEPTED, REJECTED, COUNTER_OFFER, EXPIRED}
```

### EMPLOYMENT_CONTRACT

```
DRAFT (Generate Contract)
  ↓
PENDING_SIGNATURE (Sent for signature)
  ↓
SIGNED (Signed by candidate)
  ↓
ACTIVE (Contract processed, employee onboarded)
```

---

## Task Forms & Data

### Initial Screening Task

**Form Entity:** `VACANCY_APPLICATION` (read-only) + `APPLICATION_REVIEW` (editable)

**Fields:**
- `rating` (1-5 stars) *
- `feedback` (textarea)
- `recommendation` (radio: PROCEED, REJECT, MAYBE) *
- `review_stage` (hidden: 'RESUME_SCREENING')

**Completion Actions:**
- `SHORTLIST` - Proceed to next stage
- `REJECT` - End process

---

### Technical Assessment Task

**Form Entity:** `APPLICATION_REVIEW` (technical)

**Fields:**
- `assessment_link` (URL to test)
- `score` (number, 0-100)
- `rating` (1-5 stars) *
- `feedback` (textarea)
- `recommendation` (radio: PASS, FAIL) *

**Completion Actions:**
- `PASS` - Proceed
- `FAIL` - Reject

---

### Schedule Interview Task

**Form Entity:** `APPLICATION_INTERVIEW` (create)

**Fields:**
- `interview_stage_id` (select: INTERVIEW_STAGE) *
- `interviewer_id` (select: PERSON) *
- `scheduled_date` (date) *
- `scheduled_time` (time) *
- `duration_minutes` (number, default: 60)
- `location` (text or URL)
- `interview_type` (select: PHONE, VIDEO, IN_PERSON, TECHNICAL_TEST) *

**Completion Actions:**
- `SCHEDULED` - Interview scheduled

---

### Conduct Interview Task

**Form Entity:** `APPLICATION_INTERVIEW` (update)

**Fields:**
- `completed_date` (date) *
- `rating` (1-5 stars) *
- `feedback` (textarea) *
- `recommendation` (select: STRONG_YES, YES, MAYBE, NO, STRONG_NO) *
- `notes` (textarea)

**Completion Actions:**
- `PROCEED` - Move to next stage
- `REJECT` - End process
- `MAYBE` - Needs manager review

---

### Prepare Offer Task

**Form Entity:** `JOB_OFFER` (create)

**Fields:**
- `offered_salary` (number) *
- `offered_position_id` (readonly, from vacancy)
- `start_date` (date) *
- `benefits` (textarea)
- `terms_and_conditions` (textarea)
- `expiry_date` (date, default: +7 days) *
- `offer_letter_media_file_id` (file upload: PDF) *

**Completion Actions:**
- `PREPARED` - Offer ready for sending

---

### Generate Contract Task

**Form Entity:** `EMPLOYMENT_CONTRACT` (create)

**Fields:**
- `job_offer_id` (readonly, auto-filled)
- `contract_number` (auto-generated) *
- `contract_type` (select: PERMANENT, CONTRACT, INTERN, TEMPORARY) *
- `start_date` (date, from offer) *
- `end_date` (date, if CONTRACT/INTERN/TEMPORARY)
- `salary` (number, from offer) *
- `benefits` (textarea, from offer)
- `contract_document_media_file_id` (file upload: PDF) *

**Completion Actions:**
- `GENERATED` - Contract ready for review

---

### Assign Workstation Task

**Form Entity:** `ORGANIZATION_VACANCY_WORKSTATION` (update) + `EMPLOYMENT_CONTRACT` (update workstation_id)

**Fields:**
- `workstation_id` (select: available workstations from vacancy) *
- `assigned_date` (date, auto-filled) *
- `notes` (textarea)

**Completion Actions:**
- `ASSIGNED` - Workstation assigned

---

## SLA Configuration

| Task | SLA (hours) | Escalation |
|------|-------------|------------|
| Initial Screening | 24 | Notify HR Manager |
| Technical Assessment | 48 | Notify Technical Lead |
| Schedule First Interview | 24 | Notify HR Manager |
| First Interview | 72 | Notify Hiring Manager |
| Schedule Second Interview | 24 | Notify HR Manager |
| Second Interview | 72 | Notify Technical Panel Lead |
| Reference Check | 48 | Notify HR Manager |
| Salary Negotiation | 48 | Notify HR Manager |
| CFO Approval | 48 | Notify CEO |
| Prepare Offer | 24 | Notify HR Manager |
| Send Offer | 24 | Notify HR Manager |
| Legal Review | 48 | Notify Legal Team Lead |
| Send Contract | 24 | Notify HR Coordinator |
| Process Contract | 24 | Notify HR Manager |
| Assign Workstation | 24 | Notify Facilities Manager |
| Setup IT Account | 48 | Notify IT Manager |
| Prepare Onboarding | 48 | Notify HR Manager |

---

## Wait States & External Triggers

### 1. Await Offer Response

**Wait State:** `await_offer_response`
**Trigger:** Candidate accepts/rejects/counters offer
**Timeout:** 7 days (offer expiry)
**Timeout Action:** Mark offer as EXPIRED, end process

**External Trigger API:**
```http
POST /api/process/trigger-event.php
{
  "event_code": "OFFER_RESPONSE",
  "flow_instance_id": "uuid",
  "task_instance_id": "uuid",
  "data": {
    "offer_status": "ACCEPTED" | "REJECTED" | "COUNTER_OFFER",
    "counter_amount": 85000,  // if COUNTER_OFFER
    "response_comments": "text"
  }
}
```

---

### 2. Await Contract Signature

**Wait State:** `await_contract_signature`
**Trigger:** Candidate signs employment contract
**Timeout:** 14 days
**Reminder:** Send reminder after 7 days

**External Trigger API:**
```http
POST /api/process/trigger-event.php
{
  "event_code": "CONTRACT_SIGNED",
  "flow_instance_id": "uuid",
  "task_instance_id": "uuid",
  "data": {
    "signature_date": "2024-01-15",
    "signed_document_media_file_id": "uuid",
    "signing_method": "E_SIGNATURE" | "PHYSICAL"
  }
}
```

---

## Parallel Paths (FORK/JOIN)

### Background Check Parallel Processing

**FORK Node:** After "Reference Check" PASS

**Parallel Branches:**
1. **Employment Verification** (HR Coordinator)
2. **Criminal Background Check** (External Service)
3. **Education Verification** (HR Coordinator)

**JOIN Node:** All checks complete → Proceed to Salary Negotiation

**Configuration:**
```json
{
  "fork_type": "PARALLEL",
  "join_condition": "ALL_COMPLETE",
  "branches": [
    {
      "branch_id": "employment_verify",
      "node_id": "verify_employment"
    },
    {
      "branch_id": "criminal_check",
      "node_id": "criminal_background"
    },
    {
      "branch_id": "education_verify",
      "node_id": "verify_education"
    }
  ]
}
```

---

## Process Variables

### Flow-Level Variables

```json
{
  "vacancy_id": "uuid",
  "organization_id": "uuid",
  "application_id": "uuid",
  "applicant_id": "uuid",
  "vacancy_budget_max": 100000,
  "requires_technical_assessment": true,
  "requires_multiple_interviews": true,
  "interview_count": 0,
  "rejection_reason": null,
  "negotiated_salary": null
}
```

### Task-Level Variables

Stored in `TASK_INSTANCE.task_data` (JSON):

```json
{
  "review_rating": 4,
  "technical_score": 85,
  "interview_1_rating": 5,
  "interview_2_rating": 4,
  "reference_check_status": "CLEAR",
  "final_salary": 95000,
  "workstation_id": "uuid"
}
```

---

## Notifications

### Email Notifications

| Trigger | Recipient | Template |
|---------|-----------|----------|
| Application Screened - Rejected | Applicant | `rejection_email.html` |
| Interview Scheduled | Applicant + Interviewer | `interview_invite.html` |
| Interview Reminder | Applicant + Interviewer | `interview_reminder.html` (24h before) |
| Offer Sent | Applicant | `offer_letter_email.html` |
| Offer Expiring Soon | Applicant | `offer_expiry_reminder.html` (1 day before) |
| Offer Accepted | HR Manager, Hiring Manager | `offer_accepted_notification.html` |
| Contract Sent | Applicant | `contract_signature_request.html` |
| Contract Signed | HR Manager | `contract_signed_notification.html` |
| Onboarding Prepared | New Employee | `welcome_onboarding.html` |

### System Notifications

| Trigger | Recipient | Type |
|---------|-----------|------|
| Task Assigned | Task assignee | In-app notification |
| SLA Approaching (80%) | Task assignee | Warning |
| SLA Breached | Task assignee + Manager | Alert |
| Process Stuck (>7 days) | Process owner | Escalation |

---

## Audit Trail

All process actions logged in `TASK_AUDIT_LOG`:

```sql
SELECT
  tal.action,
  tal.actor_id,
  p.first_name || ' ' || p.last_name as actor_name,
  tal.from_status,
  tal.to_status,
  tal.comments,
  tal.created_at
FROM task_audit_log tal
JOIN person p ON tal.actor_id = p.id
WHERE tal.task_instance_id = ?
ORDER BY tal.created_at;
```

**Key Actions Tracked:**
- TASK_CREATED
- TASK_STARTED
- TASK_COMPLETED
- TASK_REJECTED
- TASK_REASSIGNED
- OFFER_SENT
- OFFER_ACCEPTED
- OFFER_REJECTED
- CONTRACT_GENERATED
- CONTRACT_SIGNED
- WORKSTATION_ASSIGNED
- PROCESS_COMPLETED

---

## Fallback Assignments

If position is vacant, fallback to:

```sql
INSERT INTO process_fallback_assignment (
  organization_id,
  process_node_id,
  fallback_person_id,
  fallback_role
) VALUES (
  'org-uuid',
  'initial_screening_node',
  'hr-manager-uuid',
  'HR Manager'
);
```

---

## Integration Points

### 1. Application Submission (External)

**Trigger:** Candidate submits application via public form

```http
POST /api/public/apply.php
{
  "vacancy_id": "uuid",
  "applicant": {
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone": "+1234567890"
  },
  "resume_file": "base64_encoded_pdf",
  "cover_letter": "text",
  "expected_salary": 90000,
  "available_from": "2024-02-01"
}
```

**Process Start:**
```php
// Create VACANCY_APPLICATION
$applicationId = createApplication($data);

// Start recruitment process
$processEngine->startProcess([
  'graph_code' => 'RECRUITMENT_PROCESS',
  'organization_id' => $vacancy->organization_id,
  'entity_code' => 'VACANCY_APPLICATION',
  'entity_record_id' => $applicationId,
  'variables' => [
    'vacancy_id' => $vacancyId,
    'application_id' => $applicationId,
    'applicant_id' => $applicantId
  ]
]);
```

---

### 2. Offer Response (External)

**Trigger:** Candidate clicks "Accept/Reject" link in email

```http
POST /api/public/offer-response.php
{
  "offer_token": "secure-token",
  "action": "ACCEPT" | "REJECT" | "COUNTER",
  "counter_salary": 95000,  // if COUNTER
  "comments": "text"
}
```

**Process Trigger:**
```php
$processEngine->triggerEvent([
  'event_code' => 'OFFER_RESPONSE',
  'flow_instance_id' => $offer->flow_instance_id,
  'data' => [
    'offer_status' => $action,
    'counter_amount' => $counterSalary
  ]
]);
```

---

### 3. Contract E-Signature (External)

**Integration:** DocuSign / HelloSign / Adobe Sign

```php
// When contract signed via e-signature service
$webhookData = json_decode($request->body, true);

if ($webhookData['event'] === 'document_signed') {
  $processEngine->triggerEvent([
    'event_code' => 'CONTRACT_SIGNED',
    'flow_instance_id' => $contract->flow_instance_id,
    'data' => [
      'signature_date' => $webhookData['signed_at'],
      'signed_document_url' => $webhookData['document_url']
    ]
  ]);
}
```

---

## Performance Metrics

### Process KPIs

```sql
-- Average time to hire
SELECT
  AVG(julianday(ec.created_at) - julianday(va.application_date)) as avg_days_to_hire
FROM employment_contract ec
JOIN job_offer jo ON ec.job_offer_id = jo.id
JOIN vacancy_application va ON jo.application_id = va.id
WHERE ec.created_at >= date('now', '-90 days');

-- Application conversion rate
SELECT
  COUNT(DISTINCT CASE WHEN ec.id IS NOT NULL THEN va.id END) * 100.0 / COUNT(va.id) as conversion_rate
FROM vacancy_application va
LEFT JOIN job_offer jo ON jo.application_id = va.id
LEFT JOIN employment_contract ec ON ec.job_offer_id = jo.id
WHERE va.application_date >= date('now', '-90 days');

-- Average interviews per hire
SELECT
  AVG(interview_count) as avg_interviews_per_hire
FROM (
  SELECT
    va.id,
    COUNT(ai.id) as interview_count
  FROM vacancy_application va
  JOIN job_offer jo ON jo.application_id = va.id
  JOIN employment_contract ec ON ec.job_offer_id = jo.id
  LEFT JOIN application_interview ai ON ai.application_id = va.id
  WHERE ec.created_at >= date('now', '-90 days')
  GROUP BY va.id
);

-- Offer acceptance rate
SELECT
  COUNT(CASE WHEN status = 'ACCEPTED' THEN 1 END) * 100.0 / COUNT(*) as acceptance_rate
FROM job_offer
WHERE offer_date >= date('now', '-90 days');
```

---

## Error Handling

### Common Error Scenarios

| Error | Cause | Resolution |
|-------|-------|------------|
| No available workstation | All workstations occupied | Manual assignment, add more workstations |
| Offer expired | Candidate delayed response | Extend offer or create new one |
| Position filled | Another candidate hired first | End other pending processes |
| Budget exceeded | Negotiated salary too high | CFO approval or reject |
| Contract signing delayed | Candidate unresponsive | Follow-up reminders, escalate |
| Reference check failure | Negative references | Manager review, potential rejection |

### Rollback Actions

If process needs to be rolled back:

```sql
-- Revert application status
UPDATE vacancy_application
SET status = 'UNDER_REVIEW'
WHERE id = ?;

-- Cancel offer
UPDATE job_offer
SET status = 'WITHDRAWN'
WHERE id = ?;

-- Delete draft contract
UPDATE employment_contract
SET deleted_at = datetime('now')
WHERE id = ? AND status = 'DRAFT';
```

---

## Installation & Setup

### 1. Prerequisites

- Process Flow System installed
- Hiring & Vacancy Domain installed (HIRING_VACANCY_DOMAIN entities)
- Media File Domain installed (for document storage)
- Email service configured

### 2. Install Process Definition

```bash
sqlite3 database/v4l.sqlite < metadata/processes/recruitment_process.sql
```

### 3. Configure Positions

Ensure these positions exist with proper permissions:
- HR Coordinator (REVIEWER, SCHEDULER, VERIFIER, SENDER, ONBOARDING_PREP)
- Technical Lead (ASSESSOR)
- Hiring Manager (INTERVIEWER, REVIEWER)
- HR Manager (NEGOTIATOR, OFFER_CREATOR, OFFER_SENDER, CONTRACT_CREATOR, PROCESSOR)
- CFO (APPROVER)
- Legal Team (REVIEWER)
- Facilities Manager (WORKSPACE_MANAGER)
- IT Admin (SETUP)

### 4. Configure Interview Stages

```sql
INSERT INTO interview_stage (id, organization_id, stage_name, stage_type, order_number)
VALUES
  ('stage-1', 'org-id', 'Initial Screening', 'PHONE', 1),
  ('stage-2', 'org-id', 'Technical Interview', 'VIDEO', 2),
  ('stage-3', 'org-id', 'Manager Interview', 'IN_PERSON', 3),
  ('stage-4', 'org-id', 'Final Round', 'PANEL', 4);
```

### 5. Setup Email Templates

Create email templates in `/templates/emails/recruitment/`:
- `rejection_email.html`
- `interview_invite.html`
- `offer_letter_email.html`
- `contract_signature_request.html`
- `welcome_onboarding.html`

---

## Related Documentation

- **Hiring Domain:** [/architecture/entities/relationships/HIRING_VACANCY_DOMAIN.md](../entities/relationships/HIRING_VACANCY_DOMAIN.md)
- **Process Flow System:** [PROCESS_FLOW_SYSTEM.md](PROCESS_FLOW_SYSTEM.md)
- **Vacancy Creation Process:** [/guides/features/VACANCY_CREATION_PROCESS.md](../../guides/features/VACANCY_CREATION_PROCESS.md)
- **Process Setup Guide:** [/guides/development/PROCESS_SETUP_GUIDE.md](../../guides/development/PROCESS_SETUP_GUIDE.md)
- **Media File Management:** [/architecture/entities/relationships/MEDIA_FILE_DOMAIN.md](../entities/relationships/MEDIA_FILE_DOMAIN.md)

---

**Last Updated:** 2025-11-05
**Version:** 1.0
**Process Complexity:** High
**Estimated Implementation Time:** 40-60 hours
