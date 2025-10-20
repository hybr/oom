-- =========================================================
-- ENUM_MARKS_TYPE Data
-- =========================================================
-- Common examination marking systems used worldwide

INSERT OR IGNORE INTO enum_marks_type (id, code, name, created_at, updated_at)
VALUES
('mt-00000001-0000-4000-0000-000000000001', 'PERCENTAGE', 'Percentage (0-100%)', datetime('now'), datetime('now')),
('mt-00000002-0000-4000-0000-000000000002', 'GRADE_LETTER', 'Letter Grade (A, B, C, D, F)', datetime('now'), datetime('now')),
('mt-00000003-0000-4000-0000-000000000003', 'GRADE_PLUS_MINUS', 'Letter Grade with +/- (A+, A, A-, B+, etc.)', datetime('now'), datetime('now')),
('mt-00000004-0000-4000-0000-000000000004', 'GPA_4', 'GPA 4.0 Scale', datetime('now'), datetime('now')),
('mt-00000005-0000-4000-0000-000000000005', 'GPA_5', 'GPA 5.0 Scale', datetime('now'), datetime('now')),
('mt-00000006-0000-4000-0000-000000000006', 'GPA_10', 'GPA 10.0 Scale (CGPA)', datetime('now'), datetime('now')),
('mt-00000007-0000-4000-0000-000000000007', 'MARKS_OUT_OF', 'Marks out of Total (e.g., 85/100)', datetime('now'), datetime('now')),
('mt-00000008-0000-4000-0000-000000000008', 'PASS_FAIL', 'Pass/Fail', datetime('now'), datetime('now')),
('mt-00000009-0000-4000-0000-000000000009', 'DISTINCTION', 'Distinction/Merit/Pass', datetime('now'), datetime('now')),
('mt-00000010-0000-4000-0000-000000000010', 'GRADE_1_TO_9', 'Grade 1-9 Scale (UK GCSE)', datetime('now'), datetime('now')),
('mt-00000011-0000-4000-0000-000000000011', 'ECTS', 'ECTS Grades (A, B, C, D, E, F)', datetime('now'), datetime('now')),
('mt-00000012-0000-4000-0000-000000000012', 'PERCENTILE', 'Percentile Rank', datetime('now'), datetime('now'));
