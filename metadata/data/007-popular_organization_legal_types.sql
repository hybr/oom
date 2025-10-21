-- ============================================
-- DATA SEED: Popular Organization Legal Types
-- Legal entity types by country
-- ============================================
-- United States Legal Types
INSERT
    OR IGNORE INTO popular_organization_legal_type (
        id,
        code,
        name,
        country_id,
        description,
        requires_minimum_capital,
        minimum_capital_amount,
        created_at,
        updated_at
    )
VALUES (
        'l00000000-0001-4000-0000-000010000000',
        'LLC',
        'Limited Liability Company',
        'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b',
        'Flexible business structure with limited liability protection',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0002-4000-0000-000020000000',
        'INC',
        'Corporation (C-Corp)',
        'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b',
        'Traditional corporation taxed separately from owners',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0003-4000-0000-000030000000',
        'S-CORP',
        'S Corporation',
        'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b',
        'Corporation with pass-through taxation',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0004-4000-0000-000040000000',
        'LLP',
        'Limited Liability Partnership',
        'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b',
        'Partnership with limited liability',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0005-4000-0000-000050000000',
        'SOLE',
        'Sole Proprietorship',
        'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b',
        'Business owned by one person',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0006-4000-0000-000060000000',
        'NONPROFIT',
        'Non-Profit Corporation',
        'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b',
        'Tax-exempt charitable organization',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    );
-- United Kingdom Legal Types
INSERT
    OR IGNORE INTO popular_organization_legal_type (
        id,
        code,
        name,
        country_id,
        description,
        requires_minimum_capital,
        minimum_capital_amount,
        created_at,
        updated_at
    )
VALUES (
        'l00000000-0007-4000-0000-000070000000',
        'LTD',
        'Private Limited Company',
        'e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a',
        'Limited liability company',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0008-4000-0000-000080000000',
        'PLC',
        'Public Limited Company',
        'e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a',
        'Publicly traded company',
        1,
        50000,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0009-4000-0000-000090000000',
        'LLP',
        'Limited Liability Partnership',
        'e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a',
        'Partnership with limited liability',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-000a-4000-0000-0000a0000000',
        'SOLE',
        'Sole Trader',
        'e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a',
        'Self-employed individual',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-000b-4000-0000-0000b0000000',
        'CIC',
        'Community Interest Company',
        'e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a',
        'Social enterprise with community focus',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    );
-- India Legal Types
INSERT
    OR IGNORE INTO popular_organization_legal_type (
        id,
        code,
        name,
        country_id,
        description,
        requires_minimum_capital,
        minimum_capital_amount,
        created_at,
        updated_at
    )
VALUES (
        'l00000000-000c-4000-0000-0000c0000000',
        'PVT_LTD',
        'Private Limited Company',
        'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c',
        'Private company limited by shares',
        1,
        100000,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-000d-4000-0000-0000d0000000',
        'LTD',
        'Public Limited Company',
        'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c',
        'Public company limited by shares',
        1,
        500000,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-000e-4000-0000-0000e0000000',
        'LLP',
        'Limited Liability Partnership',
        'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c',
        'Partnership with limited liability',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-000f-4000-0000-0000f0000000',
        'OPC',
        'One Person Company',
        'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c',
        'Company with single shareholder',
        1,
        100000,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0010-4000-0000-000100000000',
        'PARTNER',
        'Partnership Firm',
        'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c',
        'Traditional partnership',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0011-4000-0000-000110000000',
        'SOLE',
        'Sole Proprietorship',
        'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c',
        'Single owner business',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0034-4000-0000-000340000000',
        'SANSTHAN',
        'Sansthan',
        'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c',
        'Trust or educational institution (India)',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0035-4000-0000-000350000000',
        'SOCIETY',
        'Society',
        'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c',
        'Registered Society (India)',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0036-4000-0000-000360000000',
        'TRUST',
        'Trust',
        'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c',
        'Public or Private Trust (India)',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    );
