-- =====================================================================
-- ENTITY METADATA: ORGANIZATION_ADMIN
-- Manages multiple administrators per organization with role-based permissions
-- Generated: 2025-01-23
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. ORGANIZATION_ADMIN Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (
    id,
    code,
    name,
    description,
    domain,
    table_name,
    is_active
)
VALUES (
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    'ORGANIZATION_ADMIN',
    'Organization Admin',
    'Manages administrators and their roles for an organization',
    'BUSINESS',
    'organization_admin',
    1
);

-- =========================================
-- 2. ORGANIZATION_ADMIN ATTRIBUTES
-- =========================================
INSERT OR IGNORE INTO entity_attribute (
    id,
    entity_id,
    code,
    name,
    data_type,
    is_required,
    is_label,
    is_unique,
    description,
    display_order
)
VALUES
-- Foreign Keys
(
    '5b6c7d8e-9f0a-1b2c-3d4e-5f6a7b8c9d0e',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    'organization_id',
    'Organization ID',
    'text',
    1,
    0,
    0,
    'Reference to ORGANIZATION entity',
    1
),
(
    '5c6d7e8f-9a0b-1c2d-3e4f-5a6b7c8d9e0f',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    'person_id',
    'Person ID',
    'text',
    1,
    0,
    0,
    'Reference to PERSON who is the admin',
    2
),
(
    '5d6e7f8a-9b0c-1d2e-3f4a-5b6c7d8e9f0a',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    'appointed_by',
    'Appointed By',
    'text',
    0,
    0,
    0,
    'Reference to PERSON who appointed this admin',
    3
),
-- Core Fields
(
    '5e6f7a8b-9c0d-1e2f-3a4b-5c6d7e8f9a0b',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    'role',
    'Role',
    'enum_strings',
    1,
    1,
    0,
    'Admin role: SUPER_ADMIN, ADMIN, or MODERATOR',
    4
),
(
    '5f6a7b8c-9d0e-1f2a-3b4c-5d6e7f8a9b0c',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    'permissions',
    'Permissions',
    'json',
    0,
    0,
    0,
    'Detailed permission configuration in JSON format',
    5
),
(
    '5a7b8c9d-0e1f-2a3b-4c5d-6e7f8a9b0c1d',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    'appointed_at',
    'Appointed At',
    'datetime',
    0,
    0,
    0,
    'Date and time when admin was appointed',
    6
),
-- Status and Metadata
(
    '5b7c8d9e-0f1a-2b3c-4d5e-6f7a8b9c0d1e',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    'is_active',
    'Is Active',
    'boolean',
    0,
    0,
    0,
    'Whether the admin is currently active',
    7
),
(
    '5c7d8e9f-0a1b-2c3d-4e5f-6a7b8c9d0e1f',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    'notes',
    'Notes',
    'text',
    0,
    0,
    0,
    'Optional notes about this admin appointment',
    8
);

-- =========================================
-- 3. UPDATE ENUM VALUES
-- =========================================
UPDATE entity_attribute
SET enum_values = '["SUPER_ADMIN","ADMIN","MODERATOR"]'
WHERE id = '5e6f7a8b-9c0d-1e2f-3a4b-5c6d7e8f9a0b';

-- =========================================
-- 4. ENTITY RELATIONSHIPS
-- =========================================
INSERT OR IGNORE INTO entity_relationship (
    id,
    from_entity_id,
    to_entity_id,
    relation_type,
    relation_name,
    fk_field,
    description
)
VALUES
-- ORGANIZATION_ADMIN belongs to ORGANIZATION
(
    '5a6b7c8d-9e0f-1a2b-3c4d-rel1',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    '1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d',
    'ManyToOne',
    'organization',
    'organization_id',
    'Admin belongs to an organization'
),
-- ORGANIZATION_ADMIN is a PERSON
(
    '5a6b7c8d-9e0f-1a2b-3c4d-rel2',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    '2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3',
    'ManyToOne',
    'person',
    'person_id',
    'Admin is a person'
),
-- ORGANIZATION_ADMIN appointed by PERSON
(
    '5a6b7c8d-9e0f-1a2b-3c4d-rel3',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    '2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3',
    'ManyToOne',
    'appointed_by_person',
    'appointed_by',
    'Admin was appointed by another person'
),
-- ORGANIZATION has many ORGANIZATION_ADMINs
(
    '5a6b7c8d-9e0f-1a2b-3c4d-rel4',
    '1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    'OneToMany',
    'admins',
    'organization_id',
    'Organization has multiple admins'
);

