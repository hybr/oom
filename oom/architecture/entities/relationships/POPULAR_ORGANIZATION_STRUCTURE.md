# Popular Organization Structure - Entity Relationships

> **📚 Note:** This is a domain-specific relationship reference. For system-wide relationship rules, see `/architecture/entities/relationships/RELATIONSHIP_RULES.md`.

---

## Domain Overview

The Popular Organization Structure domain provides reference/master data for common organizational hierarchies: industry categories, legal entity types, departments, teams, designations, and positions. These are reusable templates that multiple organizations can reference.

**Key Components:**
- **Industry Categories:** Hierarchical classification of business industries
- **Legal Types:** Country-specific legal entity structures (LLC, Ltd, GmbH, etc.)
- **Departments & Teams:** Organizational units within companies
- **Designations & Positions:** Job titles and roles within organizations

**Domain Code:** `POPULAR_ORG_STRUCTURE`

**Core Entities:** 9
- POPULAR_INDUSTRY_CATEGORY
- POPULAR_ORGANIZATION_LEGAL_TYPES
- POPULAR_ORGANIZATION_DEPARTMENTS
- POPULAR_ORGANIZATION_DEPARTMENT_TEAMS
- POPULAR_ORGANIZATION_DESIGNATION
- POPULAR_ORGANIZATION_POSITION
- POPULAR_ORGANIZATION_POSITION_SKILL
- POPULAR_ORGANIZATION_POSITION_EDUCATION
- POPULAR_ORGANIZATION_POSITION_EDUCATION_SUBJECT

**Purpose:** Standardized, reusable organizational structure templates and industry categorization

---

## Hierarchy Structure

```
POPULAR_INDUSTRY_CATEGORY (Hierarchical industry classification)
  ↓
Used by: ORGANIZATION (to classify organizations by industry)

POPULAR_ORGANIZATION_LEGAL_TYPES (Country-specific legal structures)
  ↓
Used by: ORGANIZATION (to define legal entity type)

POPULAR_ORGANIZATION_DEPARTMENTS (e.g., Engineering, Sales, HR)
  ↓ (1:Many)
POPULAR_ORGANIZATION_DEPARTMENT_TEAMS (e.g., Backend, Frontend, DevOps)
  ↓ (Many:1)
POPULAR_ORGANIZATION_DESIGNATION (e.g., Staff, Manager, Director, CEO)
  ↓
POPULAR_ORGANIZATION_POSITION (Combination of Dept + Team + Designation)
  ↓
Used by: ORGANIZATION_VACANCY, EMPLOYMENT_CONTRACT, PROCESS_NODE
```

---

## 1. POPULAR_INDUSTRY_CATEGORY

### Entity Structure
```
POPULAR_INDUSTRY_CATEGORY
├─ id* (PK)
├─ code* (Unique category code)
├─ name* (Display name of the industry category)
├─ parent_category_id? (FK → POPULAR_INDUSTRY_CATEGORY, for hierarchical structure)
├─ level? (Hierarchy level: 1=top level, 2=sub-category, etc.)
└─ description? (Detailed description of the category)
```

### Relationships
```
POPULAR_INDUSTRY_CATEGORY
  ← POPULAR_INDUSTRY_CATEGORY (Self-referencing: Many:1) [via parent_category_id]
  → POPULAR_INDUSTRY_CATEGORY (Self-referencing: 1:Many) [child categories]
  → ORGANIZATION (1:Many) [Organizations classified by industry]
```

### Purpose
Provides a hierarchical classification system for industries, allowing organizations to be categorized by their business domain. This enables industry-specific filtering, analysis, and comparisons.

### Hierarchical Structure
Industry categories can be nested to form a multi-level taxonomy:
```
Level 1: Technology
  Level 2: Software
    Level 3: SaaS
    Level 3: Enterprise Software
    Level 3: Mobile Apps
  Level 2: Hardware
    Level 3: Consumer Electronics
    Level 3: Networking Equipment

Level 1: Healthcare
  Level 2: Pharmaceuticals
  Level 2: Medical Devices
  Level 2: Healthcare Services
    Level 3: Hospitals
    Level 3: Clinics
    Level 3: Telemedicine

Level 1: Finance
  Level 2: Banking
    Level 3: Retail Banking
    Level 3: Investment Banking
  Level 2: Insurance
  Level 2: Fintech
```

### Examples
```
Top-Level Categories (level=1):
- Technology
- Healthcare
- Finance & Banking
- Manufacturing
- Retail & E-commerce
- Education
- Real Estate
- Transportation & Logistics
- Hospitality & Tourism
- Professional Services
- Energy & Utilities
- Agriculture
- Media & Entertainment
- Non-Profit & NGO

Sub-Categories (level=2):
- Technology → Software Development
- Technology → IT Consulting
- Technology → Hardware Manufacturing
- Healthcare → Pharmaceuticals
- Healthcare → Medical Devices
- Finance → Banking Services
- Finance → Insurance
- Retail → E-commerce
- Retail → Physical Stores

Specific Categories (level=3+):
- Technology → Software → SaaS Platforms
- Technology → Software → Enterprise Applications
- Healthcare → Pharmaceuticals → Biotech
- Finance → Banking → Digital Banking
```

### Notes
- **Self-Referencing Hierarchy:** Categories can have parent-child relationships
- **Flexible Depth:** No fixed limit on hierarchy depth, though 3-4 levels is typical
- **Code Uniqueness:** Each category must have a unique code (e.g., TECH, TECH-SOFTWARE, TECH-SOFTWARE-SAAS)
- **Level Tracking:** The `level` field helps with querying and display (1=root, 2=child, 3=grandchild, etc.)
- **Multi-tenant:** Organizations across the system reference the same industry categories
- **Standardization:** Ensures consistent industry classification across all organizations

---

## 2. POPULAR_ORGANIZATION_LEGAL_TYPES

### Entity Structure
```
POPULAR_ORGANIZATION_LEGAL_TYPES
├─ id* (PK)
├─ code* (Abbreviated legal type code, e.g., LLC, Ltd, Inc)
├─ name* (Full name of the legal type)
├─ country_id* (FK → COUNTRY, where this legal type is applicable)
├─ description? (Description of the legal entity type)
├─ requires_minimum_capital? (Whether minimum capital is required)
└─ minimum_capital_amount? (Minimum required capital if applicable)
```

### Relationships
```
POPULAR_ORGANIZATION_LEGAL_TYPES
  ← COUNTRY (Many:1) [via country_id]
  → ORGANIZATION (1:Many) [Organizations use legal types]
```

### Purpose
Provides a standardized catalog of legal entity types (business structures) that are specific to each country. This enables organizations to select the appropriate legal structure based on their jurisdiction.

### Country-Specific Legal Types
Different countries have different legal entity structures with varying requirements:

