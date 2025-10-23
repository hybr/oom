-- =====================================================================
-- VACANCY PROCESS SUPPORT ENTITIES
-- =====================================================================
-- This script defines 6 additional entities to support the Vacancy
-- Creation Process with proper audit trails, version management,
-- and analytics capabilities.
--
-- Entities Created:
-- 1. VACANCY_DRAFT - Draft version management
-- 2. VACANCY_APPROVAL_RECORD - Approval tracking
-- 3. VACANCY_REJECTION_REASON - Rejection tracking
-- 4. VACANCY_REVISION_HISTORY - Field-level change tracking
-- 5. VACANCY_TASK_DATA - Task-specific data storage
-- 6. VACANCY_PUBLICATION_RECORD - Publication tracking
--
-- Generated: 2025-10-23
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. VACANCY_DRAFT Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    'VD000000-0000-4000-8000-000000000001',
    'VACANCY_DRAFT',
    'Vacancy Draft',
    'Stores draft versions of vacancies before final approval and publication',
    'HIRING',
    'vacancy_draft',
    1
);

-- VACANCY_DRAFT ATTRIBUTES
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, is_unique, enum_values, description, display_order)
VALUES
-- Foreign Keys
('VD000001-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','vacancy_id','Vacancy ID','text',1,0,0,NULL,'Reference to ORGANIZATION_VACANCY entity',1),
('VD000002-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','flow_instance_id','Flow Instance ID','text',1,0,0,NULL,'Reference to TASK_FLOW_INSTANCE managing this draft',2),
('VD000003-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','organization_id','Organization ID','text',1,0,0,NULL,'Reference to ORGANIZATION entity',3),
('VD000004-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','position_id','Position ID','text',1,0,0,NULL,'Reference to POPULAR_ORGANIZATION_POSITION entity',4),
('VD000005-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','created_by','Created By','text',1,0,0,NULL,'Reference to PERSON who created this draft',5),

-- Draft Version Control
('VD000006-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','draft_version','Draft Version','integer',1,0,0,NULL,'Version number of this draft (1, 2, 3...)',6),
('VD000007-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','draft_status','Draft Status','enum_objects',1,0,0,NULL,'Status of this draft',7),
('VD000008-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','is_current_draft','Is Current Draft','boolean',1,0,0,NULL,'Whether this is the active draft version',8),

-- Draft Content (mirrors ORGANIZATION_VACANCY fields)
('VD000009-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','title_draft','Vacancy Title (Draft)','text',1,1,0,NULL,'Title of the vacancy in this draft',9),
('VD000010-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','description_draft','Description (Draft)','text',0,0,0,NULL,'Detailed description in this draft',10),
('VD000011-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','requirements_draft','Requirements (Draft)','text',0,0,0,NULL,'Required qualifications in this draft',11),
('VD000012-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','responsibilities_draft','Responsibilities (Draft)','text',0,0,0,NULL,'Key responsibilities in this draft',12),
('VD000013-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','number_of_openings_draft','Number of Openings (Draft)','integer',1,0,0,NULL,'Number of positions in this draft',13),

-- Dates
('VD000014-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','opening_date_draft','Opening Date (Draft)','date',0,0,0,NULL,'Planned opening date in this draft',14),
('VD000015-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','closing_date_draft','Closing Date (Draft)','date',0,0,0,NULL,'Planned closing date in this draft',15),

-- Compensation
('VD000016-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','min_salary_draft','Minimum Salary (Draft)','number',0,0,0,NULL,'Minimum salary in this draft',16),
('VD000017-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','max_salary_draft','Maximum Salary (Draft)','number',0,0,0,NULL,'Maximum salary in this draft',17),
('VD000018-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','employment_type_draft','Employment Type (Draft)','enum_strings',0,0,0,NULL,'Type of employment in this draft',18),

-- Draft Metadata
('VD000019-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','is_urgent_draft','Is Urgent (Draft)','boolean',0,0,0,NULL,'Whether this is urgent in this draft',19),
('VD000020-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','draft_notes','Draft Notes','text',0,0,0,NULL,'Internal notes about this draft version',20),
('VD000021-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','last_modified_by','Last Modified By','text',0,0,0,NULL,'Person who last modified this draft',21),
('VD000022-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','submitted_at','Submitted At','datetime',0,0,0,NULL,'When this draft was submitted for review',22),
('VD000023-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','approved_at','Approved At','datetime',0,0,0,NULL,'When this draft was approved',23),
('VD000024-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','rejected_at','Rejected At','datetime',0,0,0,NULL,'When this draft was rejected',24);

