# Rules Directory

This directory contains **rules and reference documentation** for system design and development standards.

## Purpose

Rules files define:
- **Standards** - Naming conventions, file structures, patterns
- **Architecture** - System design, data models, workflows
- **Quick References** - Cheat sheets and templates
- **Technical Specifications** - API contracts, data types, constraints

## Files in This Directory

### Entity Creation Rules

| File | Purpose |
|------|---------|
| `ENTITY_CREATION_RULES.md` | Complete rules for creating entity metadata files |
| `ENTITY_QUICK_REFERENCE.md` | Quick reference cheat sheet for entities |

**Use these when:**
- Creating new entity definitions
- Adding attributes to entities
- Defining relationships between entities
- Setting up validation rules

### Process Flow System Rules

| File | Purpose |
|------|---------|
| `PROCESS_FLOW_SYSTEM.md` | Complete architecture and rules for workflow engine |
| `PROCESS_SYSTEM_QUICK_START.md` | Quick reference for process system |

**Use these when:**
- Designing new workflow processes
- Understanding process architecture
- Setting up task routing and conditions
- Implementing position-based assignments

---

## Rules vs Guides

### 📏 Rules (This Directory: `/rules`)

**Purpose:** Standards, conventions, and architecture references

**Audience:** Developers building new features

**Content:**
- What naming conventions to follow
- How to structure metadata files
- What patterns to use
- Architecture diagrams
- Technical specifications

**Examples:**
- "Entity IDs must be UUIDs"
- "File naming: `NNN-domain_name.sql`"
- "Process nodes have 6 types: START, TASK, DECISION, FORK, JOIN, END"

### 📚 Guides (Directory: `/guides`)

**Purpose:** Step-by-step tutorials and complete documentation

**Audience:** Users, administrators, and developers implementing features

**Content:**
- How to install the system
- How to create a vacancy workflow
- How to run migrations
- Usage examples with code
- Troubleshooting guides

**Examples:**
- "Step 1: Install process flow system..."
- "To start a process, call POST /api/process/start.php..."
- "If you get error X, try Y..."

---

## Quick Navigation

### Need to understand system architecture?
**→ Read:** `/rules` files

### Need to implement a feature?
**→ Read:** `/guides` files

### Need both?
**→ Start with:** `/rules` for architecture understanding
**→ Then read:** `/guides` for implementation steps

---

## Directory Structure

```
oom/
├── rules/                              ← YOU ARE HERE
│   ├── README.md                       ← This file
│   ├── ENTITY_CREATION_RULES.md        ← Entity standards
│   ├── ENTITY_QUICK_REFERENCE.md       ← Entity cheat sheet
│   ├── PROCESS_FLOW_SYSTEM.md          ← Process architecture
│   └── PROCESS_SYSTEM_QUICK_START.md   ← Process reference
│
└── guides/                             ← Implementation guides
    ├── README.md                       ← Guides overview
    ├── INSTALL.md                      ← Installation guide
    ├── QUICK_START.md                  ← Getting started
    ├── MIGRATION_GUIDE.md              ← Database migrations
    ├── VACANCY_CREATION_PROCESS.md     ← Vacancy workflow guide
    ├── VACANCY_PROCESS_ENTITIES.md     ← Vacancy entities guide
    └── ...                             ← More guides
```

---

## When to Create New Rules Files

Create a new rules file when:
1. **Introducing a new system component** with its own architecture
2. **Defining standards** that apply across multiple features
3. **Documenting technical specifications** that developers need to reference

### Template for New Rules Files

```markdown
# [Component Name] - Rules and Architecture

> **📚 Note:** This is a rules reference. For implementation guides, see the `/guides` folder.

## Overview
[Brief description of the component and its purpose]

## Architecture
[System design, data models, workflow diagrams]

## Rules and Conventions
[Standards developers must follow]

## Quick Reference
[Cheat sheet of common patterns]
```

---

## Maintenance

### Updating Rules Files

**When:** System architecture changes or new standards are adopted

**Process:**
1. Update the rules file with new standards
2. Add changelog note at the top
3. Update related guides in `/guides` folder
4. Notify team of changes

### Version Control

Rules files are version-controlled in git:
- Commit rules changes separately from code
- Use descriptive commit messages
- Tag major architecture changes

---

## Related Documentation

- **`/guides`** - Implementation guides and tutorials
- **`/metadata`** - Entity definitions and migrations
- **`/lib`** - Code implementation of rules
- **`/public/api`** - API endpoints

---

## Summary

**Rules Directory Purpose:**
✅ Define system architecture
✅ Document design patterns
✅ Provide technical references
✅ Set development standards

**Not for:**
❌ Step-by-step tutorials (use `/guides`)
❌ User documentation (use `/guides`)
❌ Troubleshooting guides (use `/guides`)
❌ Installation instructions (use `/guides`)

---

For implementation guides and tutorials, see **`/guides`** directory.
