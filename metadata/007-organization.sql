-- ============================================
-- ENTITY METADATA: ORGANIZATION (for v4l.sqlite)
-- ============================================

-- First, create the INDUSTRY_CATEGORY entity
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    '8a1b2c3d-4e5f-6a7b-8c9d-0e1f2a3b4c5d',
    'INDUSTRY_CATEGORY',
    'Industry Category',
    'Categories for different industries and business sectors',
    'COMMON',
    'industry_category',
    1
);

-- INDUSTRY_CATEGORY ATTRIBUTES
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, is_unique, description, display_order)
VALUES
('8b2c3d4e-5f6a-7b8c-9d0e-1f2a3b4c5d6e','8a1b2c3d-4e5f-6a7b-8c9d-0e1f2a3b4c5d','category_code','Category Code','text',1,0,1,'Unique code for industry category',1),
('8c3d4e5f-6a7b-8c9d-0e1f-2a3b4c5d6e7f','8a1b2c3d-4e5f-6a7b-8c9d-0e1f2a3b4c5d','category_name','Category Name','text',1,1,0,'Name of the industry category',2),
('8d4e5f6a-7b8c-9d0e-1f2a-3b4c5d6e7f8a','8a1b2c3d-4e5f-6a7b-8c9d-0e1f2a3b4c5d','description','Description','text',0,0,0,'Description of the industry category',3),
('8e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b','8a1b2c3d-4e5f-6a7b-8c9d-0e1f2a3b4c5d','parent_category_id','Parent Category ID','text',0,0,0,'Reference to parent category for hierarchical structure',4);

-- Create the ORGANIZATION_LEGAL_CATEGORY entity
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    '9a1b2c3d-4e5f-6a7b-8c9d-0e1f2a3b4c5d',
    'ORGANIZATION_LEGAL_CATEGORY',
    'Organization Legal Category',
    'Legal structures and business registration types',
    'COMMON',
    'organization_legal_category',
    1
);

-- ORGANIZATION_LEGAL_CATEGORY ATTRIBUTES
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, is_unique, description, display_order)
VALUES
('9b2c3d4e-5f6a-7b8c-9d0e-1f2a3b4c5d6e','9a1b2c3d-4e5f-6a7b-8c9d-0e1f2a3b4c5d','legal_code','Legal Code','text',1,0,1,'Unique code for legal category (e.g., LLC, CORP, NPO)',1),
('9c3d4e5f-6a7b-8c9d-0e1f-2a3b4c5d6e7f','9a1b2c3d-4e5f-6a7b-8c9d-0e1f2a3b4c5d','legal_name','Legal Name','text',1,1,0,'Full name of legal category',2),
('9d4e5f6a-7b8c-9d0e-1f2a-3b4c5d6e7f8a','9a1b2c3d-4e5f-6a7b-8c9d-0e1f2a3b4c5d','description','Description','text',0,0,0,'Description of legal structure',3),
('9e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b','9a1b2c3d-4e5f-6a7b-8c9d-0e1f2a3b4c5d','requires_tax_id','Requires Tax ID','boolean',0,0,0,'Whether this legal type requires tax ID',4);

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
('1c3d4e5f-6a7b-8c9d-0e1f-2a3b4c5d6e7f','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','legal_name','Legal Name','text',0,0,0,'Full legal name of organization',2),
('1d4e5f6a-7b8c-9d0e-1f2a-3b4c5d6e7f8a','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','tag_line','Tag Line','text',0,0,0,'Marketing tagline or slogan',3),
('1e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','description','Description','text',0,0,0,'Full description of organization',4),

-- Online Presence
('1f6a7b8c-9d0e-1f2a-3b4c-5d6e7f8a9b0c','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','website','Website','text',0,0,0,'Organization website URL',5),
('1a7b8c9d-0e1f-2a3b-4c5d-6e7f8a9b0c1d','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','subdomain','Subdomain','text',0,0,1,'Unique subdomain for V4L.app (e.g., mybiz.v4l.app)',6),
('1b8c9d0e-1f2a-3b4c-5d6e-7f8a9b0c1d2e','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','logo_url','Logo URL','text',0,0,0,'URL to organization logo image',7),