-- =========================================
-- 2. VACANCY_APPROVAL_RECORD Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    'VAR00000-0000-4000-8000-000000000001',
    'VACANCY_APPROVAL_RECORD',
    'Vacancy Approval Record',
    'Tracks all approval decisions in the vacancy creation workflow',
    'HIRING',
    'vacancy_approval_record',
    1
);

-- VACANCY_APPROVAL_RECORD ATTRIBUTES
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, is_unique, enum_values, description, display_order)
VALUES
-- Foreign Keys
('VAR00001-0000-4000-8000-000000000001','VAR00000-0000-4000-8000-000000000001','flow_instance_id','Flow Instance ID','text',1,0,0,NULL,'Reference to TASK_FLOW_INSTANCE',1),
('VAR00002-0000-4000-8000-000000000001','VAR00000-0000-4000-8000-000000000001','task_instance_id','Task Instance ID','text',1,0,0,NULL,'Reference to TASK_INSTANCE',2),
('VAR00003-0000-4000-8000-000000000001','VAR00000-0000-4000-8000-000000000001','vacancy_id','Vacancy ID','text',1,0,0,NULL,'Reference to ORGANIZATION_VACANCY',3),
('VAR00004-0000-4000-8000-000000000001','VAR00000-0000-4000-8000-000000000001','node_id','Process Node ID','text',1,0,0,NULL,'Which step (HR_REVIEW, FINANCE_APPROVAL, etc.)',4),
('VAR00005-0000-4000-8000-000000000001','VAR00000-0000-4000-8000-000000000001','approved_by','Approved By','text',1,0,0,NULL,'Reference to PERSON who made the decision',5),

-- Approval Details
('VAR00006-0000-4000-8000-000000000001','VAR00000-0000-4000-8000-000000000001','approval_date','Approval Date','datetime',1,0,0,NULL,'Date and time of approval decision',6),
('VAR00007-0000-4000-8000-000000000001','VAR00000-0000-4000-8000-000000000001','approval_action','Approval Action','enum_objects',1,1,0,NULL,'Decision made (APPROVED, REJECTED, NEEDS_REVISION)',7),
('VAR00008-0000-4000-8000-000000000001','VAR00000-0000-4000-8000-000000000001','approval_sequence','Approval Sequence','integer',1,0,0,NULL,'Order in approval chain (1=HR, 2=Finance/Dept, 3=Dept)',8),
('VAR00009-0000-4000-8000-000000000001','VAR00000-0000-4000-8000-000000000001','approval_step_name','Approval Step Name','text',1,0,0,NULL,'Name of the approval step',9),

-- Decision Details
('VAR00010-0000-4000-8000-000000000001','VAR00000-0000-4000-8000-000000000001','decision_rationale','Decision Rationale','text',0,0,0,NULL,'Why this decision was made',10),
('VAR00011-0000-4000-8000-000000000001','VAR00000-0000-4000-8000-000000000001','approval_comments','Approval Comments','text',0,0,0,NULL,'Additional comments from approver',11),
('VAR00012-0000-4000-8000-000000000001','VAR00000-0000-4000-8000-000000000001','conditions_met','Conditions Met','text',0,0,0,NULL,'Which conditions were satisfied',12),
('VAR00013-0000-4000-8000-000000000001','VAR00000-0000-4000-8000-000000000001','signed_off_date','Signed Off Date','datetime',0,0,0,NULL,'Date when formal sign-off was completed',13),

-- Compliance
('VAR00014-0000-4000-8000-000000000001','VAR00000-0000-4000-8000-000000000001','is_final_approval','Is Final Approval','boolean',0,0,0,NULL,'Whether this was the final approval in chain',14),
('VAR00015-0000-4000-8000-000000000001','VAR00000-0000-4000-8000-000000000001','approval_level','Approval Level','enum_strings',0,0,0,NULL,'Level of approval (MANAGER, DIRECTOR, EXECUTIVE)',15),
('VAR00016-0000-4000-8000-000000000001','VAR00000-0000-4000-8000-000000000001','delegation_from','Delegated From','text',0,0,0,NULL,'If delegated, who originally should have approved',16);

-- =========================================
-- 3. VACANCY_REJECTION_REASON Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    'VRR00000-0000-4000-8000-000000000001',
    'VACANCY_REJECTION_REASON',
    'Vacancy Rejection Reason',
    'Tracks rejection reasons and required revisions for vacancy drafts',
    'HIRING',
    'vacancy_rejection_reason',
    1
);

