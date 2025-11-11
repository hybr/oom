-- ============================================
-- DATA SEED: Popular Organization Department Teams
-- Common teams within departments
-- ============================================

-- HR Department Teams
INSERT OR IGNORE INTO popular_organization_department_team (id, code, name, department_id, description, typical_roles, created_at, updated_at)
VALUES
('team-hr-001', 'TALENT_ACQ', 'Talent Acquisition Team', 'dept-101-recruit', 'Team focused on sourcing and hiring talent', 'Recruiters, Sourcing Specialists, Recruiting Coordinators', datetime('now'), datetime('now')),
('team-hr-002', 'ONBOARDING', 'Onboarding Team', 'dept-101-recruit', 'New employee integration and orientation', 'Onboarding Specialists, HR Coordinators', datetime('now'), datetime('now')),
('team-hr-003', 'L_D', 'Learning & Development Team', 'dept-102-training', 'Employee training and skill development', 'Training Managers, L&D Specialists, Instructional Designers', datetime('now'), datetime('now')),
('team-hr-004', 'PAYROLL', 'Payroll Team', 'dept-103-comp', 'Salary processing and administration', 'Payroll Specialists, Compensation Analysts', datetime('now'), datetime('now'));

-- Finance Department Teams
INSERT OR IGNORE INTO popular_organization_department_team (id, code, name, department_id, description, typical_roles, created_at, updated_at)
VALUES
('team-fin-001', 'AP', 'Accounts Payable Team', 'dept-111-accounting', 'Vendor payments and expense management', 'AP Specialists, AP Managers', datetime('now'), datetime('now')),
('team-fin-002', 'AR', 'Accounts Receivable Team', 'dept-111-accounting', 'Customer billing and collections', 'AR Specialists, Collections Analysts', datetime('now'), datetime('now')),
('team-fin-003', 'GL', 'General Ledger Team', 'dept-111-accounting', 'Financial records and reconciliation', 'Staff Accountants, Senior Accountants', datetime('now'), datetime('now')),
('team-fin-004', 'BUDGET', 'Budgeting Team', 'dept-112-fp-a', 'Annual budgeting and forecasting', 'FP&A Analysts, Budget Managers', datetime('now'), datetime('now')),
('team-fin-005', 'REPORTING', 'Financial Reporting Team', 'dept-112-fp-a', 'Financial statements and analysis', 'Financial Analysts, Reporting Managers', datetime('now'), datetime('now'));

-- IT Department Teams
INSERT OR IGNORE INTO popular_organization_department_team (id, code, name, department_id, description, typical_roles, created_at, updated_at)
VALUES
('team-it-001', 'NETWORK', 'Network Team', 'dept-121-infra', 'Network infrastructure management', 'Network Engineers, Network Administrators', datetime('now'), datetime('now')),
('team-it-002', 'CLOUD', 'Cloud Team', 'dept-121-infra', 'Cloud infrastructure and services', 'Cloud Engineers, Cloud Architects', datetime('now'), datetime('now')),
('team-it-003', 'SOC', 'Security Operations Center', 'dept-122-security', '24/7 security monitoring', 'Security Analysts, SOC Managers, Incident Responders', datetime('now'), datetime('now')),
('team-it-004', 'APPSEC', 'Application Security Team', 'dept-122-security', 'Application security testing', 'AppSec Engineers, Security Researchers', datetime('now'), datetime('now')),
('team-it-005', 'HELPDESK', 'Helpdesk Team', 'dept-123-support', 'Tier 1 and 2 user support', 'Support Technicians, Desktop Support', datetime('now'), datetime('now')),
('team-it-006', 'SRE', 'Site Reliability Engineering', 'dept-124-it-ops', 'System reliability and uptime', 'SRE Engineers, Operations Engineers', datetime('now'), datetime('now'));

