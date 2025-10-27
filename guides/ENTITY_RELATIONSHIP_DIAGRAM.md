# Entity Relationship Diagram (Text-Based)

## Overview

This document provides a comprehensive text-based ER diagram of the entire system, organized by domain.

---

## Legend

```
→  One-to-Many relationship (A → B means "A has many B")
← Many-to-One relationship (A ← B means "B belongs to A")
↔  Many-to-Many relationship (via junction table)
*  Required field/relationship
?  Optional field/relationship
```

---

## 1. PERSON & IDENTITY DOMAIN

### Core Entity
```
PERSON
├─ id* (PK)
├─ first_name*
├─ last_name*
├─ middle_name?
├─ date_of_birth?
├─ gender?
├─ phone_number?
├─ email?
├─ photo_url?
├─ nationality?
└─ blood_group?
```

### Relationships
```
PERSON
  → PERSON_CREDENTIAL (1:Many)
  → PERSON_EDUCATION (1:Many)
  → PERSON_SKILLS (1:Many)
  → POSTAL_ADDRESS (1:Many) [via person_id]
  → ORGANIZATION (1:Many) [as main_admin_id]
  → ORGANIZATION_ADMIN (1:Many) [via person_id]
  → EMPLOYMENT_CONTRACT (1:Many) [as employee_id]
  → ORGANIZATION_VACANCY (1:Many) [as created_by]
  → VACANCY_APPLICATION (1:Many) [as applicant_id]
  → TASK_INSTANCE (1:Many) [as assigned_to]
  → TASK_AUDIT_LOG (1:Many) [as actor_id]
```

### Education & Skills
```
PERSON_EDUCATION
├─ id* (PK)
├─ person_id* (FK → PERSON)
├─ institution_name*
├─ degree*
├─ field_of_study*
├─ start_date?
├─ end_date?
├─ is_current?
└─ grade_score?

PERSON_SKILLS
├─ id* (PK)
├─ person_id* (FK → PERSON)
├─ skill_name*
├─ proficiency_level*
├─ years_of_experience?
└─ is_certified?

PERSON_CREDENTIAL
├─ id* (PK)
├─ person_id* (FK → PERSON)
├─ username*
├─ password_hash*
├─ is_active*
├─ last_login?
└─ failed_login_attempts?
```

---

## 2. GEOGRAPHIC & ADDRESS DOMAIN

```
COUNTRY
├─ id* (PK)
├─ name*
├─ iso_code*
└─ phone_code?
  ↓
STATE/PROVINCE
├─ id* (PK)
├─ country_id* (FK → COUNTRY)
├─ name*
├─ state_code*
└─ is_active*
  ↓
CITY
├─ id* (PK)
├─ state_id* (FK → STATE)
├─ name*
├─ city_code?
└─ is_active*
  ↓
POSTAL_ADDRESS
├─ id* (PK)
├─ person_id? (FK → PERSON)
├─ organization_id? (FK → ORGANIZATION)
├─ first_street*
├─ second_street?
├─ area*
├─ landmark?
├─ postal_code*
├─ district?
├─ city_id* (FK → CITY)
├─ state_id* (FK → STATE)
├─ latitude?
├─ longitude?
├─ address_type*
├─ is_primary*
├─ contact_person?
├─ contact_phone?
└─ delivery_instructions?
```

**Relationships:**
```
COUNTRY → STATE (1:Many)
STATE → CITY (1:Many)
STATE → POSTAL_ADDRESS (1:Many)
CITY → POSTAL_ADDRESS (1:Many)
PERSON → POSTAL_ADDRESS (1:Many)
ORGANIZATION → POSTAL_ADDRESS (1:Many)
```

---

## 3. ORGANIZATION DOMAIN

### Core Organization
```
ORGANIZATION
├─ id* (PK)
├─ short_name*
├─ full_name?
├─ registration_number?
├─ tax_id?
├─ industry?
├─ description?
├─ logo_url?
├─ website?
├─ email?
├─ phone?
├─ main_admin_id* (FK → PERSON) [Owner]
├─ founded_date?
├─ employee_count?
└─ is_active*
```

