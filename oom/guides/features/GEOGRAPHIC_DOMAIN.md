# Geographic & Address Domain - Entity Relationships

> **📚 Note:** This is a domain-specific relationship reference. For system-wide relationship rules, see `/architecture/entities/relationships/RELATIONSHIP_RULES.md`.

---

## Domain Overview

The Geographic & Address domain provides location hierarchy (countries, states, cities) and postal address management.

**Domain Code:** `GEOGRAPHIC`

**Core Entities:** 6
- CONTINENT
- COUNTRY
- STATE/PROVINCE
- DISTRICT/COUNTY
- CITY
- POSTAL_ADDRESS

**Reference Entities:** 3
- LANGUAGE
- CURRENCY
- TIMEZONE

---

## Hierarchical Structure

```
CONTINENT (Root)
  ↓ (1:Many)
COUNTRY
  ↓ (1:Many)
STATE/PROVINCE
  ↓ (1:Many)
DISTRICT/COUNTY
  ↓ (1:Many)
CITY
  ↓ (Many:1)
POSTAL_ADDRESS

Reference Entities:
- LANGUAGE (Referenced by COUNTRY)
- CURRENCY (Referenced by COUNTRY)
- TIMEZONE (Referenced by COUNTRY, CITY)
```

---

## 1. CONTINENT

### Entity Structure
```
CONTINENT
├─ id* (PK)
├─ name* (Human-readable continent name)
├─ code* (Short code for continent, e.g., AS, EU)
├─ area_sq_km? (Geographic area in square kilometers)
├─ population? (Total population estimate)
├─ gdp_in_usd? (Aggregate GDP estimate in USD)
└─ description? (Free-text description)
```

### Relationships
```
CONTINENT
  → COUNTRY (1:Many)
```

**Notes:**
- Root entity in the geographic hierarchy
- Represents major continental divisions (Africa, Antarctica, Asia, Europe, North America, Oceania, South America)
- System reference data, pre-populated and rarely changed
- Both `name` and `code` must be unique
- Updates should be handled through migrations

---

## 2. COUNTRY

### Entity Structure
```
COUNTRY
├─ id* (PK)
├─ name* (Human-readable country name)
├─ code* (Short code, e.g., IND)
├─ continent_id* (FK → CONTINENT)
├─ iso_alpha2* (ISO 3166-1 alpha-2)
├─ iso_alpha3* (ISO 3166-1 alpha-3)
├─ iso_numeric? (ISO numeric code)
├─ population? (Population estimate)
├─ area_sq_km? (Area in square kilometers)
├─ official_languages? (Comma-separated official languages)
├─ currency_id? (FK → CURRENCY)
├─ timezone_id? (FK → TIMEZONE)
├─ gdp_in_usd? (GDP in USD)
├─ flag_media_file_id? (FK → MEDIA_FILE)
└─ description? (Free-text description)
```

### Relationships
```
COUNTRY
  ← CONTINENT (Many:1) [via continent_id]
  ← CURRENCY (Many:1) [via currency_id]
  ← TIMEZONE (Many:1) [via timezone_id]
  ← MEDIA_FILE (Many:1) [via flag_media_file_id] - Flag image
  → STATE (1:Many)
  → LANGUAGE (1:Many) [Countries can have multiple languages]
  → CURRENCY (1:Many) [Countries can have multiple currencies]
  → TIMEZONE (1:Many) [Countries can span multiple timezones]
```

---

## 3. STATE/PROVINCE

### Entity Structure
```
STATE/PROVINCE
├─ id* (PK)
├─ country_id* (FK → COUNTRY)
├─ name* (State or province name)
├─ code? (Optional state code)
├─ population? (Population estimate)
├─ area_sq_km? (Area in square kilometers)
├─ gdp_in_usd? (GDP in USD)
├─ capital? (Name of state capital)
├─ description? (Free-text description)
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

## 4. DISTRICT/COUNTY

### Entity Structure
```
DISTRICT/COUNTY
├─ id* (PK)
├─ state_id* (FK → STATE)
├─ name* (Name of district or county)
├─ code? (Optional district code)
├─ population? (Population estimate)
├─ area_sq_km? (Area in square kilometers)
├─ description? (Free-text description)
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