-- Engineering Department Teams
INSERT OR IGNORE INTO popular_organization_department_team (id, code, name, department_id, description, typical_roles, created_at, updated_at)
VALUES
('team-eng-001', 'WEB', 'Web Development Team', 'dept-131-frontend', 'Web application development', 'Frontend Developers, UI Engineers', datetime('now'), datetime('now')),
('team-eng-002', 'MOBILE', 'Mobile Development Team', 'dept-131-frontend', 'iOS and Android development', 'iOS Developers, Android Developers', datetime('now'), datetime('now')),
('team-eng-003', 'API', 'API Team', 'dept-132-backend', 'Backend API development', 'Backend Engineers, API Developers', datetime('now'), datetime('now')),
('team-eng-004', 'DATABASE', 'Database Team', 'dept-132-backend', 'Database design and optimization', 'Database Engineers, DBA', datetime('now'), datetime('now')),
('team-eng-005', 'CICD', 'CI/CD Team', 'dept-133-devops', 'Build and deployment pipelines', 'DevOps Engineers, Release Engineers', datetime('now'), datetime('now')),
('team-eng-006', 'MONITORING', 'Monitoring Team', 'dept-133-devops', 'Application and infrastructure monitoring', 'DevOps Engineers, Monitoring Specialists', datetime('now'), datetime('now')),
('team-eng-007', 'TOOLS', 'Internal Tools Team', 'dept-134-platform', 'Developer productivity tools', 'Platform Engineers, Tool Developers', datetime('now'), datetime('now')),
('team-eng-008', 'ETL', 'ETL Team', 'dept-135-data', 'Data extraction and transformation', 'Data Engineers, ETL Developers', datetime('now'), datetime('now')),
('team-eng-009', 'DATA_INFRA', 'Data Infrastructure Team', 'dept-135-data', 'Data warehousing and lakes', 'Data Platform Engineers, Data Architects', datetime('now'), datetime('now'));

-- Marketing Department Teams
INSERT OR IGNORE INTO popular_organization_department_team (id, code, name, department_id, description, typical_roles, created_at, updated_at)
VALUES
('team-mkt-001', 'SEO', 'SEO Team', 'dept-141-digital', 'Search engine optimization', 'SEO Specialists, SEO Managers', datetime('now'), datetime('now')),
('team-mkt-002', 'SEM', 'SEM Team', 'dept-141-digital', 'Paid search advertising', 'SEM Specialists, PPC Managers', datetime('now'), datetime('now')),
('team-mkt-003', 'SOCIAL', 'Social Media Team', 'dept-141-digital', 'Social media marketing', 'Social Media Managers, Community Managers', datetime('now'), datetime('now')),
('team-mkt-004', 'EMAIL', 'Email Marketing Team', 'dept-141-digital', 'Email campaigns and automation', 'Email Marketing Specialists, Marketing Automation', datetime('now'), datetime('now')),
('team-mkt-005', 'CONTENT_CREATE', 'Content Creation Team', 'dept-142-content', 'Content writing and production', 'Content Writers, Copywriters, Video Producers', datetime('now'), datetime('now')),
('team-mkt-006', 'DESIGN', 'Creative Design Team', 'dept-143-brand', 'Visual design and branding', 'Graphic Designers, Brand Designers, Art Directors', datetime('now'), datetime('now'));

-- Sales Department Teams
INSERT OR IGNORE INTO popular_organization_department_team (id, code, name, department_id, description, typical_roles, created_at, updated_at)
VALUES
('team-sales-001', 'SDR', 'Sales Development Representatives', 'dept-151-inside', 'Outbound lead generation', 'SDRs, BDRs', datetime('now'), datetime('now')),
('team-sales-002', 'AE', 'Account Executives', 'dept-151-inside', 'Deal closing and account management', 'Account Executives, Sales Representatives', datetime('now'), datetime('now')),
('team-sales-003', 'AM', 'Account Management Team', 'dept-152-field', 'Existing customer growth', 'Account Managers, Customer Success Managers', datetime('now'), datetime('now')),
('team-sales-004', 'STRATEGIC', 'Strategic Accounts Team', 'dept-153-enterprise', 'High-value enterprise accounts', 'Strategic Account Executives, Enterprise AEs', datetime('now'), datetime('now')),
('team-sales-005', 'SALES_ENABLE', 'Sales Enablement Team', 'dept-154-sales-ops', 'Sales training and content', 'Sales Enablement Managers, Sales Trainers', datetime('now'), datetime('now')),
('team-sales-006', 'SALES_ANALYTICS', 'Sales Analytics Team', 'dept-154-sales-ops', 'Sales data and reporting', 'Sales Analysts, Sales Operations Analysts', datetime('now'), datetime('now'));

