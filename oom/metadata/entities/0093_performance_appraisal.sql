-- =====================================================================
-- PERFORMANCE_APPRAISAL Entity - Annual/periodic performance reviews
-- Generated: 2025-11-10
-- =====================================================================

PRAGMA foreign_keys = ON;

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active) VALUES
('p1e1r1f1-a1p1-4p1r-l111-111111111111', 'PERFORMANCE_APPRAISAL', 'Performance Appraisal', 'Employee performance reviews and ratings', 'HR_EMPLOYEE_MANAGEMENT', 'performance_appraisal', 1);

INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_system, is_label, default_value, description, display_order) VALUES
('p1e1r1f1-0001-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'id', 'ID', 'uuid', 1, 1, 1, 0, NULL, 'Unique identifier', 1),
('p1e1r1f1-0002-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'created_at', 'Created At', 'datetime', 1, 0, 1, 0, 'datetime("now")', 'Creation timestamp', 2),
('p1e1r1f1-0003-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'updated_at', 'Updated At', 'datetime', 0, 0, 1, 0, NULL, 'Update timestamp', 3),
('p1e1r1f1-0004-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'deleted_at', 'Deleted At', 'datetime', 0, 0, 1, 0, NULL, 'Soft delete', 4),
('p1e1r1f1-0005-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'version_no', 'Version Number', 'integer', 1, 0, 1, 0, '1', 'Version', 5),
('p1e1r1f1-0006-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'created_by', 'Created By', 'text', 0, 0, 1, 0, NULL, 'Creator', 6),
('p1e1r1f1-0007-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'updated_by', 'Updated By', 'text', 0, 0, 1, 0, NULL, 'Updater', 7),
('p1e1r1f1-0008-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'employee_id', 'Employee ID', 'text', 1, 0, 0, 0, NULL, 'FK to PERSON', 8),
('p1e1r1f1-0009-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'reviewer_id', 'Reviewer ID', 'text', 1, 0, 0, 0, NULL, 'FK to PERSON (manager)', 9),
('p1e1r1f1-0010-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'appraisal_code', 'Appraisal Code', 'text', 1, 1, 0, 1, NULL, 'Unique appraisal code', 10),
('p1e1r1f1-0011-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'appraisal_period_start', 'Period Start', 'date', 1, 0, 0, 0, NULL, 'Review period start date', 11),
('p1e1r1f1-0012-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'appraisal_period_end', 'Period End', 'date', 1, 0, 0, 0, NULL, 'Review period end date', 12),
('p1e1r1f1-0013-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'self_rating', 'Self Rating', 'number', 0, 0, 0, 0, NULL, 'Employee self-rating (1-5)', 13),
('p1e1r1f1-0014-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'self_comments', 'Self Comments', 'text', 0, 0, 0, 0, NULL, 'Employee self-assessment', 14),
('p1e1r1f1-0015-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'manager_rating', 'Manager Rating', 'number', 0, 0, 0, 0, NULL, 'Manager rating (1-5)', 15),
('p1e1r1f1-0016-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'manager_comments', 'Manager Comments', 'text', 0, 0, 0, 0, NULL, 'Manager feedback', 16),
('p1e1r1f1-0017-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'final_rating', 'Final Rating', 'number', 0, 0, 0, 0, NULL, 'Final normalized rating (1-5)', 17),
('p1e1r1f1-0018-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'increment_percentage', 'Increment Percentage', 'number', 0, 0, 0, 0, NULL, 'Salary increment %', 18),
('p1e1r1f1-0019-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'promotion_recommended', 'Promotion Recommended', 'boolean', 1, 0, 0, 0, '0', 'Recommended for promotion', 19),
('p1e1r1f1-0020-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'goals_next_period', 'Goals for Next Period', 'text', 0, 0, 0, 0, NULL, 'Goals set for next period', 20),
('p1e1r1f1-0021-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'training_recommendations', 'Training Recommendations', 'text', 0, 0, 0, 0, NULL, 'Recommended training', 21),
('p1e1r1f1-0022-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'status', 'Status', 'enum_strings', 1, 0, 0, 0, 'DRAFT', 'Appraisal status', 22),
('p1e1r1f1-0023-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'acknowledged_by_employee', 'Acknowledged by Employee', 'boolean', 1, 0, 0, 0, '0', 'Employee acknowledgment', 23),
('p1e1r1f1-0024-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'acknowledged_date', 'Acknowledged Date', 'datetime', 0, 0, 0, 0, NULL, 'Acknowledgment date', 24),
('p1e1r1f1-0025-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'is_active', 'Is Active', 'boolean', 1, 0, 0, 0, '1', 'Active status', 25);

UPDATE entity_attribute SET enum_values = '["DRAFT","SELF_ASSESSMENT_COMPLETE","MANAGER_REVIEW_COMPLETE","NORMALIZED","FINALIZED","ACKNOWLEDGED"]'
WHERE id = 'p1e1r1f1-0022-0000-0000-000000000001';

INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description) VALUES
('p1e1r1f1-rel1-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'appraisal_to_employee', 'employee_id', 'Employee being appraised'),
('p1e1r1f1-rel2-0000-0000-000000000001', 'p1e1r1f1-a1p1-4p1r-l111-111111111111', 'p1e1r1s1-o1n1-4111-a111-b111c111d111', 'many-to-one', 'appraisal_to_reviewer', 'reviewer_id', 'Manager reviewer');