**United States:**
```
- LLC (Limited Liability Company)
- Corporation (C-Corp)
- S-Corporation (S-Corp)
- Sole Proprietorship
- Partnership (General Partnership)
- Limited Partnership (LP)
- Limited Liability Partnership (LLP)
- Non-Profit Corporation
- Professional Corporation (PC)
- Benefit Corporation (B-Corp)
```

**United Kingdom:**
```
- Ltd (Private Limited Company)
- PLC (Public Limited Company)
- LLP (Limited Liability Partnership)
- Sole Trader
- Partnership
- Community Interest Company (CIC)
- Charity
```

**India:**
```
- Private Limited Company (Pvt Ltd)
- Public Limited Company (Ltd)
- Limited Liability Partnership (LLP)
- One Person Company (OPC)
- Sole Proprietorship
- Partnership Firm
- Section 8 Company (Non-Profit)
- Producer Company
```

**European Union:**
```
- GmbH (Germany - Limited Liability Company)
- AG (Germany - Stock Corporation)
- SARL (France - Limited Liability Company)
- SA (France - Stock Corporation)
- SRL (Italy - Limited Liability Company)
- SpA (Italy - Stock Corporation)
- BV (Netherlands - Private Limited Company)
- NV (Netherlands - Public Limited Company)
```

**Australia:**
```
- Pty Ltd (Proprietary Limited Company)
- Limited Company (Public)
- Sole Trader
- Partnership
- Trust
- Cooperative
```

**Canada:**
```
- Corporation (Corp)
- Limited (Ltd)
- Incorporated (Inc)
- Sole Proprietorship
- Partnership
- Limited Partnership (LP)
- Limited Liability Partnership (LLP)
```

### Capital Requirements
Some legal types require minimum capital investment:

```
India - Private Limited Company:
  requires_minimum_capital: true
  minimum_capital_amount: 100,000 INR

UK - Public Limited Company (PLC):
  requires_minimum_capital: true
  minimum_capital_amount: 50,000 GBP

Germany - AG (Stock Corporation):
  requires_minimum_capital: true
  minimum_capital_amount: 50,000 EUR

USA - LLC:
  requires_minimum_capital: false
  minimum_capital_amount: NULL
```

### Notes
- **Country-Specific:** Each legal type is tied to a specific country
- **Code Uniqueness:** Code should be unique within a country (e.g., multiple countries can have "Ltd")
- **Regulatory Compliance:** Legal types determine tax treatment, liability, and reporting requirements
- **Multi-Tenant:** All organizations reference the same legal type catalog
- **Capital Requirements:** Some legal structures mandate minimum capital investment
- **Display Format:** Organizations typically display as "Company Name [Legal Type]" (e.g., "Acme Corp LLC", "Tech Solutions Pvt Ltd")

---

## 3. POPULAR_ORGANIZATION_DEPARTMENTS

### Entity Structure
```
POPULAR_ORGANIZATION_DEPARTMENTS
├─ id* (PK)
├─ name*
├─ code*
├─ description?
├─ objectives?
└─ is_active*
```

### Relationships
```
POPULAR_ORGANIZATION_DEPARTMENTS
  → POPULAR_ORGANIZATION_DEPARTMENT_TEAMS (1:Many)
  → POPULAR_ORGANIZATION_POSITION (1:Many)
```

### Examples
```
Engineering
Sales
Marketing
Human Resources
Finance
Operations
Customer Support
```

---

## 4. POPULAR_ORGANIZATION_DEPARTMENT_TEAMS

### Entity Structure
```
POPULAR_ORGANIZATION_DEPARTMENT_TEAMS
├─ id* (PK)
├─ department_id* (FK → POPULAR_ORGANIZATION_DEPARTMENTS)
├─ name*
├─ code*
├─ description?
├─ objectives?
└─ is_active*
```

### Relationships
```
POPULAR_ORGANIZATION_DEPARTMENT_TEAMS
  ← POPULAR_ORGANIZATION_DEPARTMENTS (Many:1)
  → POPULAR_ORGANIZATION_POSITION (1:Many)
```

### Examples
```
Engineering Department:
  ├─ Backend Team
  ├─ Frontend Team
  ├─ Mobile Team
  ├─ DevOps Team
  └─ QA Team

Sales Department:
  ├─ Inside Sales Team
  ├─ Field Sales Team
  └─ Sales Operations Team
```

---

## 5. POPULAR_ORGANIZATION_DESIGNATION

Generic designations applicable across all departments and roles. These are reusable, department-agnostic titles that can be combined with departments and teams to form specific positions.

### Entity Structure
```
POPULAR_ORGANIZATION_DESIGNATION
├─ id* (PK)
├─ name*
├─ code*
├─ level? (Seniority Level: Entry/Junior/Mid/Senior/Lead/Principal/Executive)
├─ description?
└─ is_active*
```

### Relationships
```
POPULAR_ORGANIZATION_DESIGNATION
  → POPULAR_ORGANIZATION_POSITION (1:Many)
```

### Seniority Levels
The optional `level` field categorizes designations by seniority/career progression:

- **Entry:** Entry-level positions for new graduates or career starters
- **Junior:** Early career positions with some experience
- **Mid:** Mid-level positions with moderate experience
- **Senior:** Senior-level positions with extensive experience
- **Lead:** Leadership positions responsible for teams or projects
- **Principal:** Expert-level individual contributors or technical leaders
- **Executive:** C-suite and top management positions

### Examples
```
Generic Designations with Seniority Levels:

Entry Level:
  ├─ Staff (level: Entry)
  ├─ Associate (level: Entry)
  └─ Coordinator (level: Entry)

Junior Level:
  ├─ Junior Staff (level: Junior)
  └─ Junior Associate (level: Junior)

Mid Level:
  ├─ Specialist (level: Mid)
  └─ Project Manager (level: Mid)

Senior Level:
  ├─ Senior Staff (level: Senior)
  ├─ Senior Associate (level: Senior)
  ├─ Senior Specialist (level: Senior)
  └─ Senior Manager (level: Senior)

Lead Level:
  ├─ Lead (level: Lead)
  ├─ Senior Lead (level: Lead)
  └─ Manager (level: Lead)

Principal Level:
  ├─ Director (level: Principal)
  ├─ Senior Director (level: Principal)
  ├─ Vice President (level: Principal)
  └─ Senior Vice President (level: Principal)

Executive Level:
  ├─ Chief Executive Officer (level: Executive)
  ├─ Chief Operating Officer (level: Executive)
  ├─ Chief Financial Officer (level: Executive)
  ├─ Chief Technology Officer (level: Executive)
  ├─ Chief Information Officer (level: Executive)
  ├─ Chief Human Resources Officer (level: Executive)
  ├─ President (level: Executive)
  ├─ Board Member (level: Executive)
  └─ Chairman (level: Executive)
```

