-- ============================================
-- DATA SEED: Popular Organization Legal Types
-- Legal entity types by country
-- ============================================

-- United States Legal Types
INSERT OR IGNORE INTO popular_organization_legal_type (id, code, name, country_id, description, requires_minimum_capital, minimum_capital_amount, created_at, updated_at)
VALUES
('legal-us-001', 'LLC', 'Limited Liability Company', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 'Flexible business structure with limited liability protection', 0, NULL, datetime('now'), datetime('now')),
('legal-us-002', 'INC', 'Corporation (C-Corp)', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 'Traditional corporation taxed separately from owners', 0, NULL, datetime('now'), datetime('now')),
('legal-us-003', 'S-CORP', 'S Corporation', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 'Corporation with pass-through taxation', 0, NULL, datetime('now'), datetime('now')),
('legal-us-004', 'LLP', 'Limited Liability Partnership', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 'Partnership with limited liability', 0, NULL, datetime('now'), datetime('now')),
('legal-us-005', 'SOLE', 'Sole Proprietorship', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 'Business owned by one person', 0, NULL, datetime('now'), datetime('now')),
('legal-us-006', 'NONPROFIT', 'Non-Profit Corporation', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 'Tax-exempt charitable organization', 0, NULL, datetime('now'), datetime('now'));

-- United Kingdom Legal Types
INSERT OR IGNORE INTO popular_organization_legal_type (id, code, name, country_id, description, requires_minimum_capital, minimum_capital_amount, created_at, updated_at)
VALUES
('legal-uk-001', 'LTD', 'Private Limited Company', 'e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a', 'Limited liability company', 0, NULL, datetime('now'), datetime('now')),
('legal-uk-002', 'PLC', 'Public Limited Company', 'e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a', 'Publicly traded company', 1, 50000, datetime('now'), datetime('now')),
('legal-uk-003', 'LLP', 'Limited Liability Partnership', 'e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a', 'Partnership with limited liability', 0, NULL, datetime('now'), datetime('now')),
('legal-uk-004', 'SOLE', 'Sole Trader', 'e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a', 'Self-employed individual', 0, NULL, datetime('now'), datetime('now')),
('legal-uk-005', 'CIC', 'Community Interest Company', 'e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a', 'Social enterprise with community focus', 0, NULL, datetime('now'), datetime('now'));

