# Hiring & Vacancy Domain - Entity Relationships

> **📚 Note:** This is a domain-specific relationship reference. For system-wide relationship rules, see `/architecture/entities/relationships/RELATIONSHIP_RULES.md`.

---

## Domain Overview

The Hiring & Vacancy domain manages the complete recruitment lifecycle from vacancy creation to employment contract, including applications, reviews, interviews, and job offers.

**Domain Code:** `HIRING`

**Core Entities:** 8
- ORGANIZATION_VACANCY
- ORGANIZATION_VACANCY_WORKSTATION
- VACANCY_APPLICATION
- APPLICATION_REVIEW
- INTERVIEW_STAGE
- APPLICATION_INTERVIEW
- JOB_OFFER
- EMPLOYMENT_CONTRACT

---

## Complete Hiring Flow

```
ORGANIZATION_VACANCY (Job Opening)
  ↓
VACANCY_APPLICATION (Candidate applies)
  ↓
APPLICATION_REVIEW (HR/Manager reviews)
  ↓
APPLICATION_INTERVIEW (Multiple stages)
  ↓
JOB_OFFER (Offer extended)
  ↓
EMPLOYMENT_CONTRACT (Offer accepted → Employee hired)
```

---

## 1. ORGANIZATION_VACANCY

### Entity Structure
```
ORGANIZATION_VACANCY
├─ id* (PK)
├─ organization_id* (FK → ORGANIZATION)
├─ popular_position_id* (FK → POPULAR_ORGANIZATION_POSITION)
├─ created_by* (FK → PERSON)
├─ title*
├─ description?
├─ requirements?
├─ responsibilities?
├─ number_of_openings*
├─ opening_date*
├─ closing_date?
├─ min_salary?
├─ max_salary?
├─ employment_type? [Full-time, Part-time, Contract, Intern]
├─ status* [DRAFT, PENDING, APPROVED, OPEN, CLOSED]
└─ is_urgent?
```

### Relationships
```
ORGANIZATION_VACANCY
  ← ORGANIZATION (Many:1)
  ← POPULAR_ORGANIZATION_POSITION (Many:1)
  ← PERSON (Many:1) [as creator]
  → ORGANIZATION_VACANCY_WORKSTATION (1:Many) [assigned desks]
  → VACANCY_APPLICATION (1:Many) [applicants]
  → JOB_OFFER (1:Many) [offers made]
```

**Important:** Department, Team, and Designation are NOT directly stored in ORGANIZATION_VACANCY. They are accessed through `popular_position_id`.

---

## 2. ORGANIZATION_VACANCY_WORKSTATION

### Entity Structure
```
ORGANIZATION_VACANCY_WORKSTATION (Junction Table)
├─ id* (PK)
├─ organization_vacancy_id* (FK → ORGANIZATION_VACANCY)
├─ organization_workstation_id* (FK → WORKSTATION)
└─ notes?
```

### Relationships
```
ORGANIZATION_VACANCY_WORKSTATION
  ← ORGANIZATION_VACANCY (Many:1)
  ← WORKSTATION (Many:1)
```

**Purpose:** Links job openings to physical workspace assignments. Typically assigned during department head approval in vacancy creation process.

---

## 3. VACANCY_APPLICATION

### Entity Structure
```
VACANCY_APPLICATION
├─ id* (PK)
├─ vacancy_id* (FK → ORGANIZATION_VACANCY)
├─ applicant_id* (FK → PERSON)
├─ application_date*
├─ resume_url?
├─ cover_letter?
├─ expected_salary?
├─ available_from?
├─ status* [SUBMITTED, UNDER_REVIEW, SHORTLISTED, REJECTED, WITHDRAWN]
└─ withdrawn_reason?
```

### Relationships
```
VACANCY_APPLICATION
  ← ORGANIZATION_VACANCY (Many:1)
  ← PERSON (Many:1) [as applicant]
  → APPLICATION_REVIEW (1:Many)
  → APPLICATION_INTERVIEW (1:Many)
  → JOB_OFFER (1:1) [if selected]
```

---

## 4. APPLICATION_REVIEW

### Entity Structure
```
APPLICATION_REVIEW
├─ id* (PK)
├─ application_id* (FK → VACANCY_APPLICATION)
├─ reviewed_by* (FK → PERSON)
├─ review_date*
├─ review_notes?
├─ rating? [1-10 scale]
├─ status* [PENDING, APPROVED, REJECTED]
└─ proceed_to_interview?
```

### Relationships
```
APPLICATION_REVIEW
  ← VACANCY_APPLICATION (Many:1)
  ← PERSON (Many:1) [as reviewer]
```

