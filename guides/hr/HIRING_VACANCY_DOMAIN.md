# Hiring & Vacancy Domain - Entity Relationships

> **📚 Note:** This is a domain-specific relationship reference. For system-wide relationship rules, see `/architecture/entities/relationships/RELATIONSHIP_RULES.md`.

---

## Domain Overview

The Hiring & Vacancy domain manages the complete recruitment lifecycle: from posting job openings, receiving applications, conducting interviews, making offers, to finalizing employment contracts.

**Domain Code:** `HIRING_VACANCY`

**Core Entities:** 8
- ORGANIZATION_VACANCY
- ORGANIZATION_VACANCY_WORKSTATION
- VACANCY_APPLICATION
- APPLICATION_REVIEW
- APPLICATION_INTERVIEW
- INTERVIEW_STAGE
- JOB_OFFER
- EMPLOYMENT_CONTRACT

---

## Hiring Lifecycle Overview

```
1. ORGANIZATION_VACANCY (Job opening created)
   ├─ Links to POPULAR_ORGANIZATION_POSITION
   ├─ Links to multiple WORKSTATION via junction table
   └─ Created by organization admin

2. VACANCY_APPLICATION (Candidates apply)
   ├─ Applicant submits resume and details
   └─ Status: SUBMITTED

3. APPLICATION_REVIEW (HR/Manager reviews)
   ├─ Multiple reviews possible
   └─ Status: UNDER_REVIEW → SHORTLISTED/REJECTED

4. APPLICATION_INTERVIEW (Interview process)
   ├─ Multiple interview stages
   └─ Status: SCHEDULED → COMPLETED

5. JOB_OFFER (Offer extended)
   ├─ Offer letter, salary, terms
   └─ Status: SENT → ACCEPTED/REJECTED

6. EMPLOYMENT_CONTRACT (Final contract)
   ├─ Active employment record
   └─ Links to organization and position
```

---

## 1. ORGANIZATION_VACANCY

### Entity Structure
```
ORGANIZATION_VACANCY
├─ id* (PK)
├─ organization_id* (FK → ORGANIZATION)
├─ popular_position_id* (FK → POPULAR_ORGANIZATION_POSITION)
├─ vacancy_code* (Unique identifier, e.g., "JOB-2024-001")
├─ title* (Job title, can override position title)
├─ description* (Detailed job description)
├─ responsibilities? (Key responsibilities)
├─ requirements? (Must-have requirements)
├─ preferred_qualifications? (Nice-to-have qualifications)
├─ employment_type* [FULL_TIME, PART_TIME, CONTRACT, INTERN, TEMPORARY]
├─ experience_required? (Years of experience)
├─ salary_min? (Minimum salary offered)
├─ salary_max? (Maximum salary offered)
├─ salary_currency? (Currency code, e.g., USD, EUR)
├─ number_of_openings* (How many positions available)
├─ filled_positions* (How many filled so far, default: 0)
├─ application_deadline? (Last date to apply)
├─ start_date? (Expected start date)
├─ work_location_type* [ON_SITE, REMOTE, HYBRID]
├─ is_remote_allowed* (Boolean)
├─ status* [DRAFT, PUBLISHED, CLOSED, CANCELLED, ON_HOLD]
├─ published_date? (When it was published)
├─ closed_date? (When it was closed)
├─ created_by* (FK → PERSON) (Who created the vacancy)
├─ created_at*
├─ updated_at?
└─ is_active*
```

### Relationships
```
ORGANIZATION_VACANCY
  ← ORGANIZATION (Many:1)
  ← POPULAR_ORGANIZATION_POSITION (Many:1)
  ← PERSON (Many:1) [via created_by] - Creator
  → ORGANIZATION_VACANCY_WORKSTATION (1:Many) - Assigned workstations
  → VACANCY_APPLICATION (1:Many) - Applications received
  → JOB_OFFER (1:Many) - Offers made
  → TASK_FLOW_INSTANCE (1:1) [Optional - if process flow enabled]
```

### Status Flow
```
DRAFT → PUBLISHED → {CLOSED, CANCELLED, ON_HOLD}
         ↓
    Applications received
         ↓
    CLOSED (when all positions filled or deadline passed)
```

