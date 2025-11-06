-- System Admin Person
-- This creates a system admin person to be used as admin_id for organizations during data seeding

INSERT INTO person (id, first_name, last_name, gender, created_at, updated_at)
VALUES ('00000000-0000-4000-8000-000000000001', 'System', 'Administrator', 'OTHER', datetime('now'), datetime('now'));
