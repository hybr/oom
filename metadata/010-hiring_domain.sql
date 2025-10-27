-- ============================================
-- ENTITY METADATA: HIRING DOMAIN
-- (ORGANIZATION_VACANCY, ORGANIZATION_VACANCY_WORKSTATION, VACANCY_APPLICATION,
--  APPLICATION_REVIEW, INTERVIEW_STAGE, APPLICATION_INTERVIEW, JOB_OFFER, EMPLOYMENT_CONTRACT)
-- ============================================

-- =========================================
-- 1. ORGANIZATION_VACANCY Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    '5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d',
    'ORGANIZATION_VACANCY',
    'Organization Vacancy',
    'Job vacancies or open positions within an organization',
    'HIRING',
    'organization_vacancy',
    1
);

-- ORGANIZATION_VACANCY ATTRIBUTES
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, is_unique, enum_values, description, display_order)
VALUES
-- Foreign Keys (using 'uuid' data type for entity references)
('5b6c7d8e-9f0a-1b2c-3d4e-5f6a7b8c9d0e','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','organization_id','Organization','uuid',1,0,0,NULL,'Reference to ORGANIZATION entity',1),
('5c6d7e8f-9a0b-1c2d-3e4f-5a6b7c8d9e0f','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','popular_position_id','Position','uuid',1,0,0,NULL,'Reference to POPULAR_ORGANIZATION_POSITION entity',2),
('5d6e7f8a-9b0c-1d2e-3f4a-5b6c7d8e9f0a','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','created_by','Created By','uuid',1,0,0,NULL,'Reference to PERSON who created this vacancy',3),

-- Core Fields
('5e6f7a8b-9c0d-1e2f-3a4b-5c6d7e8f9a0b','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','title','Vacancy Title','text',1,1,0,NULL,'Title of the vacancy',4),
('5f6a7b8c-9d0e-1f2a-3b4c-5d6e7f8a9b0c','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','description','Description','text',0,0,0,NULL,'Detailed description of the vacancy',5),
('5a7b8c9d-0e1f-2a3b-4c5d-6e7f8a9b0c1d','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','requirements','Requirements','text',0,0,0,NULL,'Required qualifications and skills',6),
('5b7c8d9e-0f1a-2b3c-4d5e-6f7a8b9c0d1e','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','responsibilities','Responsibilities','text',0,0,0,NULL,'Key responsibilities of the position',7),
('5c7d8e9f-0a1b-2c3d-4e5f-6a7b8c9d0e1f','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','number_of_openings','Number of Openings','integer',1,0,0,NULL,'Number of positions available',8),

-- Dates
('5d7e8f9a-0b1c-2d3e-4f5a-6b7c8d9e0f1a','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','opening_date','Opening Date','date',1,0,0,NULL,'Date when vacancy was opened',9),
('5e7f8a9b-0c1d-2e3f-4a5b-6c7d8e9f0a1b','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','closing_date','Closing Date','date',0,0,0,NULL,'Date when vacancy will close',10),

-- Compensation
('5f7a8b9c-0d1e-2f3a-4b5c-6d7e8f9a0b1c','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','min_salary','Minimum Salary','number',0,0,0,NULL,'Minimum salary offered',11),
('5a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','max_salary','Maximum Salary','number',0,0,0,NULL,'Maximum salary offered',12),
('5b8c9d0e-1f2a-3b4c-5d6e-7f8a9b0c1d2e','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','employment_type','Employment Type','enum_strings',0,0,0,NULL,'Type of employment',13),

-- Status
('5c8d9e0f-1a2b-3c4d-5e6f-7a8b9c0d1e2f','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','status','Status','enum_objects',1,0,0,NULL,'Current status of the vacancy',14),
('5d8e9f0a-1b2c-3d4e-5f6a-7b8c9d0e1f2a','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','is_urgent','Is Urgent','boolean',0,0,0,NULL,'Whether this is an urgent vacancy',15);

-- =========================================
-- 2. ORGANIZATION_VACANCY_WORKSTATION Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    '6a7b8c9d-0e1f-2a3b-4c5d-6e7f8a9b0c1d',
    'ORGANIZATION_VACANCY_WORKSTATION',
    'Organization Vacancy Workstation',
    'Links vacancies to specific workstations',
    'HIRING',
    'organization_vacancy_workstation',
    1
);

-- ORGANIZATION_VACANCY_WORKSTATION ATTRIBUTES
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, is_unique, enum_values, description, display_order)
VALUES
-- Foreign Keys (using 'uuid' data type for entity references)
('6b7c8d9e-0f1a-2b3c-4d5e-6f7a8b9c0d1e','6a7b8c9d-0e1f-2a3b-4c5d-6e7f8a9b0c1d','organization_vacancy_id','Vacancy','uuid',1,0,0,NULL,'Reference to ORGANIZATION_VACANCY entity',1),
('6c7d8e9f-0a1b-2c3d-4e5f-6a7b8c9d0e1f','6a7b8c9d-0e1f-2a3b-4c5d-6e7f8a9b0c1d','organization_workstation_id','Workstation','uuid',1,0,0,NULL,'Reference to WORKSTATION entity',2),

-- Additional Info
('6d7e8f9a-0b1c-2d3e-4f5a-6b7c8d9e0f1a','6a7b8c9d-0e1f-2a3b-4c5d-6e7f8a9b0c1d','notes','Notes','text',0,0,0,NULL,'Additional notes about this workstation assignment',3);