### Business Rules
1. **Number of Openings:** `filled_positions` cannot exceed `number_of_openings`
2. **Status Transitions:**
   - DRAFT → PUBLISHED (publish vacancy)
   - PUBLISHED → CLOSED (all positions filled or manual close)
   - PUBLISHED → CANCELLED (cancel vacancy)
   - PUBLISHED → ON_HOLD (temporarily pause)
   - ON_HOLD → PUBLISHED (resume)
3. **Workstation Assignment:** A vacancy can have multiple workstations assigned
4. **Application Deadline:** Applications not accepted after deadline passes

---

## 2. ORGANIZATION_VACANCY_WORKSTATION

**Junction Table** linking vacancies to workstations.

### Entity Structure
```
ORGANIZATION_VACANCY_WORKSTATION
├─ id* (PK)
├─ vacancy_id* (FK → ORGANIZATION_VACANCY)
├─ workstation_id* (FK → WORKSTATION)
├─ assigned_to_applicant_id? (FK → PERSON) [Set when candidate selected]
├─ assigned_date? (When workstation was assigned to candidate)
├─ is_occupied* (Boolean, default: false)
└─ notes? (Additional notes about the assignment)
```

### Relationships
```
ORGANIZATION_VACANCY_WORKSTATION
  ← ORGANIZATION_VACANCY (Many:1)
  ← WORKSTATION (Many:1)
  ← PERSON (Many:1) [Optional - assigned applicant]
```

### Purpose
- Links multiple workstations to a single vacancy
- Tracks which workstation will be assigned to which selected candidate
- Allows pre-planning of workspace allocation

### Example
```
Vacancy: "Senior Software Engineer" (3 openings)
  ├─ Workstation SF-101 → Assigned to John Doe
  ├─ Workstation SF-102 → Assigned to Jane Smith
  └─ Workstation SF-103 → Not yet assigned
```

---

## 3. VACANCY_APPLICATION

### Entity Structure
```
VACANCY_APPLICATION
├─ id* (PK)
├─ vacancy_id* (FK → ORGANIZATION_VACANCY)
├─ applicant_id* (FK → PERSON)
├─ application_date*
├─ resume_media_file_id? (FK → MEDIA_FILE)
├─ cover_letter? (Text or could be media_file_id)
├─ expected_salary?
├─ available_from? (When can start)
├─ status* [SUBMITTED, UNDER_REVIEW, SHORTLISTED, INTERVIEW_SCHEDULED,
            INTERVIEWED, OFFER_MADE, REJECTED, WITHDRAWN, HIRED]
├─ rejection_reason? (Why rejected)
├─ withdrawn_reason? (Why applicant withdrew)
├─ withdrawn_date?
└─ is_active*
```

### Relationships
```
VACANCY_APPLICATION
  ← ORGANIZATION_VACANCY (Many:1)
  ← PERSON (Many:1) [as applicant]
  ← MEDIA_FILE (Many:1) [via resume_media_file_id]
  → APPLICATION_REVIEW (1:Many) - Reviews from different reviewers
  → APPLICATION_INTERVIEW (1:Many) - Interview records
  → JOB_OFFER (1:1) [Optional - if selected]
```

### Status Flow
```
SUBMITTED → UNDER_REVIEW → SHORTLISTED → INTERVIEW_SCHEDULED
                              ↓                      ↓
                         REJECTED              INTERVIEWED
                                                      ↓
                                               OFFER_MADE → HIRED
                                                      ↓
                                                 REJECTED
```

**Note:** Polymorphic files can also be attached:
```
MEDIA_FILE (where entity_type='VACANCY_APPLICATION')
  → Resume (field_context='RESUME')
  → Cover Letter (field_context='COVER_LETTER')
  → Portfolio (field_context='PORTFOLIO')
  → Certificates (field_context='CERTIFICATE')
```

---

## 4. APPLICATION_REVIEW

### Entity Structure
```
APPLICATION_REVIEW
├─ id* (PK)
├─ application_id* (FK → VACANCY_APPLICATION)
├─ reviewed_by* (FK → PERSON) [Reviewer]
├─ review_date*
├─ rating? (1-5 or 1-10 scale)
├─ feedback? (Written feedback)
├─ recommendation* [PROCEED, REJECT, MAYBE]
├─ notes?
└─ review_stage? [RESUME_SCREENING, TECHNICAL_REVIEW, HR_REVIEW]
```

