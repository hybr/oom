# Geographic & Address Domain - Entity Relationships

> **📚 Note:** This is a domain-specific relationship reference. For system-wide relationship rules, see `/architecture/entities/relationships/RELATIONSHIP_RULES.md`.

---

## Domain Overview

The Geographic & Address domain provides location hierarchy (countries, states, cities) and postal address management.

**Domain Code:** `GEOGRAPHIC`

**Core Entities:** 5
- COUNTRY
- STATE/PROVINCE
- DISTRICT/COUNTY
- CITY
- POSTAL_ADDRESS

---

## Hierarchical Structure

```
COUNTRY (Root)
  ↓ (1:Many)
STATE/PROVINCE
  ↓ (1:Many)
DISTRICT/COUNTY
  ↓ (1:Many)
CITY
  ↓ (Many:1)
POSTAL_ADDRESS
```

---

## 1. COUNTRY

### Entity Structure
```
COUNTRY
├─ id* (PK)
├─ name*
├─ iso_code*
└─ phone_code?
```

### Relationships
```
COUNTRY
  → STATE (1:Many)
```

---

## 2. STATE/PROVINCE

### Entity Structure
```
STATE/PROVINCE
├─ id* (PK)
├─ country_id* (FK → COUNTRY)
├─ name*
├─ state_code*
└─ is_active*
```

### Relationships
```
STATE
  ← COUNTRY (Many:1)
  → DISTRICT (1:Many)
  → POSTAL_ADDRESS (1:Many)
```

---

## 3. DISTRICT/COUNTY

### Entity Structure
```
DISTRICT/COUNTY
├─ id* (PK)
├─ state_id* (FK → STATE)
├─ name*
├─ district_code*
└─ is_active*
```

### Relationships
```
DISTRICT
  ← STATE (Many:1)
  → CITY (1:Many)
  → POSTAL_ADDRESS (1:Many)
```

---

## 4. CITY

### Entity Structure
```
CITY
├─ id* (PK)
├─ district_id* (FK → DISTRICT)
├─ name*
├─ city_code?
└─ is_active*
```

### Relationships
```
CITY
  ← DISTRICT (Many:1)
  → POSTAL_ADDRESS (1:Many)
```

---

## 5. POSTAL_ADDRESS

### Entity Structure
```
POSTAL_ADDRESS
├─ id* (PK)
├─ person_id? (FK → PERSON)
├─ organization_id? (FK → ORGANIZATION)
├─ first_street*
├─ second_street?
├─ area*
├─ landmark?
├─ postal_code*
├─ city_id* (FK → CITY)
├─ latitude?
├─ longitude?
├─ address_type*
├─ is_primary*
├─ contact_person?
├─ contact_phone?
└─ delivery_instructions?
```

### Relationships
```
POSTAL_ADDRESS
  ← CITY (Many:1)
  ← DISTRICT (Indirect via CITY)
  ← STATE (Indirect via DISTRICT)
  ← COUNTRY (Indirect via STATE)
  ← PERSON (Many:1) [Optional]
  ← ORGANIZATION (Many:1) [Optional]
```

**Important:** A postal address can belong to either a PERSON or an ORGANIZATION, but not both.

---

## Complete Geographic Hierarchy

```
COUNTRY (e.g., United States)
  ↓
STATE (e.g., California)
  ↓
DISTRICT (e.g., San Francisco County)
  ↓
CITY (e.g., San Francisco)
  ↓
POSTAL_ADDRESS
  ├─ Person's home address
  ├─ Person's work address
  ├─ Organization's HQ address
  └─ Organization's branch address
```

---

## Relationship Details

### COUNTRY → STATE
- **Type:** One-to-Many
- **Constraint:** A state must belong to exactly one country
- **Cascade:** Updates cascaded, deletes restricted

### STATE → DISTRICT
- **Type:** One-to-Many
- **Constraint:** A district must belong to exactly one state
- **Cascade:** Updates cascaded, deletes restricted

### DISTRICT → CITY
- **Type:** One-to-Many
- **Constraint:** A city must belong to exactly one district
- **Cascade:** Updates cascaded, deletes restricted

### CITY → POSTAL_ADDRESS
- **Type:** One-to-Many
- **Constraint:** Address must reference a city
- **Note:** District, state, and country are accessible through city's hierarchy

### POSTAL_ADDRESS → PERSON/ORGANIZATION
- **Type:** Many-to-One (Optional)
- **Constraint:** Must link to EITHER person_id OR organization_id (not both, not neither)
- **Rule:** At least one foreign key must be set

---

## Cross-Domain Relationships

### From Person Domain
```
PERSON → POSTAL_ADDRESS (1:Many)
```
See: [PERSON_IDENTITY_DOMAIN.md](PERSON_IDENTITY_DOMAIN.md)

