# Entity Relationships Documentation

> **📏 Note:** This directory contains domain-specific entity relationship documentation. For general rules, see [RELATIONSHIP_RULES.md](RELATIONSHIP_RULES.md).

---

## Overview

This directory organizes entity relationships by business domain, making it easier to understand how entities relate within and across domains.

---

## Directory Structure

```
relationships/
├── README.md (this file)
├── RELATIONSHIP_RULES.md (unified rules reference)
│
├── Domain-Specific Relationships:
├── PERSON_IDENTITY_DOMAIN.md
├── GEOGRAPHIC_DOMAIN.md
├── ORGANIZATION_DOMAIN.md
├── POPULAR_ORGANIZATION_STRUCTURE.md
├── HIRING_VACANCY_DOMAIN.md
├── PROCESS_FLOW_DOMAIN.md
├── PERMISSIONS_SECURITY_DOMAIN.md
└── MEDIA_FILE_DOMAIN.md
```

---

## Quick Navigation

### By Domain

| Domain | File | Core Entities | Purpose |
|--------|------|---------------|---------|
| **Person & Identity** | [PERSON_IDENTITY_DOMAIN.md](PERSON_IDENTITY_DOMAIN.md) | 5 entities | User profiles, credentials, education, skills |
| **Geographic** | [GEOGRAPHIC_DOMAIN.md](GEOGRAPHIC_DOMAIN.md) | 4 entities | Countries, states, cities, addresses |
| **Organization** | [ORGANIZATION_DOMAIN.md](ORGANIZATION_DOMAIN.md) | 7 entities | Organizations, admins, branches, buildings, workstations |
| **Popular Org Structure** | [POPULAR_ORGANIZATION_STRUCTURE.md](POPULAR_ORGANIZATION_STRUCTURE.md) | 9 entities | Industries, legal types, departments, teams, positions (reference data) |
| **Hiring & Vacancy** | [HIRING_VACANCY_DOMAIN.md](HIRING_VACANCY_DOMAIN.md) | 8 entities | Vacancies, applications, interviews, offers, contracts |
| **Process Flow** | [PROCESS_FLOW_DOMAIN.md](PROCESS_FLOW_DOMAIN.md) | 8 entities | Workflow engine, task management, audit trails |
| **Permissions** | [PERMISSIONS_SECURITY_DOMAIN.md](PERMISSIONS_SECURITY_DOMAIN.md) | 2 entities | Position-based access control |
| **Media & Files** | [MEDIA_FILE_DOMAIN.md](MEDIA_FILE_DOMAIN.md) | 2 entities | File uploads, versioning, access control, storage management |

### By Purpose

**Need to understand...**

- **User management?** → [PERSON_IDENTITY_DOMAIN.md](PERSON_IDENTITY_DOMAIN.md)
- **Location hierarchy?** → [GEOGRAPHIC_DOMAIN.md](GEOGRAPHIC_DOMAIN.md)
- **Organization structure?** → [ORGANIZATION_DOMAIN.md](ORGANIZATION_DOMAIN.md)
- **Job positions?** → [POPULAR_ORGANIZATION_STRUCTURE.md](POPULAR_ORGANIZATION_STRUCTURE.md)
- **Recruitment process?** → [HIRING_VACANCY_DOMAIN.md](HIRING_VACANCY_DOMAIN.md)
- **Workflow system?** → [PROCESS_FLOW_DOMAIN.md](PROCESS_FLOW_DOMAIN.md)
- **Access control?** → [PERMISSIONS_SECURITY_DOMAIN.md](PERMISSIONS_SECURITY_DOMAIN.md)
- **File uploads & storage?** → [MEDIA_FILE_DOMAIN.md](MEDIA_FILE_DOMAIN.md)

---

## Domain Summary

### 1. Person & Identity Domain
**Entities:** PERSON, PERSON_CREDENTIAL, PERSON_EDUCATION, PERSON_EDUCATION_SUBJECT, PERSON_SKILL

**Purpose:** Core user profiles and authentication

**Key Relationships:**
- Person → Multiple credentials (multi-factor login)
- Person → Education history
- Person → Skills portfolio

---

### 2. Geographic & Address Domain
**Entities:** COUNTRY, STATE, CITY, POSTAL_ADDRESS

**Purpose:** Location hierarchy and address management

**Key Relationships:**
- COUNTRY → STATE → CITY → POSTAL_ADDRESS
- POSTAL_ADDRESS belongs to PERSON or ORGANIZATION

---

### 3. Organization Domain
**Entities:** ORGANIZATION, ORGANIZATION_ADMIN, ORGANIZATION_BRANCH, ORGANIZATION_BUILDING, WORKSTATION, POSTAL_ADDRESS, EMPLOYMENT_CONTRACT