### Membership & Permissions
```
ORGANIZATION_ADMIN
├─ id* (PK)
├─ organization_id* (FK → ORGANIZATION)
├─ person_id* (FK → PERSON)
├─ role* [SUPER_ADMIN, ADMIN, MODERATOR]
├─ permissions? (JSON)
├─ appointed_by? (FK → PERSON)
├─ appointed_at*
├─ is_active*
└─ notes?
```

**Permission Hierarchy:**
```
Level 1: MAIN_ADMIN (organization.main_admin_id)
  ↓ Full ownership
Level 2: SUPER_ADMIN (organization_admin.role)
  ↓ Transfer main_admin, manage all admins
Level 3: ADMIN (organization_admin.role)
  ↓ Manage employees
Level 4: MODERATOR (organization_admin.role)
  ↓ Read-only
Level 5: EMPLOYEE (employment_contract)
  ↓ Position-based permissions
```

### Infrastructure
```
ORGANIZATION_BRANCH
├─ id* (PK)
├─ organization_id* (FK → ORGANIZATION)
├─ name*
├─ code*
├─ description?
├─ branch_type?
├─ manager_id? (FK → PERSON)
└─ postal_address_id? (FK → POSTAL_ADDRESS)
  ↓
ORGANIZATION_BUILDING
├─ id* (PK)
├─ organization_id* (FK → ORGANIZATION)
├─ branch_id? (FK → ORGANIZATION_BRANCH)
├─ name*
├─ code*
├─ building_type?
├─ total_floors?
├─ postal_address_id? (FK → POSTAL_ADDRESS)
└─ facilities?
  ↓
WORKSTATION
├─ id* (PK)
├─ organization_building_id* (FK → ORGANIZATION_BUILDING)
├─ workstation_code*
├─ floor_number?
├─ area_section?
├─ workstation_type?
├─ capacity?
└─ amenities?
```

**Relationships:**
```
ORGANIZATION
  → ORGANIZATION_BRANCH (1:Many)
  → ORGANIZATION_BUILDING (1:Many)
  → ORGANIZATION_ADMIN (1:Many)
  → EMPLOYMENT_CONTRACT (1:Many)
  → ORGANIZATION_VACANCY (1:Many)
  → POSTAL_ADDRESS (1:Many)

ORGANIZATION_BRANCH
  → ORGANIZATION_BUILDING (1:Many)

ORGANIZATION_BUILDING
  → WORKSTATION (1:Many)
```

---

## 4. POPULAR ORGANIZATION STRUCTURE (Reference Data)

```
POPULAR_ORGANIZATION_DEPARTMENTS
├─ id* (PK)
├─ name*
├─ code*
├─ description?
└─ is_active*
  ↓
POPULAR_ORGANIZATION_DEPARTMENT_TEAMS
├─ id* (PK)
├─ department_id* (FK → POPULAR_ORGANIZATION_DEPARTMENTS)
├─ name*
├─ code*
├─ description?
└─ is_active*

POPULAR_ORGANIZATION_DESIGNATION
├─ id* (PK)
├─ name*
├─ code*
├─ description?
├─ level? [Entry, Mid, Senior, Lead, Manager]
└─ is_active*

POPULAR_ORGANIZATION_POSITION
├─ id* (PK)
├─ title*
├─ code*
├─ description?
├─ department_id* (FK → POPULAR_ORGANIZATION_DEPARTMENTS)
├─ team_id? (FK → POPULAR_ORGANIZATION_DEPARTMENT_TEAMS)
├─ designation_id* (FK → POPULAR_ORGANIZATION_DESIGNATION)
├─ responsibilities?
├─ required_skills?
├─ experience_years?
└─ is_active*
```

**Relationships:**
```
POPULAR_ORGANIZATION_DEPARTMENTS
  → POPULAR_ORGANIZATION_DEPARTMENT_TEAMS (1:Many)
  → POPULAR_ORGANIZATION_POSITION (1:Many)

POPULAR_ORGANIZATION_DEPARTMENT_TEAMS
  → POPULAR_ORGANIZATION_POSITION (1:Many)

POPULAR_ORGANIZATION_DESIGNATION
  → POPULAR_ORGANIZATION_POSITION (1:Many)

POPULAR_ORGANIZATION_POSITION
  → ORGANIZATION_VACANCY (1:Many)
  → EMPLOYMENT_CONTRACT (1:Many)
  → ENTITY_PERMISSION_DEFINITION (1:Many)
  → PROCESS_NODE (1:Many)
```

