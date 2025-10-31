-- =====================================================================
-- VACANCY CREATION & APPROVAL PROCESS
-- =====================================================================
-- A comprehensive workflow for creating and approving job vacancies
-- with multi-level approvals and budget-based conditional routing.
--
-- PROCESS FLOW:
-- START → Draft Vacancy → HR Review → Budget Check (DECISION)
--   ├─ High Budget (>100000) → Finance Approval → Dept Head Approval → Publish → END
--   └─ Normal Budget (≤100000) → Dept Head Approval → Publish → END
--
-- All approval steps support rejection loops back to Draft Vacancy for revision.
--
-- Created: 2025-10-26
-- =====================================================================

PRAGMA foreign_keys = OFF;  -- Temporarily disable to allow installation without all positions

-- =====================================================================
-- STEP 1: Create Process Graph
-- =====================================================================
INSERT INTO process_graph (
    id,
    code,
    name,
    description,
    entity_id,
    version_number,
    is_active,
    is_published,
    category,
    created_by
)
VALUES (
    'VC000000-0000-4000-8000-000000000001',
    'VACANCY_CREATION',
    'Vacancy Creation & Approval Process',
    'Multi-step approval workflow for creating and publishing job vacancies with budget-based conditional routing and workstation assignment',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',  -- ORGANIZATION_VACANCY entity
    1,
    1,
    1,
    'HIRING',
    '00000000-0000-4000-8000-000000000001'  -- System/Admin user (replace if needed)
);

-- =====================================================================
-- STEP 2: Create Process Nodes
-- =====================================================================

-- Node 1: START
INSERT INTO process_node (
    id,
    graph_id,
    node_code,
    node_name,
    node_type,
    description,
    display_x,
    display_y
)
VALUES (
    'VC000001-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'START',
    'Start Vacancy Creation',
    'START',
    'Entry point for vacancy creation process',
    100,
    200
);

-- Node 2: Draft Vacancy (TASK)
-- Can be assigned to HR Manager OR Department Head (both have REQUEST permission)
INSERT INTO process_node (
    id,
    graph_id,
    node_code,
    node_name,
    node_type,
    description,
    position_id,
    permission_type_id,
    sla_hours,
    estimated_duration_hours,
    display_x,
    display_y,
    instructions,
    notify_on_assignment,
    notify_on_due
)
VALUES (
    'VC000002-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'DRAFT_VACANCY',
    'Draft Vacancy Details',
    'TASK',
    'Create or revise vacancy details including position, salary, and job description',
    NULL,  -- Will be resolved via fallback or can be set to HR Manager position ID
    NULL,  -- Will be resolved to REQUEST permission type ID
    48,    -- 48 hours SLA
    4,     -- Estimated 4 hours
    250,
    200,
    'Create the vacancy draft with complete details:

REQUIRED FIELDS:
• Organization (organization_id) - The organization posting this vacancy
• Position (popular_position_id) - This determines department/team/designation
• Created By (created_by) - Person creating this vacancy
• Vacancy Title (title) - Job title for this position
• Number of Openings (number_of_openings) - How many positions are available
• Opening Date (opening_date) - When the vacancy opens for applications
• Status - Will be set to DRAFT automatically

OPTIONAL FIELDS:
• Description (description) - Detailed job description
• Requirements (requirements) - Required qualifications and skills
• Responsibilities (responsibilities) - Key responsibilities of the position
• Closing Date (closing_date) - When applications will close
• Minimum Salary (min_salary) - Minimum salary range
• Maximum Salary (max_salary) - Maximum salary range
• Employment Type (employment_type) - Full-time, Part-time, Contract, etc.
• Is Urgent (is_urgent) - Mark as high priority if needed

The vacancy status will be DRAFT until approved.',
    1,
    1
);

