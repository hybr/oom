-- =====================================================================
-- VACANCY CREATION PROCESS - Complete Workflow Definition
-- =====================================================================
-- This script defines a comprehensive job vacancy creation process
-- using the Process Flow System.
--
-- PROCESS FLOW:
-- START → Draft Vacancy → HR Review → Budget Check (DECISION)
--   ├─ High Budget → Finance Approval → Department Head Approval → Publish Vacancy → END
--   └─ Normal Budget → Department Head Approval → Publish Vacancy → END
--
-- Generated: 2025-10-23
-- =====================================================================

-- NOTE: This process definition requires positions and permissions to be created first.
-- Run database/setup_vacancy_process.sql after migration to configure the process.
--
-- The following warnings during migration are EXPECTED and can be ignored:
-- - FOREIGN KEY constraint failed (positions/permissions don't exist yet)
-- - Warnings will disappear after running setup script

PRAGMA foreign_keys = OFF;  -- Temporarily disable to allow process installation without positions

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
    'Job Vacancy Creation Process',
    'Multi-step approval workflow for creating and publishing job vacancies with budget-based conditional routing',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',  -- ORGANIZATION_VACANCY entity
    1,
    1,
    1,
    'HIRING',
    '00000000-0000-4000-8000-000000000001'  -- System admin (replace with actual user ID)
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
    100
);

-- Node 2: Draft Vacancy (TASK)
-- Assigned to: HR Manager with REQUEST permission
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
    form_template,
    instructions,
    notify_on_assignment,
    notify_on_due,
    escalate_after_hours,
    escalate_to_position_id
)
VALUES (
    'VC000002-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'DRAFT_VACANCY',
    'Draft Vacancy Details',
    'TASK',
    'Create initial vacancy draft with job description, requirements, and salary range',
    'POS00001-0000-4000-8000-000000000001',  -- HR Manager position ID (configured by setup script)
    'PERM0001-0000-4000-8000-000000000001',  -- REQUEST permission type ID (configured by setup script)
    48,    -- 48 hours SLA
    4,     -- Estimated 4 hours
    250,
    100,
    NULL,  -- form_template (use default form)
    'Fill in all vacancy details including:
- Position title and description
- Key responsibilities
- Required qualifications and skills
- Salary range (min/max)
- Number of openings
- Opening and closing dates
- Employment type
- Mark as urgent if needed',
    1,
    1,
    72,    -- Escalate after 72 hours
    'POS00001-0000-4000-8000-000000000001'   -- HR Manager position ID for escalation (configured by setup script)
);

-- Node 3: HR Review (TASK)
-- Assigned to: HR Manager with APPROVER permission
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
    form_template,
    instructions,
    notify_on_assignment,
    notify_on_due
)
VALUES (
    'VC000003-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'HR_REVIEW',
    'HR Review',
    'TASK',
    'HR team reviews vacancy details for compliance and completeness',
    'POS00001-0000-4000-8000-000000000001',  -- HR Manager position ID (configured by setup script)
    'PERM0002-0000-4000-8000-000000000001',  -- APPROVER permission type ID (configured by setup script)
    24,    -- 24 hours SLA
    2,     -- Estimated 2 hours
    400,
    100,
    NULL,  -- form_template (use default form)
    'Review the vacancy details:
- Verify job description clarity
- Check salary range is within market standards
- Ensure compliance with labor laws
- Validate required qualifications are reasonable
- Confirm employment type is correct
- Check for discriminatory language
Action: APPROVE to proceed or REJECT to send back for revision',
    1,
    1
);

-- Node 4: Budget Check (DECISION)
-- Conditional routing based on salary
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
    'Budget Check Decision',
    'DECISION',
    'Route based on maximum salary threshold (>$100,000 requires finance approval)',
    550,
    100
);

