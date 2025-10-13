-- ============================================
-- ENTITY METADATA: PERSON (for meta.sqlite)
-- ============================================

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name)
VALUES (
    '2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3',
    'PERSON',
    'Person',
    'Stores individual person profile and personal details',
    'COMMON',
    'person'
);

-- PERSON ATTRIBUTES
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, description, display_order)
VALUES
('fae8c84e-bb91-4d24-8e9c-98254c5f9d5b','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','name_prefix','Name Prefix','text',0,0,'Prefix such as Mr., Ms., Dr.',1),
('ea5ce5d0-798c-4cc5-a9c0-616544d18d51','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','first_name','First Name','text',1,1,'Given name',2),
('c0f58e08-84b9-4eb3-8b4c-07ff17b98651','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','middle_name','Middle Name','text',0,0,'Middle name',3),
('7d2e64d1-6633-4a39-bde7-37c527985c8e','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','last_name','Last Name','text',1,1,'Surname or family name',4),
('8a7a64d8-5d37-4715-8ac3-cb7b74bbdb1f','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','name_suffix','Name Suffix','text',0,0,'Suffix such as Jr., Sr.',5),
('35ef2f4c-69b1-4055-bb90-1cb2e84f5c7c','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','gender','Gender','text',0,0,'Male / Female / Other',6),
('6d4ef2ff-b77c-4a7f-bbc2-36216c7aef16','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','date_of_birth','Date of Birth','date',0,0,'Birth date',7),
('4a65a67d-2745-458a-9916-37ef27cb3a5d','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','primary_email','Primary Email','text',0,0,'Main contact email',8),
('bcb37a4a-dfa4-496a-bbc1-f37c3e9620ab','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','primary_phone','Primary Phone','text',0,0,'Main contact number',9),
('a8c2171a-c9ff-4726-8b3b-0b534a848e8c','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','organization_id','Organization ID','text',0,0,'Reference to organization entity',10);

-- PERSON FUNCTIONS
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type)
VALUES
('5d8c2c0f-87f4-4d3f-b52e-19d72a2c4697','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','create_person','Create Person','Insert a new person record','[{"name":"data","type":"json"}]','json'),
('4c437b17-46f3-4d3f-bfb9-1f0f8724b9d7','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','get_full_name','Get Full Name','Concatenate name fields into a display name','[{"name":"person_id","type":"text"}]','text'),
('ed5946b3-2337-490a-9bbf-2082c5237a46','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','search_person','Search Person','Search by name or contact details','[{"name":"keyword","type":"text"}]','json');

-- FUNCTION HANDLERS
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference)
VALUES
('75ad5df5-1b8c-49f4-87ab-22cc2597ad33','5d8c2c0f-87f4-4d3f-b52e-19d72a2c4697','sql','sp_create_person'),
('dd57f451-4b13-46eb-a1a8-992c56d6c765','4c437b17-46f3-4d3f-bfb9-1f0f8724b9d7','script','/scripts/person/get_full_name.php'),
('85c74172-d2f2-40c8-a9c9-b84a5a6a9344','ed5946b3-2337-490a-9bbf-2082c5237a46','api','/api/person/search');

-- VALIDATION RULES
INSERT OR IGNORE INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity)
VALUES
('703fca5a-fb8f-41c4-93b1-bb1e0e3da7e4','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','ea5ce5d0-798c-4cc5-a9c0-616544d18d51','first_name_required','first_name != ""','First name is required.','error');



-- ============================================
-- ENTITY METADATA: PERSON_CREDENTIAL (for meta.sqlite)
-- ============================================

INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name)
VALUES (
    '7c1a8923-4585-4f73-9f64-fdfaa13b3b24',
    'PERSON_CREDENTIAL',
    'Person Credential',
    'Stores login credentials, password hashes, and security question answers for users',
    'SECURITY',
    'person_credential'
);

