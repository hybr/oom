-- ============================================
-- DATA SEED: Popular Industry Categories
-- Hierarchical classification of industries
-- ============================================

-- Level 1: Top-level categories
INSERT OR IGNORE INTO popular_industry_category (id, code, name, parent_category_id, level, description, created_at, updated_at)
VALUES
-- Primary Industries
('ind-001-tech', 'TECH', 'Technology', NULL, 1, 'Technology and IT related industries', datetime('now'), datetime('now')),
('ind-002-finance', 'FINANCE', 'Finance & Banking', NULL, 1, 'Financial services and banking', datetime('now'), datetime('now')),
('ind-003-healthcare', 'HEALTHCARE', 'Healthcare & Medical', NULL, 1, 'Healthcare, medical, and pharmaceutical', datetime('now'), datetime('now')),
('ind-004-retail', 'RETAIL', 'Retail & E-commerce', NULL, 1, 'Retail and online commerce', datetime('now'), datetime('now')),
('ind-005-manufacturing', 'MANUFACTURING', 'Manufacturing', NULL, 1, 'Manufacturing and production', datetime('now'), datetime('now')),
('ind-006-education', 'EDUCATION', 'Education & Training', NULL, 1, 'Educational institutions and training', datetime('now'), datetime('now')),
('ind-007-hospitality', 'HOSPITALITY', 'Hospitality & Tourism', NULL, 1, 'Hotels, restaurants, and tourism', datetime('now'), datetime('now')),
('ind-008-construction', 'CONSTRUCTION', 'Construction & Real Estate', NULL, 1, 'Construction and property development', datetime('now'), datetime('now')),
('ind-009-transport', 'TRANSPORT', 'Transportation & Logistics', NULL, 1, 'Transportation and supply chain', datetime('now'), datetime('now')),
('ind-010-media', 'MEDIA', 'Media & Entertainment', NULL, 1, 'Media, entertainment, and publishing', datetime('now'), datetime('now')),
('ind-011-energy', 'ENERGY', 'Energy & Utilities', NULL, 1, 'Energy, utilities, and resources', datetime('now'), datetime('now')),
('ind-012-telecom', 'TELECOM', 'Telecommunications', NULL, 1, 'Telecommunications and network services', datetime('now'), datetime('now')),
('ind-013-agriculture', 'AGRICULTURE', 'Agriculture & Food', NULL, 1, 'Agriculture and food production', datetime('now'), datetime('now')),
('ind-014-professional', 'PROFESSIONAL', 'Professional Services', NULL, 1, 'Consulting, legal, and professional services', datetime('now'), datetime('now'));

-- Level 2: Technology subcategories
INSERT OR IGNORE INTO popular_industry_category (id, code, name, parent_category_id, level, description, created_at, updated_at)
VALUES
('ind-101-software', 'SOFTWARE', 'Software & Applications', 'ind-001-tech', 2, 'Software development and applications', datetime('now'), datetime('now')),
('ind-102-hardware', 'HARDWARE', 'Hardware & Electronics', 'ind-001-tech', 2, 'Computer hardware and electronics', datetime('now'), datetime('now')),
('ind-103-cloud', 'CLOUD', 'Cloud Services', 'ind-001-tech', 2, 'Cloud computing and hosting services', datetime('now'), datetime('now')),
('ind-104-cybersec', 'CYBERSEC', 'Cybersecurity', 'ind-001-tech', 2, 'Information security and protection', datetime('now'), datetime('now')),
('ind-105-ai', 'AI_ML', 'AI & Machine Learning', 'ind-001-tech', 2, 'Artificial intelligence and ML solutions', datetime('now'), datetime('now')),
('ind-106-iot', 'IOT', 'Internet of Things', 'ind-001-tech', 2, 'IoT devices and solutions', datetime('now'), datetime('now'));

