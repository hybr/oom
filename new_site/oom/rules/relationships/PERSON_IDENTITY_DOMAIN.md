# Person & Identity Domain - Entity Relationships

> **📚 Note:** This is a domain-specific relationship reference. For system-wide relationship rules, see `/rules/relationships/RELATIONSHIP_RULES.md`.

---

## Domain Overview

The Person & Identity domain manages individual user profiles, credentials, education, and skills.

**Domain Code:** `PERSON_IDENTITY`

**Core Entities:** 4
- PERSON
- PERSON_EDUCATION
- PERSON_SKILLS
- PERSON_CREDENTIAL

---

## 1. PERSON (Core Entity)

### Entity Structure
```
PERSON
├─ id* (PK)
├─ name_prefix
├─ first_name*
├─ middle_name?
├─ last_name*
├─ name_suffix
├─ date_of_birth?
├─ gender?
├─ primary_phone_number?
├─ primary_email_address?
├─ latest_photo?
├─ nationality?
└─ blood_group?
```

### Relationships

**Outbound Relationships (PERSON → Other Entities)**
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

---

## 2. PERSON_EDUCATION

### Entity Structure
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
```

### Relationship
- **Type:** Many-to-One
- **From:** PERSON_EDUCATION
- **To:** PERSON
- **Foreign Key:** `person_id`
- **Cardinality:** Each person can have multiple education records

---

## 3. PERSON_SKILLS

### Entity Structure
```
PERSON_SKILLS
├─ id* (PK)
├─ person_id* (FK → PERSON)
├─ skill_name*
├─ proficiency_level*
├─ years_of_experience?
└─ is_certified?
```

### Relationship
- **Type:** Many-to-One
- **From:** PERSON_SKILLS
- **To:** PERSON
- **Foreign Key:** `person_id`
- **Cardinality:** Each person can have multiple skills

---

## 4. PERSON_CREDENTIAL

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

### Relationship
- **Type:** Many-to-One
- **From:** PERSON_CREDENTIAL
- **To:** PERSON
- **Foreign Key:** `person_id`
- **Cardinality:** Each person can have multiple credentials

**Security Notes:**
- Passwords stored with Argon2ID hashing
- Account lockout after failed attempts
- Session-based authentication

---

## Cross-Domain Relationships

### To Geographic Domain
```
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
  ├── PERSON_CREDENTIAL (Authentication)
  ├── PERSON_EDUCATION (Education History)
  └── PERSON_SKILLS (Skills & Proficiency)

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
-- Main profile
SELECT * FROM person WHERE id = ?;

-- Active credentials
SELECT * FROM person_credential
WHERE person_id = ? AND is_active = 1;

-- Education history
SELECT * FROM person_education
WHERE person_id = ?
ORDER BY end_date DESC NULLS FIRST;

-- Skills
SELECT * FROM person_skills
WHERE person_id = ?
ORDER BY proficiency_level DESC, years_of_experience DESC;
```

### Find People by Skill
```sql
SELECT p.id, p.first_name, p.last_name,
       ps.skill_name, ps.proficiency_level
FROM person p
JOIN person_skills ps ON p.id = ps.person_id
WHERE ps.skill_name = 'PHP'
  AND ps.proficiency_level IN ('Expert', 'Advanced')
  AND p.deleted_at IS NULL;
```

---

## Related Documentation

- **Entity Creation Rules:** [/rules/ENTITY_CREATION_RULES.md](../ENTITY_CREATION_RULES.md)
- **Relationship Rules:** [RELATIONSHIP_RULES.md](RELATIONSHIP_RULES.md)
- **All Domain Relationships:** [README.md](README.md)

---

**Last Updated:** 2025-10-31
**Domain:** Person & Identity
