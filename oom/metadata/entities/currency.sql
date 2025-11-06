-- =====================================================================
-- Entity: CURRENCY
-- Domain: GEOGRAPHY
-- Description: Currencies used in countries
-- Generated: 2025-11-06
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Entity Definition
-- =========================================
INSERT OR IGNORE INTO entity_definition (id, code, name, description, domain, table_name, is_active, created_at, updated_at) VALUES
('5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a', 'CURRENCY', 'Currency', 'Currencies used in countries', 'GEOGRAPHY', 'currency', 1, datetime('now'), datetime('now'));

-- =========================================
-- 2. Entity Attributes
-- =========================================
INSERT OR IGNORE INTO entity_attribute (id, entity_id, code, name, data_type, is_required, is_unique, is_label, description, display_order, created_at, updated_at) VALUES
('c7g3d4e5-3333-4b6c-7777-223344556677','5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a','code','Currency Code','text',1,1,1,'ISO4217 code (e.g., INR)',1, datetime('now'), datetime('now')),
('c7g3d4e5-3333-4b6c-7777-223344556678','5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a','name','Currency Name','text',1,0,0,'Currency full name',2, datetime('now'), datetime('now')),
('c7g3d4e5-3333-4b6c-7777-223344556679','5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a','symbol','Currency Symbol','text',0,0,0,'Symbol like ₹, $',3, datetime('now'), datetime('now')),
('c7g3d4e5-3333-4b6c-7777-223344556680','5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a','country_id','Country','text',0,0,0,'FK to Country',4, datetime('now'), datetime('now')),
('c7g3d4e5-3333-4b6c-7777-223344556681','5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a','exchange_rate_usd','Exchange Rate to USD','number',0,0,0,'Reference exchange rate',5, datetime('now'), datetime('now'));

-- =========================================
-- 3. Entity Relationships
-- =========================================
-- CURRENCY belongs to COUNTRY (Many:1)
INSERT OR IGNORE INTO entity_relationship (id, from_entity_id, to_entity_id, relation_type, relation_name, fk_field, description, created_at) VALUES
('rel-0006','2e7b36f6-4b21-4e4a-aaf8-1d3d29c9b4f0','5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a','OneToMany','currencies','country_id','A country can have multiple currencies', datetime('now'));

-- =========================================
-- 4. Entity Functions (Methods)
-- =========================================
INSERT OR IGNORE INTO entity_function (id, entity_id, function_code, function_name, function_description, parameters, return_type, is_system, is_active, created_at, updated_at) VALUES
('func-cur-create','5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a','create','Create Currency','Create new currency','[{"name":"data","type":"json"}]','void',0,1, datetime('now'), datetime('now')),
('func-cur-read','5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a','read','Read Currency','Read currency','[{"name":"id","type":"text"}]','json',0,1, datetime('now'), datetime('now')),
('func-cur-search','5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a','search','Search Currencies','Search currencies','[{"name":"filters","type":"json"}]','json',0,1, datetime('now'), datetime('now')),
('func-cur-convert','5b12c9e4-0a2f-4b4b-8eaa-953d7adf8c2a','get_currency_conversion','Currency Conversion','Convert amount between currencies','[{"name":"amount","type":"number"},{"name":"from_currency","type":"text"},{"name":"to_currency","type":"text"}]','number',1,1, datetime('now'), datetime('now'));

-- =========================================
-- 5. Entity Function Handlers
-- =========================================
INSERT OR IGNORE INTO entity_function_handler (id, function_id, handler_type, handler_reference, is_active, created_at) VALUES
('h-cur-create','func-cur-create','sql','sp_create_currency',1, datetime('now')),
('h-cur-read','func-cur-read','sql','sp_read_currency',1, datetime('now')),
('h-cur-search','func-cur-search','sql','sp_search_currency',1, datetime('now')),
('h-cur-convert','func-cur-convert','api','https://api.exchangerate.example.com/convert',1, datetime('now'));

-- =========================================
-- 6. Entity Validation Rules
-- =========================================
-- No specific validation rules defined for CURRENCY

-- =========================================
-- 7. Entity Data
-- =========================================
-- NOTE: Currency data based on ISO 4217 standard.
-- Exchange rates are reference values and should be updated regularly.

INSERT OR IGNORE INTO currency (id, code, name, symbol, country_id, exchange_rate_usd, created_at, updated_at)
VALUES
-- Major World Currencies
('curr-001', 'USD', 'United States Dollar', '$', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 1.00, datetime('now'), datetime('now')),
('curr-002', 'EUR', 'Euro', '€', NULL, 0.92, datetime('now'), datetime('now')),
('curr-003', 'GBP', 'British Pound Sterling', '£', 'e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a', 0.79, datetime('now'), datetime('now')),
('curr-004', 'JPY', 'Japanese Yen', '¥', NULL, 149.50, datetime('now'), datetime('now')),
('curr-005', 'CNY', 'Chinese Yuan', '¥', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 7.24, datetime('now'), datetime('now')),
('curr-006', 'INR', 'Indian Rupee', '₹', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 83.12, datetime('now'), datetime('now')),