-- =========================================
-- 3. VACANCY_APPLICATION Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    '7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d',
    'VACANCY_APPLICATION',
    'Vacancy Application',
    'Applications submitted by candidates for job vacancies',
    'HIRING',
    'vacancy_application',
    1
);

-- VACANCY_APPLICATION ATTRIBUTES
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, is_unique, enum_values, description, display_order)
VALUES
-- Foreign Keys (using 'uuid' data type for entity references)
('7b8c9d0e-1f2a-3b4c-5d6e-7f8a9b0c1d2e','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','vacancy_id','Vacancy','uuid',1,0,0,NULL,'Reference to ORGANIZATION_VACANCY entity',1),
('7c8d9e0f-1a2b-3c4d-5e6f-7a8b9c0d1e2f','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','applicant_id','Applicant','uuid',1,0,0,NULL,'Reference to PERSON entity',2),

-- Application Details
('7d8e9f0a-1b2c-3d4e-5f6a-7b8c9d0e1f2a','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','application_date','Application Date','datetime',1,0,0,NULL,'Date and time when application was submitted',3),
('7e8f9a0b-1c2d-3e4f-5a6b-7c8d9e0f1a2b','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','resume_url','Resume URL','text',0,0,0,NULL,'URL to uploaded resume/CV',4),
('7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','cover_letter','Cover Letter','text',0,0,0,NULL,'Cover letter text',5),
('7a9b0c1d-2e3f-4a5b-6c7d-8e9f0a1b2c3d','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','expected_salary','Expected Salary','number',0,0,0,NULL,'Salary expectation of applicant',6),
('7b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','available_from','Available From','date',0,0,0,NULL,'Date when applicant can start',7),

-- Status
('7c9d0e1f-2a3b-4c5d-6e7f-8a9b0c1d2e3f','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','status','Status','enum_objects',1,0,0,NULL,'Current status of the application',8),
('7d9e0f1a-2b3c-4d5e-6f7a-8b9c0d1e2f3a','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','withdrawn_reason','Withdrawn Reason','text',0,0,0,NULL,'Reason if application was withdrawn',9);

-- =========================================
-- 4. APPLICATION_REVIEW Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    '8a9b0c1d-2e3f-4a5b-6c7d-8e9f0a1b2c3d',
    'APPLICATION_REVIEW',
    'Application Review',
    'Reviews of job applications by hiring managers or HR',
    'HIRING',
    'application_review',
    1
);

-- APPLICATION_REVIEW ATTRIBUTES
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, is_unique, enum_values, description, display_order)
VALUES
-- Foreign Keys (using 'uuid' data type for entity references)
('8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e','8a9b0c1d-2e3f-4a5b-6c7d-8e9f0a1b2c3d','application_id','Application','uuid',1,0,0,NULL,'Reference to VACANCY_APPLICATION entity',1),
('8c9d0e1f-2a3b-4c5d-6e7f-8a9b0c1d2e3f','8a9b0c1d-2e3f-4a5b-6c7d-8e9f0a1b2c3d','reviewed_by','Reviewed By','uuid',1,0,0,NULL,'Reference to PERSON who reviewed',2),

-- Review Details
('8d9e0f1a-2b3c-4d5e-6f7a-8b9c0d1e2f3a','8a9b0c1d-2e3f-4a5b-6c7d-8e9f0a1b2c3d','review_date','Review Date','datetime',1,0,0,NULL,'Date and time of review',3),
('8e9f0a1b-2c3d-4e5f-6a7b-8c9d0e1f2a3b','8a9b0c1d-2e3f-4a5b-6c7d-8e9f0a1b2c3d','review_notes','Review Notes','text',0,0,0,NULL,'Detailed review notes',4),
('8f9a0b1c-2d3e-4f5a-6b7c-8d9e0f1a2b3c','8a9b0c1d-2e3f-4a5b-6c7d-8e9f0a1b2c3d','rating','Rating','integer',0,0,0,NULL,'Rating out of 10',5),

-- Status
('8a0b1c2d-3e4f-5a6b-7c8d-9e0f1a2b3c4d','8a9b0c1d-2e3f-4a5b-6c7d-8e9f0a1b2c3d','status','Status','enum_objects',1,0,0,NULL,'Review status',6),
('8b0c1d2e-3f4a-5b6c-7d8e-9f0a1b2c3d4e','8a9b0c1d-2e3f-4a5b-6c7d-8e9f0a1b2c3d','proceed_to_interview','Proceed to Interview','boolean',0,0,0,NULL,'Whether to proceed with interview',7);

-- =========================================
-- 5. INTERVIEW_STAGE Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    '9a0b1c2d-3e4f-5a6b-7c8d-9e0f1a2b3c4d',
    'INTERVIEW_STAGE',
    'Interview Stage',
    'Different stages of the interview process for an organization',
    'HIRING',
    'interview_stage',
    1
);

-- INTERVIEW_STAGE ATTRIBUTES
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, is_unique, enum_values, description, display_order)
VALUES
-- Foreign Keys (using 'uuid' data type for entity references)
('9b0c1d2e-3f4a-5b6c-7d8e-9f0a1b2c3d4e','9a0b1c2d-3e4f-5a6b-7c8d-9e0f1a2b3c4d','organization_id','Organization','uuid',1,0,0,NULL,'Reference to ORGANIZATION entity',1),

