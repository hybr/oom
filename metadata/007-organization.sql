

-- Create the ORGANIZATION entity
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    '1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d',
    'ORGANIZATION',
    'Organization',
    'Local organizations, businesses, and service providers',
    'BUSINESS',
    'organization',
    1
);

-- ORGANIZATION ATTRIBUTES
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, is_unique, description, display_order)
VALUES
-- Core Identity Fields
('1b2c3d4e-5f6a-7b8c-9d0e-1f2a3b4c5d6e','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','short_name','Short Name','text',1,1,0,'Short or trade name of organization',1),
('1c1d2e3f-4a5b-6c7d-8e9f-0a1b2c3d4e5f','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','legal_category_id','Legal Category','uuid',0,0,0,'Reference to POPULAR_ORGANIZATION_LEGAL_TYPES',2),
('1d4e5f6a-7b8c-9d0e-1f2a-3b4c5d6e7f8a','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','tag_line','Tag Line','text',0,0,0,'Marketing tagline or slogan',3),
('1e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','description','Description','text',0,0,0,'Full description of organization',4),

-- Online Presence
('1f6a7b8c-9d0e-1f2a-3b4c-5d6e7f8a9b0c','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','website','Website','text',0,0,0,'Organization website URL',5),
('1a7b8c9d-0e1f-2a3b-4c5d-6e7f8a9b0c1d','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','subdomain','Subdomain','text',0,0,1,'Unique subdomain for V4L.app (e.g., mybiz.v4l.app)',6),
('1b8c9d0e-1f2a-3b4c-5d6e-7f8a9b0c1d2e','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','logo','Logo','file',0,0,0,'Organization logo image file',7),

-- Contact Information
('1c9d0e1f-2a3b-4c5d-6e7f-8a9b0c1d2e3f','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','primary_email','Primary Email','text',0,0,0,'Main contact email',8),
('1d0e1f2a-3b4c-5d6e-7f8a-9b0c1d2e3f4a','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','primary_phone','Primary Phone','text',0,0,0,'Main contact phone number',9),
('1e1f2a3b-4c5d-6e7f-8a9b-0c1d2e3f4a5b','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','support_email','Support Email','text',0,0,0,'Customer support email',10),
('1f2a3b4c-5d6e-7f8a-9b0c-1d2e3f4a5b6c','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','support_phone','Support Phone','text',0,0,0,'Customer support phone number',11),

-- Foreign Keys
('1a9b0c1d-2e3f-4a5b-6c7d-8e9f0a1b2c3d','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','admin_id','Admin ID','text',1,0,0,'Reference to PERSON who administers this organization',18),
('1b0c1d2e-3f4a-5b6c-7d8e-9f0a1b2c3d4e','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','industry_id','Industry ID','text',0,0,0,'Reference to INDUSTRY_CATEGORY',19),

-- Business Details
('1d2e3f4a-5b6c-7d8e-9f0a-1b2c3d4e5f6a','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','tax_id','Tax ID','text',0,0,0,'Tax identification number',21),
('1e3f4a5b-6c7d-8e9f-0a1b-2c3d4e5f6a7b','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','registration_number','Registration Number','text',0,0,0,'Business registration number',22),
('1f4a5b6c-7d8e-9f0a-1b2c-3d4e5f6a7b8c','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','established_date','Established Date','date',0,0,0,'Date organization was established',23),

-- Status and Settings
('1c7d8e9f-0a1b-2c3d-4e5f-6a7b8c9d0e1f','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','status','Status','enum_objects',0,0,0,'Organization status',26),
('1d8e9f0a-1b2c-3d4e-5f6a-7b8c9d0e1f2a','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','is_verified','Is Verified','boolean',0,0,0,'Whether organization is verified',27),
('1e9f0a1b-2c3d-4e5f-6a7b-8c9d0e1f2a3b','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','verification_date','Verification Date','datetime',0,0,0,'Date when organization was verified',28),
('1f0a1b2c-3d4e-5f6a-7b8c-9d0e1f2a3b4c','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','is_featured','Is Featured','boolean',0,0,0,'Whether to feature this organization',29),
('1a1b2c3d-4e5f-6a7b-8c9d-0e1f2a3b4c5d','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','rating','Rating','number',0,0,0,'Average customer rating (0-5)',30),
('1b2c3d4e-5f6a-7b8c-9d0e-1f2a3b4c5d6e','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','review_count','Review Count','integer',0,0,0,'Total number of reviews',31);