### Seniority Level Benefits

**Purpose:**
- **Career Progression:** Maps designations to career ladder stages
- **Compensation Bands:** Links positions to salary ranges
- **Filtering & Reporting:** Query positions by seniority level
- **Analytics:** Track distribution of employees across seniority levels
- **Promotion Paths:** Define clear progression from Entry → Executive

**Usage Examples:**
```sql
-- Find all Senior-level designations
SELECT name, code FROM popular_organization_designation
WHERE level = 'Senior' AND is_active = 1;

-- Get positions grouped by seniority level
SELECT
    desig.level,
    COUNT(*) as position_count
FROM popular_organization_position pos
JOIN popular_organization_designation desig ON pos.designation_id = desig.id
WHERE pos.is_active = 1
GROUP BY desig.level
ORDER BY
    CASE desig.level
        WHEN 'Entry' THEN 1
        WHEN 'Junior' THEN 2
        WHEN 'Mid' THEN 3
        WHEN 'Senior' THEN 4
        WHEN 'Lead' THEN 5
        WHEN 'Principal' THEN 6
        WHEN 'Executive' THEN 7
    END;
```

**Usage Pattern:**
```
Designation + Department + Team = Position

Examples:
- "Senior Staff" (level: Senior) + "Engineering" + "Backend Team" = Senior Backend Staff
- "Manager" (level: Lead) + "Sales" + "Inside Sales Team" = Inside Sales Manager
- "Director" (level: Principal) + "Human Resources" + (no team) = HR Director
- "Chief Technology Officer" (level: Executive) + "Engineering" + (no team) = CTO
```

---

## 6. POPULAR_ORGANIZATION_POSITION

Defines organizational positions by combining department, team (optional), and designation. The position title is automatically derived from these components.

### Entity Structure
```
POPULAR_ORGANIZATION_POSITION
├─ id* (PK)
├─ code*
├─ description?
├─ department_id* (FK → POPULAR_ORGANIZATION_DEPARTMENTS)
├─ team_id? (FK → POPULAR_ORGANIZATION_DEPARTMENT_TEAMS)
├─ designation_id* (FK → POPULAR_ORGANIZATION_DESIGNATION)
├─ responsibilities?
├─ experience_years?
└─ is_active*
```

**Note:** The `title` is derived from: `Department.name + Team.name (if exists) + Designation.name`

### Relationships
```
POPULAR_ORGANIZATION_POSITION
  ← POPULAR_ORGANIZATION_DEPARTMENTS (Many:1)
  ← POPULAR_ORGANIZATION_DEPARTMENT_TEAMS (Many:1) [Optional]
  ← POPULAR_ORGANIZATION_DESIGNATION (Many:1)
  → POPULAR_ORGANIZATION_POSITION_SKILL (1:Many)
  → POPULAR_ORGANIZATION_POSITION_EDUCATION (1:Many)
  → ORGANIZATION_VACANCY (1:Many)
  → EMPLOYMENT_CONTRACT (1:Many)
  → ENTITY_PERMISSION_DEFINITION (1:Many)
  → PROCESS_NODE (1:Many)
```

---

## 7. POPULAR_ORGANIZATION_POSITION_SKILL

Junction table linking positions to required skills.

### Entity Structure
```
POPULAR_ORGANIZATION_POSITION_SKILL
├─ id* (PK)
├─ position_id* (FK → POPULAR_ORGANIZATION_POSITION)
├─ skill_id* (FK → POPULAR_SKILL)
├─ proficiency_level? (FK → ENUM_SKILL_LEVEL)
├─ is_required*
└─ priority? [1=Critical, 2=Important, 3=Nice-to-have]
```

### Relationships
```
POPULAR_ORGANIZATION_POSITION_SKILL
  ← POPULAR_ORGANIZATION_POSITION (Many:1)
  ← POPULAR_SKILL (Many:1)
  ← ENUM_SKILL_LEVEL (Many:1) [Optional]
```

### Purpose
Defines the skills required for a position with optional proficiency levels and priorities.

---

## 8. POPULAR_ORGANIZATION_POSITION_EDUCATION

Defines education level requirements for positions.

### Entity Structure
```
POPULAR_ORGANIZATION_POSITION_EDUCATION
├─ id* (PK)
├─ position_id* (FK → POPULAR_ORGANIZATION_POSITION)
├─ education_level_id* (FK → ENUM_EDUCATION_LEVELS)
├─ is_required*
└─ description?
```

### Relationships
```
POPULAR_ORGANIZATION_POSITION_EDUCATION
  ← POPULAR_ORGANIZATION_POSITION (Many:1)
  ← ENUM_EDUCATION_LEVELS (Many:1)
  → POPULAR_ORGANIZATION_POSITION_EDUCATION_SUBJECT (1:Many)
```

### Purpose
Specifies minimum education levels required for a position (e.g., Bachelor's degree, Master's degree).

---

## 9. POPULAR_ORGANIZATION_POSITION_EDUCATION_SUBJECT

Junction table linking position education requirements to specific subjects/fields of study.

### Entity Structure
```
POPULAR_ORGANIZATION_POSITION_EDUCATION_SUBJECT
├─ id* (PK)
├─ position_education_id* (FK → POPULAR_ORGANIZATION_POSITION_EDUCATION)
├─ subject_id* (FK → POPULAR_EDUCATION_SUBJECT)
└─ is_preferred* [Required vs Preferred subject]
```

### Relationships
```
POPULAR_ORGANIZATION_POSITION_EDUCATION_SUBJECT
  ← POPULAR_ORGANIZATION_POSITION_EDUCATION (Many:1)
  ← POPULAR_EDUCATION_SUBJECT (Many:1)
```

### Purpose
Specifies which subjects/fields of study are required or preferred for the education requirement (e.g., Computer Science, Engineering, Business Administration).

---

### Position Composition
```
Position = Department + Team (optional) + Designation (with Seniority Level)

Examples:
1. Engineering → Backend Team → Senior Staff (level: Senior)
   = "Senior Backend Staff"
   Seniority: Senior level position

2. Sales → Inside Sales Team → Manager (level: Lead)
   = "Inside Sales Manager"
   Seniority: Lead level position

3. HR → (no team) → Director (level: Principal)
   = "HR Director"
   Seniority: Principal level position

4. (no department) → (no team) → Chief Technology Officer (level: Executive)
   = "Chief Technology Officer"
   Seniority: Executive level position

5. Engineering → Frontend Team → Staff (level: Entry)
   = "Frontend Staff"
   Seniority: Entry level position
```

