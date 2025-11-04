# Person & Identity Domain - Entity Relationships

> **📚 Note:** This is a domain-specific relationship reference. For system-wide relationship rules, see `/architecture/entities/relationships/RELATIONSHIP_RULES.md`.

---

## Domain Overview

The Person & Identity domain manages individual user profiles, credentials, education, and skills.

**Domain Code:** `PERSON_IDENTITY`

**Core Entities:** 5
- PERSON
- PERSON_EDUCATION
- PERSON_EDUCATION_SUBJECT
- PERSON_SKILL
- PERSON_CREDENTIAL

**Reference Entities:** 8
- ENUM_EDUCATION_LEVELS
- ENUM_SKILL_LEVEL
- ENUM_MARKS_TYPE
- ENUM_GENDER
- ENUM_BLOOD_GROUP
- POPULAR_EDUCATION_SUBJECT
- POPULAR_SKILL
- COUNTRY (from Geographic domain)

---

## 1. PERSON (Core Entity)

### Entity Structure
```
PERSON
├─ id* (PK)
├─ name_prefix?
├─ first_name*
├─ middle_name?
├─ last_name*
├─ name_suffix?
├─ date_of_birth?
├─ gender? (FK → ENUM_GENDER)
├─ primary_phone_number?
├─ primary_email_address?
├─ latest_photo?
├─ nationality? (FK → COUNTRY)
└─ blood_group? (FK → ENUM_BLOOD_GROUP)
```

### Relationships

**Outbound Relationships (PERSON → Other Entities)**
```
PERSON
  → ENUM_GENDER (Many:1) [via gender]
  → COUNTRY (Many:1) [via nationality]
  → ENUM_BLOOD_GROUP (Many:1) [via blood_group]
  → PERSON_CREDENTIAL (1:Many)
  → PERSON_EDUCATION (1:Many)
  → PERSON_SKILL (1:Many)
  → POSTAL_ADDRESS (1:Many) [via person_id]
  → ORGANIZATION (1:Many) [as main_admin_id]
  → ORGANIZATION_ADMIN (1:Many) [via person_id]
  → EMPLOYMENT_CONTRACT (1:Many) [as employee_id]
  → ORGANIZATION_VACANCY (1:Many) [as created_by]
  → VACANCY_APPLICATION (1:Many) [as applicant_id]
  → TASK_INSTANCE (1:Many) [as assigned_to]
  → TASK_AUDIT_LOG (1:Many) [as actor_id]
```

---

## 2. PERSON_EDUCATION

### Entity Structure
```
PERSON_EDUCATION
├─ id* (PK)
├─ person_id* (FK → PERSON)
├─ degree_certificate_name?
├─ institution_id* (FK → ORGANIZATION)
├─ start_date?
├─ complete_date?
└─ education_level* (FK → ENUM_EDUCATION_LEVELS)
```

### Relationships

**To Other Entities:**
- **PERSON** (Many-to-One via `person_id`)
  - Each person can have multiple education records
- **ORGANIZATION** (Many-to-One via `institution_id`)
  - Links to the educational institution
- **ENUM_EDUCATION_LEVELS** (Many-to-One via `education_level`)
  - References the level of education (Bachelor, Master, etc.)
- **PERSON_EDUCATION_SUBJECT** (One-to-Many)
  - Links to subjects/fields studied in this education

---

## 3. PERSON_EDUCATION_SUBJECT

### Entity Structure
```
PERSON_EDUCATION_SUBJECT
├─ id* (PK)
├─ person_education_id* (FK → PERSON_EDUCATION)
├─ subject_id* (FK → POPULAR_EDUCATION_SUBJECT)
├─ marks_type? (FK → ENUM_MARKS_TYPE)
└─ marks?
```

### Relationships

**To Other Entities:**
- **PERSON_EDUCATION** (Many-to-One via `person_education_id`)
  - Links subject to a specific education qualification
- **POPULAR_EDUCATION_SUBJECT** (Many-to-One via `subject_id`)
  - References common subjects (Mathematics, Physics, etc.)
- **ENUM_MARKS_TYPE** (Many-to-One via `marks_type`)
  - Type of marking system (Percentage, CGPA, Grade, etc.)

### Purpose
This junction table allows a person's education record to have multiple subjects/fields of study, each with their own grades/marks.

---

## 4. PERSON_SKILL

### Entity Structure
```
PERSON_SKILL
├─ id* (PK)
├─ person_id* (FK → PERSON)
├─ skill_id* (FK → POPULAR_SKILL)
├─ institution_id? (FK → ORGANIZATION)
├─ level? (FK → ENUM_SKILL_LEVEL)
├─ start_date?
├─ complete_date?
├─ marks_type? (FK → ENUM_MARKS_TYPE)
└─ marks?
```

### Relationships

**To Other Entities:**
- **PERSON** (Many-to-One via `person_id`)
  - Each person can have multiple skills
