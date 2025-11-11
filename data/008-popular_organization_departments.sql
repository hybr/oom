-- ============================================
-- DATA SEED: Popular Organization Departments
-- Common organizational departments
-- ============================================

-- Top-level departments
INSERT OR IGNORE INTO popular_organization_department (id, code, name, description, parent_department_id, typical_responsibilities, created_at, updated_at)
VALUES
-- Core Business Departments
('dept-001-exec', 'EXEC', 'Executive Leadership', 'Top-level management and strategic direction', NULL, 'Strategic planning, corporate governance, stakeholder management', datetime('now'), datetime('now')),
('dept-002-hr', 'HR', 'Human Resources', 'Employee management and workplace culture', NULL, 'Recruitment, employee relations, compensation, training and development', datetime('now'), datetime('now')),
('dept-003-finance', 'FINANCE', 'Finance & Accounting', 'Financial management and reporting', NULL, 'Financial planning, accounting, budgeting, auditing, tax compliance', datetime('now'), datetime('now')),
('dept-004-it', 'IT', 'Information Technology', 'Technology infrastructure and systems', NULL, 'System administration, network management, technical support, cybersecurity', datetime('now'), datetime('now')),
('dept-005-sales', 'SALES', 'Sales', 'Revenue generation and customer acquisition', NULL, 'Lead generation, deal closing, account management, sales strategy', datetime('now'), datetime('now')),
('dept-006-marketing', 'MARKETING', 'Marketing', 'Brand promotion and market presence', NULL, 'Brand management, advertising, digital marketing, market research', datetime('now'), datetime('now')),
('dept-007-ops', 'OPERATIONS', 'Operations', 'Business operations and process management', NULL, 'Process optimization, quality control, supply chain, facility management', datetime('now'), datetime('now')),
('dept-008-product', 'PRODUCT', 'Product Management', 'Product strategy and development', NULL, 'Product roadmap, feature prioritization, market analysis, product launch', datetime('now'), datetime('now')),
('dept-009-eng', 'ENGINEERING', 'Engineering', 'Software and technical development', NULL, 'Software development, architecture, code quality, technical innovation', datetime('now'), datetime('now')),
('dept-010-cs', 'CUSTOMER_SERVICE', 'Customer Service', 'Customer support and satisfaction', NULL, 'Customer inquiries, issue resolution, support tickets, customer satisfaction', datetime('now'), datetime('now')),
('dept-011-legal', 'LEGAL', 'Legal & Compliance', 'Legal affairs and regulatory compliance', NULL, 'Contract review, legal counsel, regulatory compliance, risk management', datetime('now'), datetime('now')),
('dept-012-rd', 'RND', 'Research & Development', 'Innovation and new product development', NULL, 'Innovation, prototyping, testing, research, patent development', datetime('now'), datetime('now')),
('dept-013-admin', 'ADMIN', 'Administration', 'General administrative functions', NULL, 'Office management, administrative support, document management', datetime('now'), datetime('now')),
('dept-014-quality', 'QUALITY', 'Quality Assurance', 'Quality control and assurance', NULL, 'Testing, quality standards, process improvement, defect tracking', datetime('now'), datetime('now')),
('dept-015-procurement', 'PROCUREMENT', 'Procurement', 'Purchasing and vendor management', NULL, 'Vendor selection, purchase orders, contract negotiation, supplier relations', datetime('now'), datetime('now'));

-- HR Sub-departments
INSERT OR IGNORE INTO popular_organization_department (id, code, name, description, parent_department_id, typical_responsibilities, created_at, updated_at)
VALUES
('dept-101-recruit', 'RECRUITMENT', 'Recruitment & Talent Acquisition', 'Hiring and onboarding new employees', 'dept-002-hr', 'Job postings, candidate screening, interviews, offer letters, onboarding', datetime('now'), datetime('now')),
('dept-102-training', 'TRAINING', 'Training & Development', 'Employee learning and career growth', 'dept-002-hr', 'Training programs, career development, skill enhancement, workshops', datetime('now'), datetime('now')),
('dept-103-comp', 'COMPENSATION', 'Compensation & Benefits', 'Salary and benefits administration', 'dept-002-hr', 'Salary structure, benefits management, payroll coordination, compensation analysis', datetime('now'), datetime('now')),
('dept-104-hr-ops', 'HR_OPS', 'HR Operations', 'Day-to-day HR administration', 'dept-002-hr', 'HRIS management, employee records, policy administration, compliance', datetime('now'), datetime('now'));

-- Finance Sub-departments
INSERT OR IGNORE INTO popular_organization_department (id, code, name, description, parent_department_id, typical_responsibilities, created_at, updated_at)
VALUES
('dept-111-accounting', 'ACCOUNTING', 'Accounting', 'Financial record keeping and reporting', 'dept-003-finance', 'General ledger, accounts payable/receivable, financial statements, reconciliation', datetime('now'), datetime('now')),
('dept-112-fp-a', 'FP_A', 'Financial Planning & Analysis', 'Budgeting and financial forecasting', 'dept-003-finance', 'Budgeting, forecasting, variance analysis, financial modeling', datetime('now'), datetime('now')),
('dept-113-tax', 'TAX', 'Tax', 'Tax planning and compliance', 'dept-003-finance', 'Tax returns, tax strategy, tax compliance, tax audits', datetime('now'), datetime('now')),
('dept-114-treasury', 'TREASURY', 'Treasury', 'Cash management and investments', 'dept-003-finance', 'Cash flow management, investments, banking relationships, liquidity', datetime('now'), datetime('now'));