### From Organization Domain
```
ORGANIZATION → POSTAL_ADDRESS (1:Many)
ORGANIZATION_BRANCH → POSTAL_ADDRESS (1:1 via FK)
ORGANIZATION_BUILDING → POSTAL_ADDRESS (1:1 via FK)
```
See: [ORGANIZATION_DOMAIN.md](ORGANIZATION_DOMAIN.md)

---

## Common Patterns

### Pattern 1: Multiple Addresses per Person
A person can have multiple addresses with different types:
```
PERSON (John Smith)
  ├─ POSTAL_ADDRESS (HOME, is_primary=1)
  ├─ POSTAL_ADDRESS (WORK, is_primary=0)
  └─ POSTAL_ADDRESS (BILLING, is_primary=0)
```

### Pattern 2: Primary Address
Each person/organization should have exactly one primary address:
```sql
-- Constraint (application-level)
SELECT COUNT(*) FROM postal_address
WHERE person_id = ?
AND is_primary = 1
AND deleted_at IS NULL;
-- Should return 1
```

### Pattern 3: Geographic Hierarchy Query
Getting full address with hierarchy:
```sql
SELECT
    pa.first_street,
    pa.area,
    pa.postal_code,
    c.name as city_name,
    d.name as district_name,
    s.name as state_name,
    co.name as country_name
FROM postal_address pa
JOIN city c ON pa.city_id = c.id
JOIN district d ON c.district_id = d.id
JOIN state s ON d.state_id = s.id
JOIN country co ON s.country_id = co.id
WHERE pa.id = ?;
```

---

## Address Types

Common values for `address_type`:
- `HOME` - Residential address
- `WORK` - Office/workplace
- `BILLING` - Billing address
- `SHIPPING` - Delivery address
- `MAILING` - Correspondence address
- `TEMPORARY` - Temporary location

---

## Geocoding Support

POSTAL_ADDRESS includes optional geocoding fields:
```
latitude?  - Geographic latitude (-90 to 90)
longitude? - Geographic longitude (-180 to 180)
```

**Usage:**
- Map visualization
- Distance calculations
- Location-based services
- Delivery routing

---

## Common Queries

### Get Address with Full Hierarchy
```sql
SELECT
    pa.*,
    c.name as city,
    d.name as district,
    d.district_code,
    s.name as state,
    s.state_code,
    co.name as country,
    co.iso_code as country_code
FROM postal_address pa
JOIN city c ON pa.city_id = c.id
JOIN district d ON c.district_id = d.id
JOIN state s ON d.state_id = s.id
JOIN country co ON s.country_id = co.id
WHERE pa.id = ?
AND pa.deleted_at IS NULL;
```

### Get Primary Address for Person
```sql
SELECT * FROM postal_address
WHERE person_id = ?
AND is_primary = 1
AND deleted_at IS NULL
LIMIT 1;
```

### Find Addresses by City
```sql
SELECT pa.*, p.first_name, p.last_name
FROM postal_address pa
LEFT JOIN person p ON pa.person_id = p.id
WHERE pa.city_id = ?
AND pa.deleted_at IS NULL;
```

### Get Districts in State
```sql
SELECT * FROM district
WHERE state_id = ?
AND is_active = 1
AND deleted_at IS NULL
ORDER BY name;
```

### Get Cities in District
```sql
SELECT * FROM city
WHERE district_id = ?
AND is_active = 1
AND deleted_at IS NULL
ORDER BY name;
```

---

## Data Integrity Rules

1. **Geographic Hierarchy Integrity:**
   - City must belong to a valid district
   - District must belong to a valid state
   - State must belong to a valid country
   - Enforced at database level via foreign keys

2. **Exclusive Ownership:**
   - Address must belong to EITHER person OR organization
   - Validation: `(person_id IS NOT NULL AND organization_id IS NULL) OR (person_id IS NULL AND organization_id IS NOT NULL)`

3. **Primary Address:**
   - Only one primary address per person/organization
   - Enforced at application level

4. **Soft Deletes:**
   - Geographic entities use soft deletes
   - Inactive cities/districts/states marked with `is_active = 0`

---

## Related Documentation

- **Entity Creation Rules:** [/architecture/entities/ENTITY_CREATION_RULES.md](../ENTITY_CREATION_RULES.md)
- **Relationship Rules:** [RELATIONSHIP_RULES.md](RELATIONSHIP_RULES.md)
- **Guides:** [/guides/features/GEOCODING_SETUP.md](../../guides/features/GEOCODING_SETUP.md)
- **All Domain Relationships:** [README.md](README.md)

---

**Last Updated:** 2025-10-31
**Domain:** Geographic & Address