### Title Derivation Logic
```sql
-- Formula for deriving position title:
CASE
    WHEN team_id IS NOT NULL THEN
        designation.name || ' ' || team.name || ' ' || department.name
    WHEN department_id IS NOT NULL THEN
        designation.name || ' ' || department.name
    ELSE
        designation.name
END

-- Examples:
-- Senior Staff + Backend Team + Engineering = "Senior Staff Backend Team Engineering"
-- Director + NULL + Sales = "Director Sales"
-- Chief Executive Officer + NULL + NULL = "Chief Executive Officer"
```

---

## Complete Hierarchy Example

```
POPULAR_ORGANIZATION_DEPARTMENTS: Engineering
  ↓
POPULAR_ORGANIZATION_DEPARTMENT_TEAMS: Backend Team
  ↓
POPULAR_ORGANIZATION_DESIGNATION: Senior Staff
  ├─ level: "Senior"
  ├─ code: "SENIOR_STAFF"
  └─ name: "Senior Staff"
  ↓
POPULAR_ORGANIZATION_POSITION: Senior Backend Staff
  ├─ code: "ENG-BACKEND-SENIOR-STAFF"
  ├─ title: "Senior Backend Staff" (derived from components)
  ├─ seniority_level: "Senior" (from designation)
  ├─ responsibilities: "Design APIs, mentor juniors..."
  └─ experience_years: 5

  Required Skills (via POPULAR_ORGANIZATION_POSITION_SKILL):
  ├─ PHP (Critical, Expert level)
  ├─ MySQL (Critical, Advanced level)
  ├─ Redis (Important, Intermediate level)
  └─ Docker (Nice-to-have, Intermediate level)

  Education Requirements (via POPULAR_ORGANIZATION_POSITION_EDUCATION):
  └─ Bachelor's Degree or higher
      └─ Subjects: Computer Science, Software Engineering (preferred)

  Used by:
  ├─ ORGANIZATION_VACANCY (Job posting)
  ├─ EMPLOYMENT_CONTRACT (Employee assigned)
  ├─ ENTITY_PERMISSION_DEFINITION (Position permissions)
  └─ PROCESS_NODE (Task assignment)
```

---

## Cross-Domain Relationships

### To Geographic Domain
```
POPULAR_ORGANIZATION_LEGAL_TYPES → COUNTRY (Many:1)
```
Legal types are country-specific, as each country has its own legal entity structures and requirements.
See: [GEOGRAPHIC_DOMAIN.md](GEOGRAPHIC_DOMAIN.md)

### To Organization Domain
```
POPULAR_INDUSTRY_CATEGORY → ORGANIZATION (1:Many)
POPULAR_ORGANIZATION_LEGAL_TYPES → ORGANIZATION (1:Many)
```
Organizations are classified by industry category and legal entity type. This enables:
- Industry-specific filtering and analysis
- Legal structure compliance and reporting
- Country-specific organizational requirements
See: [ORGANIZATION_DOMAIN.md](ORGANIZATION_DOMAIN.md)

### To Hiring Domain
```
POPULAR_ORGANIZATION_POSITION → ORGANIZATION_VACANCY (1:Many)
POPULAR_ORGANIZATION_POSITION → EMPLOYMENT_CONTRACT (1:Many)
```
See: [HIRING_VACANCY_DOMAIN.md](HIRING_VACANCY_DOMAIN.md)

### To Process Flow Domain
```
POPULAR_ORGANIZATION_POSITION → PROCESS_NODE (1:Many) [task assignment]
POPULAR_ORGANIZATION_POSITION → PROCESS_FALLBACK_ASSIGNMENT (1:Many)
```
See: [PROCESS_FLOW_DOMAIN.md](PROCESS_FLOW_DOMAIN.md)

### To Permissions Domain
```
POPULAR_ORGANIZATION_POSITION → ENTITY_PERMISSION_DEFINITION (1:Many)
```
See: [PERMISSIONS_SECURITY_DOMAIN.md](PERMISSIONS_SECURITY_DOMAIN.md)

### To Person & Identity Domain
```
POPULAR_ORGANIZATION_POSITION_SKILL → POPULAR_SKILL (Many:1)
POPULAR_ORGANIZATION_POSITION_SKILL → ENUM_SKILL_LEVEL (Many:1)
POPULAR_ORGANIZATION_POSITION_EDUCATION → ENUM_EDUCATION_LEVELS (Many:1)
POPULAR_ORGANIZATION_POSITION_EDUCATION_SUBJECT → POPULAR_EDUCATION_SUBJECT (Many:1)
```
See: [PERSON_IDENTITY_DOMAIN.md](PERSON_IDENTITY_DOMAIN.md)

**Rationale:** Positions reference the same skill and education taxonomies used for person profiles, enabling direct matching of candidate qualifications to job requirements.

---

## Why "Popular" Organization Structure?

**Design Philosophy:**
- **Reusable Templates:** Organizations don't reinvent the wheel
- **Standardization:** Common industry-standard positions
- **Multi-Tenant:** Multiple organizations can reference same positions
- **Extensible:** Organizations can add custom positions

**vs. Organization-Specific Structure:**
```
❌ Each organization defines "Senior Developer" separately
   → Redundant data
   → Inconsistent naming
   → Hard to compare across organizations

✅ Use POPULAR_ORGANIZATION_POSITION
   → Single source of truth
   → Consistent naming
   → Easy cross-organization analytics
   → Organizations can still add custom positions if needed
```

---

## Position Resolution Chain

The system uses this hierarchy to resolve who can do what:

```
1. PERSON (Who)
   ↓
2. EMPLOYMENT_CONTRACT (Their job)
   ↓
3. POPULAR_ORGANIZATION_POSITION (Their position)
   ↓
4. ENTITY_PERMISSION_DEFINITION (What they can do)
   ↓
5. PROCESS_NODE (Tasks they can be assigned)
```

**Example:**
```
John Smith (PERSON)
  → Employment Contract #12345
      → Position: "Senior Backend Staff"
          ├─ Department: Engineering
          ├─ Team: Backend Team
          ├─ Designation: Senior Staff
          └─ Seniority Level: Senior
          → Has permission: APPROVER on ORGANIZATION_VACANCY
          → Can be assigned: "HR Review" tasks in vacancy creation process
```

---

## Common Queries

### Industry Category Queries

#### Get Root Industry Categories
```sql
SELECT
    id,
    code,
    name,
    description
FROM popular_industry_category
WHERE parent_category_id IS NULL
AND deleted_at IS NULL
ORDER BY name;
```

#### Get Sub-Categories by Parent
```sql
SELECT
    id,
    code,
    name,
    level,
    description
FROM popular_industry_category
WHERE parent_category_id = ?
AND deleted_at IS NULL
ORDER BY name;
```