-- Stage Details
('9c0d1e2f-3a4b-5c6d-7e8f-9a0b1c2d3e4f','9a0b1c2d-3e4f-5a6b-7c8d-9e0f1a2b3c4d','name','Stage Name','text',1,1,0,NULL,'Name of the interview stage',2),
('9d0e1f2a-3b4c-5d6e-7f8a-9b0c1d2e3f4a','9a0b1c2d-3e4f-5a6b-7c8d-9e0f1a2b3c4d','description','Description','text',0,0,0,NULL,'Description of this stage',3),
('9e0f1a2b-3c4d-5e6f-7a8b-9c0d1e2f3a4b','9a0b1c2d-3e4f-5a6b-7c8d-9e0f1a2b3c4d','order_number','Order Number','integer',1,0,0,NULL,'Order of this stage in the process',4),
('9f0a1b2c-3d4e-5f6a-7b8c-9d0e1f2a3b4c','9a0b1c2d-3e4f-5a6b-7c8d-9e0f1a2b3c4d','duration_minutes','Duration (Minutes)','integer',0,0,0,NULL,'Expected duration of this stage in minutes',5),
('9a1b2c3d-4e5f-6a7b-8c9d-0e1f2a3b4c5d','9a0b1c2d-3e4f-5a6b-7c8d-9e0f1a2b3c4d','is_active','Is Active','boolean',0,0,0,NULL,'Whether this stage is currently active',6);

-- =========================================
-- 6. APPLICATION_INTERVIEW Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    'a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d',
    'APPLICATION_INTERVIEW',
    'Application Interview',
    'Individual interview sessions for job applications',
    'HIRING',
    'application_interview',
    1
);

-- APPLICATION_INTERVIEW ATTRIBUTES
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, is_unique, enum_values, description, display_order)
VALUES
-- Foreign Keys (using 'uuid' data type for entity references)
('a1b2c3d4-5e6f-7a8b-9c0d-1e2f3a4b5c6d','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','application_id','Application','uuid',1,0,0,NULL,'Reference to VACANCY_APPLICATION entity',1),
('a2b3c4d5-6e7f-8a9b-0c1d-2e3f4a5b6c7d','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','stage_id','Interview Stage','uuid',1,0,0,NULL,'Reference to INTERVIEW_STAGE entity',2),
('a3b4c5d6-7e8f-9a0b-1c2d-3e4f5a6b7c8d','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','interviewer_id','Interviewer','uuid',1,0,0,NULL,'Reference to PERSON conducting the interview',3),

-- Scheduling
('a4b5c6d7-8e9f-0a1b-2c3d-4e5f6a7b8c9d','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','scheduled_date','Scheduled Date','datetime',1,0,0,NULL,'Scheduled date and time for interview',4),
('a5b6c7d8-9e0f-1a2b-3c4d-5e6f7a8b9c0d','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','actual_date','Actual Date','datetime',0,0,0,NULL,'Actual date and time when interview occurred',5),
('a6b7c8d9-0e1f-2a3b-4c5d-6e7f8a9b0c1d','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','location','Location','text',0,0,0,NULL,'Location or meeting link for interview',6),
('a7b8c9d0-1e2f-3a4b-5c6d-7e8f9a0b1c2d','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','interview_mode','Interview Mode','enum_strings',0,0,0,NULL,'Mode of interview',7),

-- Feedback
('a8b9c0d1-2e3f-4a5b-6c7d-8e9f0a1b2c3d','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','feedback_notes','Feedback Notes','text',0,0,0,NULL,'Detailed feedback from interviewer',8),
('a9b0c1d2-3e4f-5a6b-7c8d-9e0f1a2b3c4d','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','rating','Rating','integer',0,0,0,NULL,'Rating from 1 to 10',9),
('aab1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','strengths','Strengths','text',0,0,0,NULL,'Candidate strengths identified',10),
('abb2c3d4-5e6f-7a8b-9c0d-1e2f3a4b5c6d','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','weaknesses','Weaknesses','text',0,0,0,NULL,'Candidate weaknesses identified',11),

-- Status
('acb3c4d5-6e7f-8a9b-0c1d-2e3f4a5b6c7d','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','status','Status','enum_objects',1,0,0,NULL,'Interview status',12),
('adb4c5d6-7e8f-9a0b-1c2d-3e4f5a6b7c8d','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','recommendation','Recommendation','enum_strings',0,0,0,NULL,'Interviewer recommendation',13);

-- =========================================
-- 7. JOB_OFFER Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    'b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e',
    'JOB_OFFER',
    'Job Offer',
    'Job offers extended to successful candidates',
    'HIRING',
    'job_offer',
    1
);

-- JOB_OFFER ATTRIBUTES
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, is_unique, enum_values, description, display_order)
VALUES
-- Foreign Keys (using 'uuid' data type for entity references)
('b1c2d3e4-5f6a-7b8c-9d0e-1f2a3b4c5d6e','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','application_id','Application','uuid',1,0,0,NULL,'Reference to VACANCY_APPLICATION entity',1),
('b2c3d4e5-6f7a-8b9c-0d1e-2f3a4b5c6d7e','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','offered_by','Offered By','uuid',1,0,0,NULL,'Reference to PERSON who made the offer',2),