-- India Legal Types
INSERT OR IGNORE INTO popular_organization_legal_type (id, code, name, country_id, description, requires_minimum_capital, minimum_capital_amount, created_at, updated_at)
VALUES
('legal-in-001', 'PVT_LTD', 'Private Limited Company', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Private company limited by shares', 1, 100000, datetime('now'), datetime('now')),
('legal-in-002', 'LTD', 'Public Limited Company', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Public company limited by shares', 1, 500000, datetime('now'), datetime('now')),
('legal-in-003', 'LLP', 'Limited Liability Partnership', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Partnership with limited liability', 0, NULL, datetime('now'), datetime('now')),
('legal-in-004', 'OPC', 'One Person Company', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Company with single shareholder', 1, 100000, datetime('now'), datetime('now')),
('legal-in-005', 'PARTNER', 'Partnership Firm', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Traditional partnership', 0, NULL, datetime('now'), datetime('now')),
('legal-in-006', 'SOLE', 'Sole Proprietorship', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Single owner business', 0, NULL, datetime('now'), datetime('now'));

-- Germany Legal Types
INSERT OR IGNORE INTO popular_organization_legal_type (id, code, name, country_id, description, requires_minimum_capital, minimum_capital_amount, created_at, updated_at)
VALUES
('legal-de-001', 'GMBH', 'Gesellschaft mit beschränkter Haftung', 'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 'Limited liability company', 1, 25000, datetime('now'), datetime('now')),
('legal-de-002', 'UG', 'Unternehmergesellschaft', 'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 'Entrepreneurial company (mini-GmbH)', 1, 1, datetime('now'), datetime('now')),
('legal-de-003', 'AG', 'Aktiengesellschaft', 'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 'Stock corporation', 1, 50000, datetime('now'), datetime('now')),
('legal-de-004', 'KG', 'Kommanditgesellschaft', 'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 'Limited partnership', 0, NULL, datetime('now'), datetime('now')),
('legal-de-005', 'OHG', 'Offene Handelsgesellschaft', 'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 'General partnership', 0, NULL, datetime('now'), datetime('now'));

-- France Legal Types
INSERT OR IGNORE INTO popular_organization_legal_type (id, code, name, country_id, description, requires_minimum_capital, minimum_capital_amount, created_at, updated_at)
VALUES
('legal-fr-001', 'SARL', 'Société à responsabilité limitée', 'e4f5a6b7-c8d9-40e1-2f3a-4b5c6d7e8f9a', 'Limited liability company', 0, NULL, datetime('now'), datetime('now')),
('legal-fr-002', 'SAS', 'Société par actions simplifiée', 'e4f5a6b7-c8d9-40e1-2f3a-4b5c6d7e8f9a', 'Simplified joint-stock company', 0, NULL, datetime('now'), datetime('now')),
('legal-fr-003', 'SA', 'Société Anonyme', 'e4f5a6b7-c8d9-40e1-2f3a-4b5c6d7e8f9a', 'Public limited company', 1, 37000, datetime('now'), datetime('now')),
('legal-fr-004', 'EURL', 'Entreprise unipersonnelle à responsabilité limitée', 'e4f5a6b7-c8d9-40e1-2f3a-4b5c6d7e8f9a', 'Single-person limited liability company', 0, NULL, datetime('now'), datetime('now')),
('legal-fr-005', 'SNC', 'Société en nom collectif', 'e4f5a6b7-c8d9-40e1-2f3a-4b5c6d7e8f9a', 'General partnership', 0, NULL, datetime('now'), datetime('now'));

-- China Legal Types
INSERT OR IGNORE INTO popular_organization_legal_type (id, code, name, country_id, description, requires_minimum_capital, minimum_capital_amount, created_at, updated_at)
VALUES
('legal-cn-001', 'WFOE', 'Wholly Foreign-Owned Enterprise', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Foreign-invested limited liability company', 0, NULL, datetime('now'), datetime('now')),
('legal-cn-002', 'JV', 'Joint Venture', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Sino-foreign joint venture', 0, NULL, datetime('now'), datetime('now')),
('legal-cn-003', 'REP_OFFICE', 'Representative Office', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Foreign company representative office', 0, NULL, datetime('now'), datetime('now')),
('legal-cn-004', 'LLC', 'Limited Liability Company', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Domestic limited liability company', 0, NULL, datetime('now'), datetime('now')),
('legal-cn-005', 'JSC', 'Joint Stock Company', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Company limited by shares', 0, NULL, datetime('now'), datetime('now'));

-- Japan Legal Types
INSERT OR IGNORE INTO popular_organization_legal_type (id, code, name, country_id, description, requires_minimum_capital, minimum_capital_amount, created_at, updated_at)
VALUES
('legal-jp-001', 'KK', 'Kabushiki Kaisha (株式会社)', 'b4a5c6d7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'Joint-stock company', 1, 1, datetime('now'), datetime('now')),
('legal-jp-002', 'GK', 'Godo Kaisha (合同会社)', 'b4a5c6d7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'Limited liability company', 1, 1, datetime('now'), datetime('now')),
('legal-jp-003', 'YK', 'Yugen Kaisha (有限会社)', 'b4a5c6d7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'Limited company (legacy)', 0, NULL, datetime('now'), datetime('now')),
('legal-jp-004', 'GOMEI', 'Gomei Kaisha (合名会社)', 'b4a5c6d7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'General partnership', 0, NULL, datetime('now'), datetime('now'));

-- Canada Legal Types
INSERT OR IGNORE INTO popular_organization_legal_type (id, code, name, country_id, description, requires_minimum_capital, minimum_capital_amount, created_at, updated_at)
VALUES
('legal-ca-001', 'INC', 'Corporation', 'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 'Federal or provincial corporation', 0, NULL, datetime('now'), datetime('now')),
('legal-ca-002', 'LTD', 'Limited Company', 'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 'Limited liability company', 0, NULL, datetime('now'), datetime('now')),
('legal-ca-003', 'LLP', 'Limited Liability Partnership', 'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 'Partnership with limited liability', 0, NULL, datetime('now'), datetime('now')),
('legal-ca-004', 'GP', 'General Partnership', 'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 'Traditional partnership', 0, NULL, datetime('now'), datetime('now')),
('legal-ca-005', 'SOLE', 'Sole Proprietorship', 'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 'Individual business owner', 0, NULL, datetime('now'), datetime('now'));

-- Australia Legal Types
INSERT OR IGNORE INTO popular_organization_legal_type (id, code, name, country_id, description, requires_minimum_capital, minimum_capital_amount, created_at, updated_at)
VALUES
('legal-au-001', 'PTY_LTD', 'Proprietary Limited Company', 'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 'Private company limited by shares', 0, NULL, datetime('now'), datetime('now')),
('legal-au-002', 'LTD', 'Public Company Limited', 'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 'Public company', 0, NULL, datetime('now'), datetime('now')),
('legal-au-003', 'PARTNER', 'Partnership', 'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 'Business partnership', 0, NULL, datetime('now'), datetime('now')),
('legal-au-004', 'SOLE', 'Sole Trader', 'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 'Individual business', 0, NULL, datetime('now'), datetime('now')),
('legal-au-005', 'TRUST', 'Trust', 'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 'Business trust', 0, NULL, datetime('now'), datetime('now'));

-- Singapore Legal Types
INSERT OR IGNORE INTO popular_organization_legal_type (id, code, name, country_id, description, requires_minimum_capital, minimum_capital_amount, created_at, updated_at)
VALUES
('legal-sg-001', 'PTE_LTD', 'Private Limited Company', 'b6a7c8d9-e0f1-42a3-4b5c-7d8e9f0a1b2c', 'Private company limited by shares', 1, 1, datetime('now'), datetime('now')),
('legal-sg-002', 'LTD', 'Public Limited Company', 'b6a7c8d9-e0f1-42a3-4b5c-7d8e9f0a1b2c', 'Public company', 1, 50000, datetime('now'), datetime('now')),
('legal-sg-003', 'LLP', 'Limited Liability Partnership', 'b6a7c8d9-e0f1-42a3-4b5c-7d8e9f0a1b2c', 'Partnership with limited liability', 0, NULL, datetime('now'), datetime('now')),
('legal-sg-004', 'SOLE', 'Sole Proprietorship', 'b6a7c8d9-e0f1-42a3-4b5c-7d8e9f0a1b2c', 'Individual business', 0, NULL, datetime('now'), datetime('now')),
('legal-sg-005', 'BRANCH', 'Branch Office', 'b6a7c8d9-e0f1-42a3-4b5c-7d8e9f0a1b2c', 'Foreign company branch', 0, NULL, datetime('now'), datetime('now'));