-- VACANCY_REJECTION_REASON ATTRIBUTES
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, is_unique, enum_values, description, display_order)
VALUES
-- Foreign Keys
('VRR00001-0000-4000-8000-000000000001','VRR00000-0000-4000-8000-000000000001','flow_instance_id','Flow Instance ID','text',1,0,0,NULL,'Reference to TASK_FLOW_INSTANCE',1),
('VRR00002-0000-4000-8000-000000000001','VRR00000-0000-4000-8000-000000000001','task_instance_id','Task Instance ID','text',1,0,0,NULL,'Reference to TASK_INSTANCE that rejected',2),
('VRR00003-0000-4000-8000-000000000001','VRR00000-0000-4000-8000-000000000001','vacancy_id','Vacancy ID','text',1,0,0,NULL,'Reference to ORGANIZATION_VACANCY',3),
('VRR00004-0000-4000-8000-000000000001','VRR00000-0000-4000-8000-000000000001','draft_id','Draft ID','text',0,0,0,NULL,'Reference to VACANCY_DRAFT that was rejected',4),
('VRR00005-0000-4000-8000-000000000001','VRR00000-0000-4000-8000-000000000001','rejected_by','Rejected By','text',1,0,0,NULL,'Reference to PERSON who rejected',5),

-- Rejection Details
('VRR00006-0000-4000-8000-000000000001','VRR00000-0000-4000-8000-000000000001','rejection_date','Rejection Date','datetime',1,0,0,NULL,'Date and time of rejection',6),
('VRR00007-0000-4000-8000-000000000001','VRR00000-0000-4000-8000-000000000001','rejection_reason','Rejection Reason','enum_objects',1,1,0,NULL,'Primary reason for rejection',7),
('VRR00008-0000-4000-8000-000000000001','VRR00000-0000-4000-8000-000000000001','rejection_category','Rejection Category','enum_strings',0,0,0,NULL,'Category of rejection',8),
('VRR00009-0000-4000-8000-000000000001','VRR00000-0000-4000-8000-000000000001','detailed_feedback','Detailed Feedback','text',0,0,0,NULL,'Detailed explanation of rejection',9),

-- Revision Requirements
('VRR00010-0000-4000-8000-000000000001','VRR00000-0000-4000-8000-000000000001','required_revisions','Required Revisions','text',0,0,0,NULL,'JSON array of specific items to fix',10),
('VRR00011-0000-4000-8000-000000000001','VRR00000-0000-4000-8000-000000000001','revision_deadline','Revision Deadline','datetime',0,0,0,NULL,'Deadline for submitting revised draft',11),
('VRR00012-0000-4000-8000-000000000001','VRR00000-0000-4000-8000-000000000001','is_critical','Is Critical','boolean',1,0,0,NULL,'Whether this blocks further progress',12),

-- Tracking
('VRR00013-0000-4000-8000-000000000001','VRR00000-0000-4000-8000-000000000001','revision_number','Revision Number','integer',1,0,0,NULL,'Which rejection cycle (1st, 2nd, 3rd...)',13),
('VRR00014-0000-4000-8000-000000000001','VRR00000-0000-4000-8000-000000000001','rejected_at_step','Rejected At Step','text',0,0,0,NULL,'Which process step rejected (HR_REVIEW, FINANCE_APPROVAL, etc.)',14),
('VRR00015-0000-4000-8000-000000000001','VRR00000-0000-4000-8000-000000000001','resolved_date','Resolved Date','datetime',0,0,0,NULL,'When the rejection was addressed',15),
('VRR00016-0000-4000-8000-000000000001','VRR00000-0000-4000-8000-000000000001','resolution_notes','Resolution Notes','text',0,0,0,NULL,'How the rejection was resolved',16);

-- =========================================
-- 4. VACANCY_REVISION_HISTORY Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    'VRH00000-0000-4000-8000-000000000001',
    'VACANCY_REVISION_HISTORY',
    'Vacancy Revision History',
    'Tracks field-level changes made to vacancy during the approval process',
    'HIRING',
    'vacancy_revision_history',
    1
);

