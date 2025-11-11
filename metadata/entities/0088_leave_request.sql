-- =====================================================================
-- LEAVE_REQUEST Entity Metadata
-- Employee leave applications and approvals
-- Generated: 2025-11-10
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition: LEAVE_REQUEST
-- =========================================
INSERT OR IGNORE INTO entity_definition (
    id,
    code,
    name,
    description,
    domain,
    table_name,
    is_active
) VALUES (
    'l1e1a1v1-r1q1-4s1t-a111-111111111111',
    'LEAVE_REQUEST',
    'Leave Request',
    'Employee leave applications for all leave types',
    'HR_EMPLOYEE_MANAGEMENT',
    'leave_request',
    1
);

-- =========================================
-- 2. Entity Attributes: LEAVE_REQUEST
-- =========================================
INSERT OR IGNORE INTO entity_attribute (
    id,
    entity_id,
    code,
    name,
    data_type,
    is_required,
    is_unique,
    is_system,
    is_label,
    default_value,
    min_value,
    max_value,
    enum_values,
    validation_regex,
    description,
    display_order
) VALUES
-- System Fields
('l1e1a1v1-0001-0000-0000-000000000001', 'l1e1a1v1-r1q1-4s1t-a111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Unique identifier', 1),
('l1e1a1v1-0002-0000-0000-000000000001', 'l1e1a1v1-r1q1-4s1t-a111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', NULL, NULL, NULL, NULL, 'Record creation timestamp', 2),
('l1e1a1v1-0003-0000-0000-000000000001', 'l1e1a1v1-r1q1-4s1t-a111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Record last update timestamp', 3),
('l1e1a1v1-0004-0000-0000-000000000001', 'l1e1a1v1-r1q1-4s1t-a111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'Soft delete timestamp', 4),
('l1e1a1v1-0005-0000-0000-000000000001', 'l1e1a1v1-r1q1-4s1t-a111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', NULL, NULL, NULL, NULL, 'Optimistic locking version', 5),
('l1e1a1v1-0006-0000-0000-000000000001', 'l1e1a1v1-r1q1-4s1t-a111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who created the record', 6),
('l1e1a1v1-0007-0000-0000-000000000001', 'l1e1a1v1-r1q1-4s1t-a111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, 'User who last updated the record', 7),

-- Foreign Keys
('l1e1a1v1-0008-0000-0000-000000000001', 'l1e1a1v1-r1q1-4s1t-a111-111111111111', 'employee_id', 'Employee ID', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Employee (FK to PERSON)', 8),
('l1e1a1v1-0009-0000-0000-000000000001', 'l1e1a1v1-r1q1-4s1t-a111-111111111111', 'approved_by', 'Approved By', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Approver (FK to PERSON)', 9),

-- Core Fields
('l1e1a1v1-0010-0000-0000-000000000001', 'l1e1a1v1-r1q1-4s1t-a111-111111111111', 'leave_code', 'Leave Code', 'text', 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, 'Unique leave request code', 10),
('l1e1a1v1-0011-0000-0000-000000000001', 'l1e1a1v1-r1q1-4s1t-a111-111111111111', 'leave_type', 'Leave Type', 'enum_strings', 1, 0, 0, 0, NULL, NULL, NULL, '["CASUAL_LEAVE","SICK_LEAVE","EARNED_LEAVE","MATERNITY_LEAVE","PATERNITY_LEAVE","COMP_OFF","UNPAID_LEAVE","BEREAVEMENT_LEAVE","STUDY_LEAVE"]', NULL, 'Type of leave', 11),
('l1e1a1v1-0012-0000-0000-000000000001', 'l1e1a1v1-r1q1-4s1t-a111-111111111111', 'start_date', 'Start Date', 'date', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Leave start date', 12),
('l1e1a1v1-0013-0000-0000-000000000001', 'l1e1a1v1-r1q1-4s1t-a111-111111111111', 'end_date', 'End Date', 'date', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Leave end date', 13),
('l1e1a1v1-0014-0000-0000-000000000001', 'l1e1a1v1-r1q1-4s1t-a111-111111111111', 'number_of_days', 'Number of Days', 'number', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Total leave days (including weekends if applicable)', 14),
('l1e1a1v1-0015-0000-0000-000000000001', 'l1e1a1v1-r1q1-4s1t-a111-111111111111', 'reason', 'Reason', 'text', 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Reason for leave', 15),
('l1e1a1v1-0016-0000-0000-000000000001', 'l1e1a1v1-r1q1-4s1t-a111-111111111111', 'status', 'Status', 'enum_strings', 1, 0, 0, 0, 'PENDING', NULL, NULL, '["PENDING","APPROVED","REJECTED","CANCELLED","WITHDRAWN"]', NULL, 'Request status', 16),
('l1e1a1v1-0017-0000-0000-000000000001', 'l1e1a1v1-r1q1-4s1t-a111-111111111111', 'approval_date', 'Approval Date', 'datetime', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Date when approved/rejected', 17),
('l1e1a1v1-0018-0000-0000-000000000001', 'l1e1a1v1-r1q1-4s1t-a111-111111111111', 'approval_comments', 'Approval Comments', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Approver comments', 18),
('l1e1a1v1-0019-0000-0000-000000000001', 'l1e1a1v1-r1q1-4s1t-a111-111111111111', 'medical_certificate_url', 'Medical Certificate URL', 'text', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 'Uploaded medical certificate for sick leave', 19),
('l1e1a1v1-0020-0000-0000-000000000001', 'l1e1a1v1-r1q1-4s1t-a111-111111111111', 'is_half_day', 'Is Half Day', 'boolean', 1, 0, 0, 0, '0', NULL, NULL, NULL, NULL, 'Whether it is half day leave', 20),
('l1e1a1v1-0021-0000-0000-000000000001', 'l1e1a1v1-r1q1-4s1t-a111-111111111111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', NULL, NULL, NULL, NULL, 'Whether record is active', 21);

-- =========================================
-- 3. Entity Relationships: LEAVE_REQUEST
-- =========================================
INSERT OR IGNORE INTO entity_relationship (
    id,
    from_entity_id,
    to_entity_id,
    relation_type,
    relation_name,
    fk_field,
    description
) VALUES
-- To PERSON (employee)
('l1e1a1v1-rel1-0000-0000-000000000001', 'l1e1a1v1-r1q1-4s1t-a111-111111111111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'leave_request_to_employee', 'employee_id', 'Leave requested by employee'),

-- To PERSON (approver)
('l1e1a1v1-rel2-0000-0000-000000000001', 'l1e1a1v1-r1q1-4s1t-a111-111111111111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'leave_request_to_approver', 'approved_by', 'Leave approved by manager');