- **POPULAR_SKILL** (Many-to-One via `skill_id`)
  - References common skills (Python, Communication, etc.)
- **ORGANIZATION** (Many-to-One via `institution_id`)
  - Optional: Training or certifying institution
- **ENUM_SKILL_LEVEL** (Many-to-One via `level`)
  - Proficiency level (Beginner, Intermediate, Expert, etc.)
- **ENUM_MARKS_TYPE** (Many-to-One via `marks_type`)
  - Type of marking system for certification

---

## 5. PERSON_CREDENTIAL

### Entity Structure
```
PERSON_CREDENTIAL
├─ id* (PK)
├─ person_id* (FK → PERSON)
├─ username*
├─ password_hash*
├─ is_active*
├─ last_login?
└─ failed_login_attempts?
```

### Relationships

**To Other Entities:**
- **PERSON** (Many-to-One via `person_id`)
  - Each person can have multiple credentials

**Security Notes:**
- Passwords stored with Argon2ID hashing
- Account lockout after failed attempts
- Session-based authentication

---

## Reference Entities

### ENUM_EDUCATION_LEVELS

Enumeration of education levels (Primary, Secondary, Diploma, Bachelor, Master, Doctorate, etc.)

**Structure:**
```
ENUM_EDUCATION_LEVELS
├─ id* (PK)
├─ code*
└─ name*
```

**Domain:** EDUCATION

---

### ENUM_SKILL_LEVEL

Enumeration of skill proficiency levels (Beginner, Intermediate, Advanced, Expert, etc.)

**Structure:**
```
ENUM_SKILL_LEVEL
├─ id* (PK)
├─ code*
└─ name*
```

**Domain:** SKILL

---

### ENUM_MARKS_TYPE

Enumeration of marking system types (Percentage, CGPA, GPA, Grade, etc.)

**Structure:**
```
ENUM_MARKS_TYPE
├─ id* (PK)
├─ code*
└─ name*
```

**Domain:** EDUCATION/SKILL

---

### POPULAR_EDUCATION_SUBJECT

Common education subjects (Mathematics, Physics, Computer Science, etc.)

**Structure:**
```
POPULAR_EDUCATION_SUBJECT
├─ id* (PK)
└─ name*
```

**Domain:** EDUCATION

---

### POPULAR_SKILL

Common technical or soft skills (Python, Java, Communication, Leadership, etc.)

**Structure:**
```
POPULAR_SKILL
├─ id* (PK)
└─ name*
```

**Domain:** SKILL

---

### ENUM_GENDER

Enumeration of gender options (Male, Female, Other, Prefer Not to Say, etc.)

**Structure:**
```
ENUM_GENDER
├─ id* (PK)
├─ code*
└─ name*
```

**Domain:** PERSON_IDENTITY

---

### ENUM_BLOOD_GROUP

Enumeration of blood groups (A+, A-, B+, B-, O+, O-, AB+, AB-)

**Structure:**
```
ENUM_BLOOD_GROUP
├─ id* (PK)
├─ code*
└─ name*
```

**Domain:** PERSON_IDENTITY

---

### COUNTRY

Reference to countries for nationality tracking (from Geographic domain)

**Structure:**
```
COUNTRY
├─ id* (PK)
├─ code*
├─ name*
└─ ... (other geographic attributes)
```

**Domain:** GEOGRAPHIC

See: [GEOGRAPHIC_DOMAIN.md](GEOGRAPHIC_DOMAIN.md)

---

## Cross-Domain Relationships

### To Geographic Domain
```
PERSON → COUNTRY (Many:1) [via nationality]
PERSON → POSTAL_ADDRESS (1:Many)
```
See: [GEOGRAPHIC_DOMAIN.md](GEOGRAPHIC_DOMAIN.md)

### To Organization Domain
```
PERSON → ORGANIZATION (1:Many) [as main_admin_id]
PERSON → ORGANIZATION_ADMIN (1:Many)
PERSON → EMPLOYMENT_CONTRACT (1:Many) [as employee_id]
```
See: [ORGANIZATION_DOMAIN.md](ORGANIZATION_DOMAIN.md)

### To Hiring Domain
```
PERSON → ORGANIZATION_VACANCY (1:Many) [as created_by]
PERSON → VACANCY_APPLICATION (1:Many) [as applicant_id]
```
See: [HIRING_VACANCY_DOMAIN.md](HIRING_VACANCY_DOMAIN.md)

### To Process Flow Domain
```
PERSON → TASK_INSTANCE (1:Many) [as assigned_to]
PERSON → TASK_AUDIT_LOG (1:Many) [as actor_id]
```
See: [PROCESS_FLOW_DOMAIN.md](PROCESS_FLOW_DOMAIN.md)

---

## Relationship Diagram