**Purpose:** Initial screening by HR or hiring manager. Multiple reviews possible per application.

---

## 5. INTERVIEW_STAGE

### Entity Structure
```
INTERVIEW_STAGE
├─ id* (PK)
├─ organization_id* (FK → ORGANIZATION)
├─ name* [Phone Screening, Technical, Behavioral, HR, Final]
├─ description?
├─ order_number*
├─ duration_minutes?
└─ is_active*
```

### Relationships
```
INTERVIEW_STAGE
  ← ORGANIZATION (Many:1)
  → APPLICATION_INTERVIEW (1:Many)
```

**Purpose:** Define reusable interview stage templates per organization.

**Example Stages:**
```
1. Phone Screening (15-30 mins)
2. Technical Interview (60-90 mins)
3. System Design Interview (60 mins)
4. Behavioral Interview (45 mins)
5. Final Round / Panel Interview (60 mins)
```

---

## 6. APPLICATION_INTERVIEW

### Entity Structure
```
APPLICATION_INTERVIEW
├─ id* (PK)
├─ application_id* (FK → VACANCY_APPLICATION)
├─ stage_id* (FK → INTERVIEW_STAGE)
├─ interviewer_id* (FK → PERSON)
├─ scheduled_date*
├─ actual_date?
├─ location?
├─ interview_mode? [IN_PERSON, VIDEO, PHONE]
├─ feedback_notes?
├─ rating? [1-10 scale]
├─ strengths?
├─ weaknesses?
├─ status* [SCHEDULED, COMPLETED, CANCELLED, NO_SHOW]
└─ recommendation? [HIRE, MAYBE, NO_HIRE]
```

### Relationships
```
APPLICATION_INTERVIEW
  ← VACANCY_APPLICATION (Many:1)
  ← INTERVIEW_STAGE (Many:1)
  ← PERSON (Many:1) [as interviewer]
```

**Multiple Interviews:** A candidate typically goes through multiple interview stages.

---

## 7. JOB_OFFER

### Entity Structure
```
JOB_OFFER
├─ id* (PK)
├─ application_id* (FK → VACANCY_APPLICATION)
├─ vacancy_id* (FK → ORGANIZATION_VACANCY)
├─ offered_to* (FK → PERSON)
├─ offered_by* (FK → PERSON)
├─ offer_date*
├─ offer_letter_url?
├─ offered_salary*
├─ offered_position_id* (FK → POPULAR_ORGANIZATION_POSITION)
├─ start_date?
├─ benefits?
├─ terms_and_conditions?
├─ expiry_date?
├─ status* [DRAFT, SENT, ACCEPTED, REJECTED, WITHDRAWN, EXPIRED]
├─ accepted_date?
├─ rejected_date?
└─ rejection_reason?
```

### Relationships
```
JOB_OFFER
  ← VACANCY_APPLICATION (1:1) [Optional - external hires may skip application]
  ← ORGANIZATION_VACANCY (Many:1)
  ← PERSON (Many:1) [as candidate - offered_to]
  ← PERSON (Many:1) [as hiring manager - offered_by]
  ← POPULAR_ORGANIZATION_POSITION (Many:1) [offered position]
  → EMPLOYMENT_CONTRACT (1:1) [if accepted]
```

**Status Flow:**
```
DRAFT → SENT → {ACCEPTED, REJECTED, WITHDRAWN, EXPIRED}
```

---

## 8. EMPLOYMENT_CONTRACT

### Entity Structure
```
EMPLOYMENT_CONTRACT
├─ id* (PK)
├─ job_offer_id? (FK → JOB_OFFER)
├─ organization_id* (FK → ORGANIZATION)
├─ employee_id* (FK → PERSON)
├─ position_id? (FK → POPULAR_ORGANIZATION_POSITION)
├─ job_title*
├─ contract_number*
├─ contract_type* [Permanent, Contract, Intern, Temporary]
├─ start_date*
├─ end_date? [Required for Contract/Intern]
├─ salary?
├─ status* [ACTIVE, TERMINATED, SUSPENDED, COMPLETED]
├─ termination_date?
├─ termination_reason?
└─ contract_document_url?
```

### Relationships
```
EMPLOYMENT_CONTRACT
  ← JOB_OFFER (1:1) [Optional - for new hires]
  ← ORGANIZATION (Many:1)
  ← PERSON (Many:1) [as employee]
  ← POPULAR_ORGANIZATION_POSITION (Many:1) [Optional]
```

**Purpose:** Active employment record. Creates the position resolution chain used by the process flow system.