### Relationships
```
APPLICATION_REVIEW
  ← VACANCY_APPLICATION (Many:1)
  ← PERSON (Many:1) [as reviewer]
```

### Purpose
- Multiple reviewers can review the same application
- Track feedback and recommendations at different stages
- Support collaborative hiring decisions

---

## 5. INTERVIEW_STAGE

**Reference/Configuration** entity for interview stages.

### Entity Structure
```
INTERVIEW_STAGE
├─ id* (PK)
├─ organization_id* (FK → ORGANIZATION)
├─ stage_name* (e.g., "Phone Screening", "Technical Interview", "HR Round")
├─ stage_type* [PHONE, VIDEO, IN_PERSON, TECHNICAL_TEST, HR_ROUND, PANEL]
├─ order_number* (Sequence: 1, 2, 3...)
├─ duration_minutes? (Expected duration)
├─ description?
└─ is_active*
```

### Relationships
```
INTERVIEW_STAGE
  ← ORGANIZATION (Many:1)
  → APPLICATION_INTERVIEW (1:Many)
```

### Purpose
- Organizations define their interview process stages
- Reusable templates for consistent interview flow
- Track progress through standardized stages

---

## 6. APPLICATION_INTERVIEW

### Entity Structure
```
APPLICATION_INTERVIEW
├─ id* (PK)
├─ application_id* (FK → VACANCY_APPLICATION)
├─ interview_stage_id* (FK → INTERVIEW_STAGE)
├─ interviewer_id* (FK → PERSON)
├─ scheduled_date*
├─ scheduled_time*
├─ duration_minutes?
├─ location? (Office, Zoom link, etc.)
├─ interview_type* [PHONE, VIDEO, IN_PERSON, TECHNICAL_TEST]
├─ status* [SCHEDULED, COMPLETED, CANCELLED, RESCHEDULED, NO_SHOW]
├─ completed_date?
├─ rating? (1-5 or 1-10)
├─ feedback?
├─ recommendation* [STRONG_YES, YES, MAYBE, NO, STRONG_NO]
└─ notes?
```

### Relationships
```
APPLICATION_INTERVIEW
  ← VACANCY_APPLICATION (Many:1)
  ← INTERVIEW_STAGE (Many:1)
  ← PERSON (Many:1) [as interviewer]
```

### Purpose
- Track each interview session
- Multiple interviews per application (multiple stages)
- Record feedback and recommendations

---

## 7. JOB_OFFER

### Entity Structure
```
JOB_OFFER
├─ id* (PK)
├─ application_id? (FK → VACANCY_APPLICATION) [Can be null for direct hire]
├─ vacancy_id* (FK → ORGANIZATION_VACANCY)
├─ offered_to* (FK → PERSON) [Candidate]
├─ offered_by* (FK → PERSON) [Hiring manager]
├─ offer_date*
├─ offer_letter_media_file_id? (FK → MEDIA_FILE)
├─ offered_salary*
├─ offered_position_id* (FK → POPULAR_ORGANIZATION_POSITION)
├─ start_date? (Proposed start date)
├─ benefits? (Healthcare, PTO, etc.)
├─ terms_and_conditions?
├─ expiry_date? (Offer valid until)
├─ status* [DRAFT, SENT, ACCEPTED, REJECTED, WITHDRAWN, EXPIRED]
├─ accepted_date?
├─ rejected_date?
├─ rejection_reason?
└─ notes?
```

### Relationships
```
JOB_OFFER
  ← VACANCY_APPLICATION (1:1) [Optional - can skip application for internal promotion]
  ← ORGANIZATION_VACANCY (Many:1)
  ← PERSON (Many:1) [as candidate]
  ← PERSON (Many:1) [as hiring manager]
  ← POPULAR_ORGANIZATION_POSITION (Many:1)
  ← MEDIA_FILE (Many:1) [via offer_letter_media_file_id]
  → EMPLOYMENT_CONTRACT (1:1) [if accepted]
```

### Status Flow
```
DRAFT → SENT → {ACCEPTED, REJECTED, WITHDRAWN, EXPIRED}
```

### Business Rules
1. **Application Link Optional:** Direct hires may not have application_id
2. **Expiry:** Offers automatically expire after expiry_date
3. **One Active Offer:** Only one SENT offer per vacancy per person
4. **Contract Creation:** Employment contract created only if ACCEPTED