-- Offer Details
('b3c4d5e6-7f8a-9b0c-1d2e-3f4a5b6c7d8e','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','offer_date','Offer Date','date',1,0,0,NULL,'Date when offer was made',3),
('b4c5d6e7-8f9a-0b1c-2d3e-4f5a6b7c8d9e','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','position_title','Position Title','text',1,1,0,NULL,'Title of the position offered',4),
('b5c6d7e8-9f0a-1b2c-3d4e-5f6a7b8c9d0e','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','salary_offered','Salary Offered','number',1,0,0,NULL,'Salary amount offered',5),
('b6c7d8e9-0f1a-2b3c-4d5e-6f7a8b9c0d1e','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','benefits','Benefits','text',0,0,0,NULL,'Benefits package details',6),
('b7c8d9e0-1f2a-3b4c-5d6e-7f8a9b0c1d2e','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','joining_date','Joining Date','date',1,0,0,NULL,'Expected joining date',7),
('b8c9d0e1-2f3a-4b5c-6d7e-8f9a0b1c2d3e','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','expiry_date','Expiry Date','date',0,0,0,NULL,'Date when offer expires',8),
('b9c0d1e2-3f4a-5b6c-7d8e-9f0a1b2c3d4e','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','offer_letter_url','Offer Letter URL','text',0,0,0,NULL,'URL to the offer letter document',9),

-- Status and Response
('bac1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','status','Status','enum_objects',1,0,0,NULL,'Offer status',10),
('bbc2d3e4-5f6a-7b8c-9d0e-1f2a3b4c5d6e','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','response_date','Response Date','date',0,0,0,NULL,'Date when candidate responded',11),
('bcc3d4e5-6f7a-8b9c-0d1e-2f3a4b5c6d7e','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','decline_reason','Decline Reason','text',0,0,0,NULL,'Reason if offer was declined',12);

-- =========================================
-- 8. EMPLOYMENT_CONTRACT Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    'c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f',
    'EMPLOYMENT_CONTRACT',
    'Employment Contract',
    'Employment contracts for hired employees',
    'HIRING',
    'employment_contract',
    1
);

-- EMPLOYMENT_CONTRACT ATTRIBUTES
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, is_unique, enum_values, description, display_order)
VALUES
-- Foreign Keys (using 'uuid' data type for entity references)
('c1d2e3f4-5a6b-7c8d-9e0f-1a2b3c4d5e6f','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','job_offer_id','Job Offer','uuid',1,0,0,NULL,'Reference to JOB_OFFER entity',1),
('c2d3e4f5-6a7b-8c9d-0e1f-2a3b4c5d6e7f','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','organization_id','Organization','uuid',1,0,0,NULL,'Reference to ORGANIZATION entity',2),
('c3d4e5f6-7a8b-9c0d-1e2f-3a4b5c6d7e8f','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','employee_id','Employee','uuid',1,0,0,NULL,'Reference to PERSON (employee)',3),

-- Contract Details
('c4d5e6f7-8a9b-0c1d-2e3f-4a5b6c7d8e9f','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','contract_number','Contract Number','text',1,1,1,NULL,'Unique contract number',4),
('c5d6e7f8-9a0b-1c2d-3e4f-5a6b7c8d9e0f','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','start_date','Start Date','date',1,0,0,NULL,'Employment start date',5),
('c6d7e8f9-0a1b-2c3d-4e5f-6a7b8c9d0e1f','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','end_date','End Date','date',0,0,0,NULL,'Employment end date (nullable for permanent)',6),
('c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','contract_type','Contract Type','enum_strings',1,0,0,NULL,'Type of contract',7),
('c8d9e0f1-2a3b-4c5d-6e7f-8a9b0c1d2e3f','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','contract_terms','Contract Terms','text',0,0,0,NULL,'Detailed contract terms and conditions',8),
('c9d0e1f2-3a4b-5c6d-7e8f-9a0b1c2d3e4f','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','contract_document_url','Contract Document URL','text',0,0,0,NULL,'URL to the signed contract document',9),
('cad1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','probation_period_months','Probation Period (Months)','integer',0,0,0,NULL,'Probation period in months',10),
('cbd2e3f4-5a6b-7c8d-9e0f-1a2b3c4d5e6f','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','notice_period_days','Notice Period (Days)','integer',0,0,0,NULL,'Notice period in days',11),

-- Status
('ccd3e4f5-6a7b-8c9d-0e1f-2a3b4c5d6e7f','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','status','Status','enum_objects',1,0,0,NULL,'Contract status',12),
('cdd4e5f6-7a8b-9c0d-1e2f-3a4b5c6d7e8f','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','termination_date','Termination Date','date',0,0,0,NULL,'Date when contract was terminated',13),
('ced5e6f7-8a9b-0c1d-2e3f-4a5b6c7d8e9f','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','termination_reason','Termination Reason','text',0,0,0,NULL,'Reason for termination if applicable',14);

-- =========================================
-- 9. ENTITY RELATIONSHIPS
-- =========================================
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description)
VALUES
-- ORGANIZATION_VACANCY relationships
('5a6b7c8d-9e0f-1a2b-rel1','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','ManyToOne','organization','organization_id','Vacancy belongs to an organization'),
('5a6b7c8d-9e0f-1a2b-rel2','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b','ManyToOne','position','popular_position_id','Vacancy is for a specific position'),
('5a6b7c8d-9e0f-1a2b-rel3','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','ManyToOne','creator','created_by','Vacancy was created by a person'),

-- ORGANIZATION_VACANCY_WORKSTATION relationships
('6a7b8c9d-0e1f-2a3b-rel1','6a7b8c9d-0e1f-2a3b-4c5d-6e7f8a9b0c1d','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','ManyToOne','vacancy','organization_vacancy_id','Links to vacancy'),
('6a7b8c9d-0e1f-2a3b-rel2','6a7b8c9d-0e1f-2a3b-4c5d-6e7f8a9b0c1d','4a5b6c7d-8e9f-0a1b-2c3d-4e5f6a7b8c9d','ManyToOne','workstation','organization_workstation_id','Links to workstation'),

