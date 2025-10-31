# Documentation Organization

## Overview

The V4L documentation is organized into two main directories with distinct purposes:

```
oom/
├── rules/      ← Standards, architecture, technical specs
└── guides/     ← Tutorials, how-tos, implementation guides
```

---

## 📏 Rules Directory (`/rules`)

**Purpose:** Standards, conventions, and architecture references

**Audience:** Developers building new features, architects designing systems

**Content Type:**
- System architecture diagrams
- Design patterns and standards
- Technical specifications
- Naming conventions
- Quick reference sheets

### Files in `/rules`

| File | Purpose |
|------|---------|
| `README.md` | Explains the purpose of the rules directory |
| `ENTITY_CREATION_RULES.md` | Complete rules for creating entity metadata |
| `ENTITY_QUICK_REFERENCE.md` | Quick reference cheat sheet for entities |
| `PROCESS_FLOW_SYSTEM.md` | Workflow engine architecture and rules |
| `PROCESS_SYSTEM_QUICK_START.md` | Process system quick reference |

**When to Use:**
- ✅ Designing a new feature or component
- ✅ Understanding system architecture
- ✅ Need to follow naming conventions
- ✅ Creating new entity definitions
- ✅ Designing workflow processes

**Characteristics:**
- Prescriptive (tells you what standards to follow)
- Architecture-focused
- Reference documentation
- Technical specifications
- No step-by-step tutorials

---

## 📚 Guides Directory (`/guides`)

**Purpose:** Step-by-step tutorials and implementation documentation

**Audience:** Users, administrators, developers implementing features

**Content Type:**
- Installation instructions
- How-to guides
- Tutorials with examples
- Usage documentation
- Troubleshooting guides

### Files in `/guides`

| File | Purpose |
|------|---------|
| `README.md` | Overview and guide index |
| `INSTALL.md` | Installation and setup |
| `QUICK_START.md` | Getting started guide |
| `BUILD_SUMMARY.md` | Build process overview |
| `MIGRATION_GUIDE.md` | Database migration system |
| `MIGRATION_UPDATE_SUMMARY.md` | Migration updates |
| `VACANCY_CREATION_PROCESS.md` | Vacancy workflow guide |
| `VACANCY_PROCESS_ENTITIES.md` | Vacancy entities documentation |
| `POSTAL_ADDRESS_UPDATES.md` | Postal address system |
| `GEOCODING_SETUP.md` | Geocoding integration |

**When to Use:**
- ✅ Installing the system
- ✅ Implementing a specific feature
- ✅ Need step-by-step instructions
- ✅ Troubleshooting an issue
- ✅ Understanding how to use a feature

**Characteristics:**
- Instructional (shows you how to do things)
- Implementation-focused
- Step-by-step tutorials
- Code examples
- Troubleshooting sections

---

## Quick Decision Guide

### I need to...

**Understand the system architecture**
→ Read: `/rules/PROCESS_FLOW_SYSTEM.md` or `/rules/ENTITY_CREATION_RULES.md`

**Install the system**
→ Read: `/guides/INSTALL.md` or `/guides/QUICK_START.md`

**Create a new entity**
→ Read: `/rules/ENTITY_CREATION_RULES.md` (for standards), then implement

**Implement vacancy creation workflow**
→ Read: `/guides/VACANCY_CREATION_PROCESS.md` (for how-to)

**Understand vacancy workflow architecture**
→ Read: `/rules/PROCESS_FLOW_SYSTEM.md` (for architecture)

**Run database migrations**
→ Read: `/guides/MIGRATION_GUIDE.md` (for instructions)

**Understand entity naming conventions**
→ Read: `/rules/ENTITY_QUICK_REFERENCE.md` (for standards)

**Troubleshoot a process flow issue**
→ Read: `/guides/VACANCY_CREATION_PROCESS.md` (for troubleshooting)

---

## Cross-References

Both directories now cross-reference each other:

### In `/rules` Files:
```markdown
> **📚 Note:** This is a rules reference. For implementation guides,
> see the `/guides` folder.
```

### In `/guides` Files:
```markdown
> **📏 Note:** This is an implementation guide. For system architecture
> and standards, see the `/rules` folder.
```

---

## File Organization Changes Made

### 1. Updated `/rules` Files

Added headers to all rules files:
- `ENTITY_CREATION_RULES.md`
- `ENTITY_QUICK_REFERENCE.md`
- `PROCESS_FLOW_SYSTEM.md`
- `PROCESS_SYSTEM_QUICK_START.md`

Each now includes a note directing readers to `/guides` for implementation details.

### 2. Created `/rules/README.md`

New comprehensive README explaining:
- Purpose of rules directory
- Difference between rules and guides
- When to use each
- Directory structure
- Quick navigation guide

### 3. Updated `/guides/README.md`

Enhanced with:
- Note about `/rules` directory
- Complete guide index
- Cross-reference to rules
- Clear organization by category

### 4. Moved Files to `/guides`

Files moved from root to `/guides`:
- `MIGRATION_UPDATE_SUMMARY.md` → `/guides/MIGRATION_UPDATE_SUMMARY.md`