---

## 8. EMPLOYMENT_CONTRACT

### Entity Structure
```
EMPLOYMENT_CONTRACT
├─ id* (PK)
├─ job_offer_id? (FK → JOB_OFFER) [For new hires]
├─ organization_id* (FK → ORGANIZATION)
├─ employee_id* (FK → PERSON)
├─ position_id? (FK → POPULAR_ORGANIZATION_POSITION)
├─ job_title*
├─ contract_number* (Unique contract identifier)
├─ contract_type* [PERMANENT, CONTRACT, INTERN, TEMPORARY]
├─ start_date*
├─ end_date? [Required for CONTRACT/INTERN/TEMPORARY]
├─ salary?
├─ benefits?
├─ workstation_id? (FK → WORKSTATION) [Assigned workspace]
├─ reporting_to? (FK → PERSON) [Manager/Supervisor]
├─ status* [ACTIVE, TERMINATED, SUSPENDED, COMPLETED]
├─ termination_date?
├─ termination_reason?
├─ contract_document_media_file_id? (FK → MEDIA_FILE)
└─ is_active*
```

### Relationships
```
EMPLOYMENT_CONTRACT
  ← JOB_OFFER (1:1) [Optional - for new hires from vacancy]
  ← ORGANIZATION (Many:1)
  ← PERSON (Many:1) [as employee]
  ← POPULAR_ORGANIZATION_POSITION (Many:1) [Optional]
  ← WORKSTATION (Many:1) [Optional - assigned desk]
  ← PERSON (Many:1) [as manager - via reporting_to]
  ← MEDIA_FILE (Many:1) [via contract_document_media_file_id]
```

### Purpose
- Active employment record
- Links employee to organization and position
- Used by **Process Flow System** for position resolution (task assignment)
- Tracks employment history

### Business Rules
1. **Contract Types:**
   - PERMANENT: No end_date required
   - CONTRACT/INTERN/TEMPORARY: end_date required
2. **Workstation Assignment:** Can be updated if employee changes desk
3. **Status Transitions:**
   - ACTIVE → TERMINATED (resignation, termination)
   - ACTIVE → SUSPENDED (temporary suspension)
   - CONTRACT/TEMPORARY → COMPLETED (contract period ends)

---

## Complete Hiring Flow Diagram

```
Organization creates ORGANIZATION_VACANCY
  ↓
Links to POPULAR_ORGANIZATION_POSITION (standard job role)
  ↓
Assigns WORKSTATION(s) via ORGANIZATION_VACANCY_WORKSTATION
  ↓
Candidates submit VACANCY_APPLICATION
  ↓
HR/Managers create APPLICATION_REVIEW (screening)
  ↓
Shortlisted candidates invited to APPLICATION_INTERVIEW
  ↓ (Multiple stages possible)
Interviews completed, feedback collected
  ↓
Selected candidate receives JOB_OFFER
  ↓
Candidate ACCEPTS offer
  ↓
EMPLOYMENT_CONTRACT created
  ↓
Workstation assigned from ORGANIZATION_VACANCY_WORKSTATION
  ↓
ORGANIZATION_VACANCY.filled_positions incremented
  ↓
If all positions filled → VACANCY status = CLOSED
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
EMPLOYMENT_CONTRACT ← PERSON (via employee_id, reporting_to)
```
See: [PERSON_IDENTITY_DOMAIN.md](PERSON_IDENTITY_DOMAIN.md)

### To Organization Domain
```
ORGANIZATION_VACANCY ← ORGANIZATION
INTERVIEW_STAGE ← ORGANIZATION
EMPLOYMENT_CONTRACT ← ORGANIZATION
ORGANIZATION_VACANCY_WORKSTATION ← WORKSTATION
EMPLOYMENT_CONTRACT ← WORKSTATION
```
See: [ORGANIZATION_DOMAIN.md](ORGANIZATION_DOMAIN.md)

### To Popular Organization Structure
```
ORGANIZATION_VACANCY ← POPULAR_ORGANIZATION_POSITION
JOB_OFFER ← POPULAR_ORGANIZATION_POSITION
EMPLOYMENT_CONTRACT ← POPULAR_ORGANIZATION_POSITION
```
See: [POPULAR_ORGANIZATION_STRUCTURE.md](POPULAR_ORGANIZATION_STRUCTURE.md)