**Purpose:** Organizational structure and membership

**Key Relationships:**
- Three-tier membership (Main Admin, Organization Admin, Employee)
- Infrastructure hierarchy (Branch → Building → Workstation)
- Organization → Vacancies, Employees, Addresses

---

### 4. Popular Organization Structure
**Entities:** POPULAR_INDUSTRY_CATEGORY, POPULAR_ORGANIZATION_LEGAL_TYPES, POPULAR_ORGANIZATION_DEPARTMENTS, POPULAR_ORGANIZATION_DEPARTMENT_TEAMS, POPULAR_ORGANIZATION_DESIGNATION, POPULAR_ORGANIZATION_POSITION, POPULAR_ORGANIZATION_POSITION_SKILL, POPULAR_ORGANIZATION_POSITION_EDUCATION, POPULAR_ORGANIZATION_POSITION_EDUCATION_SUBJECT

**Purpose:** Reusable reference data for organizational structures

**Key Relationships:**
- Industry categories (hierarchical)
- Legal entity types by country
- Department → Teams → Positions
- Position = Department + Team + Designation
- Used by: Vacancies, Employment Contracts, Process Nodes

---

### 5. Hiring & Vacancy Domain
**Entities:** ORGANIZATION_VACANCY, ORGANIZATION_VACANCY_WORKSTATION, VACANCY_APPLICATION, APPLICATION_REVIEW, APPLICATION_INTERVIEW, INTERVIEW_STAGE, JOB_OFFER, EMPLOYMENT_CONTRACT

**Purpose:** Complete recruitment lifecycle

**Key Relationships:**
- Vacancy → Applications → Reviews → Interviews → Offer → Contract
- Vacancy ← Position (reference data)
- Vacancy → Workstations (many-to-many)
- Position Resolution Chain (used by Process Flow)

---

### 6. Process Flow System
**Entities:** PROCESS_GRAPH, PROCESS_NODE, PROCESS_EDGE, TASK_FLOW_INSTANCE, TASK_INSTANCE, TASK_AUDIT_LOG, PROCESS_FALLBACK_ASSIGNMENT, POSITION_RESOLVER

**Purpose:** Workflow engine and task management

**Key Relationships:**
- Graph (template) → Nodes (steps) → Edges (transitions)
- Flow Instance (running) → Task Instances (work items)
- Position-based task assignment

---

### 7. Permissions & Security
**Entities:** ENUM_ENTITY_PERMISSION_TYPE, ENTITY_PERMISSION_DEFINITION

**Purpose:** Position-based access control

**Key Relationships:**
- Position → Permission Definition → Permission Type
- Used by: Process Flow (task assignment), Entity CRUD operations

---

### 8. Media & File Management
**Entities:** MEDIA_FILE, MEDIA_FILE_ACCESS_LOG

**Purpose:** Centralized file upload, storage, versioning, and access control

**Key Relationships:**
- MEDIA_FILE → Polymorphic relationship to any entity (PERSON, ORGANIZATION, etc.)
- MEDIA_FILE → Version chain (replaces_file_id)
- MEDIA_FILE → Person (uploader)
- MEDIA_FILE → Access logs (audit trail)

---

## Cross-Domain Relationships

### Major Connection Points

```
PERSON (Identity)
  ├─→ ORGANIZATION (Organization) [as main_admin]
  ├─→ EMPLOYMENT_CONTRACT (Hiring) [as employee]
  ├─→ VACANCY_APPLICATION (Hiring) [as applicant]
  ├─→ TASK_INSTANCE (Process Flow) [as assignee]
  ├─→ POSTAL_ADDRESS (Geographic)
  └─→ MEDIA_FILE (Media) [as uploader, and polymorphic owner]

POPULAR_ORGANIZATION_POSITION (Org Structure)
  ├─→ ORGANIZATION_VACANCY (Hiring)
  ├─→ EMPLOYMENT_CONTRACT (Hiring)
  ├─→ PROCESS_NODE (Process Flow) [task assignment]
  └─→ ENTITY_PERMISSION_DEFINITION (Permissions)

ORGANIZATION (Organization)
  ├─→ ORGANIZATION_VACANCY (Hiring)
  ├─→ EMPLOYMENT_CONTRACT (Hiring)
  ├─→ TASK_FLOW_INSTANCE (Process Flow)
  ├─→ POSTAL_ADDRESS (Geographic)
  └─→ MEDIA_FILE (Media) [polymorphic: logos, documents]

ORGANIZATION_VACANCY (Hiring)
  ├─→ POPULAR_ORGANIZATION_POSITION (Org Structure)
  ├─→ WORKSTATION (Organization) [via junction table]
  ├─→ VACANCY_APPLICATION (Hiring)
  └─→ TASK_FLOW_INSTANCE (Process Flow) [optional]

MEDIA_FILE (Media)
  ├─→ PERSON (Identity) [polymorphic files]
  ├─→ ORGANIZATION (Organization) [polymorphic files]
  ├─→ ORGANIZATION_VACANCY (Hiring) [polymorphic files]
  ├─→ VACANCY_APPLICATION (Hiring) [polymorphic files]
  └─→ ANY ENTITY [via entity_type + entity_id]
```