---

## 5. HIRING & VACANCY DOMAIN

### Vacancy Creation Flow
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
├─ employment_type?
├─ status* [DRAFT, PENDING, APPROVED, OPEN, CLOSED]
└─ is_urgent?
  ↓
ORGANIZATION_VACANCY_WORKSTATION (Junction Table)
├─ id* (PK)
├─ organization_vacancy_id* (FK → ORGANIZATION_VACANCY)
├─ organization_workstation_id* (FK → WORKSTATION)
└─ notes?
```

### Application Process
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
├─ status* [SUBMITTED, UNDER_REVIEW, SHORTLISTED, REJECTED]
└─ withdrawn_reason?
  ↓
APPLICATION_REVIEW
├─ id* (PK)
├─ application_id* (FK → VACANCY_APPLICATION)
├─ reviewed_by* (FK → PERSON)
├─ review_date*
├─ review_notes?
├─ rating?
├─ status* [PENDING, APPROVED, REJECTED]
└─ proceed_to_interview?
```

### Interview Process
```
INTERVIEW_STAGE
├─ id* (PK)
├─ organization_id* (FK → ORGANIZATION)
├─ name* [Phone Screening, Technical, HR, Final]
├─ description?
├─ order_number*
├─ duration_minutes?
└─ is_active*

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
├─ rating?
├─ strengths?
├─ weaknesses?
├─ status* [SCHEDULED, COMPLETED, CANCELLED, NO_SHOW]
└─ recommendation? [HIRE, MAYBE, NO_HIRE]
```

### Hiring Decision
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
  ↓
EMPLOYMENT_CONTRACT
├─ id* (PK)
├─ job_offer_id? (FK → JOB_OFFER)
├─ organization_id* (FK → ORGANIZATION)
├─ employee_id* (FK → PERSON)
├─ position_id? (FK → POPULAR_ORGANIZATION_POSITION)
├─ job_title*
├─ contract_number*
├─ contract_type* [Permanent, Contract, Intern]
├─ start_date*
├─ end_date?
├─ salary?
├─ status* [ACTIVE, TERMINATED, SUSPENDED, COMPLETED]
├─ termination_date?
├─ termination_reason?
└─ contract_document_url?
```

**Complete Hiring Flow:**
```
ORGANIZATION_VACANCY
  ↓ (applicants apply)
VACANCY_APPLICATION
  ↓ (review)
APPLICATION_REVIEW
  ↓ (if proceed_to_interview)
APPLICATION_INTERVIEW (multiple stages)
  ↓ (if recommended)
JOB_OFFER
  ↓ (if accepted)
EMPLOYMENT_CONTRACT
  ↓ (position resolution chain for process flow)
POPULAR_ORGANIZATION_POSITION
```

---

## 6. PROCESS FLOW SYSTEM

### Process Definition (Templates)
```
PROCESS_GRAPH
├─ id* (PK)
├─ code* [VACANCY_CREATION, REQUISITION_APPROVAL]
├─ name*
├─ description?
├─ entity_id? (FK → ENTITY_DEFINITION)
├─ version_number*
├─ is_active*
├─ is_published*
├─ category?
├─ created_by* (FK → PERSON)
├─ published_at?
└─ published_by? (FK → PERSON)
  ↓
PROCESS_NODE
├─ id* (PK)
├─ graph_id* (FK → PROCESS_GRAPH)
├─ node_code*
├─ node_name*
├─ node_type* [START, TASK, DECISION, FORK, JOIN, END]
├─ description?
├─ position_id? (FK → POPULAR_ORGANIZATION_POSITION) [for TASK nodes]
├─ permission_type_id? (FK → ENUM_ENTITY_PERMISSION_TYPE) [for TASK nodes]
├─ sla_hours?
├─ estimated_duration_hours?
├─ display_x?
├─ display_y?
├─ form_entities? (JSON array) [for dynamic form generation]
├─ instructions?
├─ notify_on_assignment?
├─ notify_on_due?
├─ escalate_after_hours?
└─ escalate_to_position_id? (FK → POPULAR_ORGANIZATION_POSITION)
  ↓