-- =========================================
-- 5. ENTITY FUNCTIONS
-- =========================================
INSERT OR IGNORE INTO entity_function (
    id,
    entity_id,
    function_code,
    function_name,
    function_description,
    parameters,
    return_type,
    is_active
)
VALUES
(
    '5a6b7c8d-func-0001',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    'create',
    'Create Organization Admin',
    'Add a new admin to an organization',
    '[{"name":"data","type":"json"}]',
    'json',
    1
),
(
    '5a6b7c8d-func-0002',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    'read',
    'Read Organization Admin',
    'Get admin details by ID',
    '[{"name":"id","type":"text"}]',
    'json',
    1
),
(
    '5a6b7c8d-func-0003',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    'update',
    'Update Organization Admin',
    'Update admin role or permissions',
    '[{"name":"id","type":"text"},{"name":"data","type":"json"}]',
    'void',
    1
),
(
    '5a6b7c8d-func-0004',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    'delete',
    'Delete Organization Admin',
    'Remove an admin from organization',
    '[{"name":"id","type":"text"}]',
    'void',
    1
),
(
    '5a6b7c8d-func-0005',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    'search',
    'Search Organization Admins',
    'Search admins with filters',
    '[{"name":"filters","type":"json"}]',
    'json',
    1
),
(
    '5a6b7c8d-func-0006',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    'get_by_organization',
    'Get Admins by Organization',
    'Get all admins for a specific organization',
    '[{"name":"organization_id","type":"text"}]',
    'json',
    1
),
(
    '5a6b7c8d-func-0007',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    'check_admin_permission',
    'Check Admin Permission',
    'Check if a person is admin of an organization',
    '[{"name":"organization_id","type":"text"},{"name":"person_id","type":"text"}]',
    'json',
    1
),
(
    '5a6b7c8d-func-0008',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    'transfer_main_admin',
    'Transfer Main Admin',
    'Transfer main admin ownership to another person',
    '[{"name":"organization_id","type":"text"},{"name":"new_main_admin_id","type":"text"}]',
    'boolean',
    1
),
(
    '5a6b7c8d-func-0009',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    'add_admin',
    'Add Admin',
    'Add new admin to organization (main admin only)',
    '[{"name":"organization_id","type":"text"},{"name":"person_id","type":"text"},{"name":"role","type":"text"}]',
    'json',
    1
),
(
    '5a6b7c8d-func-0010',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    'remove_admin',
    'Remove Admin',
    'Remove admin from organization (cannot remove main admin)',
    '[{"name":"id","type":"text"}]',
    'boolean',
    1
);

-- =========================================
-- 6. FUNCTION HANDLERS
-- =========================================
INSERT OR IGNORE INTO entity_function_handler (
    id,
    function_id,
    handler_type,
    handler_reference,
    is_active
)
VALUES
(
    '5a6b7c8d-handler-0001',
    '5a6b7c8d-func-0001',
    'sql',
    'sp_create_organization_admin',
    1
),
(
    '5a6b7c8d-handler-0002',
    '5a6b7c8d-func-0002',
    'sql',
    'sp_read_organization_admin',
    1
),
(
    '5a6b7c8d-handler-0003',
    '5a6b7c8d-func-0003',
    'sql',
    'sp_update_organization_admin',
    1
),
(
    '5a6b7c8d-handler-0004',
    '5a6b7c8d-func-0004',
    'sql',
    'sp_delete_organization_admin',
    1
),
(
    '5a6b7c8d-handler-0005',
    '5a6b7c8d-func-0005',
    'api',
    '/api/organization/admin/search',
    1
),
(
    '5a6b7c8d-handler-0006',
    '5a6b7c8d-func-0006',
    'sql',
    'sp_get_admins_by_organization',
    1
),
(
    '5a6b7c8d-handler-0007',
    '5a6b7c8d-func-0007',
    'api',
    '/api/organization/admin/check-permission',
    1
),
(
    '5a6b7c8d-handler-0008',
    '5a6b7c8d-func-0008',
    'api',
    '/api/organization/admin/transfer-ownership',
    1
),
(
    '5a6b7c8d-handler-0009',
    '5a6b7c8d-func-0009',
    'api',
    '/api/organization/admin/add',
    1
),
(
    '5a6b7c8d-handler-0010',
    '5a6b7c8d-func-0010',
    'api',
    '/api/organization/admin/remove',
    1
);

-- =========================================
-- 7. VALIDATION RULES
-- =========================================
INSERT OR IGNORE INTO entity_validation_rule (
    id,
    entity_id,
    attribute_id,
    rule_name,
    rule_expression,
    error_message,
    severity
)
VALUES
(
    '5a6b7c8d-valid-0001',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    '5b6c7d8e-9f0a-1b2c-3d4e-5f6a7b8c9d0e',
    'organization_id_required',
    'organization_id != ""',
    'Organization ID is required.',
    'error'
),
(
    '5a6b7c8d-valid-0002',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    '5c6d7e8f-9a0b-1c2d-3e4f-5a6b7c8d9e0f',
    'person_id_required',
    'person_id != ""',
    'Person ID is required.',
    'error'
),
(
    '5a6b7c8d-valid-0003',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    '5e6f7a8b-9c0d-1e2f-3a4b-5c6d7e8f9a0b',
    'role_required',
    'role != ""',
    'Admin role is required.',
    'error'
),
(
    '5a6b7c8d-valid-0004',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    '5e6f7a8b-9c0d-1e2f-3a4b-5c6d7e8f9a0b',
    'role_valid',
    'role IN ("SUPER_ADMIN","ADMIN","MODERATOR")',
    'Role must be SUPER_ADMIN, ADMIN, or MODERATOR.',
    'error'
),
(
    '5a6b7c8d-valid-0005',
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    NULL,
    'unique_org_person',
    'is_unique_combination(organization_id, person_id)',
    'This person is already an admin of this organization.',
    'error'
);

-- =========================================
-- End of script
-- =========================================