#### Get Full Industry Hierarchy Path
```sql
-- Recursive CTE to get full path from category to root
WITH RECURSIVE category_path AS (
    -- Base case: start with the given category
    SELECT
        id,
        code,
        name,
        parent_category_id,
        level,
        CAST(name AS TEXT) as path,
        0 as depth
    FROM popular_industry_category
    WHERE id = ?

    UNION ALL

    -- Recursive case: get parent categories
    SELECT
        pic.id,
        pic.code,
        pic.name,
        pic.parent_category_id,
        pic.level,
        CAST(pic.name || ' > ' || cp.path AS TEXT),
        cp.depth + 1
    FROM popular_industry_category pic
    INNER JOIN category_path cp ON pic.id = cp.parent_category_id
)
SELECT
    id,
    code,
    name,
    level,
    path as full_path
FROM category_path
ORDER BY depth DESC
LIMIT 1;
```

#### Get All Descendant Categories
```sql
-- Recursive CTE to get all child categories
WITH RECURSIVE descendants AS (
    -- Base case: start with the given category
    SELECT
        id,
        code,
        name,
        parent_category_id,
        level,
        0 as depth
    FROM popular_industry_category
    WHERE id = ?

    UNION ALL

    -- Recursive case: get child categories
    SELECT
        pic.id,
        pic.code,
        pic.name,
        pic.parent_category_id,
        pic.level,
        d.depth + 1
    FROM popular_industry_category pic
    INNER JOIN descendants d ON pic.parent_category_id = d.id
)
SELECT
    id,
    code,
    name,
    level,
    depth
FROM descendants
WHERE deleted_at IS NULL
ORDER BY depth, name;
```

#### Get Organizations by Industry Category
```sql
SELECT
    o.id,
    o.name,
    o.code,
    pic.name as industry_category,
    pic.code as industry_code
FROM organization o
JOIN popular_industry_category pic ON o.industry_category_id = pic.id
WHERE pic.id = ?
AND o.deleted_at IS NULL
AND pic.deleted_at IS NULL
ORDER BY o.name;
```

#### Get Industry Category with Organization Count
```sql
SELECT
    pic.id,
    pic.code,
    pic.name,
    pic.level,
    COUNT(o.id) as organization_count
FROM popular_industry_category pic
LEFT JOIN organization o ON pic.id = o.industry_category_id AND o.deleted_at IS NULL
WHERE pic.deleted_at IS NULL
GROUP BY pic.id, pic.code, pic.name, pic.level
ORDER BY organization_count DESC, pic.name;
```

---

### Legal Types Queries

#### Get Legal Types by Country
```sql
SELECT
    polt.id,
    polt.code,
    polt.name,
    polt.description,
    polt.requires_minimum_capital,
    polt.minimum_capital_amount,
    c.name as country_name,
    c.code as country_code,
    curr.symbol as currency_symbol
FROM popular_organization_legal_types polt
JOIN country c ON polt.country_id = c.id
LEFT JOIN currency curr ON c.currency_id = curr.id
WHERE polt.country_id = ?
AND polt.deleted_at IS NULL
ORDER BY polt.name;
```

#### Get Legal Type with Full Details
```sql
SELECT
    polt.id,
    polt.code,
    polt.name,
    polt.description,
    polt.requires_minimum_capital,
    polt.minimum_capital_amount,
    c.name as country_name,
    c.code as country_code,
    c.iso_alpha2,
    c.iso_alpha3,
    curr.code as currency_code,
    curr.symbol as currency_symbol
FROM popular_organization_legal_types polt
JOIN country c ON polt.country_id = c.id
LEFT JOIN currency curr ON c.currency_id = curr.id
WHERE polt.id = ?
AND polt.deleted_at IS NULL;
```

#### Get Legal Types Requiring Minimum Capital
```sql
SELECT
    polt.code,
    polt.name,
    polt.minimum_capital_amount,
    c.name as country_name,
    curr.code as currency_code,
    curr.symbol as currency_symbol
FROM popular_organization_legal_types polt
JOIN country c ON polt.country_id = c.id
LEFT JOIN currency curr ON c.currency_id = curr.id
WHERE polt.requires_minimum_capital = 1
AND polt.deleted_at IS NULL
ORDER BY c.name, polt.minimum_capital_amount DESC;
```

#### Get Organizations by Legal Type
```sql
SELECT
    o.id,
    o.name,
    o.code,
    polt.name as legal_type,
    polt.code as legal_type_code,
    c.name as country
FROM organization o
JOIN popular_organization_legal_types polt ON o.legal_type_id = polt.id
JOIN country c ON polt.country_id = c.id
WHERE polt.id = ?
AND o.deleted_at IS NULL
ORDER BY o.name;
```

#### Get Legal Type Usage Statistics
```sql
SELECT
    polt.code,
    polt.name,
    c.name as country,
    COUNT(o.id) as organization_count
FROM popular_organization_legal_types polt
JOIN country c ON polt.country_id = c.id
LEFT JOIN organization o ON polt.id = o.legal_type_id AND o.deleted_at IS NULL
WHERE polt.deleted_at IS NULL
GROUP BY polt.id, polt.code, polt.name, c.name
ORDER BY organization_count DESC, c.name, polt.name;
```

#### Search Legal Types by Name or Code
```sql
SELECT
    polt.id,
    polt.code,
    polt.name,
    c.name as country_name,
    c.iso_alpha2
FROM popular_organization_legal_types polt
JOIN country c ON polt.country_id = c.id
WHERE (polt.name LIKE '%' || ? || '%' OR polt.code LIKE '%' || ? || '%')
AND polt.deleted_at IS NULL
ORDER BY c.name, polt.name;
```

---

### Position Queries

### Get Complete Position Details
```sql
-- Get position with derived title and seniority level
SELECT
    pos.code,
    CASE
        WHEN team.name IS NOT NULL THEN desig.name || ' ' || team.name || ' ' || dept.name
        ELSE desig.name || ' ' || dept.name
    END as derived_title,
    dept.name as department,
    team.name as team,
    desig.name as designation,
    desig.code as designation_code,
    desig.level as seniority_level,
    pos.responsibilities,
    pos.experience_years
FROM popular_organization_position pos
JOIN popular_organization_departments dept ON pos.department_id = dept.id
LEFT JOIN popular_organization_department_teams team ON pos.team_id = team.id
JOIN popular_organization_designation desig ON pos.designation_id = desig.id
WHERE pos.id = ?
AND pos.is_active = 1;

-- Get position required skills
SELECT
    ps.name as skill_name,
    esl.name as proficiency_level,
    pops.is_required,
    pops.priority
FROM popular_organization_position_skill pops
JOIN popular_skill ps ON pops.skill_id = ps.id
LEFT JOIN enum_skill_level esl ON pops.proficiency_level = esl.id
WHERE pops.position_id = ?
ORDER BY pops.priority, pops.is_required DESC;

-- Get position education requirements
SELECT
    eel.name as education_level,
    pope.is_required,
    pope.description
FROM popular_organization_position_education pope
JOIN enum_education_level eel ON pope.education_level_id = eel.id
WHERE pope.position_id = ?;

-- Get position education subjects
SELECT
    eel.name as education_level,
    pes.name as subject_name,
    popes.is_preferred
FROM popular_organization_position_education pope
JOIN popular_organization_position_education_subject popes ON pope.id = popes.position_education_id
JOIN popular_education_subject pes ON popes.subject_id = pes.id
JOIN enum_education_level eel ON pope.education_level_id = eel.id
WHERE pope.position_id = ?
ORDER BY eel.code, popes.is_preferred DESC;
```