-- VACANCY_REVISION_HISTORY ATTRIBUTES
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, is_unique, enum_values, description, display_order)
VALUES
-- Foreign Keys
('VRH00001-0000-4000-8000-000000000001','VRH00000-0000-4000-8000-000000000001','vacancy_id','Vacancy ID','text',1,0,0,NULL,'Reference to ORGANIZATION_VACANCY',1),
('VRH00002-0000-4000-8000-000000000001','VRH00000-0000-4000-8000-000000000001','flow_instance_id','Flow Instance ID','text',1,0,0,NULL,'Reference to TASK_FLOW_INSTANCE',2),
('VRH00003-0000-4000-8000-000000000001','VRH00000-0000-4000-8000-000000000001','draft_id','Draft ID','text',0,0,0,NULL,'Reference to VACANCY_DRAFT version',3),
('VRH00004-0000-4000-8000-000000000001','VRH00000-0000-4000-8000-000000000001','changed_by','Changed By','text',1,0,0,NULL,'Reference to PERSON who made the change',4),

-- Change Details
('VRH00005-0000-4000-8000-000000000001','VRH00000-0000-4000-8000-000000000001','revision_number','Revision Number','integer',1,0,0,NULL,'Sequential revision number',5),
('VRH00006-0000-4000-8000-000000000001','VRH00000-0000-4000-8000-000000000001','changed_at','Changed At','datetime',1,0,0,NULL,'Date and time of change',6),
('VRH00007-0000-4000-8000-000000000001','VRH00000-0000-4000-8000-000000000001','field_changed','Field Changed','text',1,1,0,NULL,'Name of the field that was changed',7),
('VRH00008-0000-4000-8000-000000000001','VRH00000-0000-4000-8000-000000000001','field_type','Field Type','enum_strings',0,0,0,NULL,'Data type of the field (text, number, date, etc.)',8),

-- Values
('VRH00009-0000-4000-8000-000000000001','VRH00000-0000-4000-8000-000000000001','old_value','Old Value','text',0,0,0,NULL,'Value before change',9),
('VRH00010-0000-4000-8000-000000000001','VRH00000-0000-4000-8000-000000000001','new_value','New Value','text',0,0,0,NULL,'Value after change',10),

-- Context
('VRH00011-0000-4000-8000-000000000001','VRH00000-0000-4000-8000-000000000001','change_reason','Change Reason','text',0,0,0,NULL,'Why the change was made',11),
('VRH00012-0000-4000-8000-000000000001','VRH00000-0000-4000-8000-000000000001','triggered_by','Triggered By','text',0,0,0,NULL,'Which approval/rejection caused this change',12),
('VRH00013-0000-4000-8000-000000000001','VRH00000-0000-4000-8000-000000000001','change_type','Change Type','enum_strings',0,0,0,NULL,'Type of change (CREATE, UPDATE, DELETE)',13),
('VRH00014-0000-4000-8000-000000000001','VRH00000-0000-4000-8000-000000000001','changed_at_step','Changed At Step','text',0,0,0,NULL,'Which process step triggered the change',14);

-- =========================================
-- 5. VACANCY_TASK_DATA Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    'VTD00000-0000-4000-8000-000000000001',
    'VACANCY_TASK_DATA',
    'Vacancy Task Data',
    'Stores task-specific data such as comments, attachments, and decision details for each workflow step',
    'HIRING',
    'vacancy_task_data',
    1
);

-- VACANCY_TASK_DATA ATTRIBUTES
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, is_unique, enum_values, description, display_order)
VALUES
-- Foreign Keys
('VTD00001-0000-4000-8000-000000000001','VTD00000-0000-4000-8000-000000000001','task_instance_id','Task Instance ID','text',1,0,0,NULL,'Reference to TASK_INSTANCE',1),
('VTD00002-0000-4000-8000-000000000001','VTD00000-0000-4000-8000-000000000001','vacancy_id','Vacancy ID','text',1,0,0,NULL,'Reference to ORGANIZATION_VACANCY',2),
('VTD00003-0000-4000-8000-000000000001','VTD00000-0000-4000-8000-000000000001','flow_instance_id','Flow Instance ID','text',1,0,0,NULL,'Reference to TASK_FLOW_INSTANCE',3),

-- Task Identification
('VTD00004-0000-4000-8000-000000000001','VTD00000-0000-4000-8000-000000000001','task_type','Task Type','enum_strings',1,1,0,NULL,'Type of task (DRAFT, HR_REVIEW, FINANCE_APPROVAL, etc.)',4),
('VTD00005-0000-4000-8000-000000000001','VTD00000-0000-4000-8000-000000000001','task_type_code','Task Type Code','text',1,0,0,NULL,'Standardized task code',5),

