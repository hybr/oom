-- =====================================================================
-- Add form_entities column to process_node table
-- Purpose: Allow each task node to specify which related entities should
--          be included in the task form (in addition to the main entity)
-- Generated: 2025-10-25
-- =====================================================================

PRAGMA foreign_keys = ON;

-- Add form_entities column to process_node table
-- This will store a JSON array of entity codes to include in the task form
-- Example: ["PERSON", "ORGANIZATION_VACANCY", "VACANCY_APPLICATION"]
ALTER TABLE process_node ADD COLUMN form_entities TEXT;

-- Add comment explaining the field
-- The form_entities field contains a JSON array of entity codes
-- When a task is opened, the system will:
-- 1. Load the main entity from task_flow_instance.entity_code
-- 2. Load any additional entities specified in this form_entities field
-- 3. Generate forms for all entities using PageGenerator
-- 4. Display forms in sections organized by entity

-- Example usage:
-- UPDATE process_node
-- SET form_entities = '["PERSON", "ORGANIZATION"]'
-- WHERE node_code = 'REVIEW_APPLICATION';

-- This would show forms for:
-- 1. Main entity (e.g., VACANCY_APPLICATION from the flow)
-- 2. Related PERSON entity
-- 3. Related ORGANIZATION entity