## 5. CITY

### Entity Structure
```
CITY
├─ id* (PK)
├─ name* (Name of city or town)
├─ code? (Optional city code)
├─ district_id* (FK → DISTRICT)
├─ state_id* (FK → STATE)
├─ country_id* (FK → COUNTRY)
├─ population? (Population estimate)
├─ area_sq_km? (Area in square kilometers)
├─ latitude? (Decimal degrees)
├─ longitude? (Decimal degrees)
├─ timezone_id? (FK → TIMEZONE)
├─ description? (Free-text description)
└─ is_active*
```

### Relationships
```
CITY
  ← DISTRICT (Many:1) [via district_id]
  ← STATE (Many:1) [via state_id]
  ← COUNTRY (Many:1) [via country_id]
  ← TIMEZONE (Many:1) [via timezone_id]
  → POSTAL_ADDRESS (1:Many)
```

---

## 6. POSTAL_ADDRESS

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

## Reference Entities

### LANGUAGE

Represents languages spoken in countries.

**Structure:**
```
LANGUAGE
├─ id* (PK)
├─ name* (English name of the language)
├─ code? (ISO language code, e.g., en, hi, fr)
├─ native_name? (Native script name)
├─ country_id? (FK → COUNTRY)
└─ is_official? (1 if official language of the country)
```

**Domain:** GEOGRAPHIC

**Relationships:**
- COUNTRY (Many:1) - Languages belong to countries

**Notes:**
- Represents both official and commonly spoken languages
- Multiple languages can exist for a single country
- `is_official` flag indicates government-recognized official status
- ISO 639 language codes used where applicable

---

### CURRENCY

Represents world currencies used by countries.

**Structure:**
```
CURRENCY
├─ id* (PK)
├─ code* (ISO 4217 currency code, e.g., USD, EUR, INR)
├─ name* (Currency full name, e.g., "Indian Rupee")
├─ symbol? (Currency symbol, e.g., $, €, ₹)
├─ country_id? (FK → COUNTRY)
└─ exchange_rate_usd? (Reference exchange rate to USD)
```

**Domain:** GEOGRAPHIC

**Relationships:**
- COUNTRY (Many:1) - Currencies belong to countries
- COUNTRY (1:Many via country.currency_id) - Countries may have a primary currency

**Notes:**
- ISO 4217 standard currency codes
- `code` must be unique
- Multiple currencies can exist for a single country
- Primary currency referenced by COUNTRY.currency_id
- Exchange rates are reference values and should be updated regularly

---

### TIMEZONE

Represents time zones for countries and cities.

**Structure:**
```
TIMEZONE
├─ id* (PK)
├─ name* (IANA timezone name, e.g., "Asia/Kolkata", "America/New_York")
├─ utc_offset* (UTC offset, e.g., "+05:30", "-05:00")
├─ dst? (Daylight Saving Time flag, 1 if DST used)
└─ country_id? (FK → COUNTRY)
```

**Domain:** GEOGRAPHIC

**Relationships:**
- COUNTRY (Many:1) - Timezones belong to countries
- COUNTRY (1:Many via country.timezone_id) - Countries may have a primary timezone
- CITY (1:Many via city.timezone_id) - Cities may reference specific timezones

**Notes:**
- IANA Time Zone Database format
- `name` must be unique
- Multiple timezones can exist for a single country
- Primary timezone referenced by COUNTRY.timezone_id
- DST flag indicates if Daylight Saving Time is observed

---

## Complete Geographic Hierarchy