-- Node 5: Finance Approval (TASK)
-- Assigned to: Finance Manager with APPROVER permission
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
    form_template,
    instructions,
    notify_on_assignment,
    notify_on_due
)
VALUES (
    'VC000005-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'FINANCE_APPROVAL',
    'Finance Approval',
    'TASK',
    'Finance team approves budget allocation for high-value position',
    'POS00002-0000-4000-8000-000000000001',  -- Finance Manager position ID (configured by setup script)
    'PERM0002-0000-4000-8000-000000000001',  -- APPROVER permission type ID (configured by setup script)
    48,    -- 48 hours SLA
    3,     -- Estimated 3 hours
    700,
    50,
    NULL,  -- form_template (use default form)
    'Review budget allocation for this position:
- Verify budget availability
- Check salary range against budget constraints
- Confirm headcount allocation
- Assess long-term financial impact
- Review cost center assignment
Action: APPROVE to proceed or REJECT to send back for revision',
    1,
    1
);

-- Node 6: Department Head Approval (TASK)
-- Assigned to: Department Head with APPROVER permission
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
    form_template,
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
    'Department head gives final approval for the vacancy',
    'POS00003-0000-4000-8000-000000000001',  -- Department Head position ID (configured by setup script)
    'PERM0002-0000-4000-8000-000000000001',  -- APPROVER permission type ID (configured by setup script)
    48,    -- 48 hours SLA
    2,     -- Estimated 2 hours
    850,
    100,
    NULL,  -- form_template (use default form)
    'Final review and approval of the vacancy:
- Confirm department need for this position
- Verify job description matches department requirements
- Approve salary range
- Confirm reporting structure
- Validate workstation/location assignments
Action: APPROVE to publish or REJECT to revise',
    1,
    1
);

-- Node 7: Publish Vacancy (TASK)
-- Assigned to: HR Coordinator with IMPLEMENTOR permission
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
    form_template,
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
    'HR publishes the vacancy to job boards and internal systems',
    'POS00004-0000-4000-8000-000000000001',  -- HR Coordinator position ID (configured by setup script)
    'PERM0003-0000-4000-8000-000000000001',  -- IMPLEMENTOR permission type ID (configured by setup script)
    24,    -- 24 hours SLA
    2,     -- Estimated 2 hours
    1000,
    100,
    NULL,  -- form_template (use default form)
    'Publish the approved vacancy:
- Update vacancy status to "Open"
- Post to internal job board
- Post to external job boards (if applicable)
- Share on company social media
- Notify relevant departments
- Set up application tracking
- Configure automated email responses
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
    'Vacancy successfully created and published',
    1150,
    100
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
    'Submit draft to HR for review'
);

-- Edge 3: HR Review → Budget Check (when APPROVED)
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
    'VCE00003-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'VC000003-0000-4000-8000-000000000001',  -- HR_REVIEW
    'VC000004-0000-4000-8000-000000000001',  -- BUDGET_CHECK
    'HR Approved',
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
    'High Budget (>$100k)',
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
    'Normal Budget (≤$100k)',
    2,
    1,  -- Default path
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
    description
)
VALUES (
    'VCE00007-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'VC000005-0000-4000-8000-000000000001',  -- FINANCE_APPROVAL
    'VC000006-0000-4000-8000-000000000001',  -- DEPT_HEAD_APPROVAL
    'Finance Approved',
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
    description
)
VALUES (
    'VCE00009-0000-4000-8000-000000000001',
    'VC000000-0000-4000-8000-000000000001',
    'VC000006-0000-4000-8000-000000000001',  -- DEPT_HEAD_APPROVAL
    'VC000007-0000-4000-8000-000000000001',  -- PUBLISH_VACANCY
    'Approved',
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
-- (Checks task completion action from HR_REVIEW task)
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

-- Success message
SELECT 'Vacancy Creation Process has been successfully created!' as message,
       'VC000000-0000-4000-8000-000000000001' as graph_id,
       'VACANCY_CREATION' as process_code,
       8 as total_nodes,
       11 as total_edges,
       7 as total_conditions;