-- VACANCY_APPLICATION relationships
('7a8b9c0d-1e2f-3a4b-rel1','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','ManyToOne','vacancy','vacancy_id','Application is for a vacancy'),
('7a8b9c0d-1e2f-3a4b-rel2','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','ManyToOne','applicant','applicant_id','Application is by an applicant'),

-- APPLICATION_REVIEW relationships
('8a9b0c1d-2e3f-4a5b-rel1','8a9b0c1d-2e3f-4a5b-6c7d-8e9f0a1b2c3d','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','ManyToOne','application','application_id','Review is for an application'),
('8a9b0c1d-2e3f-4a5b-rel2','8a9b0c1d-2e3f-4a5b-6c7d-8e9f0a1b2c3d','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','ManyToOne','reviewer','reviewed_by','Review was done by a person'),

-- INTERVIEW_STAGE relationships
('9a0b1c2d-3e4f-5a6b-rel1','9a0b1c2d-3e4f-5a6b-7c8d-9e0f1a2b3c4d','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','ManyToOne','organization','organization_id','Stage belongs to organization'),

-- APPLICATION_INTERVIEW relationships
('a0b1c2d3-4e5f-6a7b-rel1','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','ManyToOne','application','application_id','Interview is for an application'),
('a0b1c2d3-4e5f-6a7b-rel2','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','9a0b1c2d-3e4f-5a6b-7c8d-9e0f1a2b3c4d','ManyToOne','stage','stage_id','Interview is at a specific stage'),
('a0b1c2d3-4e5f-6a7b-rel3','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','ManyToOne','interviewer','interviewer_id','Interview conducted by interviewer'),

-- JOB_OFFER relationships
('b0c1d2e3-4f5a-6b7c-rel1','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','ManyToOne','application','application_id','Offer is for an application'),
('b0c1d2e3-4f5a-6b7c-rel2','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','ManyToOne','offerer','offered_by','Offer made by a person'),

-- EMPLOYMENT_CONTRACT relationships
('c0d1e2f3-4a5b-6c7d-rel1','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','ManyToOne','job_offer','job_offer_id','Contract is based on job offer'),
('c0d1e2f3-4a5b-6c7d-rel2','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','ManyToOne','organization','organization_id','Contract is with organization'),
('c0d1e2f3-4a5b-6c7d-rel3','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','ManyToOne','employee','employee_id','Contract is for employee');

-- =========================================
-- 10. ENTITY FUNCTIONS
-- =========================================

-- ORGANIZATION_VACANCY FUNCTIONS
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_active)
VALUES
('5a6b7c8d-func-0001','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','create','Create Vacancy','Create a new job vacancy','[{"name":"data","type":"json"}]','json',1),
('5a6b7c8d-func-0002','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','read','Read Vacancy','Read vacancy by id','[{"name":"id","type":"text"}]','json',1),
('5a6b7c8d-func-0003','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','update','Update Vacancy','Update vacancy information','[{"name":"id","type":"text"},{"name":"data","type":"json"}]','void',1),
('5a6b7c8d-func-0004','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','delete','Delete Vacancy','Delete vacancy by id','[{"name":"id","type":"text"}]','void',1),
('5a6b7c8d-func-0005','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','search','Search Vacancies','Search vacancies with filters','[{"name":"filters","type":"json"}]','json',1),
('5a6b7c8d-func-0006','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','get_open_vacancies','Get Open Vacancies','Get all open vacancies for organization','[{"name":"organization_id","type":"text"}]','json',1),
('5a6b7c8d-func-0007','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','close_vacancy','Close Vacancy','Mark vacancy as closed','[{"name":"vacancy_id","type":"text"}]','boolean',1),
('5a6b7c8d-func-0008','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','get_applications_count','Get Applications Count','Count applications for vacancy','[{"name":"vacancy_id","type":"text"}]','integer',1);

-- VACANCY_APPLICATION FUNCTIONS
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_active)
VALUES
('7a8b9c0d-func-0001','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','create','Create Application','Submit a new application','[{"name":"data","type":"json"}]','json',1),
('7a8b9c0d-func-0002','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','read','Read Application','Read application by id','[{"name":"id","type":"text"}]','json',1),
('7a8b9c0d-func-0003','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','update','Update Application','Update application details','[{"name":"id","type":"text"},{"name":"data","type":"json"}]','void',1),
('7a8b9c0d-func-0004','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','withdraw','Withdraw Application','Withdraw an application','[{"name":"application_id","type":"text"},{"name":"reason","type":"text"}]','boolean',1),
('7a8b9c0d-func-0005','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','shortlist','Shortlist Application','Mark application as shortlisted','[{"name":"application_id","type":"text"}]','boolean',1),
('7a8b9c0d-func-0006','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','reject','Reject Application','Reject an application','[{"name":"application_id","type":"text"},{"name":"reason","type":"text"}]','boolean',1),
('7a8b9c0d-func-0007','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','get_by_vacancy','Get Applications by Vacancy','Get all applications for a vacancy','[{"name":"vacancy_id","type":"text"}]','json',1),
('7a8b9c0d-func-0008','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','get_by_applicant','Get Applications by Applicant','Get all applications by an applicant','[{"name":"applicant_id","type":"text"}]','json',1);