-- Level 3: Software subcategories
INSERT OR IGNORE INTO popular_industry_category (id, code, name, parent_category_id, level, description, created_at, updated_at)
VALUES
('ind-201-saas', 'SAAS', 'SaaS', 'ind-101-software', 3, 'Software as a Service platforms', datetime('now'), datetime('now')),
('ind-202-enterprise', 'ENTERPRISE', 'Enterprise Software', 'ind-101-software', 3, 'Enterprise resource planning and management', datetime('now'), datetime('now')),
('ind-203-mobile', 'MOBILE_APP', 'Mobile Applications', 'ind-101-software', 3, 'Mobile app development', datetime('now'), datetime('now')),
('ind-204-game', 'GAMING', 'Gaming & Entertainment Software', 'ind-101-software', 3, 'Video games and entertainment software', datetime('now'), datetime('now'));

-- Level 2: Finance subcategories
INSERT OR IGNORE INTO popular_industry_category (id, code, name, parent_category_id, level, description, created_at, updated_at)
VALUES
('ind-111-banking', 'BANKING', 'Banking Services', 'ind-002-finance', 2, 'Commercial and retail banking', datetime('now'), datetime('now')),
('ind-112-investment', 'INVESTMENT', 'Investment & Trading', 'ind-002-finance', 2, 'Investment banking and trading', datetime('now'), datetime('now')),
('ind-113-insurance', 'INSURANCE', 'Insurance', 'ind-002-finance', 2, 'Insurance services', datetime('now'), datetime('now')),
('ind-114-fintech', 'FINTECH', 'Financial Technology', 'ind-002-finance', 2, 'Digital financial services', datetime('now'), datetime('now')),
('ind-115-accounting', 'ACCOUNTING', 'Accounting Services', 'ind-002-finance', 2, 'Accounting and auditing', datetime('now'), datetime('now'));

-- Level 2: Healthcare subcategories
INSERT OR IGNORE INTO popular_industry_category (id, code, name, parent_category_id, level, description, created_at, updated_at)
VALUES
('ind-121-hospitals', 'HOSPITALS', 'Hospitals & Clinics', 'ind-003-healthcare', 2, 'Healthcare facilities', datetime('now'), datetime('now')),
('ind-122-pharma', 'PHARMA', 'Pharmaceuticals', 'ind-003-healthcare', 2, 'Pharmaceutical manufacturing', datetime('now'), datetime('now')),
('ind-123-medtech', 'MEDTECH', 'Medical Technology', 'ind-003-healthcare', 2, 'Medical devices and technology', datetime('now'), datetime('now')),
('ind-124-biotech', 'BIOTECH', 'Biotechnology', 'ind-003-healthcare', 2, 'Biotechnology and life sciences', datetime('now'), datetime('now'));

-- Level 2: Retail subcategories
INSERT OR IGNORE INTO popular_industry_category (id, code, name, parent_category_id, level, description, created_at, updated_at)
VALUES
('ind-131-ecommerce', 'ECOMMERCE', 'E-commerce', 'ind-004-retail', 2, 'Online retail platforms', datetime('now'), datetime('now')),
('ind-132-fashion', 'FASHION', 'Fashion & Apparel', 'ind-004-retail', 2, 'Clothing and fashion retail', datetime('now'), datetime('now')),
('ind-133-grocery', 'GROCERY', 'Grocery & Food Retail', 'ind-004-retail', 2, 'Grocery stores and supermarkets', datetime('now'), datetime('now')),
('ind-134-specialty', 'SPECIALTY', 'Specialty Retail', 'ind-004-retail', 2, 'Specialized retail stores', datetime('now'), datetime('now'));

-- Level 2: Manufacturing subcategories
INSERT OR IGNORE INTO popular_industry_category (id, code, name, parent_category_id, level, description, created_at, updated_at)
VALUES
('ind-141-automotive', 'AUTOMOTIVE', 'Automotive', 'ind-005-manufacturing', 2, 'Automobile manufacturing', datetime('now'), datetime('now')),
('ind-142-aerospace', 'AEROSPACE', 'Aerospace & Defense', 'ind-005-manufacturing', 2, 'Aircraft and defense manufacturing', datetime('now'), datetime('now')),
('ind-143-chemical', 'CHEMICAL', 'Chemicals', 'ind-005-manufacturing', 2, 'Chemical manufacturing', datetime('now'), datetime('now')),
('ind-144-consumer', 'CONSUMER_GOODS', 'Consumer Goods', 'ind-005-manufacturing', 2, 'Consumer products manufacturing', datetime('now'), datetime('now'));