Previously moved files (already in place):
- `MIGRATION_GUIDE.md`
- `VACANCY_CREATION_PROCESS.md`
- `VACANCY_PROCESS_ENTITIES.md`

---

## Directory Structure

```
oom/
├── rules/
│   ├── README.md                       ← Rules directory overview
│   ├── ENTITY_CREATION_RULES.md        ← Entity standards (UPDATED)
│   ├── ENTITY_QUICK_REFERENCE.md       ← Entity cheat sheet (UPDATED)
│   ├── PROCESS_FLOW_SYSTEM.md          ← Process architecture (UPDATED)
│   └── PROCESS_SYSTEM_QUICK_START.md   ← Process reference (UPDATED)
│
├── guides/
│   ├── README.md                       ← Guide index (UPDATED)
│   ├── INSTALL.md
│   ├── QUICK_START.md
│   ├── BUILD_SUMMARY.md
│   ├── MIGRATION_GUIDE.md
│   ├── MIGRATION_UPDATE_SUMMARY.md     ← MOVED HERE
│   ├── VACANCY_CREATION_PROCESS.md
│   ├── VACANCY_PROCESS_ENTITIES.md
│   ├── POSTAL_ADDRESS_UPDATES.md
│   └── GEOCODING_SETUP.md
│
└── DOCUMENTATION_ORGANIZATION.md       ← THIS FILE
```

---

## Best Practices

### Creating New Documentation

**Creating a Rules File:**
1. Add to `/rules` directory
2. Include architecture diagrams
3. Define standards and conventions
4. Add quick reference section
5. Include note pointing to `/guides` for implementation

**Creating a Guide File:**
1. Add to `/guides` directory
2. Include step-by-step instructions
3. Add code examples
4. Include troubleshooting section
5. Include note pointing to `/rules` for architecture

### Updating Documentation

**When to Update Rules:**
- System architecture changes
- New standards adopted
- Design patterns updated
- Naming conventions changed

**When to Update Guides:**
- New features implemented
- Installation steps changed
- API endpoints modified
- New troubleshooting solutions

---

## Examples

### Example 1: Entity Creation

**Scenario:** You need to create a new entity for job applications

**Path:**
1. **Read Rules:** `/rules/ENTITY_CREATION_RULES.md`
   - Learn naming conventions
   - Understand required fields
   - See architecture patterns

2. **Implement:** Create `011-job_applications.sql`
   - Follow standards from rules
   - Use templates provided

3. **Run Migration:** `/guides/MIGRATION_GUIDE.md`
   - Follow step-by-step instructions
   - Verify installation

### Example 2: Process Workflow

**Scenario:** You need to create a new approval workflow

**Path:**
1. **Read Rules:** `/rules/PROCESS_FLOW_SYSTEM.md`
   - Understand process architecture
   - Learn node types and edges
   - See condition logic rules

2. **Reference Example:** `/guides/VACANCY_CREATION_PROCESS.md`
   - See complete working example
   - Understand implementation

3. **Implement:** Create `my_approval_process.sql`
   - Follow architecture from rules
   - Use patterns from example guide

4. **Install:** `/guides/MIGRATION_GUIDE.md`
   - Run migration
   - Verify process installed

---

## Migration Path

### For Existing Documentation Readers

**If you were reading files in root directory:**
- Most guides are now in `/guides`
- Architecture docs are in `/rules`
- Check the README in each directory

**If you were looking for system specs:**
- Check `/rules` directory first
- Architecture, standards, conventions are there

**If you were looking for how-tos:**
- Check `/guides` directory first
- Tutorials, installation, usage guides are there

---

## Summary

### Key Changes

✅ **Created `/rules` directory** - Standards and architecture references
✅ **Enhanced `/guides` directory** - Implementation guides and tutorials
✅ **Added cross-references** - Each directory points to the other
✅ **Created READMEs** - Clear navigation and purpose
✅ **Moved files** - Documentation now properly organized
✅ **Updated headers** - All files indicate their purpose

### Benefits

✅ **Clear Separation** - Know where to find what you need
✅ **Better Navigation** - READMEs guide you to the right place
✅ **Cross-Referenced** - Easy to move between rules and guides
✅ **Scalable** - Easy to add new documentation
✅ **Maintainable** - Clear organization reduces confusion

---

## Quick Reference Card

```
┌─────────────────────────────────────────────┐
│  NEED...                →  LOOK IN...       │
├─────────────────────────────────────────────┤
│  System Architecture    →  /rules           │
│  Design Standards       →  /rules           │
│  Technical Specs        →  /rules           │
│  Quick Reference        →  /rules           │
├─────────────────────────────────────────────┤
│  Installation Guide     →  /guides          │
│  How-To Tutorial        →  /guides          │
│  Usage Examples         →  /guides          │
│  Troubleshooting        →  /guides          │
│  Step-by-Step           →  /guides          │
└─────────────────────────────────────────────┘
```

---

**Documentation is now organized and cross-referenced!** 🎉