-- Node 3: HR Review (TASK)
INSERT INTO process_node (
    id,
    graph_id,
    node_code,
    node_name,
    node_type,
    description,
    position_id,
    permission_type_id,
    sla_hours,
    estimated_duration_hours,
    display_x,
    display_y,
    instructions,
    notify_on_assignment,
    notify_on_due
)
VALUES (
    'VC000003-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'HR_REVIEW',
    'HR Compliance Review',
    'TASK',
    'HR team reviews vacancy for compliance, market alignment, and policy adherence',
    NULL,  -- Set to HR Manager position ID
    NULL,  -- Set to APPROVER permission type ID
    24,    -- 24 hours SLA
    2,     -- Estimated 2 hours
    400,
    200,
    'Review the vacancy draft for:
• Compliance with labor laws and company policies
• Job description clarity and non-discriminatory language
• Salary range alignment with market standards and internal equity
• Required qualifications are reasonable and job-related
• Employment type is appropriate for the role

Action:
• APPROVE - Proceed to next approval step
• REJECT - Send back for revision (provide specific feedback in comments)',
    1,
    1
);

-- Node 4: Budget Check (DECISION)
INSERT INTO process_node (
    id,
    graph_id,
    node_code,
    node_name,
    node_type,
    description,
    display_x,
    display_y
)
VALUES (
    'VC000004-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'BUDGET_CHECK',
    'Budget Threshold Decision',
    'DECISION',
    'Routes to Finance Approval if max_salary > 100000, otherwise skips to Department Head',
    550,
    200
);

-- Node 5: Finance Approval (TASK)
INSERT INTO process_node (
    id,
    graph_id,
    node_code,
    node_name,
    node_type,
    description,
    position_id,
    permission_type_id,
    sla_hours,
    estimated_duration_hours,
    display_x,
    display_y,
    instructions,
    notify_on_assignment,
    notify_on_due
)
VALUES (
    'VC000005-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'FINANCE_APPROVAL',
    'Finance Budget Approval',
    'TASK',
    'Finance reviews and approves budget allocation for high-value positions',
    NULL,  -- Set to Finance Manager position ID
    NULL,  -- Set to APPROVER permission type ID
    48,    -- 48 hours SLA
    3,     -- Estimated 3 hours
    700,
    100,
    'Review budget allocation for this high-value position (max salary > 100000):
• Verify budget availability in the appropriate cost center
• Confirm salary range is within budget constraints
• Validate headcount allocation
• Assess long-term financial impact
• Review fiscal year planning

Action:
• APPROVE - Proceed to Department Head approval
• REJECT - Send back for budget revision (explain budget constraints in comments)',
    1,
    1
);

-- Node 6: Department Head Approval (TASK)
-- This task includes workstation assignment
INSERT INTO process_node (
    id,
    graph_id,
    node_code,
    node_name,
    node_type,
    description,
    position_id,
    permission_type_id,
    sla_hours,
    estimated_duration_hours,
    display_x,
    display_y,
    form_entities,
    instructions,
    notify_on_assignment,
    notify_on_due
)
VALUES (
    'VC000006-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'DEPT_HEAD_APPROVAL',
    'Department Head Approval',
    'TASK',
    'Department head gives final approval and assigns workstation for the new hire',
    NULL,  -- Set to Department Head position ID
    NULL,  -- Set to APPROVER permission type ID
    48,    -- 48 hours SLA
    2,     -- Estimated 2 hours
    850,
    200,
    '["ORGANIZATION_VACANCY_WORKSTATION"]',  -- Show workstation assignment form
    'Final approval and workstation assignment:
• Confirm department need for this position
• Verify job description matches department requirements
• Approve salary range
• Confirm reporting structure
• ASSIGN WORKSTATION: Select workstation where new hire will be seated

Action:
• APPROVE - Ready to publish vacancy
• REJECT - Send back for changes (explain required changes in comments)',
    1,
    1
);