-- Task Content
('VTD00006-0000-4000-8000-000000000001','VTD00000-0000-4000-8000-000000000001','task_comments','Task Comments','text',0,0,0,NULL,'Detailed comments for this task',6),
('VTD00007-0000-4000-8000-000000000001','VTD00000-0000-4000-8000-000000000001','attachment_urls','Attachment URLs','text',0,0,0,NULL,'JSON array of file URLs',7),
('VTD00008-0000-4000-8000-000000000001','VTD00000-0000-4000-8000-000000000001','decision_details','Decision Details','text',0,0,0,NULL,'JSON object with structured decision data',8),
('VTD00009-0000-4000-8000-000000000001','VTD00000-0000-4000-8000-000000000001','documents_reviewed','Documents Reviewed','text',0,0,0,NULL,'List of documents reviewed',9),

-- Task-Specific Fields
('VTD00010-0000-4000-8000-000000000001','VTD00000-0000-4000-8000-000000000001','action_items','Action Items','text',0,0,0,NULL,'Follow-up items from this step',10),
('VTD00011-0000-4000-8000-000000000001','VTD00000-0000-4000-8000-000000000001','priority_flag','Priority Flag','boolean',0,0,0,NULL,'Whether flagged for escalation',11),
('VTD00012-0000-4000-8000-000000000001','VTD00000-0000-4000-8000-000000000001','time_spent_minutes','Time Spent (Minutes)','integer',0,0,0,NULL,'Actual time spent on this task',12),
('VTD00013-0000-4000-8000-000000000001','VTD00000-0000-4000-8000-000000000001','resource_notes','Resource Notes','text',0,0,0,NULL,'Notes on resource allocation',13),

-- HR Review Specific
('VTD00014-0000-4000-8000-000000000001','VTD00000-0000-4000-8000-000000000001','compliance_checks','Compliance Checks','text',0,0,0,NULL,'JSON array of compliance items checked',14),
('VTD00015-0000-4000-8000-000000000001','VTD00000-0000-4000-8000-000000000001','market_analysis_notes','Market Analysis Notes','text',0,0,0,NULL,'Notes on market salary comparison',15),

-- Finance Specific
('VTD00016-0000-4000-8000-000000000001','VTD00000-0000-4000-8000-000000000001','cost_center','Cost Center','text',0,0,0,NULL,'Cost center code for budget allocation',16),
('VTD00017-0000-4000-8000-000000000001','VTD00000-0000-4000-8000-000000000001','budget_code','Budget Code','text',0,0,0,NULL,'Budget code for tracking',17),
('VTD00018-0000-4000-8000-000000000001','VTD00000-0000-4000-8000-000000000001','budget_allocation','Budget Allocation','number',0,0,0,NULL,'Amount allocated from budget',18),

-- Department Head Specific
('VTD00019-0000-4000-8000-000000000001','VTD00000-0000-4000-8000-000000000001','workstation_assignment','Workstation Assignment','text',0,0,0,NULL,'Assigned workstation details',19),
('VTD00020-0000-4000-8000-000000000001','VTD00000-0000-4000-8000-000000000001','reporting_structure','Reporting Structure','text',0,0,0,NULL,'Who the new hire will report to',20);

-- =========================================
-- 6. VACANCY_PUBLICATION_RECORD Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active)
VALUES (
    'VPR00000-0000-4000-8000-000000000001',
    'VACANCY_PUBLICATION_RECORD',
    'Vacancy Publication Record',
    'Tracks publication of vacancies to various job boards and channels',
    'HIRING',
    'vacancy_publication_record',
    1
);

-- VACANCY_PUBLICATION_RECORD ATTRIBUTES
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_label, is_unique, enum_values, description, display_order)
VALUES
-- Foreign Keys
('VPR00001-0000-4000-8000-000000000001','VPR00000-0000-4000-8000-000000000001','vacancy_id','Vacancy ID','text',1,0,0,NULL,'Reference to ORGANIZATION_VACANCY',1),
('VPR00002-0000-4000-8000-000000000001','VPR00000-0000-4000-8000-000000000001','flow_instance_id','Flow Instance ID','text',1,0,0,NULL,'Reference to TASK_FLOW_INSTANCE',2),
('VPR00003-0000-4000-8000-000000000001','VPR00000-0000-4000-8000-000000000001','task_instance_id','Task Instance ID','text',0,0,0,NULL,'Reference to TASK_INSTANCE (publish task)',3),
('VPR00004-0000-4000-8000-000000000001','VPR00000-0000-4000-8000-000000000001','published_by','Published By','text',1,0,0,NULL,'Reference to PERSON who published',4),