-- ATTRIBUTES
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, description, display_order)
VALUES
('bf2b97c8-03e2-4ab1-a8ea-2b8b6c7a02a3','7c1a8923-4585-4f73-9f64-fdfaa13b3b24','person_id','Person ID','text',1,0,'Linked person ID',1),
('f7e3a648-3083-44c5-a9e2-36c89ce5ac14','7c1a8923-4585-4f73-9f64-fdfaa13b3b24','username','Username','text',1,1,'Unique username',2),
('a1b2c3d4-e5f6-7890-abcd-ef1234567890','7c1a8923-4585-4f73-9f64-fdfaa13b3b24','email','Email','text',0,0,'User email address',3),
('76dc255e-1a67-4a69-8b5b-5c354e4176b7','7c1a8923-4585-4f73-9f64-fdfaa13b3b24','hashed_password','Hashed Password','text',1,0,'Password hash using bcrypt/argon2',4),
('0b82b81b-1877-492b-b679-02f6611c4ff0','7c1a8923-4585-4f73-9f64-fdfaa13b3b24','security_question_1','Security Question 1','text',0,0,'First security question',5),
('4a26f7a8-56a9-4db3-bdb6-1161c4112c4d','7c1a8923-4585-4f73-9f64-fdfaa13b3b24','security_answer_1_hash','Answer 1 Hash','text',0,0,'Hashed answer for security question 1',6),
('32e43502-54b0-4b7a-9b07-890155dd8394','7c1a8923-4585-4f73-9f64-fdfaa13b3b24','security_question_2','Security Question 2','text',0,0,'Second security question',7),
('c7e33e4f-4308-41a8-a94b-21526b274fcf','7c1a8923-4585-4f73-9f64-fdfaa13b3b24','security_answer_2_hash','Answer 2 Hash','text',0,0,'Hashed answer for security question 2',8),
('61b07bfa-6a45-4745-b9d0-74b4980a88c4','7c1a8923-4585-4f73-9f64-fdfaa13b3b24','last_login_at','Last Login Timestamp','datetime',0,0,'Last successful login timestamp',9),
('4ff495a5-3f40-4a15-9de9-1837c607f70f','7c1a8923-4585-4f73-9f64-fdfaa13b3b24','failed_attempts','Failed Attempts','number',0,0,'Count of consecutive failed login attempts',10),
('3ccf5155-7a59-4d6b-83b9-9024d249d8a3','7c1a8923-4585-4f73-9f64-fdfaa13b3b24','locked_until','Locked Until','datetime',0,0,'If account locked, date/time until unlock',11);

-- FUNCTIONS
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type)
VALUES
('8a7b125a-52a1-463a-a1ff-5a8e5a02151a','7c1a8923-4585-4f73-9f64-fdfaa13b3b24','sign_up','Sign Up','Create new credentials and link to person','[{"name":"data","type":"json"}]','boolean'),
('b51b82f0-1c5f-45f2-b26a-67c747b0e8d5','7c1a8923-4585-4f73-9f64-fdfaa13b3b24','sign_in','Sign In','Authenticate user with username and password','[{"name":"username","type":"text"},{"name":"password","type":"text"}]','json'),
('0e0d9f14-f37d-44b3-bec8-05f3f7209c7c','7c1a8923-4585-4f73-9f64-fdfaa13b3b24','forgot_password','Forgot Password','Initiate password reset using security questions','[{"name":"username","type":"text"}]','json'),
('3b732ce7-10b8-46db-9240-cf89b87098b1','7c1a8923-4585-4f73-9f64-fdfaa13b3b24','reset_password','Reset Password','Reset after verifying security questions','[{"name":"username","type":"text"},{"name":"answers","type":"json"}]','boolean'),
('5efb5016-f57a-490b-9a9d-d4c932c9f4a8','7c1a8923-4585-4f73-9f64-fdfaa13b3b24','change_password','Change Password','Change password from within user session','[{"name":"username","type":"text"},{"name":"old_password","type":"text"},{"name":"new_password","type":"text"}]','boolean');

-- FUNCTION HANDLERS
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference)
VALUES
('51e1b0ce-bb6b-4047-a8a2-64a6c6cf65f1','8a7b125a-52a1-463a-a1ff-5a8e5a02151a','api','/api/auth/signup'),
('a2c29a94-78ea-4c4a-8362-8c66f5d9b0d5','b51b82f0-1c5f-45f2-b26a-67c747b0e8d5','script','/scripts/auth/sign_in.php'),
('0bafc7b0-d28b-4e59-9b43-1b44e4c5d4cf','0e0d9f14-f37d-44b3-bec8-05f3f7209c7c','api','/api/auth/forgot_password'),
('c6f327e7-344c-46b4-a4cb-cb09eeae7861','3b732ce7-10b8-46db-9240-cf89b87098b1','script','/scripts/auth/reset_password.php'),
('96ef4462-7a2a-4d89-9506-097912f4763a','5efb5016-f57a-490b-9a9d-d4c932c9f4a8','sql','sp_change_password');

-- RELATIONSHIP: PERSON_CREDENTIAL belongs to PERSON
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description)
VALUES (
    '28b61baf-b1e2-4656-b04c-b42f3b46ad0c',
    '7c1a8923-4585-4f73-9f64-fdfaa13b3b24',
    '2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3',
    'OneToOne',
    'person',
    'person_id',
    'Each credential belongs to one person.'
);

-- VALIDATION RULES
INSERT OR IGNORE INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity)
VALUES
('b8327e2a-36b4-46ee-bf14-4e50d3b4ff0d','7c1a8923-4585-4f73-9f64-fdfaa13b3b24','f7e3a648-3083-44c5-a9e2-36c89ce5ac14','unique_username','is_unique(username)','Username must be unique.','error'),
('c9438f3b-47c5-47ff-bf25-5e61e4b5gg1e','7c1a8923-4585-4f73-9f64-fdfaa13b3b24','a1b2c3d4-e5f6-7890-abcd-ef1234567890','unique_email','is_unique(email)','Email must be unique.','error');