-- Node 7: Publish Vacancy (TASK)
INSERT INTO process_node (
    id,
    graph_id,
    node_code,
    node_name,
    node_type,
    description,
    position_id,
    permission_type_id,
    sla_hours,
    estimated_duration_hours,
    display_x,
    display_y,
    instructions,
    notify_on_assignment,
    notify_on_due
)
VALUES (
    'VC000007-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'PUBLISH_VACANCY',
    'Publish Vacancy',
    'TASK',
    'HR publishes the approved vacancy to make it active and visible to applicants',
    NULL,  -- Set to HR Coordinator position ID
    NULL,  -- Set to IMPLEMENTOR permission type ID
    24,    -- 24 hours SLA
    2,     -- Estimated 2 hours
    1000,
    200,
    'Publish the approved vacancy:
• Update vacancy status to OPEN or APPROVED
• Verify all required fields are complete
• Post to internal job board
• Post to external job boards (if applicable)
• Share on company social media channels
• Set up application tracking
• Configure automated email responses for applicants

Action: COMPLETE when published',
    1,
    1
);

-- Node 8: END
INSERT INTO process_node (
    id,
    graph_id,
    node_code,
    node_name,
    node_type,
    description,
    display_x,
    display_y
)
VALUES (
    'VC000008-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'END',
    'Vacancy Creation Complete',
    'END',
    'Vacancy successfully created, approved, and published',
    1150,
    200
);

-- =====================================================================
-- STEP 3: Create Process Edges (Transitions)
-- =====================================================================

-- Edge 1: START → Draft Vacancy
INSERT INTO process_edge (
    id,
    graph_id,
    from_node_id,
    to_node_id,
    edge_label,
    edge_order,
    description
)
VALUES (
    'VCE00001-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'VC000001-0000-4000-8000-000000000001',  -- START
    'VC000002-0000-4000-8000-000000000001',  -- DRAFT_VACANCY
    'Begin',
    1,
    'Start the vacancy creation process'
);

-- Edge 2: Draft Vacancy → HR Review
INSERT INTO process_edge (
    id,
    graph_id,
    from_node_id,
    to_node_id,
    edge_label,
    edge_order,
    description
)
VALUES (
    'VCE00002-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'VC000002-0000-4000-8000-000000000001',  -- DRAFT_VACANCY
    'VC000003-0000-4000-8000-000000000001',  -- HR_REVIEW
    'Submit for Review',
    1,
    'Submit draft to HR for compliance review'
);

-- Edge 3: HR Review → Budget Check (when APPROVED)
INSERT INTO process_edge (
    id,
    graph_id,
    from_node_id,
    to_node_id,
    edge_label,
    edge_order,
    is_default,
    description
)
VALUES (
    'VCE00003-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'VC000003-0000-4000-8000-000000000001',  -- HR_REVIEW
    'VC000004-0000-4000-8000-000000000001',  -- BUDGET_CHECK
    'HR Approved',
    1,
    1,
    'HR approved, proceed to budget check'
);

-- Edge 4: HR Review → Draft Vacancy (when REJECTED - loop back)
INSERT INTO process_edge (
    id,
    graph_id,
    from_node_id,
    to_node_id,
    edge_label,
    edge_order,
    description
)
VALUES (
    'VCE00004-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'VC000003-0000-4000-8000-000000000001',  -- HR_REVIEW
    'VC000002-0000-4000-8000-000000000001',  -- DRAFT_VACANCY (loop back)
    'Needs Revision',
    2,
    'HR rejected, send back for revision'
);

-- Edge 5: Budget Check → Finance Approval (HIGH BUDGET PATH - max_salary > 100000)
INSERT INTO process_edge (
    id,
    graph_id,
    from_node_id,
    to_node_id,
    edge_label,
    edge_order,
    description
)
VALUES (
    'VCE00005-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'VC000004-0000-4000-8000-000000000001',  -- BUDGET_CHECK
    'VC000005-0000-4000-8000-000000000001',  -- FINANCE_APPROVAL
    'High Budget (>100k)',
    1,
    'High budget position requires finance approval'
);