-- Publication Details
('VPR00005-0000-4000-8000-000000000001','VPR00000-0000-4000-8000-000000000001','published_date','Published Date','datetime',1,0,0,NULL,'Date and time when vacancy was published',5),
('VPR00006-0000-4000-8000-000000000001','VPR00000-0000-4000-8000-000000000001','publication_status','Publication Status','enum_objects',1,1,0,NULL,'Status of publication',6),

-- Internal Publication
('VPR00007-0000-4000-8000-000000000001','VPR00000-0000-4000-8000-000000000001','internal_board_published','Internal Board Published','boolean',0,0,0,NULL,'Whether published to internal job board',7),
('VPR00008-0000-4000-8000-000000000001','VPR00000-0000-4000-8000-000000000001','internal_published_date','Internal Published Date','datetime',0,0,0,NULL,'When published internally',8),
('VPR00009-0000-4000-8000-000000000001','VPR00000-0000-4000-8000-000000000001','internal_url','Internal URL','text',0,0,0,NULL,'URL to internal job posting',9),

-- External Publication
('VPR00010-0000-4000-8000-000000000001','VPR00000-0000-4000-8000-000000000001','external_boards','External Boards','text',0,0,0,NULL,'JSON array of external boards (LinkedIn, Indeed, etc.)',10),
('VPR00011-0000-4000-8000-000000000001','VPR00000-0000-4000-8000-000000000001','external_publish_date','External Publish Date','datetime',0,0,0,NULL,'When published externally',11),
('VPR00012-0000-4000-8000-000000000001','VPR00000-0000-4000-8000-000000000001','posting_id_external','External Posting ID','text',0,0,0,NULL,'Reference number from external job board',12),

-- Social Media
('VPR00013-0000-4000-8000-000000000001','VPR00000-0000-4000-8000-000000000001','social_media_published','Social Media Published','boolean',0,0,0,NULL,'Whether shared on social media',13),
('VPR00014-0000-4000-8000-000000000001','VPR00000-0000-4000-8000-000000000001','social_media_date','Social Media Date','datetime',0,0,0,NULL,'When shared on social media',14),
('VPR00015-0000-4000-8000-000000000001','VPR00000-0000-4000-8000-000000000001','social_platforms','Social Platforms','text',0,0,0,NULL,'JSON array of platforms (LinkedIn, Twitter, etc.)',15),

-- Closure
('VPR00016-0000-4000-8000-000000000001','VPR00000-0000-4000-8000-000000000001','close_date','Close Date','datetime',0,0,0,NULL,'When vacancy was actually closed',16),
('VPR00017-0000-4000-8000-000000000001','VPR00000-0000-4000-8000-000000000001','closed_by','Closed By','text',0,0,0,NULL,'Reference to PERSON who closed',17),
('VPR00018-0000-4000-8000-000000000001','VPR00000-0000-4000-8000-000000000001','closure_reason','Closure Reason','enum_strings',0,0,0,NULL,'Reason for closure (FILLED, CANCELLED, etc.)',18),

-- Analytics
('VPR00019-0000-4000-8000-000000000001','VPR00000-0000-4000-8000-000000000001','applications_count','Applications Count','integer',0,0,0,NULL,'Total applications received',19),
('VPR00020-0000-4000-8000-000000000001','VPR00000-0000-4000-8000-000000000001','selected_candidate_count','Selected Candidates','integer',0,0,0,NULL,'Number of candidates selected',20),
('VPR00021-0000-4000-8000-000000000001','VPR00000-0000-4000-8000-000000000001','views_count','Views Count','integer',0,0,0,NULL,'Number of views on job boards',21),
('VPR00022-0000-4000-8000-000000000001','VPR00000-0000-4000-8000-000000000001','publication_notes','Publication Notes','text',0,0,0,NULL,'Notes about publication',22);

-- =========================================
-- STEP 7: Create Entity Relationships
-- =========================================

-- VACANCY_DRAFT relationships
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description)
VALUES
('VD-rel-001','VD000000-0000-4000-8000-000000000001','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','ManyToOne','vacancy','vacancy_id','Draft belongs to vacancy'),
('VD-rel-002','VD000000-0000-4000-8000-000000000001','10000000-0000-4000-8000-000000000005','ManyToOne','flow_instance','flow_instance_id','Draft linked to flow'),
('VD-rel-003','VD000000-0000-4000-8000-000000000001','1a2b3c4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d','ManyToOne','organization','organization_id','Draft belongs to organization'),
('VD-rel-004','VD000000-0000-4000-8000-000000000001','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','ManyToOne','creator','created_by','Draft created by person');