-- Germany Legal Types
INSERT
    OR IGNORE INTO popular_organization_legal_type (
        id,
        code,
        name,
        country_id,
        description,
        requires_minimum_capital,
        minimum_capital_amount,
        created_at,
        updated_at
    )
VALUES (
        'l00000000-0012-4000-0000-000120000000',
        'GMBH',
        'Gesellschaft mit beschränkter Haftung',
        'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a',
        'Limited liability company',
        1,
        25000,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0013-4000-0000-000130000000',
        'UG',
        'Unternehmergesellschaft',
        'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a',
        'Entrepreneurial company (mini-GmbH)',
        1,
        1,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0014-4000-0000-000140000000',
        'AG',
        'Aktiengesellschaft',
        'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a',
        'Stock corporation',
        1,
        50000,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0015-4000-0000-000150000000',
        'KG',
        'Kommanditgesellschaft',
        'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a',
        'Limited partnership',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0016-4000-0000-000160000000',
        'OHG',
        'Offene Handelsgesellschaft',
        'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a',
        'General partnership',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    );
-- France Legal Types
INSERT
    OR IGNORE INTO popular_organization_legal_type (
        id,
        code,
        name,
        country_id,
        description,
        requires_minimum_capital,
        minimum_capital_amount,
        created_at,
        updated_at
    )
VALUES (
        'l00000000-0017-4000-0000-000170000000',
        'SARL',
        'Société à responsabilité limitée',
        'e4f5a6b7-c8d9-40e1-2f3a-4b5c6d7e8f9a',
        'Limited liability company',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0018-4000-0000-000180000000',
        'SAS',
        'Société par actions simplifiée',
        'e4f5a6b7-c8d9-40e1-2f3a-4b5c6d7e8f9a',
        'Simplified joint-stock company',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0019-4000-0000-000190000000',
        'SA',
        'Société Anonyme',
        'e4f5a6b7-c8d9-40e1-2f3a-4b5c6d7e8f9a',
        'Public limited company',
        1,
        37000,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-001a-4000-0000-0001a0000000',
        'EURL',
        'Entreprise unipersonnelle à responsabilité limitée',
        'e4f5a6b7-c8d9-40e1-2f3a-4b5c6d7e8f9a',
        'Single-person limited liability company',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-001b-4000-0000-0001b0000000',
        'SNC',
        'Société en nom collectif',
        'e4f5a6b7-c8d9-40e1-2f3a-4b5c6d7e8f9a',
        'General partnership',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    );
-- China Legal Types
INSERT
    OR IGNORE INTO popular_organization_legal_type (
        id,
        code,
        name,
        country_id,
        description,
        requires_minimum_capital,
        minimum_capital_amount,
        created_at,
        updated_at
    )
VALUES (
        'l00000000-001c-4000-0000-0001c0000000',
        'WFOE',
        'Wholly Foreign-Owned Enterprise',
        'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c',
        'Foreign-invested limited liability company',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-001d-4000-0000-0001d0000000',
        'JV',
        'Joint Venture',
        'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c',
        'Sino-foreign joint venture',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-001e-4000-0000-0001e0000000',
        'REP_OFFICE',
        'Representative Office',
        'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c',
        'Foreign company representative office',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-001f-4000-0000-0001f0000000',
        'LLC',
        'Limited Liability Company',
        'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c',
        'Domestic limited liability company',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0020-4000-0000-000200000000',
        'JSC',
        'Joint Stock Company',
        'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c',
        'Company limited by shares',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    );
-- Japan Legal Types
INSERT
    OR IGNORE INTO popular_organization_legal_type (
        id,
        code,
        name,
        country_id,
        description,
        requires_minimum_capital,
        minimum_capital_amount,
        created_at,
        updated_at
    )