### To Media & File Domain
```
VACANCY_APPLICATION ← MEDIA_FILE (via resume_media_file_id)
JOB_OFFER ← MEDIA_FILE (via offer_letter_media_file_id)
EMPLOYMENT_CONTRACT ← MEDIA_FILE (via contract_document_media_file_id)
```
See: [MEDIA_FILE_DOMAIN.md](MEDIA_FILE_DOMAIN.md)

**Note:** Additional files via polymorphic relationship:
```
MEDIA_FILE (where entity_type='VACANCY_APPLICATION')
  → Resumes (field_context='RESUME')
  → Cover Letters (field_context='COVER_LETTER')
  → Portfolios (field_context='PORTFOLIO')
  → Certificates (field_context='CERTIFICATE')
```

### To Process Flow Domain
```
ORGANIZATION_VACANCY → TASK_FLOW_INSTANCE (vacancy creation process)
EMPLOYMENT_CONTRACT used by PositionResolver (task assignment)
```
See: [PROCESS_FLOW_DOMAIN.md](PROCESS_FLOW_DOMAIN.md)

---

## Common Queries

### 1. Get Active Vacancies for Organization
```sql
SELECT
    v.*,
    o.short_name as organization_name,
    pos.code as position_code,
    p.first_name || ' ' || p.last_name as created_by_name,
    (v.number_of_openings - v.filled_positions) as open_positions
FROM organization_vacancy v
JOIN organization o ON v.organization_id = o.id
JOIN popular_organization_position pos ON v.popular_position_id = pos.id
JOIN person p ON v.created_by = p.id
WHERE v.organization_id = ?
  AND v.status = 'PUBLISHED'
  AND v.is_active = 1
  AND v.deleted_at IS NULL
ORDER BY v.created_at DESC;
```

### 2. Get Workstations Assigned to Vacancy
```sql
SELECT
    vw.*,
    w.workstation_code,
    w.floor_number,
    b.name as building_name,
    CASE
        WHEN vw.assigned_to_applicant_id IS NOT NULL
        THEN p.first_name || ' ' || p.last_name
        ELSE 'Not assigned'
    END as assigned_to
FROM organization_vacancy_workstation vw
JOIN workstation w ON vw.workstation_id = w.id
JOIN organization_building b ON w.organization_building_id = b.id
LEFT JOIN person p ON vw.assigned_to_applicant_id = p.id
WHERE vw.vacancy_id = ?
  AND vw.deleted_at IS NULL
ORDER BY w.workstation_code;
```

### 3. Get Applications for Vacancy with Status
```sql
SELECT
    va.*,
    p.first_name || ' ' || p.last_name as applicant_name,
    p.primary_email_address,
    p.primary_phone_number,
    COUNT(DISTINCT ar.id) as review_count,
    COUNT(DISTINCT ai.id) as interview_count
FROM vacancy_application va
JOIN person p ON va.applicant_id = p.id
LEFT JOIN application_review ar ON ar.application_id = va.id
LEFT JOIN application_interview ai ON ai.application_id = va.id
WHERE va.vacancy_id = ?
  AND va.deleted_at IS NULL
GROUP BY va.id
ORDER BY va.application_date DESC;
```

### 4. Get Interview Schedule for Organization
```sql
SELECT
    ai.*,
    va.vacancy_id,
    v.title as vacancy_title,
    p1.first_name || ' ' || p1.last_name as applicant_name,
    p2.first_name || ' ' || p2.last_name as interviewer_name,
    ist.stage_name
FROM application_interview ai
JOIN vacancy_application va ON ai.application_id = va.id
JOIN organization_vacancy v ON va.vacancy_id = v.id
JOIN person p1 ON va.applicant_id = p1.id
JOIN person p2 ON ai.interviewer_id = p2.id
JOIN interview_stage ist ON ai.interview_stage_id = ist.id
WHERE v.organization_id = ?
  AND ai.status = 'SCHEDULED'
  AND ai.scheduled_date >= date('now')
  AND ai.deleted_at IS NULL
ORDER BY ai.scheduled_date, ai.scheduled_time;
```