-- VACANCY_APPROVAL_RECORD relationships
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description)
VALUES
('VAR-rel-001','VAR00000-0000-4000-8000-000000000001','10000000-0000-4000-8000-000000000005','ManyToOne','flow_instance','flow_instance_id','Approval part of flow'),
('VAR-rel-002','VAR00000-0000-4000-8000-000000000001','10000000-0000-4000-8000-000000000006','ManyToOne','task_instance','task_instance_id','Approval from task'),
('VAR-rel-003','VAR00000-0000-4000-8000-000000000001','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','ManyToOne','vacancy','vacancy_id','Approval for vacancy'),
('VAR-rel-004','VAR00000-0000-4000-8000-000000000001','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','ManyToOne','approver','approved_by','Approved by person');

-- VACANCY_REJECTION_REASON relationships
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description)
VALUES
('VRR-rel-001','VRR00000-0000-4000-8000-000000000001','10000000-0000-4000-8000-000000000005','ManyToOne','flow_instance','flow_instance_id','Rejection in flow'),
('VRR-rel-002','VRR00000-0000-4000-8000-000000000001','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','ManyToOne','vacancy','vacancy_id','Rejection for vacancy'),
('VRR-rel-003','VRR00000-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','ManyToOne','draft','draft_id','Rejection of draft'),
('VRR-rel-004','VRR00000-0000-4000-8000-000000000001','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','ManyToOne','rejector','rejected_by','Rejected by person');

-- VACANCY_REVISION_HISTORY relationships
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description)
VALUES
('VRH-rel-001','VRH00000-0000-4000-8000-000000000001','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','ManyToOne','vacancy','vacancy_id','Revision to vacancy'),
('VRH-rel-002','VRH00000-0000-4000-8000-000000000001','10000000-0000-4000-8000-000000000005','ManyToOne','flow_instance','flow_instance_id','Revision in flow'),
('VRH-rel-003','VRH00000-0000-4000-8000-000000000001','VD000000-0000-4000-8000-000000000001','ManyToOne','draft','draft_id','Revision to draft'),
('VRH-rel-004','VRH00000-0000-4000-8000-000000000001','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','ManyToOne','changer','changed_by','Changed by person');

-- VACANCY_TASK_DATA relationships
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description)
VALUES
('VTD-rel-001','VTD00000-0000-4000-8000-000000000001','10000000-0000-4000-8000-000000000006','ManyToOne','task_instance','task_instance_id','Data for task'),
('VTD-rel-002','VTD00000-0000-4000-8000-000000000001','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','ManyToOne','vacancy','vacancy_id','Task data for vacancy'),
('VTD-rel-003','VTD00000-0000-4000-8000-000000000001','10000000-0000-4000-8000-000000000005','ManyToOne','flow_instance','flow_instance_id','Task data in flow');

-- VACANCY_PUBLICATION_RECORD relationships
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description)
VALUES
('VPR-rel-001','VPR00000-0000-4000-8000-000000000001','5a6b7c8d-9e0f-1a2b-3c4d-5e6f7a8b9c0d','ManyToOne','vacancy','vacancy_id','Publication of vacancy'),
('VPR-rel-002','VPR00000-0000-4000-8000-000000000001','10000000-0000-4000-8000-000000000005','ManyToOne','flow_instance','flow_instance_id','Publication in flow'),
('VPR-rel-003','VPR00000-0000-4000-8000-000000000001','2d6fcb36-5c93-4e4d-b4b3-3c145aa093e3','ManyToOne','publisher','published_by','Published by person');

-- =========================================
-- STEP 8: Update Enum Values
-- =========================================

-- VACANCY_DRAFT enum values
UPDATE entity_attribute SET enum_values = '[{"value":"draft","label":"Draft"},{"value":"submitted","label":"Submitted"},{"value":"under_review","label":"Under Review"},{"value":"approved","label":"Approved"},{"value":"rejected","label":"Rejected"}]'
WHERE id = 'VD000007-0000-4000-8000-000000000001';

UPDATE entity_attribute SET enum_values = '["Full-time","Part-time","Contract","Temporary","Internship","Freelance"]'
WHERE id = 'VD000018-0000-4000-8000-000000000001';

-- VACANCY_APPROVAL_RECORD enum values
UPDATE entity_attribute SET enum_values = '[{"value":"approved","label":"Approved"},{"value":"rejected","label":"Rejected"},{"value":"needs_revision","label":"Needs Revision"},{"value":"conditional_approval","label":"Conditional Approval"}]'
WHERE id = 'VAR00007-0000-4000-8000-000000000001';