-- APPLICATION_INTERVIEW FUNCTIONS
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_active)
VALUES
('a0b1c2d3-func-0001','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','create','Create Interview','Schedule a new interview','[{"name":"data","type":"json"}]','json',1),
('a0b1c2d3-func-0002','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','read','Read Interview','Read interview by id','[{"name":"id","type":"text"}]','json',1),
('a0b1c2d3-func-0003','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','update','Update Interview','Update interview details','[{"name":"id","type":"text"},{"name":"data","type":"json"}]','void',1),
('a0b1c2d3-func-0004','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','reschedule','Reschedule Interview','Reschedule an interview','[{"name":"interview_id","type":"text"},{"name":"new_date","type":"datetime"}]','boolean',1),
('a0b1c2d3-func-0005','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','complete','Complete Interview','Mark interview as completed','[{"name":"interview_id","type":"text"},{"name":"feedback","type":"json"}]','boolean',1),
('a0b1c2d3-func-0006','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','cancel','Cancel Interview','Cancel an interview','[{"name":"interview_id","type":"text"}]','boolean',1),
('a0b1c2d3-func-0007','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','get_by_application','Get Interviews by Application','Get all interviews for an application','[{"name":"application_id","type":"text"}]','json',1);

-- JOB_OFFER FUNCTIONS
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_active)
VALUES
('b0c1d2e3-func-0001','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','create','Create Offer','Create a new job offer','[{"name":"data","type":"json"}]','json',1),
('b0c1d2e3-func-0002','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','read','Read Offer','Read offer by id','[{"name":"id","type":"text"}]','json',1),
('b0c1d2e3-func-0003','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','update','Update Offer','Update offer details','[{"name":"id","type":"text"},{"name":"data","type":"json"}]','void',1),
('b0c1d2e3-func-0004','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','accept','Accept Offer','Mark offer as accepted','[{"name":"offer_id","type":"text"}]','boolean',1),
('b0c1d2e3-func-0005','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','decline','Decline Offer','Decline a job offer','[{"name":"offer_id","type":"text"},{"name":"reason","type":"text"}]','boolean',1),
('b0c1d2e3-func-0006','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','extend_expiry','Extend Expiry','Extend offer expiry date','[{"name":"offer_id","type":"text"},{"name":"new_expiry_date","type":"date"}]','boolean',1);

-- EMPLOYMENT_CONTRACT FUNCTIONS
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_active)
VALUES
('c0d1e2f3-func-0001','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','create','Create Contract','Create a new employment contract','[{"name":"data","type":"json"}]','json',1),
('c0d1e2f3-func-0002','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','read','Read Contract','Read contract by id','[{"name":"id","type":"text"}]','json',1),
('c0d1e2f3-func-0003','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','update','Update Contract','Update contract details','[{"name":"id","type":"text"},{"name":"data","type":"json"}]','void',1),
('c0d1e2f3-func-0004','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','terminate','Terminate Contract','Terminate an employment contract','[{"name":"contract_id","type":"text"},{"name":"reason","type":"text"}]','boolean',1),
('c0d1e2f3-func-0005','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','renew','Renew Contract','Renew a contract','[{"name":"contract_id","type":"text"},{"name":"new_end_date","type":"date"}]','boolean',1),
('c0d1e2f3-func-0006','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','get_active_contracts','Get Active Contracts','Get all active contracts for organization','[{"name":"organization_id","type":"text"}]','json',1);

-- =========================================
-- 11. FUNCTION HANDLERS
-- =========================================

-- ORGANIZATION_VACANCY HANDLERS
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference, is_active)
VALUES
('5a6b7c8d-handler-0001','5a6b7c8d-func-0001','sql','sp_create_organization_vacancy',1),
('5a6b7c8d-handler-0002','5a6b7c8d-func-0002','sql','sp_read_organization_vacancy',1),
('5a6b7c8d-handler-0003','5a6b7c8d-func-0003','sql','sp_update_organization_vacancy',1),
('5a6b7c8d-handler-0004','5a6b7c8d-func-0004','sql','sp_delete_organization_vacancy',1),
('5a6b7c8d-handler-0005','5a6b7c8d-func-0005','api','/api/vacancy/search',1),
('5a6b7c8d-handler-0006','5a6b7c8d-func-0006','sql','sp_get_open_vacancies',1),
('5a6b7c8d-handler-0007','5a6b7c8d-func-0007','script','/scripts/vacancy/close_vacancy.php',1),
('5a6b7c8d-handler-0008','5a6b7c8d-func-0008','sql','sp_get_applications_count',1);

-- VACANCY_APPLICATION HANDLERS
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference, is_active)
VALUES
('7a8b9c0d-handler-0001','7a8b9c0d-func-0001','script','/scripts/application/create_application.php',1),
('7a8b9c0d-handler-0002','7a8b9c0d-func-0002','sql','sp_read_vacancy_application',1),
('7a8b9c0d-handler-0003','7a8b9c0d-func-0003','sql','sp_update_vacancy_application',1),
('7a8b9c0d-handler-0004','7a8b9c0d-func-0004','script','/scripts/application/withdraw.php',1),
('7a8b9c0d-handler-0005','7a8b9c0d-func-0005','script','/scripts/application/shortlist.php',1),
('7a8b9c0d-handler-0006','7a8b9c0d-func-0006','script','/scripts/application/reject.php',1),
('7a8b9c0d-handler-0007','7a8b9c0d-func-0007','sql','sp_get_applications_by_vacancy',1),
('7a8b9c0d-handler-0008','7a8b9c0d-func-0008','sql','sp_get_applications_by_applicant',1);