### 5. Get Offers Pending Response
```sql
SELECT
    jo.*,
    p.first_name || ' ' || p.last_name as candidate_name,
    v.title as vacancy_title,
    pos.code as position_code
FROM job_offer jo
JOIN person p ON jo.offered_to = p.id
JOIN organization_vacancy v ON jo.vacancy_id = v.id
JOIN popular_organization_position pos ON jo.offered_position_id = pos.id
WHERE v.organization_id = ?
  AND jo.status = 'SENT'
  AND (jo.expiry_date IS NULL OR jo.expiry_date >= date('now'))
  AND jo.deleted_at IS NULL
ORDER BY jo.offer_date DESC;
```

### 6. Get Employment Contracts for Organization
```sql
SELECT
    ec.*,
    p.first_name || ' ' || p.last_name as employee_name,
    pos.code as position_code,
    w.workstation_code
FROM employment_contract ec
JOIN person p ON ec.employee_id = p.id
LEFT JOIN popular_organization_position pos ON ec.position_id = pos.id
LEFT JOIN workstation w ON ec.workstation_id = w.id
WHERE ec.organization_id = ?
  AND ec.status = 'ACTIVE'
  AND ec.deleted_at IS NULL
ORDER BY ec.start_date DESC;
```

### 7. Vacancy Statistics Dashboard
```sql
SELECT
    v.id,
    v.title,
    v.number_of_openings,
    v.filled_positions,
    COUNT(DISTINCT va.id) as total_applications,
    COUNT(DISTINCT CASE WHEN va.status = 'SHORTLISTED' THEN va.id END) as shortlisted,
    COUNT(DISTINCT ai.id) as interviews_conducted,
    COUNT(DISTINCT jo.id) as offers_made,
    COUNT(DISTINCT ec.id) as contracts_signed
FROM organization_vacancy v
LEFT JOIN vacancy_application va ON va.vacancy_id = v.id AND va.deleted_at IS NULL
LEFT JOIN application_interview ai ON ai.application_id = va.id AND ai.deleted_at IS NULL
LEFT JOIN job_offer jo ON jo.vacancy_id = v.id AND jo.deleted_at IS NULL
LEFT JOIN employment_contract ec ON ec.job_offer_id = jo.id AND ec.deleted_at IS NULL
WHERE v.organization_id = ?
  AND v.deleted_at IS NULL
GROUP BY v.id
ORDER BY v.created_at DESC;
```

---

## Data Integrity Rules

1. **Vacancy Capacity:**
   - `filled_positions` ≤ `number_of_openings`
   - Auto-close when `filled_positions` = `number_of_openings`

2. **Application Uniqueness:**
   - One person can apply once per vacancy
   - Unique constraint: (vacancy_id, applicant_id)

3. **Workstation Assignment:**
   - One workstation assigned to one person per vacancy
   - Cannot assign already-occupied workstation

4. **Interview Sequencing:**
   - Interviews follow interview_stage.order_number
   - Cannot skip stages

5. **Offer Expiry:**
   - Auto-expire offers past expiry_date
   - Cannot accept expired offers

6. **Contract Creation:**
   - Only create contract after offer ACCEPTED
   - Link contract to job_offer_id

7. **Soft Deletes:**
   - All entities use soft deletes (deleted_at)
   - Always filter `deleted_at IS NULL`

---

## Related Documentation

- **Entity Creation Rules:** [/architecture/entities/ENTITY_CREATION_RULES.md](../ENTITY_CREATION_RULES.md)
- **Relationship Rules:** [RELATIONSHIP_RULES.md](RELATIONSHIP_RULES.md)
- **Media & File Management:** [MEDIA_FILE_DOMAIN.md](MEDIA_FILE_DOMAIN.md)
- **Popular Positions:** [POPULAR_ORGANIZATION_STRUCTURE.md](POPULAR_ORGANIZATION_STRUCTURE.md)
- **Vacancy Creation Guide:** [/guides/features/VACANCY_CREATION_PROCESS.md](../../guides/features/VACANCY_CREATION_PROCESS.md)
- **Process Setup Guide:** [/guides/development/PROCESS_SETUP_GUIDE.md](../../guides/development/PROCESS_SETUP_GUIDE.md)

---

**Last Updated:** 2025-11-05
**Domain:** Hiring & Vacancy