PROCESS_EDGE
├─ id* (PK)
├─ graph_id* (FK → PROCESS_GRAPH)
├─ from_node_id* (FK → PROCESS_NODE)
├─ to_node_id* (FK → PROCESS_NODE)
├─ edge_label?
├─ edge_order*
├─ is_default?
└─ description?
  ↓
PROCESS_EDGE_CONDITION
├─ id* (PK)
├─ edge_id* (FK → PROCESS_EDGE)
├─ condition_order*
├─ field_source* [ENTITY_FIELD, FLOW_VARIABLE, TASK_VARIABLE, SYSTEM]
├─ field_name*
├─ operator* [EQ, NEQ, GT, LT, IN, CONTAINS, etc.]
├─ value_type* [STRING, NUMBER, BOOLEAN, DATE]
├─ compare_value*
├─ logic_operator* [AND, OR]
└─ condition_group*
```

### Process Execution (Instances)
```
TASK_FLOW_INSTANCE
├─ id* (PK)
├─ graph_id* (FK → PROCESS_GRAPH)
├─ organization_id* (FK → ORGANIZATION)
├─ entity_code?
├─ entity_record_id?
├─ reference_number* [AUTO-GENERATED]
├─ current_node_id? (FK → PROCESS_NODE)
├─ status* [PENDING, RUNNING, COMPLETED, CANCELLED, FAILED]
├─ started_by* (FK → PERSON)
├─ started_at*
├─ completed_at?
├─ cancelled_at?
├─ cancellation_reason?
└─ flow_variables? (JSON)
  ↓
TASK_INSTANCE
├─ id* (PK)
├─ flow_instance_id* (FK → TASK_FLOW_INSTANCE)
├─ node_id* (FK → PROCESS_NODE)
├─ assigned_to? (FK → PERSON)
├─ status* [PENDING, IN_PROGRESS, COMPLETED, CANCELLED]
├─ created_at*
├─ started_at?
├─ completed_at?
├─ due_date?
├─ completion_action? [COMPLETE, APPROVE, REJECT, CANCEL]
├─ completion_comments?
├─ completion_data? (JSON) [form submissions]
└─ task_variables? (JSON)
  ↓
TASK_AUDIT_LOG
├─ id* (PK)
├─ flow_instance_id* (FK → TASK_FLOW_INSTANCE)
├─ task_instance_id? (FK → TASK_INSTANCE)
├─ from_node_id? (FK → PROCESS_NODE)
├─ to_node_id? (FK → PROCESS_NODE)
├─ action* [START, ASSIGN, COMPLETE, TRANSITION, CANCEL]
├─ actor_id* (FK → PERSON)
├─ comments?
├─ changes? (JSON)
└─ created_at*
```

### Fallback Assignments
```
PROCESS_FALLBACK_ASSIGNMENT
├─ id* (PK)
├─ organization_id* (FK → ORGANIZATION)
├─ position_id* (FK → POPULAR_ORGANIZATION_POSITION)
├─ fallback_type* [PERSON, POSITION, AUTO_ADMIN]
├─ fallback_person_id? (FK → PERSON)
├─ fallback_position_id? (FK → POPULAR_ORGANIZATION_POSITION)
├─ priority*
└─ is_active*
```

**Process Flow Relationships:**
```
PROCESS_GRAPH
  → PROCESS_NODE (1:Many)
  → PROCESS_EDGE (1:Many)
  → TASK_FLOW_INSTANCE (1:Many)

PROCESS_NODE
  → PROCESS_EDGE (1:Many) [as from_node]
  → PROCESS_EDGE (1:Many) [as to_node]
  → TASK_INSTANCE (1:Many)
  ← POPULAR_ORGANIZATION_POSITION (Many:1) [task assignment]

PROCESS_EDGE
  → PROCESS_EDGE_CONDITION (1:Many)