-- Contact Information
('1c9d0e1f-2a3b-4c5d-6e7f-8a9b0c1d2e3f','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','primary_email','Primary Email','text',0,0,0,'Main contact email',8),
('1d0e1f2a-3b4c-5d6e-7f8a-9b0c1d2e3f4a','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','primary_phone','Primary Phone','text',0,0,0,'Main contact phone number',9),
('1e1f2a3b-4c5d-6e7f-8a9b-0c1d2e3f4a5b','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','support_email','Support Email','text',0,0,0,'Customer support email',10),
('1f2a3b4c-5d6e-7f8a-9b0c-1d2e3f4a5b6c','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','support_phone','Support Phone','text',0,0,0,'Customer support phone number',11),

-- Address Information
('1a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','address_line_1','Address Line 1','text',0,0,0,'Street address line 1',12),
('1b4c5d6e-7f8a-9b0c-1d2e-3f4a5b6c7d8e','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','address_line_2','Address Line 2','text',0,0,0,'Street address line 2',13),
('1c5d6e7f-8a9b-0c1d-2e3f-4a5b6c7d8e9f','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','city','City','text',0,0,0,'City name',14),
('1d6e7f8a-9b0c-1d2e-3f4a-5b6c7d8e9f0a','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','state_province','State/Province','text',0,0,0,'State or province',15),
('1e7f8a9b-0c1d-2e3f-4a5b-6c7d8e9f0a1b','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','postal_code','Postal Code','text',0,0,0,'Postal/ZIP code',16),
('1f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','country','Country','text',0,0,0,'Country name',17),

-- Foreign Keys
('1a9b0c1d-2e3f-4a5b-6c7d-8e9f0a1b2c3d','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','admin_id','Admin ID','text',1,0,0,'Reference to PERSON who administers this organization',18),
('1b0c1d2e-3f4a-5b6c-7d8e-9f0a1b2c3d4e','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','industry_id','Industry ID','text',0,0,0,'Reference to INDUSTRY_CATEGORY',19),
('1c1d2e3f-4a5b-6c7d-8e9f-0a1b2c3d4e5f','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','legal_category_id','Legal Category ID','text',0,0,0,'Reference to ORGANIZATION_LEGAL_CATEGORY',20),

-- Business Details
('1d2e3f4a-5b6c-7d8e-9f0a-1b2c3d4e5f6a','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','tax_id','Tax ID','text',0,0,0,'Tax identification number',21),
('1e3f4a5b-6c7d-8e9f-0a1b-2c3d4e5f6a7b','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','registration_number','Registration Number','text',0,0,0,'Business registration number',22),
('1f4a5b6c-7d8e-9f0a-1b2c-3d4e5f6a7b8c','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','established_date','Established Date','date',0,0,0,'Date organization was established',23),
('1a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','employee_count','Employee Count','integer',0,0,0,'Number of employees',24),
('1b6c7d8e-9f0a-1b2c-3d4e-5f6a7b8c9d0e','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','annual_revenue','Annual Revenue','number',0,0,0,'Annual revenue amount',25),

-- Status and Settings
('1c7d8e9f-0a1b-2c3d-4e5f-6a7b8c9d0e1f','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','status','Status','text',0,0,0,'Organization status (active, suspended, closed)',26),
('1d8e9f0a-1b2c-3d4e-5f6a-7b8c9d0e1f2a','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','is_verified','Is Verified','boolean',0,0,0,'Whether organization is verified',27),
('1e9f0a1b-2c3d-4e5f-6a7b-8c9d0e1f2a3b','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','verification_date','Verification Date','datetime',0,0,0,'Date when organization was verified',28),
('1f0a1b2c-3d4e-5f6a-7b8c-9d0e1f2a3b4c','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','is_featured','Is Featured','boolean',0,0,0,'Whether to feature this organization',29),
('1a1b2c3d-4e5f-6a7b-8c9d-0e1f2a3b4c5d','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','rating','Rating','number',0,0,0,'Average customer rating (0-5)',30),
('1b2c3d4e-5f6a-7b8c-9d0e-1f2a3b4c5d6e','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','review_count','Review Count','integer',0,0,0,'Total number of reviews',31);

-- ORGANIZATION RELATIONSHIPS
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description)
VALUES
-- Organization belongs to Admin (PERSON)
('1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d-rel1','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','ManyToOne','admin','admin_id','Organization is administered by a person'),

-- Organization belongs to Industry Category
('1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d-rel2','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','8a1b2c3d-4e5f-6a7b-8c9d-0e1f2a3b4c5d','ManyToOne','industry','industry_id','Organization belongs to an industry category'),

-- Organization belongs to Legal Category
('1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d-rel3','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','9a1b2c3d-4e5f-6a7b-8c9d-0e1f2a3b4c5d','ManyToOne','legal_category','legal_category_id','Organization has a legal category/structure');

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