VALUES (
        'l00000000-0021-4000-0000-000210000000',
        'KK',
        'Kabushiki Kaisha (株式会社)',
        'b4a5c6d7-e8f9-40a1-2b3c-4d5e6f7a8b9c',
        'Joint-stock company',
        1,
        1,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0022-4000-0000-000220000000',
        'GK',
        'Godo Kaisha (合同会社)',
        'b4a5c6d7-e8f9-40a1-2b3c-4d5e6f7a8b9c',
        'Limited liability company',
        1,
        1,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0023-4000-0000-000230000000',
        'YK',
        'Yugen Kaisha (有限会社)',
        'b4a5c6d7-e8f9-40a1-2b3c-4d5e6f7a8b9c',
        'Limited company (legacy)',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0024-4000-0000-000240000000',
        'GOMEI',
        'Gomei Kaisha (合名会社)',
        'b4a5c6d7-e8f9-40a1-2b3c-4d5e6f7a8b9c',
        'General partnership',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    );
-- Canada Legal Types
INSERT
    OR IGNORE INTO popular_organization_legal_type (
        id,
        code,
        name,
        country_id,
        description,
        requires_minimum_capital,
        minimum_capital_amount,
        created_at,
        updated_at
    )
VALUES (
        'l00000000-0025-4000-0000-000250000000',
        'INC',
        'Corporation',
        'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b',
        'Federal or provincial corporation',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0026-4000-0000-000260000000',
        'LTD',
        'Limited Company',
        'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b',
        'Limited liability company',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0027-4000-0000-000270000000',
        'LLP',
        'Limited Liability Partnership',
        'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b',
        'Partnership with limited liability',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0028-4000-0000-000280000000',
        'GP',
        'General Partnership',
        'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b',
        'Traditional partnership',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0029-4000-0000-000290000000',
        'SOLE',
        'Sole Proprietorship',
        'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b',
        'Individual business owner',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    );
-- Australia Legal Types
INSERT
    OR IGNORE INTO popular_organization_legal_type (
        id,
        code,
        name,
        country_id,
        description,
        requires_minimum_capital,
        minimum_capital_amount,
        created_at,
        updated_at
    )
VALUES (
        'l00000000-002a-4000-0000-0002a0000000',
        'PTY_LTD',
        'Proprietary Limited Company',
        'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b',
        'Private company limited by shares',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-002b-4000-0000-0002b0000000',
        'LTD',
        'Public Company Limited',
        'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b',
        'Public company',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-002c-4000-0000-0002c0000000',
        'PARTNER',
        'Partnership',
        'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b',
        'Business partnership',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-002d-4000-0000-0002d0000000',
        'SOLE',
        'Sole Trader',
        'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b',
        'Individual business',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-002e-4000-0000-0002e0000000',
        'TRUST',
        'Trust',
        'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b',
        'Business trust',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    );
-- Singapore Legal Types
INSERT
    OR IGNORE INTO popular_organization_legal_type (
        id,
        code,
        name,
        country_id,
        description,
        requires_minimum_capital,
        minimum_capital_amount,
        created_at,
        updated_at
    )
VALUES (
        'l00000000-002f-4000-0000-0002f0000000',
        'PTE_LTD',
        'Private Limited Company',
        'b6a7c8d9-e0f1-42a3-4b5c-7d8e9f0a1b2c',
        'Private company limited by shares',
        1,
        1,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0030-4000-0000-000300000000',
        'LTD',
        'Public Limited Company',
        'b6a7c8d9-e0f1-42a3-4b5c-7d8e9f0a1b2c',
        'Public company',
        1,
        50000,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0031-4000-0000-000310000000',
        'LLP',
        'Limited Liability Partnership',
        'b6a7c8d9-e0f1-42a3-4b5c-7d8e9f0a1b2c',
        'Partnership with limited liability',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0032-4000-0000-000320000000',
        'SOLE',
        'Sole Proprietorship',
        'b6a7c8d9-e0f1-42a3-4b5c-7d8e9f0a1b2c',
        'Individual business',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    ),
    (
        'l00000000-0033-4000-0000-000330000000',
        'BRANCH',
        'Branch Office',
        'b6a7c8d9-e0f1-42a3-4b5c-7d8e9f0a1b2c',
        'Foreign company branch',
        0,
        NULL,
        datetime('now'),
        datetime('now')
    );