```
CONTINENT (e.g., Asia)
  ↓
COUNTRY (e.g., India)
  ├─ Continent: Asia
  ├─ Primary Currency: Indian Rupee (INR)
  ├─ Primary Timezone: Asia/Kolkata (UTC+05:30)
  ├─ Languages: Hindi (Official), English (Official), Tamil, Telugu, etc.
  ├─ Currencies: INR
  └─ Timezones: Asia/Kolkata
  ↓
STATE (e.g., Maharashtra)
  ↓
DISTRICT (e.g., Mumbai Suburban)
  ↓
CITY (e.g., Mumbai)
  ├─ Timezone: Asia/Kolkata (UTC+05:30)
  ↓
POSTAL_ADDRESS
  ├─ Person's home address
  ├─ Person's work address
  ├─ Organization's HQ address
  └─ Organization's branch address
```

---

## Relationship Details

### CONTINENT → COUNTRY
- **Type:** One-to-Many
- **Constraint:** A country must belong to exactly one continent
- **Cascade:** Updates cascaded, deletes restricted

### COUNTRY → STATE
- **Type:** One-to-Many
- **Constraint:** A state must belong to exactly one country
- **Cascade:** Updates cascaded, deletes restricted

### COUNTRY → CURRENCY
- **Type:** Many-to-One (Optional)
- **Constraint:** A country may reference one primary currency
- **Note:** Countries can have multiple currencies in practice

### COUNTRY → TIMEZONE
- **Type:** Many-to-One (Optional)
- **Constraint:** A country may reference one primary timezone
- **Note:** Countries can span multiple timezones in practice

### COUNTRY → LANGUAGE
- **Type:** One-to-Many
- **Constraint:** Languages reference their country via country_id
- **Note:** Countries typically have multiple official and spoken languages

### COUNTRY → CURRENCY (Reverse)
- **Type:** One-to-Many
- **Constraint:** Currencies reference their country via country_id
- **Note:** Some countries have multiple currencies in circulation

### COUNTRY → TIMEZONE (Reverse)
- **Type:** One-to-Many
- **Constraint:** Timezones reference their country via country_id
- **Note:** Large countries may span multiple timezone regions

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

### To Media & File Domain
```
COUNTRY ← MEDIA_FILE (Many:1) [via flag_media_file_id]
```
See: [MEDIA_FILE_DOMAIN.md](MEDIA_FILE_DOMAIN.md)

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

### Pattern 4: Country with Reference Entities
Getting complete country information with all reference data:
```sql
SELECT
    co.*,
    cont.name as continent_name,
    curr.code as primary_currency_code,
    curr.symbol as primary_currency_symbol,
    tz.name as primary_timezone_name,
    tz.utc_offset as primary_timezone_offset,
    GROUP_CONCAT(DISTINCT l.name) as languages,
    GROUP_CONCAT(DISTINCT CASE WHEN l.is_official = 1 THEN l.name END) as official_languages
FROM country co
LEFT JOIN continent cont ON co.continent_id = cont.id
LEFT JOIN currency curr ON co.currency_id = curr.id
LEFT JOIN timezone tz ON co.timezone_id = tz.id
LEFT JOIN language l ON l.country_id = co.id
WHERE co.id = ?
GROUP BY co.id;
```

### Pattern 5: Multiple Reference Entities per Country
A country can have multiple languages, currencies, and timezones:
```
COUNTRY (Switzerland)
  ├─ Languages:
  │   ├─ German (Official)
  │   ├─ French (Official)
  │   ├─ Italian (Official)
  │   └─ Romansh (Official)
  ├─ Currency: Swiss Franc (CHF) [Primary]
  └─ Timezone: Europe/Zurich [Primary]
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
    co.code as country_code,
    co.iso_alpha2,
    co.iso_alpha3,
    cont.name as continent,
    curr.code as currency_code,
    tz.name as timezone
FROM postal_address pa
JOIN city c ON pa.city_id = c.id
JOIN district d ON c.district_id = d.id
JOIN state s ON d.state_id = s.id
JOIN country co ON s.country_id = co.id
LEFT JOIN continent cont ON co.continent_id = cont.id
LEFT JOIN currency curr ON co.currency_id = curr.id
LEFT JOIN timezone tz ON co.timezone_id = tz.id
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

### Get Country with Details
```sql
SELECT
    co.*,
    cont.name as continent_name,
    cont.code as continent_code,
    curr.name as currency_name,
    curr.code as currency_code,
    curr.symbol as currency_symbol,
    tz.name as timezone_name,
    tz.utc_offset as timezone_offset