-- Edge 6: Budget Check → Department Head Approval (NORMAL BUDGET PATH - max_salary <= 100000)
INSERT INTO process_edge (
    id,
    graph_id,
    from_node_id,
    to_node_id,
    edge_label,
    edge_order,
    is_default,
    description
)
VALUES (
    'VCE00006-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'VC000004-0000-4000-8000-000000000001',  -- BUDGET_CHECK
    'VC000006-0000-4000-8000-000000000001',  -- DEPT_HEAD_APPROVAL
    'Normal Budget (≤100k)',
    2,
    1,
    'Normal budget, skip finance approval'
);

-- Edge 7: Finance Approval → Department Head Approval (when APPROVED)
INSERT INTO process_edge (
    id,
    graph_id,
    from_node_id,
    to_node_id,
    edge_label,
    edge_order,
    is_default,
    description
)
VALUES (
    'VCE00007-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'VC000005-0000-4000-8000-000000000001',  -- FINANCE_APPROVAL
    'VC000006-0000-4000-8000-000000000001',  -- DEPT_HEAD_APPROVAL
    'Finance Approved',
    1,
    1,
    'Finance approved, proceed to department head'
);

-- Edge 8: Finance Approval → Draft Vacancy (when REJECTED - loop back)
INSERT INTO process_edge (
    id,
    graph_id,
    from_node_id,
    to_node_id,
    edge_label,
    edge_order,
    description
)
VALUES (
    'VCE00008-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'VC000005-0000-4000-8000-000000000001',  -- FINANCE_APPROVAL
    'VC000002-0000-4000-8000-000000000001',  -- DRAFT_VACANCY (loop back)
    'Budget Rejected',
    2,
    'Finance rejected, send back for budget revision'
);

-- Edge 9: Department Head Approval → Publish Vacancy (when APPROVED)
INSERT INTO process_edge (
    id,
    graph_id,
    from_node_id,
    to_node_id,
    edge_label,
    edge_order,
    is_default,
    description
)
VALUES (
    'VCE00009-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'VC000006-0000-4000-8000-000000000001',  -- DEPT_HEAD_APPROVAL
    'VC000007-0000-4000-8000-000000000001',  -- PUBLISH_VACANCY
    'Approved',
    1,
    1,
    'Department head approved, ready to publish'
);

-- Edge 10: Department Head Approval → Draft Vacancy (when REJECTED - loop back)
INSERT INTO process_edge (
    id,
    graph_id,
    from_node_id,
    to_node_id,
    edge_label,
    edge_order,
    description
)
VALUES (
    'VCE00010-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'VC000006-0000-4000-8000-000000000001',  -- DEPT_HEAD_APPROVAL
    'VC000002-0000-4000-8000-000000000001',  -- DRAFT_VACANCY (loop back)
    'Needs Changes',
    2,
    'Department head rejected, send back for changes'
);

-- Edge 11: Publish Vacancy → END
INSERT INTO process_edge (
    id,
    graph_id,
    from_node_id,
    to_node_id,
    edge_label,
    edge_order,
    description
)
VALUES (
    'VCE00011-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'VC000007-0000-4000-8000-000000000001',  -- PUBLISH_VACANCY
    'VC000008-0000-4000-8000-000000000001',  -- END
    'Published',
    1,
    'Vacancy published successfully'
);

-- =====================================================================
-- STEP 4: Create Edge Conditions
-- =====================================================================

-- Condition 1: High Budget Path (max_salary > 100000)
INSERT INTO process_edge_condition (
    id,
    edge_id,
    condition_order,
    field_source,
    field_name,
    operator,
    value_type,
    compare_value,
    logic_operator,
    condition_group
)
VALUES (
    'VCC00001-0000-4000-8000-000000000001',
    'VCE00005-0000-4000-8000-000000000001',  -- High budget edge
    1,
    'ENTITY_FIELD',
    'max_salary',
    'GT',
    'NUMBER',
    '100000',
    'AND',
    0
);