TASK_FLOW_INSTANCE
  → TASK_INSTANCE (1:Many)
  → TASK_AUDIT_LOG (1:Many)
  ← ORGANIZATION (Many:1)
  ← PERSON (Many:1) [started_by]

TASK_INSTANCE
  → TASK_AUDIT_LOG (1:Many)
  ← PERSON (Many:1) [assigned_to]
```

---

## 7. PERMISSIONS & SECURITY DOMAIN

```
ENUM_ENTITY_PERMISSION_TYPE
├─ id* (PK)
├─ code* [REQUEST, APPROVER, IMPLEMENTOR, REVIEWER]
├─ name*
└─ description?
  ↓
ENTITY_PERMISSION_DEFINITION
├─ id* (PK)
├─ entity_id* (FK → ENTITY_DEFINITION)
├─ permission_type_id* (FK → ENUM_ENTITY_PERMISSION_TYPE)
├─ position_id* (FK → POPULAR_ORGANIZATION_POSITION)
└─ is_allowed* [1 = allowed, 0 = denied]
```

**Permission Flow:**
```
PERSON
  → EMPLOYMENT_CONTRACT (employee_id)
    → POPULAR_ORGANIZATION_POSITION (position_id)
      → ENTITY_PERMISSION_DEFINITION (position_id)
        → ENUM_ENTITY_PERMISSION_TYPE (permission_type_id)
          → ALLOWED ACTIONS ON ENTITY
```

---

## 8. SYSTEM METADATA (Entity Framework)

```
ENTITY_DEFINITION
├─ id* (PK)
├─ code*
├─ name*
├─ description?
├─ domain?
├─ table_name*
└─ is_active*
  ↓
ENTITY_ATTRIBUTE
├─ id* (PK)
├─ entity_id* (FK → ENTITY_DEFINITION)
├─ code*
├─ name*
├─ data_type* [text, integer, number, date, datetime, boolean, enum_strings, enum_objects]
├─ is_required*
├─ is_label*
├─ is_unique*
├─ enum_values? (JSON)
├─ description?
└─ display_order*

ENTITY_RELATIONSHIP
├─ id* (PK)
├─ from_entity_id* (FK → ENTITY_DEFINITION)
├─ to_entity_id* (FK → ENTITY_DEFINITION)
├─ relation_type* [OneToMany, ManyToOne, ManyToMany]
├─ relation_name*
├─ fk_field*
└─ description?

ENTITY_VALIDATION_RULE
├─ id* (PK)
├─ entity_id* (FK → ENTITY_DEFINITION)
├─ attribute_id? (FK → ENTITY_ATTRIBUTE)
├─ rule_name*
├─ rule_expression*
├─ error_message*
└─ severity* [error, warning, info]
```

---

## COMPLETE SYSTEM OVERVIEW (Simplified)

```
┌─────────────────────────────────────────────────────────────────────┐
│                         SYSTEM ARCHITECTURE                          │
└─────────────────────────────────────────────────────────────────────┘

┌──────────────┐
│   PERSON     │──┬── PERSON_EDUCATION
│              │  ├── PERSON_SKILLS
│              │  ├── PERSON_CREDENTIAL
│              │  └── POSTAL_ADDRESS
└──────────────┘
       │
       ├─── (main_admin_id) ──→ ORGANIZATION
       │                            │
       │                            ├── ORGANIZATION_ADMIN (3-tier membership)
       │                            ├── ORGANIZATION_BRANCH
       │                            │     └── ORGANIZATION_BUILDING
       │                            │           └── WORKSTATION
       │                            └── ORGANIZATION_VACANCY
       │                                      │
       │                                      ├── VACANCY_APPLICATION
       │                                      │     ├── APPLICATION_REVIEW
       │                                      │     └── APPLICATION_INTERVIEW
       │                                      │           └── JOB_OFFER
       │                                      │                 └── EMPLOYMENT_CONTRACT
       │                                      │
       │                                      └── ORGANIZATION_VACANCY_WORKSTATION
       │
       └─── (employee_id) ──→ EMPLOYMENT_CONTRACT
                                      │
                                      └── POPULAR_ORGANIZATION_POSITION
                                            │
                                            ├── POPULAR_ORGANIZATION_DEPARTMENTS
                                            ├── POPULAR_ORGANIZATION_DEPARTMENT_TEAMS
                                            ├── POPULAR_ORGANIZATION_DESIGNATION
                                            └── ENTITY_PERMISSION_DEFINITION
                                                      │
                                                      └── PROCESS_NODE (task assignment)
                                                            │
                                                            └── PROCESS_GRAPH
                                                                  └── TASK_FLOW_INSTANCE
                                                                        └── TASK_INSTANCE