-- Customer Service Department Teams
INSERT OR IGNORE INTO popular_organization_department_team (id, code, name, department_id, description, typical_roles, created_at, updated_at)
VALUES
('team-cs-001', 'TIER1', 'Tier 1 Support Team', 'dept-010-cs', 'First-line customer support', 'Support Representatives, Customer Service Agents', datetime('now'), datetime('now')),
('team-cs-002', 'TIER2', 'Tier 2 Support Team', 'dept-010-cs', 'Advanced technical support', 'Senior Support Engineers, Technical Support Specialists', datetime('now'), datetime('now')),
('team-cs-003', 'CS_SUCCESS', 'Customer Success Team', 'dept-010-cs', 'Customer retention and growth', 'Customer Success Managers, CSMs', datetime('now'), datetime('now'));

-- Product Management Teams
INSERT OR IGNORE INTO popular_organization_department_team (id, code, name, department_id, description, typical_roles, created_at, updated_at)
VALUES
('team-prod-001', 'CORE_PROD', 'Core Product Team', 'dept-008-product', 'Main product features and roadmap', 'Product Managers, Senior PMs', datetime('now'), datetime('now')),
('team-prod-002', 'GROWTH', 'Growth Team', 'dept-008-product', 'Product growth and optimization', 'Growth PMs, Growth Analysts', datetime('now'), datetime('now')),
('team-prod-003', 'UX', 'UX Research Team', 'dept-008-product', 'User experience research', 'UX Researchers, User Researchers', datetime('now'), datetime('now')),
('team-prod-004', 'DESIGN_SYS', 'Design System Team', 'dept-008-product', 'Design system and components', 'Product Designers, UI/UX Designers', datetime('now'), datetime('now'));

-- Quality Assurance Teams
INSERT OR IGNORE INTO popular_organization_department_team (id, code, name, department_id, description, typical_roles, created_at, updated_at)
VALUES
('team-qa-001', 'MANUAL_QA', 'Manual QA Team', 'dept-014-quality', 'Manual testing and validation', 'QA Testers, QA Engineers', datetime('now'), datetime('now')),
('team-qa-002', 'AUTO_QA', 'Automation QA Team', 'dept-014-quality', 'Test automation', 'Automation Engineers, SDET', datetime('now'), datetime('now')),
('team-qa-003', 'PERF', 'Performance Testing Team', 'dept-014-quality', 'Load and performance testing', 'Performance Test Engineers', datetime('now'), datetime('now'));

-- Operations Department Teams
INSERT OR IGNORE INTO popular_organization_department_team (id, code, name, department_id, description, typical_roles, created_at, updated_at)
VALUES
('team-ops-001', 'PROCESS', 'Process Improvement Team', 'dept-007-ops', 'Business process optimization', 'Business Analysts, Process Engineers', datetime('now'), datetime('now')),
('team-ops-002', 'SUPPLY_CHAIN', 'Supply Chain Team', 'dept-007-ops', 'Supply chain management', 'Supply Chain Analysts, Logistics Coordinators', datetime('now'), datetime('now')),
('team-ops-003', 'FACILITIES', 'Facilities Team', 'dept-007-ops', 'Office and facility management', 'Facilities Managers, Office Managers', datetime('now'), datetime('now'));