-- Condition 2: HR Review APPROVED condition
INSERT INTO process_edge_condition (
    id,
    edge_id,
    condition_order,
    field_source,
    field_name,
    operator,
    value_type,
    compare_value,
    logic_operator,
    condition_group
)
VALUES (
    'VCC00002-0000-4000-8000-000000000001',
    'VCE00003-0000-4000-8000-000000000001',  -- HR approved edge
    1,
    'TASK_VARIABLE',
    'completion_action',
    'EQ',
    'STRING',
    'APPROVE',
    'AND',
    0
);

-- Condition 3: HR Review REJECTED condition
INSERT INTO process_edge_condition (
    id,
    edge_id,
    condition_order,
    field_source,
    field_name,
    operator,
    value_type,
    compare_value,
    logic_operator,
    condition_group
)
VALUES (
    'VCC00003-0000-4000-8000-000000000001',
    'VCE00004-0000-4000-8000-000000000001',  -- HR rejected edge
    1,
    'TASK_VARIABLE',
    'completion_action',
    'EQ',
    'STRING',
    'REJECT',
    'AND',
    0
);

-- Condition 4: Finance APPROVED condition
INSERT INTO process_edge_condition (
    id,
    edge_id,
    condition_order,
    field_source,
    field_name,
    operator,
    value_type,
    compare_value,
    logic_operator,
    condition_group
)
VALUES (
    'VCC00004-0000-4000-8000-000000000001',
    'VCE00007-0000-4000-8000-000000000001',  -- Finance approved edge
    1,
    'TASK_VARIABLE',
    'completion_action',
    'EQ',
    'STRING',
    'APPROVE',
    'AND',
    0
);

-- Condition 5: Finance REJECTED condition
INSERT INTO process_edge_condition (
    id,
    edge_id,
    condition_order,
    field_source,
    field_name,
    operator,
    value_type,
    compare_value,
    logic_operator,
    condition_group
)
VALUES (
    'VCC00005-0000-4000-8000-000000000001',
    'VCE00008-0000-4000-8000-000000000001',  -- Finance rejected edge
    1,
    'TASK_VARIABLE',
    'completion_action',
    'EQ',
    'STRING',
    'REJECT',
    'AND',
    0
);

-- Condition 6: Department Head APPROVED condition
INSERT INTO process_edge_condition (
    id,
    edge_id,
    condition_order,
    field_source,
    field_name,
    operator,
    value_type,
    compare_value,
    logic_operator,
    condition_group
)
VALUES (
    'VCC00006-0000-4000-8000-000000000001',
    'VCE00009-0000-4000-8000-000000000001',  -- Dept head approved edge
    1,
    'TASK_VARIABLE',
    'completion_action',
    'EQ',
    'STRING',
    'APPROVE',
    'AND',
    0
);

-- Condition 7: Department Head REJECTED condition
INSERT INTO process_edge_condition (
    id,
    edge_id,
    condition_order,
    field_source,
    field_name,
    operator,
    value_type,
    compare_value,
    logic_operator,
    condition_group
)
VALUES (
    'VCC00007-0000-4000-8000-000000000001',
    'VCE00010-0000-4000-8000-000000000001',  -- Dept head rejected edge
    1,
    'TASK_VARIABLE',
    'completion_action',
    'EQ',
    'STRING',
    'REJECT',
    'AND',
    0
);

-- =====================================================================
-- END OF VACANCY CREATION PROCESS DEFINITION
-- =====================================================================

PRAGMA foreign_keys = ON;  -- Re-enable foreign keys

-- Verification query
SELECT
    'Vacancy Creation Process installed successfully!' as message,
    'VC000000-0000-4000-8000-000000000001' as graph_id,
    'VACANCY_CREATION' as process_code,
    8 as total_nodes,
    11 as total_edges,
    7 as total_conditions;

-- =====================================================================
-- NEXT STEPS
-- =====================================================================
-- 1. Update position_id and permission_type_id in process_node table
--    with actual IDs from your organization
-- 2. Start a process: POST /api/process/start.php with graph_code='VACANCY_CREATION'
-- 3. View tasks: Navigate to /pages/process/my-tasks.php
-- 4. Monitor: Use /api/process/flow-status.php
-- =====================================================================