FROM country co
LEFT JOIN continent cont ON co.continent_id = cont.id
LEFT JOIN currency curr ON co.currency_id = curr.id
LEFT JOIN timezone tz ON co.timezone_id = tz.id
WHERE co.id = ?
AND co.deleted_at IS NULL;
```

### Get Countries by Continent
```sql
SELECT
    co.name,
    co.code,
    co.iso_alpha2,
    co.iso_alpha3,
    co.population,
    co.area_sq_km
FROM country co
WHERE co.continent_id = ?
AND co.deleted_at IS NULL
ORDER BY co.name;
```

### Get Languages by Country
```sql
SELECT
    l.name,
    l.code,
    l.native_name,
    l.is_official
FROM language l
WHERE l.country_id = ?
AND l.deleted_at IS NULL
ORDER BY l.is_official DESC, l.name;
```

### Get Official Languages for Country
```sql
SELECT
    l.name,
    l.code,
    l.native_name
FROM language l
WHERE l.country_id = ?
AND l.is_official = 1
AND l.deleted_at IS NULL
ORDER BY l.name;
```

### Get Currencies by Country
```sql
SELECT
    c.code,
    c.name,
    c.symbol,
    c.exchange_rate_usd
FROM currency c
WHERE c.country_id = ?
AND c.deleted_at IS NULL
ORDER BY c.code;
```

### Get Timezones by Country
```sql
SELECT
    tz.name,
    tz.utc_offset,
    tz.dst
FROM timezone tz
WHERE tz.country_id = ?
AND tz.deleted_at IS NULL
ORDER BY tz.utc_offset;
```

---

## Data Integrity Rules

1. **Geographic Hierarchy Integrity:**
   - Country must belong to a valid continent
   - State must belong to a valid country
   - District must belong to a valid state
   - City must belong to a valid district
   - Enforced at database level via foreign keys

2. **ISO Code Uniqueness:**
   - `iso_alpha2` must be unique across all countries
   - `iso_alpha3` must be unique across all countries
   - `code` must be unique for countries within system
   - Enforced at database level via unique constraints

3. **Exclusive Ownership:**
   - Address must belong to EITHER person OR organization
   - Validation: `(person_id IS NOT NULL AND organization_id IS NULL) OR (person_id IS NULL AND organization_id IS NOT NULL)`

4. **Primary Address:**
   - Only one primary address per person/organization
   - Enforced at application level

5. **Soft Deletes:**
   - Geographic entities use soft deletes
   - Inactive cities/districts/states marked with `is_active = 0`

6. **Reference Data:**
   - Continents, languages, currencies, and timezones are system reference data
   - Should be pre-populated and rarely changed
   - Updates should be handled through migrations

7. **Reference Entity Uniqueness:**
   - LANGUAGE: `name` should be unique per country
   - CURRENCY: `code` must be unique globally (ISO 4217)
   - TIMEZONE: `name` must be unique globally (IANA)
   - Enforced at database level via unique constraints

8. **Foreign Key Relationships:**
   - LANGUAGE.country_id → COUNTRY.id (Optional)
   - CURRENCY.country_id → COUNTRY.id (Optional)
   - TIMEZONE.country_id → COUNTRY.id (Optional)
   - These allow reference entities to be associated with specific countries

---

## Related Documentation

- **Entity Creation Rules:** [/architecture/entities/ENTITY_CREATION_RULES.md](../ENTITY_CREATION_RULES.md)
- **Relationship Rules:** [RELATIONSHIP_RULES.md](RELATIONSHIP_RULES.md)
- **Guides:** [/guides/features/GEOCODING_SETUP.md](../../guides/features/GEOCODING_SETUP.md)
- **Media & File Management:** [MEDIA_FILE_DOMAIN.md](MEDIA_FILE_DOMAIN.md)
- **All Domain Relationships:** [README.md](README.md)

---

**Last Updated:** 2025-11-05
**Domain:** Geographic & Address