---

## Relationship Legend

All domain files use this consistent notation:

```
→  One-to-Many (A → B means "A has many B")
← Many-to-One (B ← A means "B belongs to A")
↔  Many-to-Many (via junction table)
*  Required field/relationship
?  Optional field/relationship
```

---

## How to Use This Documentation

### For New Developers

1. **Start with:** [RELATIONSHIP_RULES.md](RELATIONSHIP_RULES.md) - Learn general relationship patterns
2. **Then read:** Domain files relevant to your feature
3. **Cross-reference:** Follow links to related domains

### For Feature Development

1. **Identify domains** your feature touches
2. **Read domain files** to understand existing relationships
3. **Check cross-domain links** if integrating multiple areas
4. **Follow rules** when creating new relationships

### For Debugging

1. **Find entity** in appropriate domain file
2. **Review relationships** to understand data dependencies
3. **Check cross-domain links** for unexpected connections
4. **Use query examples** provided in domain files

---

## What's in Each Domain File

Every domain-specific file includes:

✅ **Domain Overview** - Purpose and scope
✅ **Entity Structures** - Complete field lists
✅ **Relationship Diagrams** - Visual representations
✅ **Relationship Details** - Type, cardinality, constraints
✅ **Cross-Domain Links** - Connections to other domains
✅ **Common Queries** - SQL examples
✅ **Data Integrity Rules** - Validation requirements
✅ **Related Documentation** - Links to guides and rules

---

## Migration from Original ER Diagram

This structure replaces the monolithic entity relationship documentation with:

- **9 domain-specific files** (easier to navigate)
- **1 unified rules file** (common patterns)
- **Better organization** (grouped by business domain)
- **Improved cross-referencing** (links between domains)

**New structure:**
```
relationships/
├── README.md (this file)
├── RELATIONSHIP_RULES.md (unified rules)
├── PERSON_IDENTITY_DOMAIN.md
├── GEOGRAPHIC_DOMAIN.md
├── ORGANIZATION_DOMAIN.md
├── POPULAR_ORGANIZATION_STRUCTURE.md
├── HIRING_VACANCY_DOMAIN.md
├── PROCESS_FLOW_DOMAIN.md
├── PERMISSIONS_SECURITY_DOMAIN.md
└── MEDIA_FILE_DOMAIN.md
```

---

## Contributing

When adding new entities or relationships:

1. **Identify domain** (or create new domain file if needed)
2. **Follow RELATIONSHIP_RULES.md** for conventions
3. **Update domain-specific file** with new entity/relationship
4. **Document cross-domain links** in both affected domains
5. **Update this README** if adding a new domain

---

## Related Documentation

- **Entity Creation Rules:** [/architecture/entities/ENTITY_CREATION_RULES.md](../ENTITY_CREATION_RULES.md)
- **Process Flow System:** [/architecture/processes/PROCESS_FLOW_SYSTEM.md](../../processes/PROCESS_FLOW_SYSTEM.md)
- **Quick References:** [/architecture/processes/PROCESS_SYSTEM_QUICK_START.md](../../processes/PROCESS_SYSTEM_QUICK_START.md)
- **Implementation Guides:** [/guides/](../../../guides/)
- **Main Architecture Directory:** [/architecture/README.md](../../README.md)

---

**Total Domains:** 8
**Total Core Entities:** ~45+
**Last Updated:** 2025-11-05
**Version:** 1.1

---

**Quick Links:**
- [Relationship Rules](RELATIONSHIP_RULES.md)
- [Person & Identity](PERSON_IDENTITY_DOMAIN.md)
- [Geographic](GEOGRAPHIC_DOMAIN.md)
- [Organization](ORGANIZATION_DOMAIN.md)
- [Popular Org Structure](POPULAR_ORGANIZATION_STRUCTURE.md)
- [Hiring & Vacancy](HIRING_VACANCY_DOMAIN.md)
- [Process Flow](PROCESS_FLOW_DOMAIN.md)
- [Permissions](PERMISSIONS_SECURITY_DOMAIN.md)
- [Media & Files](MEDIA_FILE_DOMAIN.md)