### Get All Positions in Department
```sql
SELECT
    pos.code,
    CASE
        WHEN team.name IS NOT NULL THEN desig.name || ' ' || team.name
        ELSE desig.name
    END as derived_title,
    team.name as team,
    desig.name as designation,
    desig.code as designation_code
FROM popular_organization_position pos
LEFT JOIN popular_organization_department_teams team ON pos.team_id = team.id
JOIN popular_organization_designation desig ON pos.designation_id = desig.id
WHERE pos.department_id = ?
AND pos.is_active = 1
ORDER BY desig.name, team.name;
```

### Find Positions by Designation
```sql
SELECT
    pos.code,
    CASE
        WHEN team.name IS NOT NULL THEN desig.name || ' ' || team.name || ' ' || dept.name
        ELSE desig.name || ' ' || dept.name
    END as derived_title,
    dept.name as department,
    team.name as team,
    desig.name as designation
FROM popular_organization_position pos
JOIN popular_organization_departments dept ON pos.department_id = dept.id
LEFT JOIN popular_organization_department_teams team ON pos.team_id = team.id
JOIN popular_organization_designation desig ON pos.designation_id = desig.id
WHERE desig.code = 'MANAGER'
AND pos.is_active = 1;
```

### Find Positions by Seniority Level
```sql
SELECT
    pos.code,
    CASE
        WHEN team.name IS NOT NULL THEN desig.name || ' ' || team.name || ' ' || dept.name
        ELSE desig.name || ' ' || dept.name
    END as derived_title,
    dept.name as department,
    team.name as team,
    desig.name as designation,
    desig.level as seniority_level
FROM popular_organization_position pos
JOIN popular_organization_departments dept ON pos.department_id = dept.id
LEFT JOIN popular_organization_department_teams team ON pos.team_id = team.id
JOIN popular_organization_designation desig ON pos.designation_id = desig.id
WHERE desig.level IN ('Senior', 'Lead', 'Principal')
AND pos.is_active = 1
ORDER BY
    CASE desig.level
        WHEN 'Senior' THEN 1
        WHEN 'Lead' THEN 2
        WHEN 'Principal' THEN 3
    END,
    dept.name,
    team.name;
```

### Get Teams in Department
```sql
SELECT * FROM popular_organization_department_teams
WHERE department_id = ?
AND is_active = 1
ORDER BY name;
```

### Find Positions Requiring Specific Skill
```sql
SELECT DISTINCT
    pos.code,
    CASE
        WHEN team.name IS NOT NULL THEN desig.name || ' ' || team.name || ' ' || dept.name
        ELSE desig.name || ' ' || dept.name
    END as derived_title,
    ps.name as skill_name,
    esl.name as proficiency_level,
    pops.is_required,
    pops.priority
FROM popular_organization_position pos
JOIN popular_organization_position_skill pops ON pos.id = pops.position_id
JOIN popular_skill ps ON pops.skill_id = ps.id
LEFT JOIN enum_skill_level esl ON pops.proficiency_level = esl.id
JOIN popular_organization_departments dept ON pos.department_id = dept.id
LEFT JOIN popular_organization_department_teams team ON pos.team_id = team.id
JOIN popular_organization_designation desig ON pos.designation_id = desig.id
WHERE ps.name = 'PHP'
  AND pops.is_required = 1
  AND pos.is_active = 1;
```

### Find Positions by Education Level
```sql
SELECT DISTINCT
    pos.code,
    CASE
        WHEN team.name IS NOT NULL THEN desig.name || ' ' || team.name || ' ' || dept.name
        ELSE desig.name || ' ' || dept.name
    END as derived_title,
    eel.name as education_level,
    pope.is_required
FROM popular_organization_position pos
JOIN popular_organization_position_education pope ON pos.id = pope.position_id
JOIN enum_education_level eel ON pope.education_level_id = eel.id
JOIN popular_organization_departments dept ON pos.department_id = dept.id
LEFT JOIN popular_organization_department_teams team ON pos.team_id = team.id
JOIN popular_organization_designation desig ON pos.designation_id = desig.id
WHERE eel.code IN ('BACHELOR', 'MASTER')
  AND pos.is_active = 1;
```

---

## Creating New Positions

### Pattern: Build from Components
```sql
-- Step 1: Find department
SELECT id FROM popular_organization_departments
WHERE code = 'ENG';

-- Step 2: Find team (optional)
SELECT id FROM popular_organization_department_teams
WHERE department_id = ? AND code = 'BACKEND';

-- Step 3: Find designation
SELECT id FROM popular_organization_designation
WHERE code = 'SENIOR_STAFF';

-- Step 4: Create position
INSERT INTO popular_organization_position (
    id, code,
    department_id, team_id, designation_id,
    responsibilities, experience_years, is_active
) VALUES (
    ?,
    'ENG-BACKEND-SENIOR-STAFF',
    ?, ?, ?,
    'Design APIs, mentor juniors, code reviews',
    5,
    1
);

-- Step 5: Add required skills
INSERT INTO popular_organization_position_skill (
    id, position_id, skill_id, proficiency_level, is_required, priority
) VALUES
    (?, ?, (SELECT id FROM popular_skill WHERE name = 'PHP'),
     (SELECT id FROM enum_skill_level WHERE code = 'EXPERT'), 1, 1),
    (?, ?, (SELECT id FROM popular_skill WHERE name = 'MySQL'),
     (SELECT id FROM enum_skill_level WHERE code = 'ADVANCED'), 1, 1),
    (?, ?, (SELECT id FROM popular_skill WHERE name = 'Redis'),
     (SELECT id FROM enum_skill_level WHERE code = 'INTERMEDIATE'), 1, 2),
    (?, ?, (SELECT id FROM popular_skill WHERE name = 'Docker'),
     (SELECT id FROM enum_skill_level WHERE code = 'INTERMEDIATE'), 0, 3);

-- Step 6: Add education requirements
INSERT INTO popular_organization_position_education (
    id, position_id, education_level_id, is_required, description
) VALUES (
    ?,
    ?,
    (SELECT id FROM enum_education_level WHERE code = 'BACHELOR'),
    1,
    'Bachelor degree or higher in relevant field'
);

-- Step 7: Add preferred education subjects
INSERT INTO popular_organization_position_education_subject (
    id, position_education_id, subject_id, is_preferred
) VALUES
    (?, ?, (SELECT id FROM popular_education_subject WHERE name = 'Computer Science'), 0),
    (?, ?, (SELECT id FROM popular_education_subject WHERE name = 'Software Engineering'), 1);
```