UPDATE entity_attribute SET enum_values = '["Manager","Director","Executive","VP","C-Level"]'
WHERE id = 'VAR00015-0000-4000-8000-000000000001';

-- VACANCY_REJECTION_REASON enum values
UPDATE entity_attribute SET enum_values = '[{"value":"compliance_issue","label":"Compliance Issue"},{"value":"salary_concerns","label":"Salary Concerns"},{"value":"missing_info","label":"Missing Information"},{"value":"market_analysis","label":"Market Analysis Required"},{"value":"budget_constraint","label":"Budget Constraint"},{"value":"role_clarity","label":"Role Clarity Needed"},{"value":"other","label":"Other"}]'
WHERE id = 'VRR00007-0000-4000-8000-000000000001';

UPDATE entity_attribute SET enum_values = '["Content","Budget","Compliance","Strategic"]'
WHERE id = 'VRR00008-0000-4000-8000-000000000001';

-- VACANCY_REVISION_HISTORY enum values
UPDATE entity_attribute SET enum_values = '["text","number","date","enum","boolean"]'
WHERE id = 'VRH00008-0000-4000-8000-000000000001';

UPDATE entity_attribute SET enum_values = '["CREATE","UPDATE","DELETE"]'
WHERE id = 'VRH00013-0000-4000-8000-000000000001';

-- VACANCY_TASK_DATA enum values
UPDATE entity_attribute SET enum_values = '["DRAFT_VACANCY","HR_REVIEW","FINANCE_APPROVAL","DEPT_HEAD_APPROVAL","PUBLISH_VACANCY"]'
WHERE id = 'VTD00004-0000-4000-8000-000000000001';

-- VACANCY_PUBLICATION_RECORD enum values
UPDATE entity_attribute SET enum_values = '[{"value":"draft","label":"Draft"},{"value":"scheduled","label":"Scheduled"},{"value":"published","label":"Published"},{"value":"archived","label":"Archived"}]'
WHERE id = 'VPR00006-0000-4000-8000-000000000001';

UPDATE entity_attribute SET enum_values = '["FILLED","CANCELLED","EXPIRED","ON_HOLD"]'
WHERE id = 'VPR00018-0000-4000-8000-000000000001';

-- =========================================
-- STEP 9: Validation Rules
-- =========================================

-- VACANCY_DRAFT validation rules
INSERT OR IGNORE INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity)
VALUES
('VD-valid-001','VD000000-0000-4000-8000-000000000001','VD000009-0000-4000-8000-000000000001','title_required','title_draft != ""','Title is required in draft.','error'),
('VD-valid-002','VD000000-0000-4000-8000-000000000001','VD000013-0000-4000-8000-000000000001','number_positive','number_of_openings_draft > 0','Number of openings must be positive.','error'),
('VD-valid-003','VD000000-0000-4000-8000-000000000001','VD000017-0000-4000-8000-000000000001','salary_range','max_salary_draft >= min_salary_draft','Max salary must be >= min salary.','error');

-- VACANCY_APPROVAL_RECORD validation rules
INSERT OR IGNORE INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity)
VALUES
('VAR-valid-001','VAR00000-0000-4000-8000-000000000001','VAR00005-0000-4000-8000-000000000001','approver_required','approved_by != ""','Approver is required.','error'),
('VAR-valid-002','VAR00000-0000-4000-8000-000000000001','VAR00008-0000-4000-8000-000000000001','sequence_positive','approval_sequence > 0','Approval sequence must be positive.','error');

-- VACANCY_REJECTION_REASON validation rules
INSERT OR IGNORE INTO entity_validation_rule (id, entity_id, attribute_id, rule_name, rule_expression, error_message, severity)
VALUES
('VRR-valid-001','VRR00000-0000-4000-8000-000000000001','VRR00007-0000-4000-8000-000000000001','reason_required','rejection_reason != ""','Rejection reason is required.','error'),
('VRR-valid-002','VRR00000-0000-4000-8000-000000000001','VRR00013-0000-4000-8000-000000000001','revision_number_positive','revision_number > 0','Revision number must be positive.','error');

-- =========================================
-- End of script
-- =========================================

SELECT '6 new entities created successfully!' as message,
       'Run this migration after metadata/009-hiring_domain.sql and metadata/010-process_flow_system.sql' as note;
