-- ============================================
-- ENTITY METADATA: ORGANIZATION INFRASTRUCTURE
-- (ORGANIZATION_BRANCH, ORGANIZATION_BUILDING, WORKSTATION)
-- ============================================
-- =========================================
-- 1. ORGANIZATION_BRANCH Entity Definition
-- =========================================
INSERT
    OR IGNORE INTO entity_definition (
        id,
        code,
        name,
        description,
        domain,
        table_name,
        is_active
    )
VALUES (
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        'ORGANIZATION_BRANCH',
        'Organization Branch',
        'Branches or regional offices of an organization',
        'BUSINESS',
        'organization_branch',
        1
    );
-- ORGANIZATION_BRANCH ATTRIBUTES
INSERT
    OR IGNORE INTO entity_attribute (
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
VALUES -- Foreign Keys
    (
        '2b3c4d5e-6f7a-8b9c-0d1e-2f3a4b5c6d7e',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        'organization_id',
        'Organization ID',
        'text',
        1,
        0,
        0,
        'Reference to ORGANIZATION entity',
        1
    ),
    -- Core Identity Fields
    (
        '2c3d4e5f-6a7b-8c9d-0e1f-2a3b4c5d6e7f',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        'name',
        'Branch Name',
        'text',
        1,
        1,
        0,
        'Name of the branch or office',
        2
    ),
    (
        '2d3e4f5a-6b7c-8d9e-0f1a-2b3c4d5e6f7a',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        'code',
        'Branch Code',
        'text',
        1,
        0,
        1,
        'Unique code for the branch within organization',
        3
    ),
    -- Additional Fields
    (
        '2e3f4a5b-6c7d-8e9f-0a1b-2c3d4e5f6a7b',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        'description',
        'Description',
        'text',
        0,
        0,
        0,
        'Description of the branch',
        4
    ),
    (
        '2f3a4b5c-6d7e-8f9a-0b1c-2d3e4f5a6b7c',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        'branch_type',
        'Branch Type',
        'enum_strings',
        0,
        0,
        0,
        'Type of branch',
        5
    ),
    (
        '2a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        'contact_email',
        'Contact Email',
        'text',
        0,
        0,
        0,
        'Branch contact email',
        6
    ),
    (
        '2b4c5d6e-7f8a-9b0c-1d2e-3f4a5b6c7d8e',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        'contact_phone',
        'Contact Phone',
        'text',
        0,
        0,
        0,
        'Branch contact phone number',
        7
    ),
    (
        '2c4d5e6f-7a8b-9c0d-1e2f-3a4b5c6d7e8f',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        'manager_id',
        'Manager ID',
        'text',
        0,
        0,
        0,
        'Reference to PERSON who manages this branch',
        8
    ),
    -- Status and Metadata
    (
        '2d4e5f6a-7b8c-9d0e-1f2a-3b4c5d6e7f8a',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        'is_active',
        'Is Active',
        'boolean',
        0,
        0,
        0,
        'Whether the branch is currently active',
        9
    ),
    (
        '2e4f5a6b-7c8d-9e0f-1a2b-3c4d5e6f7a8b',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        'established_date',
        'Established Date',
        'date',
        0,
        0,
        0,
        'Date when branch was established',
        10
    );
-- =========================================
-- 2. ORGANIZATION_BUILDING Entity Definition
-- =========================================
INSERT
    OR IGNORE INTO entity_definition (
        id,
        code,
        name,
        description,
        domain,
        table_name,
        is_active
    )
VALUES (
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        'ORGANIZATION_BUILDING',
        'Organization Building',
        'Physical buildings or facilities of an organization branch',
        'BUSINESS',
        'organization_building',
        1
    );
-- ORGANIZATION_BUILDING ATTRIBUTES
INSERT
    OR IGNORE INTO entity_attribute (
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
VALUES -- Foreign Keys
    (
        '3b4c5d6e-7f8a-9b0c-1d2e-3f4a5b6c7d8e',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        'organization_branch_id',
        'Organization Branch ID',
        'text',
        1,
        0,
        0,
        'Reference to ORGANIZATION_BRANCH entity',
        1
    ),
    (
        '3c4d5e6f-7a8b-9c0d-1e2f-3a4b5c6d7e8f',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        'postal_address_id',
        'Postal Address ID',
        'text',
        1,
        0,
        0,
        'Reference to POSTAL_ADDRESS entity',
        2
    ),
    -- Core Identity Fields
    (
        '3d4e5f6a-7b8c-9d0e-1f2a-3b4c5d6e7f8a',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        'name',
        'Building Name',
        'text',
        1,
        1,
        0,
        'Name or designation of the building',
        3
    ),
    -- Additional Fields
    (
        '3e4f5a6b-7c8d-9e0f-1a2b-3c4d5e6f7a8b',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        'building_code',
        'Building Code',
        'text',
        0,
        0,
        0,
        'Internal code for the building',
        4
    ),
    (
        '3f4a5b6c-7d8e-9f0a-1b2c-3d4e5f6a7b8c',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        'building_type',
        'Building Type',
        'enum_strings',
        0,
        0,
        0,
        'Type of building',
        5
    ),
    (
        '3a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        'total_floors',
        'Total Floors',
        'integer',
        0,
        0,
        0,
        'Total number of floors in the building',
        6
    ),
    (
        '3b5c6d7e-8f9a-0b1c-2d3e-4f5a6b7c8d9e',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        'total_area_sqm',
        'Total Area (sqm)',
        'number',
        0,
        0,
        0,
        'Total area of the building in square meters',
        7
    ),
    (
        '3c5d6e7f-8a9b-0c1d-2e3f-4a5b6c7d8e9f',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        'year_built',
        'Year Built',
        'integer',
        0,
        0,
        0,
        'Year the building was constructed',
        8
    ),
    (
        '3d5e6f7a-8b9c-0d1e-2f3a-4b5c6d7e8f9a',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        'is_owned',
        'Is Owned',
        'boolean',
        0,
        0,
        0,
        'Whether the building is owned (true) or leased (false)',
        9
    ),
    (
        '3e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        'facilities',
        'Facilities',
        'text',
        0,
        0,
        0,
        'Available facilities (e.g., parking, cafeteria, gym)',
        10
    );
-- =========================================
-- 3. WORKSTATION Entity Definition
-- =========================================
INSERT
    OR IGNORE INTO entity_definition (
        id,
        code,
        name,
        description,
        domain,
        table_name,
        is_active
    )
VALUES (
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        'WORKSTATION',
        'Workstation',
        'Individual workstations or desks within an organization building',
        'BUSINESS',
        'workstation',
        1
    );
-- WORKSTATION ATTRIBUTES
INSERT
    OR IGNORE INTO entity_attribute (
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
VALUES -- Foreign Keys
    (
        '4b5c6d7e-8f9a-0b1c-2d3e-4f5a6b7c8d9e',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        'organization_building_id',
        'Organization Building ID',
        'text',
        1,
        0,
        0,
        'Reference to ORGANIZATION_BUILDING entity',
        1
    ),
    -- Core Identity Fields
    (
        '4c5d6e7f-8a9b-0c1d-2e3f-4a5b6c7d8e9f',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        'floor',
        'Floor',
        'text',
        1,
        0,
        0,
        'Floor number or identifier',
        2
    ),
    (
        '4d5e6f7a-8b9c-0d1e-2f3a-4b5c6d7e8f9a',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        'room',
        'Room',
        'text',
        0,
        0,
        0,
        'Room number or identifier',
        3
    ),
    (
        '4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        'workstation_number',
        'Workstation Number',
        'text',
        1,
        1,
        0,
        'Unique workstation number or code',
        4
    ),
    -- Additional Fields
    (
        '4f5a6b7c-8d9e-0f1a-2b3c-4d5e6f7a8b9c',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        'workstation_type',
        'Workstation Type',
        'enum_strings',
        0,
        0,
        0,
        'Type of workstation',
        5
    ),
    (
        '4a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        'assigned_person_id',
        'Assigned Person ID',
        'text',
        0,
        0,
        0,
        'Reference to PERSON assigned to this workstation',
        6
    ),
    (
        '4c6d7e8f-9a0b-1c2d-3e4f-5a6b7c8d9e0f',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        'equipment',
        'Equipment',
        'text',
        0,
        0,
        0,
        'Equipment assigned to workstation (e.g., computer, phone)',
        8
    ),
    (
        '4d6e7f8a-9b0c-1d2e-3f4a-5b6c7d8e9f0a',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        'capacity',
        'Capacity',
        'integer',
        0,
        0,
        0,
        'Number of people that can use this workstation',
        9
    ),
    (
        '4e6f7a8b-9c0d-1e2f-3a4b-5c6d7e8f9a0b',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        'is_available',
        'Is Available',
        'boolean',
        0,
        0,
        0,
        'Whether the workstation is currently available',
        10
    ),
    (
        '4f6a7b8c-9d0e-1f2a-3b4c-5d6e7f8a9b0c',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        'notes',
        'Notes',
        'text',
        0,
        0,
        0,
        'Additional notes about the workstation',
        11
    );
-- =========================================
-- 4. ENTITY RELATIONSHIPS
-- =========================================
INSERT
    OR IGNORE INTO entity_relationship (
        id,
        from_entity_id,
        to_entity_id,
        relation_type,
        relation_name,
        fk_field,
        description
    )
VALUES -- ORGANIZATION_BRANCH belongs to ORGANIZATION
    (
        '2a3b4c5d-6e7f-8a9b-0c1d-rel1',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        '1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d',
        'ManyToOne',
        'organization',
        'organization_id',
        'Branch belongs to an organization'
    ),
    -- ORGANIZATION_BRANCH managed by PERSON
    (
        '2a3b4c5d-6e7f-8a9b-0c1d-rel2',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        '2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3',
        'ManyToOne',
        'manager',
        'manager_id',
        'Branch is managed by a person'
    ),
    -- ORGANIZATION_BUILDING belongs to ORGANIZATION_BRANCH
    (
        '3a4b5c6d-7e8f-9a0b-1c2d-rel1',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        'ManyToOne',
        'branch',
        'organization_branch_id',
        'Building belongs to a branch'
    ),
    -- ORGANIZATION_BUILDING has POSTAL_ADDRESS
    (
        '3a4b5c6d-7e8f-9a0b-1c2d-rel2',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        'f34c3a2b-7f94-4fa7-9a33-8d9f0b1c7d2e',
        'ManyToOne',
        'postal_address',
        'postal_address_id',
        'Building has a postal address'
    ),
    -- WORKSTATION belongs to ORGANIZATION_BUILDING
    (
        '4a5b6c7d-8e9f-0a1b-2c3d-rel1',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        'ManyToOne',
        'building',
        'organization_building_id',
        'Workstation belongs to a building'
    ),
    -- WORKSTATION assigned to PERSON
    (
        '4a5b6c7d-8e9f-0a1b-2c3d-rel2',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        '2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3',
        'ManyToOne',
        'assigned_person',
        'assigned_person_id',
        'Workstation can be assigned to a person'
    );
-- =========================================
-- 5. ENTITY FUNCTIONS
-- =========================================
-- ORGANIZATION_BRANCH FUNCTIONS
INSERT
    OR IGNORE INTO entity_function (
        id,
        entity_id,
        function_code,
        function_name,
        function_description,
        parameters,
        return_type,
        is_active
    )
VALUES (
        '2a3b4c5d-func-0001',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        'create',
        'Create Branch',
        'Create a new organization branch',
        '[{"name":"data","type":"json"}]',
        'json',
        1
    ),
    (
        '2a3b4c5d-func-0002',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        'read',
        'Read Branch',
        'Read branch by id',
        '[{"name":"id","type":"text"}]',
        'json',
        1
    ),
    (
        '2a3b4c5d-func-0003',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        'update',
        'Update Branch',
        'Update branch information',
        '[{"name":"id","type":"text"},{"name":"data","type":"json"}]',
        'void',
        1
    ),
    (
        '2a3b4c5d-func-0004',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        'delete',
        'Delete Branch',
        'Delete branch by id',
        '[{"name":"id","type":"text"}]',
        'void',
        1
    ),
    (
        '2a3b4c5d-func-0005',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        'search',
        'Search Branches',
        'Search branches with filters',
        '[{"name":"filters","type":"json"}]',
        'json',
        1
    ),
    (
        '2a3b4c5d-func-0006',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        'get_by_organization',
        'Get Branches by Organization',
        'Get all branches for an organization',
        '[{"name":"organization_id","type":"text"}]',
        'json',
        1
    ),
    (
        '2a3b4c5d-func-0007',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        'check_code_unique',
        'Check Code Unique',
        'Check if branch code is unique within organization',
        '[{"name":"code","type":"text"},{"name":"organization_id","type":"text"}]',
        'boolean',
        1
    ),
    (
        '2a3b4c5d-func-0008',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        'get_branch_manager',
        'Get Branch Manager',
        'Get manager details for branch',
        '[{"name":"branch_id","type":"text"}]',
        'json',
        1
    );
-- ORGANIZATION_BUILDING FUNCTIONS
INSERT
    OR IGNORE INTO entity_function (
        id,
        entity_id,
        function_code,
        function_name,
        function_description,
        parameters,
        return_type,
        is_active
    )
VALUES (
        '3a4b5c6d-func-0001',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        'create',
        'Create Building',
        'Create a new organization building',
        '[{"name":"data","type":"json"}]',
        'json',
        1
    ),
    (
        '3a4b5c6d-func-0002',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        'read',
        'Read Building',
        'Read building by id',
        '[{"name":"id","type":"text"}]',
        'json',
        1
    ),
    (
        '3a4b5c6d-func-0003',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        'update',
        'Update Building',
        'Update building information',
        '[{"name":"id","type":"text"},{"name":"data","type":"json"}]',
        'void',
        1
    ),
    (
        '3a4b5c6d-func-0004',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        'delete',
        'Delete Building',
        'Delete building by id',
        '[{"name":"id","type":"text"}]',
        'void',
        1
    ),
    (
        '3a4b5c6d-func-0005',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        'search',
        'Search Buildings',
        'Search buildings with filters',
        '[{"name":"filters","type":"json"}]',
        'json',
        1
    ),
    (
        '3a4b5c6d-func-0006',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        'get_by_branch',
        'Get Buildings by Branch',
        'Get all buildings for a branch',
        '[{"name":"branch_id","type":"text"}]',
        'json',
        1
    ),
    (
        '3a4b5c6d-func-0007',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        'get_building_address',
        'Get Building Address',
        'Get full postal address for building',
        '[{"name":"building_id","type":"text"}]',
        'json',
        1
    ),
    (
        '3a4b5c6d-func-0008',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        'calculate_total_capacity',
        'Calculate Total Capacity',
        'Calculate total workstation capacity for building',
        '[{"name":"building_id","type":"text"}]',
        'integer',
        1
    );
-- WORKSTATION FUNCTIONS
INSERT
    OR IGNORE INTO entity_function (
        id,
        entity_id,
        function_code,
        function_name,
        function_description,
        parameters,
        return_type,
        is_active
    )
VALUES (
        '4a5b6c7d-func-0001',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        'create',
        'Create Workstation',
        'Create a new workstation',
        '[{"name":"data","type":"json"}]',
        'json',
        1
    ),
    (
        '4a5b6c7d-func-0002',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        'read',
        'Read Workstation',
        'Read workstation by id',
        '[{"name":"id","type":"text"}]',
        'json',
        1
    ),
    (
        '4a5b6c7d-func-0003',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        'update',
        'Update Workstation',
        'Update workstation information',
        '[{"name":"id","type":"text"},{"name":"data","type":"json"}]',
        'void',
        1
    ),
    (
        '4a5b6c7d-func-0004',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        'delete',
        'Delete Workstation',
        'Delete workstation by id',
        '[{"name":"id","type":"text"}]',
        'void',
        1
    ),
    (
        '4a5b6c7d-func-0005',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        'search',
        'Search Workstations',
        'Search workstations with filters',
        '[{"name":"filters","type":"json"}]',
        'json',
        1
    ),
    (
        '4a5b6c7d-func-0006',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        'get_by_building',
        'Get Workstations by Building',
        'Get all workstations for a building',
        '[{"name":"building_id","type":"text"}]',
        'json',
        1
    ),
    (
        '4a5b6c7d-func-0007',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        'get_available',
        'Get Available Workstations',
        'Get all available workstations in a building',
        '[{"name":"building_id","type":"text"}]',
        'json',
        1
    ),
    (
        '4a5b6c7d-func-0008',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        'assign_person',
        'Assign Person',
        'Assign a person to a workstation',
        '[{"name":"workstation_id","type":"text"},{"name":"person_id","type":"text"}]',
        'boolean',
        1
    ),
    (
        '4a5b6c7d-func-0009',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        'unassign_person',
        'Unassign Person',
        'Remove person assignment from workstation',
        '[{"name":"workstation_id","type":"text"}]',
        'boolean',
        1
    ),
    (
        '4a5b6c7d-func-0010',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        'get_by_person',
        'Get Workstation by Person',
        'Get workstation assigned to a person',
        '[{"name":"person_id","type":"text"}]',
        'json',
        1
    );
-- =========================================
-- 6. FUNCTION HANDLERS
-- =========================================
-- ORGANIZATION_BRANCH HANDLERS
INSERT
    OR IGNORE INTO entity_function_handler (
        id,
        function_id,
        handler_type,
        handler_reference,
        is_active
    )
VALUES (
        '2a3b4c5d-handler-0001',
        '2a3b4c5d-func-0001',
        'sql',
        'sp_create_organization_branch',
        1
    ),
    (
        '2a3b4c5d-handler-0002',
        '2a3b4c5d-func-0002',
        'sql',
        'sp_read_organization_branch',
        1
    ),
    (
        '2a3b4c5d-handler-0003',
        '2a3b4c5d-func-0003',
        'sql',
        'sp_update_organization_branch',
        1
    ),
    (
        '2a3b4c5d-handler-0004',
        '2a3b4c5d-func-0004',
        'sql',
        'sp_delete_organization_branch',
        1
    ),
    (
        '2a3b4c5d-handler-0005',
        '2a3b4c5d-func-0005',
        'api',
        '/api/organization/branch/search',
        1
    ),
    (
        '2a3b4c5d-handler-0006',
        '2a3b4c5d-func-0006',
        'sql',
        'sp_get_branches_by_organization',
        1
    ),
    (
        '2a3b4c5d-handler-0007',
        '2a3b4c5d-func-0007',
        'script',
        '/scripts/organization/check_branch_code.php',
        1
    ),
    (
        '2a3b4c5d-handler-0008',
        '2a3b4c5d-func-0008',
        'script',
        '/scripts/organization/get_branch_manager.php',
        1
    );
-- ORGANIZATION_BUILDING HANDLERS
INSERT
    OR IGNORE INTO entity_function_handler (
        id,
        function_id,
        handler_type,
        handler_reference,
        is_active
    )
VALUES (
        '3a4b5c6d-handler-0001',
        '3a4b5c6d-func-0001',
        'sql',
        'sp_create_organization_building',
        1
    ),
    (
        '3a4b5c6d-handler-0002',
        '3a4b5c6d-func-0002',
        'sql',
        'sp_read_organization_building',
        1
    ),
    (
        '3a4b5c6d-handler-0003',
        '3a4b5c6d-func-0003',
        'sql',
        'sp_update_organization_building',
        1
    ),
    (
        '3a4b5c6d-handler-0004',
        '3a4b5c6d-func-0004',
        'sql',
        'sp_delete_organization_building',
        1
    ),
    (
        '3a4b5c6d-handler-0005',
        '3a4b5c6d-func-0005',
        'api',
        '/api/organization/building/search',
        1
    ),
    (
        '3a4b5c6d-handler-0006',
        '3a4b5c6d-func-0006',
        'sql',
        'sp_get_buildings_by_branch',
        1
    ),
    (
        '3a4b5c6d-handler-0007',
        '3a4b5c6d-func-0007',
        'script',
        '/scripts/organization/get_building_address.php',
        1
    ),
    (
        '3a4b5c6d-handler-0008',
        '3a4b5c6d-func-0008',
        'sql',
        'sp_calculate_building_capacity',
        1
    );
-- WORKSTATION HANDLERS
INSERT
    OR IGNORE INTO entity_function_handler (
        id,
        function_id,
        handler_type,
        handler_reference,
        is_active
    )
VALUES (
        '4a5b6c7d-handler-0001',
        '4a5b6c7d-func-0001',
        'sql',
        'sp_create_workstation',
        1
    ),
    (
        '4a5b6c7d-handler-0002',
        '4a5b6c7d-func-0002',
        'sql',
        'sp_read_workstation',
        1
    ),
    (
        '4a5b6c7d-handler-0003',
        '4a5b6c7d-func-0003',
        'sql',
        'sp_update_workstation',
        1
    ),
    (
        '4a5b6c7d-handler-0004',
        '4a5b6c7d-func-0004',
        'sql',
        'sp_delete_workstation',
        1
    ),
    (
        '4a5b6c7d-handler-0005',
        '4a5b6c7d-func-0005',
        'api',
        '/api/workstation/search',
        1
    ),
    (
        '4a5b6c7d-handler-0006',
        '4a5b6c7d-func-0006',
        'sql',
        'sp_get_workstations_by_building',
        1
    ),
    (
        '4a5b6c7d-handler-0007',
        '4a5b6c7d-func-0007',
        'sql',
        'sp_get_available_workstations',
        1
    ),
    (
        '4a5b6c7d-handler-0008',
        '4a5b6c7d-func-0008',
        'script',
        '/scripts/workstation/assign_person.php',
        1
    ),
    (
        '4a5b6c7d-handler-0009',
        '4a5b6c7d-func-0009',
        'script',
        '/scripts/workstation/unassign_person.php',
        1
    ),
    (
        '4a5b6c7d-handler-0010',
        '4a5b6c7d-func-0010',
        'sql',
        'sp_get_workstation_by_person',
        1
    );
-- =========================================
-- 7. VALIDATION RULES
-- =========================================
-- ORGANIZATION_BRANCH VALIDATION RULES
INSERT
    OR IGNORE INTO entity_validation_rule (
        id,
        entity_id,
        attribute_id,
        rule_name,
        rule_expression,
        error_message,
        severity
    )
VALUES (
        '2a3b4c5d-valid-0001',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        '2b3c4d5e-6f7a-8b9c-0d1e-2f3a4b5c6d7e',
        'organization_id_required',
        'organization_id != ""',
        'Organization ID is required.',
        'error'
    ),
    (
        '2a3b4c5d-valid-0002',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        '2c3d4e5f-6a7b-8c9d-0e1f-2a3b4c5d6e7f',
        'name_required',
        'name != ""',
        'Branch name is required.',
        'error'
    ),
    (
        '2a3b4c5d-valid-0003',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        '2d3e4f5a-6b7c-8d9e-0f1a-2b3c4d5e6f7a',
        'code_required',
        'code != ""',
        'Branch code is required.',
        'error'
    ),
    (
        '2a3b4c5d-valid-0004',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        '2d3e4f5a-6b7c-8d9e-0f1a-2b3c4d5e6f7a',
        'code_unique',
        'is_unique_in_organization(code, organization_id)',
        'Branch code must be unique within the organization.',
        'error'
    ),
    (
        '2a3b4c5d-valid-0005',
        '2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d',
        '2a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        'email_format',
        'is_valid_email(contact_email)',
        'Please enter a valid email address.',
        'error'
    );
-- ORGANIZATION_BUILDING VALIDATION RULES
INSERT
    OR IGNORE INTO entity_validation_rule (
        id,
        entity_id,
        attribute_id,
        rule_name,
        rule_expression,
        error_message,
        severity
    )
VALUES (
        '3a4b5c6d-valid-0001',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        '3b4c5d6e-7f8a-9b0c-1d2e-3f4a5b6c7d8e',
        'branch_id_required',
        'organization_branch_id != ""',
        'Organization Branch ID is required.',
        'error'
    ),
    (
        '3a4b5c6d-valid-0002',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        '3c4d5e6f-7a8b-9c0d-1e2f-3a4b5c6d7e8f',
        'postal_address_required',
        'postal_address_id != ""',
        'Postal Address ID is required.',
        'error'
    ),
    (
        '3a4b5c6d-valid-0003',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        '3d4e5f6a-7b8c-9d0e-1f2a-3b4c5d6e7f8a',
        'name_required',
        'name != ""',
        'Building name is required.',
        'error'
    ),
    (
        '3a4b5c6d-valid-0004',
        '3a4b5c6d-7e8f-9a0b-1c2d-3e4f5a6b7c8d',
        '3a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        'total_floors_positive',
        'total_floors > 0',
        'Total floors must be greater than zero.',
        'warning'
    );
-- WORKSTATION VALIDATION RULES
INSERT
    OR IGNORE INTO entity_validation_rule (
        id,
        entity_id,
        attribute_id,
        rule_name,
        rule_expression,
        error_message,
        severity
    )
VALUES (
        '4a5b6c7d-valid-0001',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        '4b5c6d7e-8f9a-0b1c-2d3e-4f5a6b7c8d9e',
        'building_id_required',
        'organization_building_id != ""',
        'Organization Building ID is required.',
        'error'
    ),
    (
        '4a5b6c7d-valid-0002',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        '4c5d6e7f-8a9b-0c1d-2e3f-4a5b6c7d8e9f',
        'floor_required',
        'floor != ""',
        'Floor is required.',
        'error'
    ),
    (
        '4a5b6c7d-valid-0003',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        '4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b',
        'workstation_number_required',
        'workstation_number != ""',
        'Workstation number is required.',
        'error'
    ),
    (
        '4a5b6c7d-valid-0004',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        '4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b',
        'workstation_number_unique',
        'is_unique_in_building(workstation_number, organization_building_id)',
        'Workstation number must be unique within the building.',
        'error'
    ),
    (
        '4a5b6c7d-valid-0005',
        '4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d',
        '4d6e7f8a-9b0c-1d2e-3f4a-5b6c7d8e9f0a',
        'capacity_positive',
        'capacity > 0',
        'Capacity must be greater than zero.',
        'warning'
    );
-- =========================================
-- 8. UPDATE ENUM_VALUES
-- =========================================
-- ORGANIZATION_BRANCH enum values
UPDATE entity_attribute
SET enum_values = '["Head Office / Corporate HQ","Zonal Office","Area Office / Field Office","Administrative Office","Liaison Office / Representative Office","Production Plant","Assembly Unit","Fabrication Unit","Quality Control Center","Warehouse / Storage Facility","Maintenance Depot","Logistics Hub","Retail Outlet / Showroom","Service Center / Customer Care Center","Franchise Branch","Dealership / Authorized Partner Branch","Marketing Office","Design Studio / Innovation Lab","Testing Facility / QA Lab","Prototype Center","Technology Center","Skill Development Center","Knowledge Hub","Internship / Apprenticeship Center","Project Office / Site Office","Construction Site Branch","Temporary Camp Office","IT Support Center","HR Shared Service Center","Finance & Accounting Hub","Call Center / BPO Unit","Joint Venture Office","Subsidiary Branch","Partner Office","Export / Import Office","Regulatory / Compliance Office","Other"]'
WHERE id = '2f3a4b5c-6d7e-8f9a-0b1c-2d3e4f5a6b7c';
-- ORGANIZATION_BUILDING enum values
UPDATE entity_attribute
SET enum_values = '["Office","Warehouse","Factory","Data Center","Retail Store","Laboratory","Mixed Use","Other"]'
WHERE id = '3f4a5b6c-7d8e-9f0a-1b2c-3d4e5f6a7b8c';
-- WORKSTATION enum values
UPDATE entity_attribute
SET enum_values = '["Desk","Cubicle","Private Office","Hot Desk","Meeting Room","Workbench","Lab Station","Other"]'
WHERE id = '4f5a6b7c-8d9e-0f1a-2b3c-4d5e6f7a8b9c';
-- =========================================
-- End of script
-- =========================================