-- APPLICATION_INTERVIEW HANDLERS
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference, is_active)
VALUES
('a0b1c2d3-handler-0001','a0b1c2d3-func-0001','script','/scripts/interview/schedule_interview.php',1),
('a0b1c2d3-handler-0002','a0b1c2d3-func-0002','sql','sp_read_application_interview',1),
('a0b1c2d3-handler-0003','a0b1c2d3-func-0003','sql','sp_update_application_interview',1),
('a0b1c2d3-handler-0004','a0b1c2d3-func-0004','script','/scripts/interview/reschedule.php',1),
('a0b1c2d3-handler-0005','a0b1c2d3-func-0005','script','/scripts/interview/complete.php',1),
('a0b1c2d3-handler-0006','a0b1c2d3-func-0006','script','/scripts/interview/cancel.php',1),
('a0b1c2d3-handler-0007','a0b1c2d3-func-0007','sql','sp_get_interviews_by_application',1);

-- JOB_OFFER HANDLERS
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference, is_active)
VALUES
('b0c1d2e3-handler-0001','b0c1d2e3-func-0001','script','/scripts/offer/create_offer.php',1),
('b0c1d2e3-handler-0002','b0c1d2e3-func-0002','sql','sp_read_job_offer',1),
('b0c1d2e3-handler-0003','b0c1d2e3-func-0003','sql','sp_update_job_offer',1),
('b0c1d2e3-handler-0004','b0c1d2e3-func-0004','script','/scripts/offer/accept.php',1),
('b0c1d2e3-handler-0005','b0c1d2e3-func-0005','script','/scripts/offer/decline.php',1),
('b0c1d2e3-handler-0006','b0c1d2e3-func-0006','script','/scripts/offer/extend_expiry.php',1);

-- EMPLOYMENT_CONTRACT HANDLERS
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference, is_active)
VALUES
('c0d1e2f3-handler-0001','c0d1e2f3-func-0001','script','/scripts/contract/create_contract.php',1),
('c0d1e2f3-handler-0002','c0d1e2f3-func-0002','sql','sp_read_employment_contract',1),
('c0d1e2f3-handler-0003','c0d1e2f3-func-0003','sql','sp_update_employment_contract',1),
('c0d1e2f3-handler-0004','c0d1e2f3-func-0004','script','/scripts/contract/terminate.php',1),
('c0d1e2f3-handler-0005','c0d1e2f3-func-0005','script','/scripts/contract/renew.php',1),
('c0d1e2f3-handler-0006','c0d1e2f3-func-0006','sql','sp_get_active_contracts',1);

-- =========================================
-- 12. VALIDATION RULES
-- =========================================

-- ORGANIZATION_VACANCY VALIDATION RULES
INSERT OR IGNORE INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity)
VALUES
('5a6b7c8d-valid-0001','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','5b6c7d8e-9f0a-1b2c-3d4e-5f6a7b8c9d0e','organization_id_required','organization_id != ""','Organization ID is required.','error'),
('5a6b7c8d-valid-0002','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','5e6f7a8b-9c0d-1e2f-3a4b-5c6d7e8f9a0b','title_required','title != ""','Vacancy title is required.','error'),
('5a6b7c8d-valid-0003','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','5c7d8e9f-0a1b-2c3d-4e5f-6a7b8c9d0e1f','number_positive','number_of_openings > 0','Number of openings must be greater than zero.','error'),
('5a6b7c8d-valid-0004','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','5e7f8a9b-0c1d-2e3f-4a5b-6c7d8e9f0a1b','closing_after_opening','closing_date > opening_date','Closing date must be after opening date.','error'),
('5a6b7c8d-valid-0005','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','5a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','salary_range_valid','max_salary >= min_salary','Maximum salary must be greater than or equal to minimum salary.','warning');

-- VACANCY_APPLICATION VALIDATION RULES
INSERT OR IGNORE INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity)
VALUES
('7a8b9c0d-valid-0001','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','7b8c9d0e-1f2a-3b4c-5d6e-7f8a9b0c1d2e','vacancy_id_required','vacancy_id != ""','Vacancy ID is required.','error'),
('7a8b9c0d-valid-0002','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','7c8d9e0f-1a2b-3c4d-5e6f-7a8b9c0d1e2f','applicant_id_required','applicant_id != ""','Applicant ID is required.','error'),
('7a8b9c0d-valid-0003','7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d','7b8c9d0e-1f2a-3b4c-5d6e-7f8a9b0c1d2e','unique_application','is_unique_application(vacancy_id, applicant_id)','You have already applied for this vacancy.','error');

-- APPLICATION_REVIEW VALIDATION RULES
INSERT OR IGNORE INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity)
VALUES
('8a9b0c1d-valid-0001','8a9b0c1d-2e3f-4a5b-6c7d-8e9f0a1b2c3d','8b9c0d1e-2f3a-4b5c-6d7e-8f9a0b1c2d3e','application_id_required','application_id != ""','Application ID is required.','error'),
('8a9b0c1d-valid-0002','8a9b0c1d-2e3f-4a5b-6c7d-8e9f0a1b2c3d','8f9a0b1c-2d3e-4f5a-6b7c-8d9e0f1a2b3c','rating_range','rating >= 1 AND rating <= 10','Rating must be between 1 and 10.','error');

-- APPLICATION_INTERVIEW VALIDATION RULES
INSERT OR IGNORE INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity)
VALUES
('a0b1c2d3-valid-0001','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','a1b2c3d4-5e6f-7a8b-9c0d-1e2f3a4b5c6d','application_id_required','application_id != ""','Application ID is required.','error'),
('a0b1c2d3-valid-0002','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','a4b5c6d7-8e9f-0a1b-2c3d-4e5f6a7b8c9d','scheduled_date_required','scheduled_date != ""','Scheduled date is required.','error'),
('a0b1c2d3-valid-0003','a0b1c2d3-4e5f-6a7b-8c9d-0e1f2a3b4c5d','a9b0c1d2-3e4f-5a6b-7c8d-9e0f1a2b3c4d','rating_range','rating >= 1 AND rating <= 10','Rating must be between 1 and 10.','error');