-- Americas
('curr-007', 'CAD', 'Canadian Dollar', '$', 'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 1.36, datetime('now'), datetime('now')),
('curr-008', 'MXN', 'Mexican Peso', '$', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 17.15, datetime('now'), datetime('now')),
('curr-009', 'BRL', 'Brazilian Real', 'R$', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 4.98, datetime('now'), datetime('now')),
('curr-010', 'ARS', 'Argentine Peso', '$', NULL, 350.00, datetime('now'), datetime('now')),

-- Europe
('curr-011', 'CHF', 'Swiss Franc', 'Fr', NULL, 0.88, datetime('now'), datetime('now')),
('curr-012', 'SEK', 'Swedish Krona', 'kr', NULL, 10.62, datetime('now'), datetime('now')),
('curr-013', 'NOK', 'Norwegian Krone', 'kr', NULL, 10.82, datetime('now'), datetime('now')),
('curr-014', 'DKK', 'Danish Krone', 'kr', NULL, 6.87, datetime('now'), datetime('now')),
('curr-015', 'PLN', 'Polish Zloty', 'zł', NULL, 4.02, datetime('now'), datetime('now')),
('curr-016', 'CZK', 'Czech Koruna', 'Kč', NULL, 22.50, datetime('now'), datetime('now')),
('curr-017', 'HUF', 'Hungarian Forint', 'Ft', NULL, 355.00, datetime('now'), datetime('now')),
('curr-018', 'RUB', 'Russian Ruble', '₽', NULL, 91.50, datetime('now'), datetime('now')),
('curr-019', 'TRY', 'Turkish Lira', '₺', NULL, 28.50, datetime('now'), datetime('now')),

-- Asia-Pacific
('curr-020', 'AUD', 'Australian Dollar', '$', 'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 1.53, datetime('now'), datetime('now')),
('curr-021', 'NZD', 'New Zealand Dollar', '$', NULL, 1.65, datetime('now'), datetime('now')),
('curr-022', 'SGD', 'Singapore Dollar', '$', NULL, 1.35, datetime('now'), datetime('now')),
('curr-023', 'HKD', 'Hong Kong Dollar', '$', NULL, 7.82, datetime('now'), datetime('now')),
('curr-024', 'KRW', 'South Korean Won', '₩', NULL, 1310.00, datetime('now'), datetime('now')),
('curr-025', 'THB', 'Thai Baht', '฿', NULL, 35.40, datetime('now'), datetime('now')),
('curr-026', 'MYR', 'Malaysian Ringgit', 'RM', NULL, 4.69, datetime('now'), datetime('now')),
('curr-027', 'IDR', 'Indonesian Rupiah', 'Rp', NULL, 15650.00, datetime('now'), datetime('now')),
('curr-028', 'PHP', 'Philippine Peso', '₱', NULL, 56.20, datetime('now'), datetime('now')),
('curr-029', 'VND', 'Vietnamese Dong', '₫', NULL, 24500.00, datetime('now'), datetime('now')),

-- Middle East & Africa
('curr-030', 'SAR', 'Saudi Riyal', '﷼', NULL, 3.75, datetime('now'), datetime('now')),
('curr-031', 'AED', 'UAE Dirham', 'د.إ', NULL, 3.67, datetime('now'), datetime('now')),
('curr-032', 'ILS', 'Israeli New Shekel', '₪', NULL, 3.70, datetime('now'), datetime('now')),
('curr-033', 'ZAR', 'South African Rand', 'R', NULL, 18.80, datetime('now'), datetime('now')),
('curr-034', 'EGP', 'Egyptian Pound', '£', NULL, 30.90, datetime('now'), datetime('now')),
('curr-035', 'NGN', 'Nigerian Naira', '₦', NULL, 790.00, datetime('now'), datetime('now')),

-- South America
('curr-036', 'CLP', 'Chilean Peso', '$', NULL, 890.00, datetime('now'), datetime('now')),
('curr-037', 'COP', 'Colombian Peso', '$', NULL, 3950.00, datetime('now'), datetime('now')),
('curr-038', 'PEN', 'Peruvian Sol', 'S/', NULL, 3.75, datetime('now'), datetime('now')),

-- Other Major Currencies
('curr-039', 'PKR', 'Pakistani Rupee', '₨', NULL, 280.00, datetime('now'), datetime('now')),
('curr-040', 'BDT', 'Bangladeshi Taka', '৳', NULL, 110.00, datetime('now'), datetime('now')),
('curr-041', 'LKR', 'Sri Lankan Rupee', '₨', NULL, 325.00, datetime('now'), datetime('now')),
('curr-042', 'NPR', 'Nepalese Rupee', '₨', NULL, 133.00, datetime('now'), datetime('now')),
('curr-043', 'TWD', 'Taiwan Dollar', 'NT$', NULL, 31.50, datetime('now'), datetime('now')),

-- Cryptocurrencies (for reference - not tied to countries)
('curr-100', 'BTC', 'Bitcoin', '₿', NULL, 0.000027, datetime('now'), datetime('now')),
('curr-101', 'ETH', 'Ethereum', 'Ξ', NULL, 0.00042, datetime('now'), datetime('now'));

-- =========================================
-- Verification
-- =========================================
SELECT 'CURRENCY entity metadata and sample data installed' AS status;
SELECT COUNT(*) AS currency_count FROM currency;
SELECT 'NOTE: Exchange rates are reference values and should be updated regularly' AS note;