---

## Data Integrity Rules

1. **Unique Codes:**
   - Industry category codes must be unique across all categories
   - Legal type codes should be unique within a country (e.g., USA can have "LLC", UK can have "Ltd")
   - Department codes, team codes, designation codes must be unique
   - Position codes should be unique (pattern: DEPT-TEAM-DESIGNATION)

2. **Industry Category Hierarchy:**
   - Categories can reference a parent category (self-referencing relationship)
   - Root categories have `parent_category_id = NULL` and `level = 1`
   - Child categories must have valid parent_category_id
   - Level should be automatically calculated based on hierarchy depth
   - Circular references must be prevented (category cannot be its own ancestor)
   - Deleting a category with child categories should be restricted or cascade appropriately

3. **Legal Type Country Association:**
   - Every legal type must be associated with a valid country
   - Legal type code should be unique within a country (composite unique: country_id + code)
   - Multiple countries can have the same legal type code (e.g., "Ltd" exists in UK, India, etc.)
   - Capital requirements (minimum_capital_amount) should use the country's primary currency
   - Deleting a legal type used by organizations should be restricted

4. **Seniority Level Values:**
   - Valid seniority levels: Entry, Junior, Mid, Senior, Lead, Principal, Executive
   - Level is optional but recommended for better career progression tracking
   - Levels follow a standard hierarchy for reporting and analytics
   - Applications should validate level values against the standard set

5. **Active Status:**
   - Inactive positions cannot be assigned to new vacancies
   - Existing contracts remain valid even if position becomes inactive

6. **Team Optional:**
   - Positions can exist without a team (department-level positions)
   - Example: "HR Director" (no specific team)
   - Executive positions may have neither department nor team

7. **Hierarchical Consistency:**
   - If position has a team, the team must belong to the same department

8. **Title Derivation:**
   - Position title is NOT stored, it is derived from components
   - Applications should compute title using: Designation + Team + Department
   - This ensures consistency and eliminates duplicate data

9. **Skills and Education:**
   - A position can have multiple required/preferred skills
   - Skills can have different priority levels (Critical, Important, Nice-to-have)
   - A position can have multiple education requirements (e.g., Bachelor OR Master)
   - Education requirements can specify preferred subjects

10. **Cascade Deletion:**
   - Deleting a position should cascade to position_skill, position_education
   - Deleting position_education should cascade to position_education_subject
   - Do not delete positions that are referenced by active vacancies or contracts
   - Do not delete industry categories that are referenced by organizations
   - Do not delete legal types that are referenced by organizations

---

## Benefits of Normalized Structure

### Industry Categorization Benefits

**1. Standardized Classification**
- Consistent industry naming across all organizations
- Enables accurate cross-organization comparisons
- Supports industry benchmarking and analytics

**2. Hierarchical Flexibility**
- Multi-level categorization (e.g., Technology → Software → SaaS)
- Organizations can be classified at any level of specificity
- Easy to aggregate data at higher levels (all Technology companies)

**3. Industry-Specific Features**
- Enable/disable features based on industry
- Industry-specific reporting and dashboards
- Compliance requirements by industry sector

**4. Search and Filtering**
- Filter organizations by industry
- Find similar organizations in the same sector
- Industry-based recommendations and connections

**5. Analytics and Insights**
- Industry trends and comparisons
- Market share analysis
- Industry-specific KPIs and metrics

---

### Legal Type Standardization Benefits

**1. Regulatory Compliance**
- Ensures organizations use valid legal structures for their jurisdiction
- Tracks minimum capital requirements automatically
- Supports country-specific legal compliance checks

**2. International Support**
- Handles legal entity types from multiple countries
- Same legal type code (e.g., "Ltd") can exist in different countries with different requirements
- Enables multi-national organizational structures

**3. Business Intelligence**
- Analyze organizations by legal structure
- Identify capital compliance issues
- Filter by legal type for targeted operations

**4. Automated Validation**
- Validate legal type is appropriate for organization's country
- Check minimum capital requirements
- Ensure proper legal entity naming conventions

**5. Data Consistency**
- Standardized legal type naming across all organizations
- Eliminates variations (e.g., "LLC" vs "L.L.C." vs "Limited Liability Company")
- Maintains accurate relationship between legal type and country

**6. Reporting and Analytics**
- Group organizations by legal structure
- Compare characteristics of different legal types
- Country-specific legal type distribution analysis

---

### Position Structure Benefits

### 1. **Seniority Level Tracking**
- **Career Ladders:** Define clear progression paths (Entry → Junior → Mid → Senior → Lead → Principal → Executive)
- **Compensation Management:** Link salary bands to seniority levels
- **Workforce Analytics:** Track seniority distribution across organization
- **Promotion Planning:** Identify employees ready for next level
- **Reporting:** Filter and group positions by career stage
- **Talent Development:** Design training programs by seniority level

### 2. **No Data Duplication**
- Title is computed, not stored → eliminates inconsistency
- Skills are in separate table → easy to update/add/remove
- Education requirements are normalized → flexible matching
- Seniority level is standardized across all designations

### 3. **Powerful Querying**
```sql
-- Find all positions requiring "Python" skill
-- Find all positions requiring Bachelor's in Computer Science
-- Find positions with specific skill proficiency levels
-- Compare positions by education requirements
-- Filter positions by seniority level
```

### 4. **Flexible Requirements**
- Position can require multiple skills with different priorities
- Position can accept multiple education levels (Bachelor OR Master)
- Can specify preferred vs required subjects
- Easy to add/remove requirements without schema changes
- Seniority level provides additional filtering capability

### 5. **Better Matching**
```sql
-- Match candidates to positions based on:
--   ✓ Skills they have vs skills position requires
--   ✓ Education level and subjects
--   ✓ Proficiency levels
--   ✓ Required vs preferred criteria
--   ✓ Career stage (seniority level)
```

### 6. **Consistency**
- Title always matches components (Department + Team + Designation)
- No risk of title being out of sync with structure
- Easier to maintain and update

---

## Common Position Templates

Using generic designations combined with departments and teams:

### Engineering Department
```
Backend Team:
  - Junior Staff (Engineering → Backend Team)
  - Staff (Engineering → Backend Team)
  - Senior Staff (Engineering → Backend Team)
  - Lead (Engineering → Backend Team)
  - Manager (Engineering → Backend Team)

Frontend Team:
  - Junior Staff (Engineering → Frontend Team)
  - Staff (Engineering → Frontend Team)
  - Senior Staff (Engineering → Frontend Team)
  - Lead (Engineering → Frontend Team)

Department Level:
  - Director (Engineering)
  - Senior Director (Engineering)
  - Vice President (Engineering)
  - Chief Technology Officer
```