-- JOB_OFFER VALIDATION RULES
INSERT OR IGNORE INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity)
VALUES
('b0c1d2e3-valid-0001','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','b1c2d3e4-5f6a-7b8c-9d0e-1f2a3b4c5d6e','application_id_required','application_id != ""','Application ID is required.','error'),
('b0c1d2e3-valid-0002','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','b5c6d7e8-9f0a-1b2c-3d4e-5f6a7b8c9d0e','salary_positive','salary_offered > 0','Salary offered must be greater than zero.','error'),
('b0c1d2e3-valid-0003','b0c1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e','b7c8d9e0-1f2a-3b4c-5d6e-7f8a9b0c1d2e','joining_after_offer','joining_date >= offer_date','Joining date must be on or after offer date.','warning');

-- EMPLOYMENT_CONTRACT VALIDATION RULES
INSERT OR IGNORE INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity)
VALUES
('c0d1e2f3-valid-0001','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','c1d2e3f4-5a6b-7c8d-9e0f-1a2b3c4d5e6f','job_offer_id_required','job_offer_id != ""','Job offer ID is required.','error'),
('c0d1e2f3-valid-0002','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','c4d5e6f7-8a9b-0c1d-2e3f-4a5b6c7d8e9f','contract_number_required','contract_number != ""','Contract number is required.','error'),
('c0d1e2f3-valid-0003','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','c4d5e6f7-8a9b-0c1d-2e3f-4a5b6c7d8e9f','contract_number_unique','is_unique(contract_number)','Contract number must be unique.','error'),
('c0d1e2f3-valid-0004','c0d1e2f3-4a5b-6c7d-8e9f-0a1b2c3d4e5f','c6d7e8f9-0a1b-2c3d-4e5f-6a7b8c9d0e1f','end_after_start','end_date IS NULL OR end_date > start_date','End date must be after start date.','error');

-- =========================================
-- 13. UPDATE ENUM_VALUES
-- =========================================

-- ORGANIZATION_VACANCY enum values
UPDATE entity_attribute SET enum_values = '["Full-time","Part-time","Contract","Temporary","Internship","Freelance"]'
WHERE id = '5b8c9d0e-1f2a-3b4c-5d6e-7f8a9b0c1d2e';

UPDATE entity_attribute SET enum_values = '[{"value":"open","label":"Open"},{"value":"closed","label":"Closed"},{"value":"on_hold","label":"On Hold"}]'
WHERE id = '5c8d9e0f-1a2b-3c4d-5e6f-7a8b9c0d1e2f';

-- VACANCY_APPLICATION enum values
UPDATE entity_attribute SET enum_values = '[{"value":"applied","label":"Applied"},{"value":"shortlisted","label":"Shortlisted"},{"value":"rejected","label":"Rejected"},{"value":"selected","label":"Selected"},{"value":"withdrawn","label":"Withdrawn"}]'
WHERE id = '7c9d0e1f-2a3b-4c5d-6e7f-8a9b0c1d2e3f';

-- APPLICATION_REVIEW enum values
UPDATE entity_attribute SET enum_values = '[{"value":"pending","label":"Pending"},{"value":"approved","label":"Approved"},{"value":"rejected","label":"Rejected"}]'
WHERE id = '8a0b1c2d-3e4f-5a6b-7c8d-9e0f1a2b3c4d';

-- APPLICATION_INTERVIEW enum values
UPDATE entity_attribute SET enum_values = '["In-person","Video Call","Phone Call","Assessment Center"]'
WHERE id = 'a7b8c9d0-1e2f-3a4b-5c6d-7e8f9a0b1c2d';

UPDATE entity_attribute SET enum_values = '[{"value":"scheduled","label":"Scheduled"},{"value":"completed","label":"Completed"},{"value":"cancelled","label":"Cancelled"},{"value":"no_show","label":"No Show"}]'
WHERE id = 'acb3c4d5-6e7f-8a9b-0c1d-2e3f4a5b6c7d';

UPDATE entity_attribute SET enum_values = '["Strongly Recommend","Recommend","Maybe","Do Not Recommend"]'
WHERE id = 'adb4c5d6-7e8f-9a0b-1c2d-3e4f5a6b7c8d';

-- JOB_OFFER enum values
UPDATE entity_attribute SET enum_values = '[{"value":"offered","label":"Offered"},{"value":"accepted","label":"Accepted"},{"value":"declined","label":"Declined"},{"value":"expired","label":"Expired"}]'
WHERE id = 'bac1d2e3-4f5a-6b7c-8d9e-0f1a2b3c4d5e';

-- EMPLOYMENT_CONTRACT enum values
UPDATE entity_attribute SET enum_values = '["Permanent","Fixed-term","Probationary","Temporary","Casual"]'
WHERE id = 'c7d8e9f0-1a2b-3c4d-5e6f-7a8b9c0d1e2f';

UPDATE entity_attribute SET enum_values = '[{"value":"active","label":"Active"},{"value":"completed","label":"Completed"},{"value":"terminated","label":"Terminated"},{"value":"suspended","label":"Suspended"}]'
WHERE id = 'ccd3e4f5-6a7b-8c9d-0e1f-2a3b4c5d6e7f';

-- =========================================
-- End of script
-- =========================================