---

## Complete Hiring Lifecycle Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                     HIRING LIFECYCLE                             │
└─────────────────────────────────────────────────────────────────┘

ORGANIZATION_VACANCY (Created)
  ↓ Status: DRAFT → PENDING → APPROVED → OPEN
  │
  ├─ ORGANIZATION_VACANCY_WORKSTATION (Workstation assigned)
  │
  ↓
VACANCY_APPLICATION (Candidate applies)
  ↓ Status: SUBMITTED
  │
APPLICATION_REVIEW (Initial screening)
  ↓ Status: APPROVED → proceed_to_interview = true
  │
APPLICATION_INTERVIEW (Stage 1: Phone Screening)
  ↓ Status: COMPLETED, recommendation: HIRE
  │
APPLICATION_INTERVIEW (Stage 2: Technical)
  ↓ Status: COMPLETED, recommendation: HIRE
  │
APPLICATION_INTERVIEW (Stage 3: Behavioral)
  ↓ Status: COMPLETED, recommendation: HIRE
  │
APPLICATION_INTERVIEW (Stage 4: Final)
  ↓ Status: COMPLETED, recommendation: HIRE
  │
JOB_OFFER (Offer extended)
  ↓ Status: DRAFT → SENT
  │
  ├─ ACCEPTED → EMPLOYMENT_CONTRACT (Employee hired)
  │                ↓ Status: ACTIVE
  │                └─ Used for: Position resolution in process flow
  │
  ├─ REJECTED → End (Candidate declined)
  │
  ├─ WITHDRAWN → End (Company withdrew offer)
  │
  └─ EXPIRED → End (Candidate didn't respond)
```

---

## Position Resolution Chain

This is critical for the Process Flow System:

```
EMPLOYMENT_CONTRACT
  ↓ (via job_offer_id)
JOB_OFFER
  ↓ (via application_id)
VACANCY_APPLICATION
  ↓ (via vacancy_id)
ORGANIZATION_VACANCY
  ↓ (via popular_position_id)
POPULAR_ORGANIZATION_POSITION
  ↓
Used by: PROCESS_NODE (task assignment)
```

**Example:**
```
John Smith is hired as "Senior Backend Developer"
  → EMPLOYMENT_CONTRACT (employee_id = John, position_id = Senior Backend Dev)
    → Tasks requiring "Senior Backend Developer with APPROVER permission"
      → Can be assigned to John Smith
```

---

## Cross-Domain Relationships

### To Person Domain
```
ORGANIZATION_VACANCY ← PERSON (via created_by)
VACANCY_APPLICATION ← PERSON (via applicant_id)
APPLICATION_REVIEW ← PERSON (via reviewed_by)
APPLICATION_INTERVIEW ← PERSON (via interviewer_id)
JOB_OFFER ← PERSON (via offered_to, offered_by)
EMPLOYMENT_CONTRACT ← PERSON (via employee_id)
```
See: [PERSON_IDENTITY_DOMAIN.md](PERSON_IDENTITY_DOMAIN.md)

### To Organization Domain
```
ORGANIZATION_VACANCY ← ORGANIZATION
INTERVIEW_STAGE ← ORGANIZATION
EMPLOYMENT_CONTRACT ← ORGANIZATION
ORGANIZATION_VACANCY_WORKSTATION ← WORKSTATION
```
See: [ORGANIZATION_DOMAIN.md](ORGANIZATION_DOMAIN.md)

### To Popular Organization Structure
```
ORGANIZATION_VACANCY ← POPULAR_ORGANIZATION_POSITION
JOB_OFFER ← POPULAR_ORGANIZATION_POSITION
EMPLOYMENT_CONTRACT ← POPULAR_ORGANIZATION_POSITION
```
See: [POPULAR_ORGANIZATION_STRUCTURE.md](POPULAR_ORGANIZATION_STRUCTURE.md)

### To Process Flow Domain
```
ORGANIZATION_VACANCY used by TASK_FLOW_INSTANCE (vacancy creation process)
EMPLOYMENT_CONTRACT used by PositionResolver (task assignment)
```
See: [PROCESS_FLOW_DOMAIN.md](PROCESS_FLOW_DOMAIN.md)

---

## Common Queries

### Get Active Vacancies
```sql
SELECT
    v.*,
    o.short_name as organization,
    pos.title as position,
    p.first_name || ' ' || p.last_name as created_by_name
FROM organization_vacancy v
JOIN organization o ON v.organization_id = o.id
JOIN popular_organization_position pos ON v.popular_position_id = pos.id
JOIN person p ON v.created_by = p.id
WHERE v.status = 'OPEN'
AND v.deleted_at IS NULL
AND (v.closing_date IS NULL OR v.closing_date > date('now'))
ORDER BY v.is_urgent DESC, v.opening_date DESC;
```

### Get Application Pipeline for Vacancy
```sql
SELECT
    va.id,
    p.first_name || ' ' || p.last_name as applicant_name,
    va.application_date,
    va.status,
    COUNT(DISTINCT ai.id) as interview_count,
    AVG(ai.rating) as avg_interview_rating,
    jo.status as offer_status
FROM vacancy_application va
JOIN person p ON va.applicant_id = p.id
LEFT JOIN application_interview ai ON ai.application_id = va.id
LEFT JOIN job_offer jo ON jo.application_id = va.id
WHERE va.vacancy_id = ?
AND va.deleted_at IS NULL
GROUP BY va.id
ORDER BY va.application_date DESC;
```

### Get Interview Schedule for Applicant
```sql
SELECT
    ai.*,
    ist.name as stage_name,
    ist.order_number,
    interviewer.first_name || ' ' || interviewer.last_name as interviewer_name,
    v.title as vacancy_title
FROM application_interview ai
JOIN interview_stage ist ON ai.stage_id = ist.id
JOIN person interviewer ON ai.interviewer_id = interviewer.id
JOIN vacancy_application va ON ai.application_id = va.id
JOIN organization_vacancy v ON va.vacancy_id = v.id
WHERE va.applicant_id = ?
AND ai.status IN ('SCHEDULED', 'COMPLETED')
ORDER BY ist.order_number, ai.scheduled_date;
```

### Get Active Employees by Position
```sql
SELECT
    ec.*,
    p.first_name || ' ' || p.last_name as employee_name,
    pos.title as position_title,
    o.short_name as organization
FROM employment_contract ec
JOIN person p ON ec.employee_id = p.id
LEFT JOIN popular_organization_position pos ON ec.position_id = pos.id
JOIN organization o ON ec.organization_id = o.id
WHERE ec.status = 'ACTIVE'
AND ec.deleted_at IS NULL
AND ec.position_id = ?
ORDER BY ec.start_date;
```

---

## Common Patterns

### Pattern: Vacancy Status Flow
```
DRAFT (being created)
  ↓
PENDING (submitted for approval)
  ↓
APPROVED (approved by management)
  ↓
OPEN (published, accepting applications)
  ↓
CLOSED (no longer accepting applications)
```

### Pattern: Application Status Flow
```
SUBMITTED (initial application)
  ↓
UNDER_REVIEW (being screened)
  ↓
SHORTLISTED (passed initial review)
  ↓
{REJECTED, WITHDRAWN} (end states)
```

### Pattern: Multiple Interviews
```
APPLICATION_INTERVIEW (Phone Screening, order_number=1)
  ↓ recommendation = HIRE
APPLICATION_INTERVIEW (Technical, order_number=2)
  ↓ recommendation = HIRE
APPLICATION_INTERVIEW (Final, order_number=3)
  ↓ recommendation = HIRE
JOB_OFFER created
```

---

## Data Integrity Rules

1. **Vacancy Must Have Position:**
   - Every vacancy MUST reference a `popular_position_id`
   - Position determines department, team, and designation

2. **Application Uniqueness:**
   - A person cannot apply to the same vacancy twice
   - Constraint: UNIQUE(vacancy_id, applicant_id)

3. **Offer Acceptance:**
   - Only ACCEPTED offers create employment contracts
   - One offer per application

4. **Employment Contract:**
   - Can exist without job_offer_id (e.g., existing employees)
   - Must have organization_id and employee_id

5. **Interview Ordering:**
   - Interview stages should be completed in order
   - Use `interview_stage.order_number` to enforce sequence

---

## Related Documentation

- **Entity Creation Rules:** [/architecture/entities/ENTITY_CREATION_RULES.md](../ENTITY_CREATION_RULES.md)
- **Relationship Rules:** [RELATIONSHIP_RULES.md](RELATIONSHIP_RULES.md)
- **Vacancy Creation Guide:** [/guides/features/VACANCY_CREATION_PROCESS.md](../../guides/features/VACANCY_CREATION_PROCESS.md)
- **Process Setup Guide:** [/guides/development/PROCESS_SETUP_GUIDE.md](../../guides/development/PROCESS_SETUP_GUIDE.md)
- **All Domain Relationships:** [README.md](README.md)

---

**Last Updated:** 2025-10-31
**Domain:** Hiring & Vacancy