-- Update enum_values for enum fields
UPDATE entity_attribute SET enum_values = '[{"value":"active","label":"Active"},{"value":"suspended","label":"Suspended"},{"value":"closed","label":"Closed"}]'
WHERE id = '1c7d8e9f-0a1b-2c3d-4e5f-6a7b8c9d0e1f';

-- ORGANIZATION RELATIONSHIPS
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description)
VALUES
-- Organization belongs to Admin (PERSON)
('1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d-rel1','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','ManyToOne','admin','admin_id','Organization is administered by a person'),

-- Organization belongs to Industry Category
('1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d-rel2','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c','ManyToOne','industry','industry_id','Organization belongs to an industry category'),

-- Organization belongs to Legal Category
('1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d-rel3','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','b2c3d4e5-f6a7-48b9-0c1d-2e3f4a5b6c7d','ManyToOne','legal_category','legal_category_id','Organization has a legal category/structure');

-- ORGANIZATION FUNCTIONS
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_active)
VALUES
('1a2b3c4d-func-0001','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','get_organization_full_name','Get Organization Full Name','Returns the full display name combining short name and legal category','[{"name":"organization_id","type":"text"}]','text',1),
('1a2b3c4d-func-0002','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','get_organization_url','Get Organization URL','Returns the full V4L subdomain URL','[{"name":"organization_id","type":"text"}]','text',1),
('1a2b3c4d-func-0003','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','verify_organization','Verify Organization','Mark organization as verified','[{"name":"organization_id","type":"text"},{"name":"verifier_id","type":"text"}]','boolean',1),
('1a2b3c4d-func-0004','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','update_rating','Update Rating','Recalculate average rating based on reviews','[{"name":"organization_id","type":"text"}]','boolean',1),
('1a2b3c4d-func-0005','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','search_organizations','Search Organizations','Search organizations by name, industry, or location','[{"name":"keyword","type":"text"},{"name":"industry_id","type":"text"},{"name":"city","type":"text"}]','json',1),
('1a2b3c4d-func-0006','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','get_featured_organizations','Get Featured Organizations','Get list of featured organizations','[{"name":"limit","type":"integer"}]','json',1),
('1a2b3c4d-func-0007','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','check_subdomain_available','Check Subdomain Available','Check if a subdomain is available','[{"name":"subdomain","type":"text"}]','boolean',1),
('1a2b3c4d-func-0008','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','get_organization_admin','Get Organization Admin','Get the admin person details','[{"name":"organization_id","type":"text"}]','json',1);

-- FUNCTION HANDLERS
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference, is_active)
VALUES
('1a2b3c4d-handler-0001','1a2b3c4d-func-0001','script','/scripts/organization/get_full_name.php',1),
('1a2b3c4d-handler-0002','1a2b3c4d-func-0002','script','/scripts/organization/get_url.php',1),
('1a2b3c4d-handler-0003','1a2b3c4d-func-0003','script','/scripts/organization/verify.php',1),
('1a2b3c4d-handler-0004','1a2b3c4d-func-0004','script','/scripts/organization/update_rating.php',1),
('1a2b3c4d-handler-0005','1a2b3c4d-func-0005','api','/api/organization/search',1),
('1a2b3c4d-handler-0006','1a2b3c4d-func-0006','api','/api/organization/featured',1),
('1a2b3c4d-handler-0007','1a2b3c4d-func-0007','script','/scripts/organization/check_subdomain.php',1),
('1a2b3c4d-handler-0008','1a2b3c4d-func-0008','script','/scripts/organization/get_admin.php',1);