```
PERSON (Core)
  ├── → ENUM_GENDER (Gender)
  ├── → COUNTRY (Nationality)
  ├── → ENUM_BLOOD_GROUP (Blood Group)
  │
  ├── PERSON_CREDENTIAL (Authentication)
  ├── PERSON_EDUCATION (Education History)
  │     ├── → ORGANIZATION (Institution)
  │     ├── → ENUM_EDUCATION_LEVELS (Level)
  │     └── → PERSON_EDUCATION_SUBJECT
  │           ├── → POPULAR_EDUCATION_SUBJECT (Subject)
  │           └── → ENUM_MARKS_TYPE (Grading System)
  │
  └── PERSON_SKILL (Skills & Proficiency)
        ├── → POPULAR_SKILL (Skill)
        ├── → ORGANIZATION (Training Institution)
        ├── → ENUM_SKILL_LEVEL (Proficiency)
        └── → ENUM_MARKS_TYPE (Certification Grading)

  └── Cross-Domain Links
      ├── POSTAL_ADDRESS (Geographic)
      ├── ORGANIZATION (Organization - as owner)
      ├── ORGANIZATION_ADMIN (Organization - as admin)
      ├── EMPLOYMENT_CONTRACT (Organization - as employee)
      ├── ORGANIZATION_VACANCY (Hiring - as creator)
      ├── VACANCY_APPLICATION (Hiring - as applicant)
      ├── TASK_INSTANCE (Process - as assignee)
      └── TASK_AUDIT_LOG (Process - as actor)
```

---

## Common Queries

### Get Complete Person Profile
```sql
-- Main profile with reference data
SELECT
  p.*,
  g.name as gender_name,
  c.name as nationality_name,
  bg.name as blood_group_name
FROM person p
LEFT JOIN enum_gender g ON p.gender = g.id
LEFT JOIN country c ON p.nationality = c.id
LEFT JOIN enum_blood_group bg ON p.blood_group = bg.id
WHERE p.id = ?;

-- Active credentials
SELECT * FROM person_credential
WHERE person_id = ? AND is_active = 1;

-- Education history with institution and level
SELECT
  pe.*,
  o.name as institution_name,
  eel.name as education_level_name
FROM person_education pe
JOIN organization o ON pe.institution_id = o.id
JOIN enum_education_level eel ON pe.education_level = eel.id
WHERE pe.person_id = ?
ORDER BY pe.complete_date DESC NULLS FIRST;

-- Education subjects with marks
SELECT
  pe.degree_certificate_name,
  pes.subject_id,
  ps.name as subject_name,
  pes.marks,
  emt.name as marks_type
FROM person_education pe
JOIN person_education_subject pes ON pe.id = pes.person_education_id
JOIN popular_education_subject ps ON pes.subject_id = ps.id
LEFT JOIN enum_marks_type emt ON pes.marks_type = emt.id
WHERE pe.person_id = ?;

-- Skills with proficiency
SELECT
  psk.*,
  ps.name as skill_name,
  esl.name as skill_level_name,
  o.name as training_institution
FROM person_skill psk
JOIN popular_skill ps ON psk.skill_id = ps.id
LEFT JOIN enum_skill_level esl ON psk.level = esl.id
LEFT JOIN organization o ON psk.institution_id = o.id
WHERE psk.person_id = ?
ORDER BY esl.code DESC, psk.complete_date DESC;
```

### Find People by Skill
```sql
SELECT
  p.id,
  p.first_name,
  p.last_name,
  ps.name as skill_name,
  esl.name as proficiency_level
FROM person p
JOIN person_skill psk ON p.id = psk.person_id
JOIN popular_skill ps ON psk.skill_id = ps.id
JOIN enum_skill_level esl ON psk.level = esl.id
WHERE ps.name = 'PHP'
  AND esl.code IN ('EXPERT', 'ADVANCED')
  AND p.deleted_at IS NULL;
```

### Find People by Education Subject
```sql
SELECT DISTINCT
  p.id,
  p.first_name,
  p.last_name,
  pe.degree_certificate_name,
  eel.name as education_level
FROM person p
JOIN person_education pe ON p.id = pe.person_id
JOIN person_education_subject pes ON pe.id = pes.person_education_id
JOIN popular_education_subject subj ON pes.subject_id = subj.id
JOIN enum_education_level eel ON pe.education_level = eel.id
WHERE subj.name = 'Computer Science'
  AND eel.code IN ('BACHELOR', 'MASTER', 'DOCTORATE')
  AND p.deleted_at IS NULL;
```

---

## Related Documentation

- **Entity Creation Rules:** [/architecture/entities/ENTITY_CREATION_RULES.md](../ENTITY_CREATION_RULES.md)
- **Relationship Rules:** [RELATIONSHIP_RULES.md](RELATIONSHIP_RULES.md)
- **All Domain Relationships:** [README.md](README.md)

---

**Last Updated:** 2025-11-01
**Domain:** Person & Identity
