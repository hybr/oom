# Process Flow Architecture

This directory contains architecture documentation for the process flow system and specific process definitions.

## Contents

### Core System Documentation
- **`PROCESS_FLOW_SYSTEM.md`** - Complete architecture and rules for workflow engine
- **`PROCESS_SYSTEM_QUICK_START.md`** - Quick reference for process system

### Process Definitions
- **`RECRUITMENT_PROCESS.md`** - Complete end-to-end recruitment & hiring workflow

## Purpose

This directory defines:
- Process node types and structures
- Workflow routing and conditions
- Task assignment patterns
- Process state management
- Specific business process architectures

## When to Use

Consult these documents when:
- Designing new workflow processes
- Understanding process architecture
- Implementing task routing
- Configuring position-based assignments
- Planning recruitment workflows

## Process Architecture Documents

### RECRUITMENT_PROCESS.md
**Scope:** Application Receipt → Employment Contract
**Complexity:** High (Multi-entity, Multi-stage)
**Entities:** VACANCY_APPLICATION, APPLICATION_REVIEW, APPLICATION_INTERVIEW, JOB_OFFER, EMPLOYMENT_CONTRACT
**Features:**
- Automated screening workflows
- Multi-stage interview management
- Conditional routing (technical assessment, budget approvals)
- Offer management with wait states
- Contract generation and e-signature
- Workstation assignment
- Onboarding preparation

## Implementation

For step-by-step implementation guides, see:
- `/guides/development/PROCESS_SETUP_GUIDE.md` - Process system setup
- `/guides/features/VACANCY_CREATION_PROCESS.md` - Vacancy creation workflow example

---

> **📚 Note:** For implementation guides, see the `/guides` folder.