-- RELATIONSHIPS
-- Relationships from PERSON_EDUCATION and PERSON_SKILL to ORGANIZATION
-- (Moved from 003-person_education_and_skills.sql to avoid FK constraint violation)
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description)
VALUES
('63d954a0-5404-4775-8abf-06cb3dce3117', '1e6b403f-5a37-47c3-8d8a-80419e2c9e25', '1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d', 'ManyToOne', 'organization', 'organization_id', 'Educational institution where qualification was obtained'),
('e3f4a5b6-c7d8-4e9f-0a1b-2c3d4e5f6a7b', '6d6b3a5b-72ac-4ccf-b089-9e8a53b115c2', '1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d', 'ManyToOne', 'organization', 'organization_id', 'Training or certifying institution for skill');

-- VALIDATION RULES
INSERT OR IGNORE INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity)
VALUES
('1a2b3c4d-valid-0001','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','1b2c3d4e-5f6a-7b8c-9d0e-1f2a3b4c5d6e','short_name_required','short_name != ""','Short name is required.','error'),
('1a2b3c4d-valid-0002','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','1a7b8c9d-0e1f-2a3b-4c5d-6e7f8a9b0c1d','subdomain_format','is_valid_subdomain(subdomain)','Subdomain must contain only lowercase letters, numbers, and hyphens.','error'),
('1a2b3c4d-valid-0003','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','1a7b8c9d-0e1f-2a3b-4c5d-6e7f8a9b0c1d','subdomain_unique','is_unique(subdomain)','This subdomain is already taken.','error'),
('1a2b3c4d-valid-0004','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','1c9d0e1f-2a3b-4c5d-6e7f-8a9b0c1d2e3f','email_format','is_valid_email(primary_email)','Please enter a valid email address.','error'),
('1a2b3c4d-valid-0005','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','1f6a7b8c-9d0e-1f2a-3b4c-5d6e7f8a9b0c','website_format','is_valid_url(website)','Please enter a valid URL.','warning'),
('1a2b3c4d-valid-0006','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','1a9b0c1d-2e3f-4a5b-6c7d-8e9f0a1b2c3d','admin_required','admin_id != ""','An administrator must be assigned.','error');

-- Create unique constraint for (short_name, legal_category_id)
-- Note: This will be enforced at the application level or via CREATE UNIQUE INDEX in the table creation

-- =========================================
-- UPDATE: Rename admin_id to main_admin_id
-- Added: 2025-01-23
-- =========================================
-- Update attribute code and metadata
UPDATE entity_attribute
SET code = 'main_admin_id',
    name = 'Main Admin ID',
    description = 'Reference to PERSON who is the primary owner/administrator of this organization',
    display_order = 12
WHERE id = '1a9b0c1d-2e3f-4a5b-6c7d-8e9f0a1b2c3d';

-- Update relationship name
UPDATE entity_relationship
SET relation_name = 'main_admin',
    description = 'Organization is owned by a main admin person'
WHERE id = '1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d-rel1';

-- Update validation rule
UPDATE entity_validation_rule
SET error_message = 'A main administrator must be assigned.'
WHERE id = '1a2b3c4d-valid-0006';

-- Add new functions for main admin management
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_active)
VALUES
('1a2b3c4d-func-0009','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','get_main_admin','Get Main Admin','Get the main admin person details','[{"name":"organization_id","type":"text"}]','json',1),
('1a2b3c4d-func-0010','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','is_main_admin','Is Main Admin','Check if person is main admin of organization','[{"name":"organization_id","type":"text"},{"name":"person_id","type":"text"}]','boolean',1);

INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference, is_active)
VALUES
('1a2b3c4d-handler-0009','1a2b3c4d-func-0009','script','/scripts/organization/get_main_admin.php',1),
('1a2b3c4d-handler-0010','1a2b3c4d-func-0010','api','/api/organization/is-main-admin',1);