-- IT Sub-departments
INSERT OR IGNORE INTO popular_organization_department (id, code, name, description, parent_department_id, typical_responsibilities, created_at, updated_at)
VALUES
('dept-121-infra', 'INFRASTRUCTURE', 'IT Infrastructure', 'Server and network management', 'dept-004-it', 'Server management, network administration, data center operations, cloud infrastructure', datetime('now'), datetime('now')),
('dept-122-security', 'SECURITY', 'IT Security', 'Cybersecurity and information protection', 'dept-004-it', 'Security monitoring, incident response, access control, security policies', datetime('now'), datetime('now')),
('dept-123-support', 'IT_SUPPORT', 'IT Support', 'User support and helpdesk', 'dept-004-it', 'Helpdesk tickets, user support, hardware/software troubleshooting, onboarding support', datetime('now'), datetime('now')),
('dept-124-it-ops', 'IT_OPS', 'IT Operations', 'Day-to-day IT operations', 'dept-004-it', 'System monitoring, backup and recovery, patch management, incident management', datetime('now'), datetime('now'));

-- Engineering Sub-departments
INSERT OR IGNORE INTO popular_organization_department (id, code, name, description, parent_department_id, typical_responsibilities, created_at, updated_at)
VALUES
('dept-131-frontend', 'FRONTEND', 'Frontend Engineering', 'User interface development', 'dept-009-eng', 'UI development, web applications, mobile apps, user experience implementation', datetime('now'), datetime('now')),
('dept-132-backend', 'BACKEND', 'Backend Engineering', 'Server-side development', 'dept-009-eng', 'API development, database design, server logic, system integration', datetime('now'), datetime('now')),
('dept-133-devops', 'DEVOPS', 'DevOps', 'Development operations and automation', 'dept-009-eng', 'CI/CD pipelines, deployment automation, monitoring, infrastructure as code', datetime('now'), datetime('now')),
('dept-134-platform', 'PLATFORM', 'Platform Engineering', 'Core platform and infrastructure', 'dept-009-eng', 'Platform architecture, internal tools, developer experience, scalability', datetime('now'), datetime('now')),
('dept-135-data', 'DATA_ENG', 'Data Engineering', 'Data infrastructure and pipelines', 'dept-009-eng', 'Data pipelines, ETL processes, data warehousing, data quality', datetime('now'), datetime('now'));

-- Marketing Sub-departments
INSERT OR IGNORE INTO popular_organization_department (id, code, name, description, parent_department_id, typical_responsibilities, created_at, updated_at)
VALUES
('dept-141-digital', 'DIGITAL_MARKETING', 'Digital Marketing', 'Online marketing and promotion', 'dept-006-marketing', 'SEO, SEM, social media marketing, email campaigns, digital advertising', datetime('now'), datetime('now')),
('dept-142-content', 'CONTENT', 'Content Marketing', 'Content creation and strategy', 'dept-006-marketing', 'Blog posts, videos, whitepapers, content strategy, storytelling', datetime('now'), datetime('now')),
('dept-143-brand', 'BRAND', 'Brand Management', 'Brand identity and positioning', 'dept-006-marketing', 'Brand strategy, visual identity, brand guidelines, brand awareness', datetime('now'), datetime('now')),
('dept-144-events', 'EVENTS', 'Events & Conferences', 'Event planning and execution', 'dept-006-marketing', 'Conference planning, trade shows, webinars, event marketing, sponsorships', datetime('now'), datetime('now'));

-- Sales Sub-departments
INSERT OR IGNORE INTO popular_organization_department (id, code, name, description, parent_department_id, typical_responsibilities, created_at, updated_at)
VALUES
('dept-151-inside', 'INSIDE_SALES', 'Inside Sales', 'Remote sales and lead qualification', 'dept-005-sales', 'Lead qualification, phone sales, demo scheduling, pipeline management', datetime('now'), datetime('now')),
('dept-152-field', 'FIELD_SALES', 'Field Sales', 'On-site sales and account management', 'dept-005-sales', 'In-person meetings, relationship building, deal closing, territory management', datetime('now'), datetime('now')),
('dept-153-enterprise', 'ENTERPRISE_SALES', 'Enterprise Sales', 'Large account sales', 'dept-005-sales', 'Enterprise deals, strategic accounts, complex sales cycles, executive relationships', datetime('now'), datetime('now')),
('dept-154-sales-ops', 'SALES_OPS', 'Sales Operations', 'Sales process and enablement', 'dept-005-sales', 'CRM management, sales analytics, territory planning, sales tools', datetime('now'), datetime('now'));