┌──────────────────────────────────────────────────────────────────────┐
│  GEOGRAPHIC HIERARCHY:  COUNTRY → STATE → CITY → POSTAL_ADDRESS     │
└──────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────┐
│  PROCESS FLOW:  PROCESS_GRAPH → PROCESS_NODE → PROCESS_EDGE →       │
│                 TASK_FLOW_INSTANCE → TASK_INSTANCE → TASK_AUDIT_LOG │
└──────────────────────────────────────────────────────────────────────┘
```

---

## KEY RELATIONSHIP PATTERNS

### 1. Organization Membership (3-Tier)
```
PERSON ──┬── (main_admin_id) ──→ ORGANIZATION [Level 1: Owner]
         ├── ORGANIZATION_ADMIN ─→ ORGANIZATION [Level 2: Admin Roles]
         └── EMPLOYMENT_CONTRACT → ORGANIZATION [Level 3: Employee]
```

### 2. Position Resolution Chain
```
EMPLOYMENT_CONTRACT → JOB_OFFER → VACANCY_APPLICATION →
ORGANIZATION_VACANCY → POPULAR_ORGANIZATION_POSITION
```

### 3. Permission Hierarchy
```
PERSON → EMPLOYMENT_CONTRACT → POPULAR_ORGANIZATION_POSITION →
ENTITY_PERMISSION_DEFINITION → ENUM_ENTITY_PERMISSION_TYPE
```

### 4. Vacancy Lifecycle
```
ORGANIZATION_VACANCY [DRAFT] →
VACANCY_APPLICATION →
APPLICATION_REVIEW →
APPLICATION_INTERVIEW →
JOB_OFFER →
EMPLOYMENT_CONTRACT [ACTIVE]
```

### 5. Process Execution
```
PROCESS_GRAPH (template) →
TASK_FLOW_INSTANCE (running instance) →
TASK_INSTANCE (individual task) →
TASK_AUDIT_LOG (audit trail)
```

---

## DOMAIN SUMMARY

| Domain | Entities | Purpose |
|--------|----------|---------|
| **Person & Identity** | 4 | Person profiles, education, skills, credentials |
| **Geographic** | 4 | Countries, states, cities, addresses |
| **Organization** | 7 | Organizations, admins, branches, buildings, workstations |
| **Reference Data** | 4 | Departments, teams, designations, positions |
| **Hiring** | 8 | Vacancies, applications, interviews, offers, contracts |
| **Process Flow** | 8 | Workflow definitions and execution |
| **Permissions** | 2 | Position-based security |
| **System Metadata** | 4 | Entity framework (definitions, attributes, relationships) |

**Total Core Entities:** ~40+ entities

---

## NOTES

1. **All entities** have standard audit fields: `id`, `created_at`, `updated_at`, `deleted_at`, `version_no`, `changed_by`

2. **Foreign Keys** are implemented with soft deletes (`deleted_at IS NULL` checks)

3. **Enums** can be either:
   - `enum_strings`: Simple array of strings
   - `enum_objects`: Array of objects with `{value, label}` pairs

4. **JSON Fields** are used for flexible data storage:
   - `form_entities` in PROCESS_NODE
   - `flow_variables` in TASK_FLOW_INSTANCE
   - `completion_data` in TASK_INSTANCE
   - `permissions` in ORGANIZATION_ADMIN

5. **Three-Tier Membership** allows a person to be:
   - Main Admin (owner)
   - Organization Admin (role-based)
   - Employee (position-based)
   - Or any combination!

---

**Last Updated:** 2025-10-26
**Version:** 1.0
**Generated From:** System guides documentation