### Sales Department
```
Inside Sales Team:
  - Staff (Sales → Inside Sales Team)
  - Senior Staff (Sales → Inside Sales Team)
  - Lead (Sales → Inside Sales Team)
  - Manager (Sales → Inside Sales Team)

Field Sales Team:
  - Staff (Sales → Field Sales Team)
  - Manager (Sales → Field Sales Team)

Department Level:
  - Director (Sales)
  - Vice President (Sales)
```

### Human Resources
```
(No specific teams)
  - Coordinator (Human Resources)
  - Specialist (Human Resources)
  - Senior Specialist (Human Resources)
  - Manager (Human Resources)
  - Director (Human Resources)
  - Chief Human Resources Officer
```

### Executive Level
```
(No department/team - organization-wide)
  - Chief Executive Officer
  - Chief Operating Officer
  - Chief Financial Officer
  - President
  - Board Member
  - Chairman
```

---

## Legal Type Usage Examples

### Example 1: US-Based LLC
```
Organization: "Tech Innovations LLC"
Legal Type: LLC (Limited Liability Company)
  ├─ Country: United States
  ├─ Code: LLC
  ├─ Requires Minimum Capital: No
  └─ Description: Limited liability with pass-through taxation

Display Format: "Tech Innovations LLC"
```

### Example 2: Indian Private Limited Company
```
Organization: "Software Solutions"
Legal Type: Private Limited Company
  ├─ Country: India
  ├─ Code: Pvt Ltd
  ├─ Requires Minimum Capital: Yes
  ├─ Minimum Capital: ₹100,000 INR
  └─ Description: Limited liability company with restrictions on share transfer

Display Format: "Software Solutions Pvt Ltd"
```

### Example 3: UK Public Limited Company
```
Organization: "Global Enterprises"
Legal Type: Public Limited Company
  ├─ Country: United Kingdom
  ├─ Code: PLC
  ├─ Requires Minimum Capital: Yes
  ├─ Minimum Capital: £50,000 GBP
  └─ Description: Can offer shares to the public, listed on stock exchange

Display Format: "Global Enterprises PLC"
```

### Example 4: German GmbH
```
Organization: "Deutsche Tech"
Legal Type: GmbH (Gesellschaft mit beschränkter Haftung)
  ├─ Country: Germany
  ├─ Code: GmbH
  ├─ Requires Minimum Capital: Yes
  ├─ Minimum Capital: €25,000 EUR
  └─ Description: Limited liability company with restricted ownership transfer

Display Format: "Deutsche Tech GmbH"
```

### Example 5: Multi-Country Legal Type Filtering
```sql
-- Find all organizations in a specific country with a specific legal type
SELECT
    o.name,
    polt.code as legal_type,
    c.name as country
FROM organization o
JOIN popular_organization_legal_types polt ON o.legal_type_id = polt.id
JOIN country c ON polt.country_id = c.id
WHERE c.iso_alpha2 = 'IN'  -- India
AND polt.code = 'Pvt Ltd'
AND o.deleted_at IS NULL;
```

### Example 6: Capital Requirement Validation
```sql
-- Organizations needing capital compliance check
SELECT
    o.name,
    o.paid_up_capital,
    polt.name as legal_type,
    polt.minimum_capital_amount as required_capital,
    curr.symbol,
    CASE
        WHEN o.paid_up_capital >= polt.minimum_capital_amount THEN 'Compliant'
        ELSE 'Non-Compliant'
    END as compliance_status
FROM organization o
JOIN popular_organization_legal_types polt ON o.legal_type_id = polt.id
JOIN country c ON polt.country_id = c.id
LEFT JOIN currency curr ON c.currency_id = curr.id
WHERE polt.requires_minimum_capital = 1
AND o.deleted_at IS NULL;
```

---

## Industry Category Usage Examples

### Example 1: Technology Company Classification
```
Organization: "TechCorp Inc."
Industry Category: Technology → Software → SaaS Platforms
  ├─ Root: Technology (level 1)
  ├─ Parent: Software (level 2)
  └─ Selected: SaaS Platforms (level 3)
```

### Example 2: Healthcare Organization
```
Organization: "MediHealth Services"
Industry Category: Healthcare → Healthcare Services → Telemedicine
  ├─ Root: Healthcare (level 1)
  ├─ Parent: Healthcare Services (level 2)
  └─ Selected: Telemedicine (level 3)
```

### Example 3: Finding Similar Organizations
```sql
-- Find all organizations in the same industry category
SELECT o.name, o.code
FROM organization o
WHERE o.industry_category_id = ?
AND o.id != ? -- exclude current organization
AND o.deleted_at IS NULL;

-- Find organizations in same parent category (broader match)
SELECT o.name, o.code, pic.name as specific_category
FROM organization o
JOIN popular_industry_category pic ON o.industry_category_id = pic.id
WHERE pic.parent_category_id = ?
AND o.deleted_at IS NULL;
```

### Example 4: Industry-Based Analytics
```sql
-- Count organizations by top-level industry
WITH root_categories AS (
    SELECT
        pic.id,
        pic.name,
        pic.code
    FROM popular_industry_category pic
    WHERE pic.parent_category_id IS NULL
),
org_counts AS (
    SELECT
        COALESCE(
            rc.id,
            (SELECT id FROM popular_industry_category WHERE id = pic.parent_category_id),
            pic.id
        ) as root_id,
        COUNT(o.id) as org_count
    FROM organization o
    JOIN popular_industry_category pic ON o.industry_category_id = pic.id
    LEFT JOIN root_categories rc ON pic.parent_category_id = rc.id OR pic.id = rc.id
    WHERE o.deleted_at IS NULL
    GROUP BY root_id
)
SELECT
    rc.name as industry,
    rc.code,
    COALESCE(oc.org_count, 0) as organization_count
FROM root_categories rc
LEFT JOIN org_counts oc ON rc.id = oc.root_id
ORDER BY organization_count DESC;
```

---

## Related Documentation

- **Entity Creation Rules:** [/architecture/entities/ENTITY_CREATION_RULES.md](../ENTITY_CREATION_RULES.md)
- **Relationship Rules:** [RELATIONSHIP_RULES.md](RELATIONSHIP_RULES.md)
- **Hiring Guide:** [/guides/features/VACANCY_CREATION_PROCESS.md](../../guides/features/VACANCY_CREATION_PROCESS.md)
- **All Domain Relationships:** [README.md](README.md)

---

**Last Updated:** 2025-11-05
**Domain:** Popular Organization Structure (Reference Data)
