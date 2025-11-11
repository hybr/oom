-- ============================================
-- DATA SEED: States, Provinces, Union Territories, Regions
-- Comprehensive list of administrative divisions for all countries
-- ============================================

-- Note: The STATE entity is used for all first-level administrative divisions:
-- - States (USA, India, Brazil, etc.)
-- - Provinces (Canada, China, Pakistan, etc.)
-- - Union Territories (India)
-- - Regions (France, Italy, etc.)
-- - Oblasts/Krais (Russia)
-- - Länder (Germany, Austria)
-- - Counties (UK, Ireland)
-- - Prefectures (Japan)
-- - And other regional divisions

INSERT OR IGNORE INTO state (id, name, code, country_id, population, area_sq_km, gdp_usd, capital, description, created_at, updated_at)
VALUES

-- ============================================
-- UNITED STATES (50 States + DC + 5 Territories)
-- ============================================
-- Country ID: n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b
('s00000000-0001-4000-0000-000010000000', 'Alabama', 'AL', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 5000000, 135767, NULL, 'Montgomery', 'Heart of Dixie', datetime('now'), datetime('now')),
('s00000000-0002-4000-0000-000020000000', 'Alaska', 'AK', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 730000, 1723337, NULL, 'Juneau', 'Last Frontier', datetime('now'), datetime('now')),
('s00000000-0003-4000-0000-000030000000', 'Arizona', 'AZ', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 7300000, 295234, NULL, 'Phoenix', 'Grand Canyon State', datetime('now'), datetime('now')),
('s00000000-0004-4000-0000-000040000000', 'Arkansas', 'AR', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 3000000, 137732, NULL, 'Little Rock', 'Natural State', datetime('now'), datetime('now')),
('s00000000-0005-4000-0000-000050000000', 'California', 'CA', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 39500000, 423967, NULL, 'Sacramento', 'Golden State', datetime('now'), datetime('now')),
('s00000000-0006-4000-0000-000060000000', 'Colorado', 'CO', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 5800000, 269601, NULL, 'Denver', 'Centennial State', datetime('now'), datetime('now')),
('s00000000-0007-4000-0000-000070000000', 'Connecticut', 'CT', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 3600000, 14357, NULL, 'Hartford', 'Constitution State', datetime('now'), datetime('now')),
('s00000000-0008-4000-0000-000080000000', 'Delaware', 'DE', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 990000, 6446, NULL, 'Dover', 'First State', datetime('now'), datetime('now')),
('s00000000-0009-4000-0000-000090000000', 'Florida', 'FL', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 21500000, 170312, NULL, 'Tallahassee', 'Sunshine State', datetime('now'), datetime('now')),
('s00000000-000a-4000-0000-0000a0000000', 'Georgia', 'GA', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 10700000, 153910, NULL, 'Atlanta', 'Peach State', datetime('now'), datetime('now')),
('s00000000-000b-4000-0000-0000b0000000', 'Hawaii', 'HI', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 1420000, 28313, NULL, 'Honolulu', 'Aloha State', datetime('now'), datetime('now')),
('s00000000-000c-4000-0000-0000c0000000', 'Idaho', 'ID', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 1840000, 216443, NULL, 'Boise', 'Gem State', datetime('now'), datetime('now')),
('s00000000-000d-4000-0000-0000d0000000', 'Illinois', 'IL', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 12700000, 149995, NULL, 'Springfield', 'Prairie State', datetime('now'), datetime('now')),
('s00000000-000e-4000-0000-0000e0000000', 'Indiana', 'IN', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 6800000, 94326, NULL, 'Indianapolis', 'Hoosier State', datetime('now'), datetime('now')),
('s00000000-000f-4000-0000-0000f0000000', 'Iowa', 'IA', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 3190000, 145746, NULL, 'Des Moines', 'Hawkeye State', datetime('now'), datetime('now')),
('s00000000-0010-4000-0000-000100000000', 'Kansas', 'KS', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 2940000, 213100, NULL, 'Topeka', 'Sunflower State', datetime('now'), datetime('now')),
('s00000000-0011-4000-0000-000110000000', 'Kentucky', 'KY', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 4500000, 104656, NULL, 'Frankfort', 'Bluegrass State', datetime('now'), datetime('now')),
('s00000000-0012-4000-0000-000120000000', 'Louisiana', 'LA', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 4650000, 135659, NULL, 'Baton Rouge', 'Pelican State', datetime('now'), datetime('now')),
('s00000000-0013-4000-0000-000130000000', 'Maine', 'ME', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 1360000, 91633, NULL, 'Augusta', 'Pine Tree State', datetime('now'), datetime('now')),
('s00000000-0014-4000-0000-000140000000', 'Maryland', 'MD', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 6200000, 32131, NULL, 'Annapolis', 'Old Line State', datetime('now'), datetime('now')),
('s00000000-0015-4000-0000-000150000000', 'Massachusetts', 'MA', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 7000000, 27336, NULL, 'Boston', 'Bay State', datetime('now'), datetime('now')),
('s00000000-0016-4000-0000-000160000000', 'Michigan', 'MI', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 10000000, 250487, NULL, 'Lansing', 'Great Lakes State', datetime('now'), datetime('now')),
('s00000000-0017-4000-0000-000170000000', 'Minnesota', 'MN', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 5700000, 225163, NULL, 'St. Paul', 'Land of 10,000 Lakes', datetime('now'), datetime('now')),
('s00000000-0018-4000-0000-000180000000', 'Mississippi', 'MS', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 2960000, 125438, NULL, 'Jackson', 'Magnolia State', datetime('now'), datetime('now')),
('s00000000-0019-4000-0000-000190000000', 'Missouri', 'MO', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 6170000, 180540, NULL, 'Jefferson City', 'Show-Me State', datetime('now'), datetime('now')),
('s00000000-001a-4000-0000-0001a0000000', 'Montana', 'MT', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 1080000, 380831, NULL, 'Helena', 'Treasure State', datetime('now'), datetime('now')),
('s00000000-001b-4000-0000-0001b0000000', 'Nebraska', 'NE', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 1960000, 200330, NULL, 'Lincoln', 'Cornhusker State', datetime('now'), datetime('now')),
('s00000000-001c-4000-0000-0001c0000000', 'Nevada', 'NV', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 3100000, 286380, NULL, 'Carson City', 'Silver State', datetime('now'), datetime('now')),
('s00000000-001d-4000-0000-0001d0000000', 'New Hampshire', 'NH', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 1380000, 24214, NULL, 'Concord', 'Granite State', datetime('now'), datetime('now')),
('s00000000-001e-4000-0000-0001e0000000', 'New Jersey', 'NJ', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 9300000, 22591, NULL, 'Trenton', 'Garden State', datetime('now'), datetime('now')),
('s00000000-001f-4000-0000-0001f0000000', 'New Mexico', 'NM', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 2120000, 314917, NULL, 'Santa Fe', 'Land of Enchantment', datetime('now'), datetime('now')),
('s00000000-0020-4000-0000-000200000000', 'New York', 'NY', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 20200000, 141297, NULL, 'Albany', 'Empire State', datetime('now'), datetime('now')),
('s00000000-0021-4000-0000-000210000000', 'North Carolina', 'NC', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 10500000, 139391, NULL, 'Raleigh', 'Tar Heel State', datetime('now'), datetime('now')),
('s00000000-0022-4000-0000-000220000000', 'North Dakota', 'ND', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 780000, 183108, NULL, 'Bismarck', 'Peace Garden State', datetime('now'), datetime('now')),
('s00000000-0023-4000-0000-000230000000', 'Ohio', 'OH', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 11800000, 116098, NULL, 'Columbus', 'Buckeye State', datetime('now'), datetime('now')),
('s00000000-0024-4000-0000-000240000000', 'Oklahoma', 'OK', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 4000000, 181037, NULL, 'Oklahoma City', 'Sooner State', datetime('now'), datetime('now')),
('s00000000-0025-4000-0000-000250000000', 'Oregon', 'OR', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 4240000, 254799, NULL, 'Salem', 'Beaver State', datetime('now'), datetime('now')),
('s00000000-0026-4000-0000-000260000000', 'Pennsylvania', 'PA', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 13000000, 119280, NULL, 'Harrisburg', 'Keystone State', datetime('now'), datetime('now')),
('s00000000-0027-4000-0000-000270000000', 'Rhode Island', 'RI', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 1100000, 4001, NULL, 'Providence', 'Ocean State', datetime('now'), datetime('now')),
('s00000000-0028-4000-0000-000280000000', 'South Carolina', 'SC', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 5200000, 82933, NULL, 'Columbia', 'Palmetto State', datetime('now'), datetime('now')),
('s00000000-0029-4000-0000-000290000000', 'South Dakota', 'SD', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 890000, 199729, NULL, 'Pierre', 'Mount Rushmore State', datetime('now'), datetime('now')),
('s00000000-002a-4000-0000-0002a0000000', 'Tennessee', 'TN', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 6900000, 109153, NULL, 'Nashville', 'Volunteer State', datetime('now'), datetime('now')),
('s00000000-002b-4000-0000-0002b0000000', 'Texas', 'TX', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 29100000, 695662, NULL, 'Austin', 'Lone Star State', datetime('now'), datetime('now')),
('s00000000-002c-4000-0000-0002c0000000', 'Utah', 'UT', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 3270000, 219882, NULL, 'Salt Lake City', 'Beehive State', datetime('now'), datetime('now')),
('s00000000-002d-4000-0000-0002d0000000', 'Vermont', 'VT', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 640000, 24906, NULL, 'Montpelier', 'Green Mountain State', datetime('now'), datetime('now')),
('s00000000-002e-4000-0000-0002e0000000', 'Virginia', 'VA', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 8630000, 110787, NULL, 'Richmond', 'Old Dominion', datetime('now'), datetime('now')),
('s00000000-002f-4000-0000-0002f0000000', 'Washington', 'WA', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 7700000, 184661, NULL, 'Olympia', 'Evergreen State', datetime('now'), datetime('now')),
('s00000000-0030-4000-0000-000300000000', 'West Virginia', 'WV', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 1790000, 62756, NULL, 'Charleston', 'Mountain State', datetime('now'), datetime('now')),
('s00000000-0031-4000-0000-000310000000', 'Wisconsin', 'WI', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 5900000, 169635, NULL, 'Madison', 'Badger State', datetime('now'), datetime('now')),
('s00000000-0032-4000-0000-000320000000', 'Wyoming', 'WY', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 580000, 253335, NULL, 'Cheyenne', 'Equality State', datetime('now'), datetime('now')),
('s00000000-0033-4000-0000-000330000000', 'District of Columbia', 'DC', 'n1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 710000, 177, NULL, 'Washington', 'Federal District', datetime('now'), datetime('now')),

-- ============================================
-- INDIA (28 States + 8 Union Territories)
-- ============================================
-- Country ID: b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c

-- States
('s00000000-0034-4000-0000-000340000000', 'Andhra Pradesh', 'AP', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 49400000, 160205, NULL, 'Amaravati', 'Rice bowl of India', datetime('now'), datetime('now')),
('s00000000-0035-4000-0000-000350000000', 'Arunachal Pradesh', 'AR', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 1380000, 83743, NULL, 'Itanagar', 'Land of rising sun', datetime('now'), datetime('now')),
('s00000000-0036-4000-0000-000360000000', 'Assam', 'AS', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 31200000, 78438, NULL, 'Dispur', 'Gateway to Northeast India', datetime('now'), datetime('now')),
('s00000000-0037-4000-0000-000370000000', 'Bihar', 'BR', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 104100000, 94163, NULL, 'Patna', 'Land of monasteries', datetime('now'), datetime('now')),
('s00000000-0038-4000-0000-000380000000', 'Chhattisgarh', 'CG', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 25500000, 135192, NULL, 'Raipur', 'Rice bowl of India', datetime('now'), datetime('now')),
('s00000000-0039-4000-0000-000390000000', 'Goa', 'GA', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 1460000, 3702, NULL, 'Panaji', 'Pearl of the Orient', datetime('now'), datetime('now')),
('s00000000-003a-4000-0000-0003a0000000', 'Gujarat', 'GJ', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 60400000, 196244, NULL, 'Gandhinagar', 'Jewel of Western India', datetime('now'), datetime('now')),
('s00000000-003b-4000-0000-0003b0000000', 'Haryana', 'HR', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 25400000, 44212, NULL, 'Chandigarh', 'Agricultural hub', datetime('now'), datetime('now')),
('s00000000-003c-4000-0000-0003c0000000', 'Himachal Pradesh', 'HP', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 6860000, 55673, NULL, 'Shimla', 'Land of Gods', datetime('now'), datetime('now')),
('s00000000-003d-4000-0000-0003d0000000', 'Jharkhand', 'JH', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 33000000, 79716, NULL, 'Ranchi', 'Land of forests', datetime('now'), datetime('now')),
('s00000000-003e-4000-0000-0003e0000000', 'Karnataka', 'KA', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 61100000, 191791, NULL, 'Bengaluru', 'Silicon Valley of India', datetime('now'), datetime('now')),
('s00000000-003f-4000-0000-0003f0000000', 'Kerala', 'KL', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 33400000, 38852, NULL, 'Thiruvananthapuram', 'Gods Own Country', datetime('now'), datetime('now')),
('s00000000-0040-4000-0000-000400000000', 'Madhya Pradesh', 'MP', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 72600000, 308245, NULL, 'Bhopal', 'Heart of India', datetime('now'), datetime('now')),
('s00000000-0041-4000-0000-000410000000', 'Maharashtra', 'MH', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 112400000, 307713, NULL, 'Mumbai', 'Economic powerhouse', datetime('now'), datetime('now')),
('s00000000-0042-4000-0000-000420000000', 'Manipur', 'MN', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 2860000, 22327, NULL, 'Imphal', 'Jewel of India', datetime('now'), datetime('now')),
('s00000000-0043-4000-0000-000430000000', 'Meghalaya', 'ML', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 2960000, 22429, NULL, 'Shillong', 'Abode of clouds', datetime('now'), datetime('now')),
('s00000000-0044-4000-0000-000440000000', 'Mizoram', 'MZ', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 1090000, 21081, NULL, 'Aizawl', 'Land of the Mizos', datetime('now'), datetime('now')),
('s00000000-0045-4000-0000-000450000000', 'Nagaland', 'NL', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 1980000, 16579, NULL, 'Kohima', 'Land of festivals', datetime('now'), datetime('now')),
('s00000000-0046-4000-0000-000460000000', 'Odisha', 'OR', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 42000000, 155707, NULL, 'Bhubaneswar', 'Soul of India', datetime('now'), datetime('now')),
('s00000000-0047-4000-0000-000470000000', 'Punjab', 'PB', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 27700000, 50362, NULL, 'Chandigarh', 'Granary of India', datetime('now'), datetime('now')),
('s00000000-0048-4000-0000-000480000000', 'Rajasthan', 'RJ', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 68500000, 342239, NULL, 'Jaipur', 'Land of Kings', datetime('now'), datetime('now')),
('s00000000-0049-4000-0000-000490000000', 'Sikkim', 'SK', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 610000, 7096, NULL, 'Gangtok', 'Himalayan paradise', datetime('now'), datetime('now')),
('s00000000-004a-4000-0000-0004a0000000', 'Tamil Nadu', 'TN', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 72100000, 130060, NULL, 'Chennai', 'Land of Tamils', datetime('now'), datetime('now')),
('s00000000-004b-4000-0000-0004b0000000', 'Telangana', 'TS', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 35200000, 112077, NULL, 'Hyderabad', 'Youngest state', datetime('now'), datetime('now')),
('s00000000-004c-4000-0000-0004c0000000', 'Tripura', 'TR', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 3670000, 10486, NULL, 'Agartala', 'Land of diversity', datetime('now'), datetime('now')),
('s00000000-004d-4000-0000-0004d0000000', 'Uttar Pradesh', 'UP', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 199800000, 240928, NULL, 'Lucknow', 'Most populous state', datetime('now'), datetime('now')),
('s00000000-004e-4000-0000-0004e0000000', 'Uttarakhand', 'UK', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 10100000, 53483, NULL, 'Dehradun', 'Land of Gods', datetime('now'), datetime('now')),
('s00000000-004f-4000-0000-0004f0000000', 'West Bengal', 'WB', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 91300000, 88752, NULL, 'Kolkata', 'Cultural capital', datetime('now'), datetime('now')),

-- Union Territories
('s00000000-0050-4000-0000-000500000000', 'Andaman and Nicobar Islands', 'AN', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 380000, 8249, NULL, 'Port Blair', 'Island territory', datetime('now'), datetime('now')),
('s00000000-0051-4000-0000-000510000000', 'Chandigarh', 'CH', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 1050000, 114, NULL, 'Chandigarh', 'City Beautiful', datetime('now'), datetime('now')),
('s00000000-0052-4000-0000-000520000000', 'Dadra and Nagar Haveli and Daman and Diu', 'DH', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 590000, 603, NULL, 'Daman', 'Merged territory', datetime('now'), datetime('now')),
('s00000000-0053-4000-0000-000530000000', 'Delhi', 'DL', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 16800000, 1484, NULL, 'New Delhi', 'National Capital Territory', datetime('now'), datetime('now')),
('s00000000-0054-4000-0000-000540000000', 'Jammu and Kashmir', 'JK', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 12500000, 42241, NULL, 'Srinagar (Summer), Jammu (Winter)', 'Paradise on Earth', datetime('now'), datetime('now')),
('s00000000-0055-4000-0000-000550000000', 'Ladakh', 'LA', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 280000, 59146, NULL, 'Leh', 'Land of high passes', datetime('now'), datetime('now')),
('s00000000-0056-4000-0000-000560000000', 'Lakshadweep', 'LD', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 64000, 32, NULL, 'Kavaratti', 'Coral island paradise', datetime('now'), datetime('now')),
('s00000000-0057-4000-0000-000570000000', 'Puducherry', 'PY', 'b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 1240000, 492, NULL, 'Pondicherry', 'French heritage', datetime('now'), datetime('now')),

-- ============================================
-- CANADA (10 Provinces + 3 Territories)
-- ============================================
-- Country ID: n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b

-- Provinces
('s00000000-0058-4000-0000-000580000000', 'Alberta', 'AB', 'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 4400000, 661848, NULL, 'Edmonton', 'Energy province', datetime('now'), datetime('now')),
('s00000000-0059-4000-0000-000590000000', 'British Columbia', 'BC', 'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 5100000, 944735, NULL, 'Victoria', 'Beautiful British Columbia', datetime('now'), datetime('now')),
('s00000000-005a-4000-0000-0005a0000000', 'Manitoba', 'MB', 'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 1380000, 647797, NULL, 'Winnipeg', 'Keystone province', datetime('now'), datetime('now')),
('s00000000-005b-4000-0000-0005b0000000', 'New Brunswick', 'NB', 'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 780000, 72908, NULL, 'Fredericton', 'Picture province', datetime('now'), datetime('now')),
('s00000000-005c-4000-0000-0005c0000000', 'Newfoundland and Labrador', 'NL', 'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 520000, 405212, NULL, 'St Johns', 'Easternmost province', datetime('now'), datetime('now')),
('s00000000-005d-4000-0000-0005d0000000', 'Nova Scotia', 'NS', 'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 980000, 55284, NULL, 'Halifax', 'Canadas Ocean Playground', datetime('now'), datetime('now')),
('s00000000-005e-4000-0000-0005e0000000', 'Ontario', 'ON', 'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 14700000, 1076395, NULL, 'Toronto', 'Economic engine', datetime('now'), datetime('now')),
('s00000000-005f-4000-0000-0005f0000000', 'Prince Edward Island', 'PE', 'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 160000, 5660, NULL, 'Charlottetown', 'Garden of the Gulf', datetime('now'), datetime('now')),
('s00000000-0060-4000-0000-000600000000', 'Quebec', 'QC', 'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 8600000, 1542056, NULL, 'Quebec City', 'La Belle Province', datetime('now'), datetime('now')),
('s00000000-0061-4000-0000-000610000000', 'Saskatchewan', 'SK', 'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 1180000, 651036, NULL, 'Regina', 'Land of Living Skies', datetime('now'), datetime('now')),

-- Territories
('s00000000-0062-4000-0000-000620000000', 'Northwest Territories', 'NT', 'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 45000, 1346106, NULL, 'Yellowknife', 'True North', datetime('now'), datetime('now')),
('s00000000-0063-4000-0000-000630000000', 'Nunavut', 'NU', 'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 39000, 2093190, NULL, 'Iqaluit', 'Our Land', datetime('now'), datetime('now')),
('s00000000-0064-4000-0000-000640000000', 'Yukon', 'YT', 'n2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 42000, 482443, NULL, 'Whitehorse', 'True North Strong and Free', datetime('now'), datetime('now')),

-- ============================================
-- CHINA (23 Provinces + 5 Autonomous Regions + 4 Municipalities + 2 SARs)
-- ============================================
-- Country ID: b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c

-- Provinces
('s00000000-0065-4000-0000-000650000000', 'Anhui', 'AH', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 63000000, 139600, NULL, 'Hefei', 'Central China province', datetime('now'), datetime('now')),
('s00000000-0066-4000-0000-000660000000', 'Fujian', 'FJ', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 41500000, 121400, NULL, 'Fuzhou', 'Southeastern coastal province', datetime('now'), datetime('now')),
('s00000000-0067-4000-0000-000670000000', 'Gansu', 'GS', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 25000000, 454000, NULL, 'Lanzhou', 'Northwestern province', datetime('now'), datetime('now')),
('s00000000-0068-4000-0000-000680000000', 'Guangdong', 'GD', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 126000000, 179800, NULL, 'Guangzhou', 'Most populous province', datetime('now'), datetime('now')),
('s00000000-0069-4000-0000-000690000000', 'Guizhou', 'GZ', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 38500000, 176167, NULL, 'Guiyang', 'Mountainous southwestern province', datetime('now'), datetime('now')),
('s00000000-006a-4000-0000-0006a0000000', 'Hainan', 'HI', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 10000000, 35400, NULL, 'Haikou', 'Tropical island province', datetime('now'), datetime('now')),
('s00000000-006b-4000-0000-0006b0000000', 'Hebei', 'HE', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 75000000, 188800, NULL, 'Shijiazhuang', 'Surrounds Beijing and Tianjin', datetime('now'), datetime('now')),
('s00000000-006c-4000-0000-0006c0000000', 'Heilongjiang', 'HL', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 31500000, 473000, NULL, 'Harbin', 'Northernmost province', datetime('now'), datetime('now')),
('s00000000-006d-4000-0000-0006d0000000', 'Henan', 'HA', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 99000000, 167000, NULL, 'Zhengzhou', 'Central Plains province', datetime('now'), datetime('now')),
('s00000000-006e-4000-0000-0006e0000000', 'Hubei', 'HB', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 57800000, 185900, NULL, 'Wuhan', 'Central China province', datetime('now'), datetime('now')),
('s00000000-006f-4000-0000-0006f0000000', 'Hunan', 'HN', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 66000000, 211800, NULL, 'Changsha', 'South-central province', datetime('now'), datetime('now')),
('s00000000-0070-4000-0000-000700000000', 'Jiangsu', 'JS', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 84700000, 107200, NULL, 'Nanjing', 'Eastern coastal province', datetime('now'), datetime('now')),
('s00000000-0071-4000-0000-000710000000', 'Jiangxi', 'JX', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 45200000, 166900, NULL, 'Nanchang', 'Southeastern inland province', datetime('now'), datetime('now')),
('s00000000-0072-4000-0000-000720000000', 'Jilin', 'JL', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 24000000, 187400, NULL, 'Changchun', 'Northeastern province', datetime('now'), datetime('now')),
('s00000000-0073-4000-0000-000730000000', 'Liaoning', 'LN', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 43000000, 148000, NULL, 'Shenyang', 'Northeastern coastal province', datetime('now'), datetime('now')),
('s00000000-0074-4000-0000-000740000000', 'Qinghai', 'QH', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 6000000, 720000, NULL, 'Xining', 'Northwestern plateau province', datetime('now'), datetime('now')),
('s00000000-0075-4000-0000-000750000000', 'Shaanxi', 'SN', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 39500000, 205800, NULL, 'Xian', 'Northwestern province', datetime('now'), datetime('now')),
('s00000000-0076-4000-0000-000760000000', 'Shandong', 'SD', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 101500000, 157100, NULL, 'Jinan', 'Eastern coastal province', datetime('now'), datetime('now')),
('s00000000-0077-4000-0000-000770000000', 'Shanxi', 'SX', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 34900000, 156700, NULL, 'Taiyuan', 'Northern inland province', datetime('now'), datetime('now')),
('s00000000-0078-4000-0000-000780000000', 'Sichuan', 'SC', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 83700000, 486000, NULL, 'Chengdu', 'Southwestern province', datetime('now'), datetime('now')),
('s00000000-0079-4000-0000-000790000000', 'Taiwan', 'TW', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 23500000, 36193, NULL, 'Taipei', 'Island province', datetime('now'), datetime('now')),
('s00000000-007a-4000-0000-0007a0000000', 'Yunnan', 'YN', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 48300000, 394100, NULL, 'Kunming', 'Southwestern border province', datetime('now'), datetime('now')),
('s00000000-007b-4000-0000-0007b0000000', 'Zhejiang', 'ZJ', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 64600000, 105500, NULL, 'Hangzhou', 'Eastern coastal province', datetime('now'), datetime('now')),

-- Autonomous Regions
('s00000000-007c-4000-0000-0007c0000000', 'Guangxi', 'GX', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 50100000, 236700, NULL, 'Nanning', 'Zhuang Autonomous Region', datetime('now'), datetime('now')),
('s00000000-007d-4000-0000-0007d0000000', 'Inner Mongolia', 'NM', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 24000000, 1183000, NULL, 'Hohhot', 'Mongolian Autonomous Region', datetime('now'), datetime('now')),
('s00000000-007e-4000-0000-0007e0000000', 'Ningxia', 'NX', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 7200000, 66400, NULL, 'Yinchuan', 'Hui Autonomous Region', datetime('now'), datetime('now')),
('s00000000-007f-4000-0000-0007f0000000', 'Tibet', 'XZ', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 3600000, 1228400, NULL, 'Lhasa', 'Tibetan Autonomous Region', datetime('now'), datetime('now')),
('s00000000-0080-4000-0000-000800000000', 'Xinjiang', 'XJ', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 25900000, 1664900, NULL, 'Urumqi', 'Uyghur Autonomous Region', datetime('now'), datetime('now')),

-- Municipalities
('s00000000-0081-4000-0000-000810000000', 'Beijing', 'BJ', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 21900000, 16410, NULL, 'Beijing', 'Capital municipality', datetime('now'), datetime('now')),
('s00000000-0082-4000-0000-000820000000', 'Chongqing', 'CQ', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 32100000, 82400, NULL, 'Chongqing', 'Southwestern municipality', datetime('now'), datetime('now')),
('s00000000-0083-4000-0000-000830000000', 'Shanghai', 'SH', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 24900000, 6340, NULL, 'Shanghai', 'Largest city municipality', datetime('now'), datetime('now')),
('s00000000-0084-4000-0000-000840000000', 'Tianjin', 'TJ', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 13900000, 11920, NULL, 'Tianjin', 'Northern coastal municipality', datetime('now'), datetime('now')),

-- Special Administrative Regions
('s00000000-0085-4000-0000-000850000000', 'Hong Kong', 'HK', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 7500000, 1104, NULL, 'Hong Kong', 'Special Administrative Region', datetime('now'), datetime('now')),
('s00000000-0086-4000-0000-000860000000', 'Macau', 'MO', 'b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 680000, 32, NULL, 'Macau', 'Special Administrative Region', datetime('now'), datetime('now')),

-- ============================================
-- AUSTRALIA (6 States + 2 Territories)
-- ============================================
-- Country ID: o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b

-- States
('s00000000-0087-4000-0000-000870000000', 'New South Wales', 'NSW', 'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 8200000, 809444, NULL, 'Sydney', 'Most populous state', datetime('now'), datetime('now')),
('s00000000-0088-4000-0000-000880000000', 'Queensland', 'QLD', 'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 5200000, 1852642, NULL, 'Brisbane', 'Sunshine State', datetime('now'), datetime('now')),
('s00000000-0089-4000-0000-000890000000', 'South Australia', 'SA', 'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 1770000, 1044353, NULL, 'Adelaide', 'Festival state', datetime('now'), datetime('now')),
('s00000000-008a-4000-0000-0008a0000000', 'Tasmania', 'TAS', 'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 540000, 90758, NULL, 'Hobart', 'Island state', datetime('now'), datetime('now')),
('s00000000-008b-4000-0000-0008b0000000', 'Victoria', 'VIC', 'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 6700000, 237657, NULL, 'Melbourne', 'Garden State', datetime('now'), datetime('now')),
('s00000000-008c-4000-0000-0008c0000000', 'Western Australia', 'WA', 'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 2700000, 2642753, NULL, 'Perth', 'Largest state', datetime('now'), datetime('now')),

-- Territories
('s00000000-008d-4000-0000-0008d0000000', 'Australian Capital Territory', 'ACT', 'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 430000, 2358, NULL, 'Canberra', 'Capital territory', datetime('now'), datetime('now')),
('s00000000-008e-4000-0000-0008e0000000', 'Northern Territory', 'NT', 'o1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 246000, 1420968, NULL, 'Darwin', 'Outback territory', datetime('now'), datetime('now')),

-- ============================================
-- BRAZIL (26 States + 1 Federal District)
-- ============================================
-- Country ID: s1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b

-- States
('s00000000-008f-4000-0000-0008f0000000', 'Acre', 'AC', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 900000, 164124, NULL, 'Rio Branco', 'Northwestern Amazonian state', datetime('now'), datetime('now')),
('s00000000-0090-4000-0000-000900000000', 'Alagoas', 'AL', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 3300000, 27768, NULL, 'Maceio', 'Northeastern coastal state', datetime('now'), datetime('now')),
('s00000000-0091-4000-0000-000910000000', 'Amapa', 'AP', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 860000, 142828, NULL, 'Macapa', 'Northern Amazonian state', datetime('now'), datetime('now')),
('s00000000-0092-4000-0000-000920000000', 'Amazonas', 'AM', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 4200000, 1559148, NULL, 'Manaus', 'Largest state by area', datetime('now'), datetime('now')),
('s00000000-0093-4000-0000-000930000000', 'Bahia', 'BA', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 14900000, 564733, NULL, 'Salvador', 'Northeastern state', datetime('now'), datetime('now')),
('s00000000-0094-4000-0000-000940000000', 'Ceara', 'CE', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 9200000, 148920, NULL, 'Fortaleza', 'Northeastern coastal state', datetime('now'), datetime('now')),
('s00000000-0095-4000-0000-000950000000', 'Distrito Federal', 'DF', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 3100000, 5760, NULL, 'Brasilia', 'Federal District capital', datetime('now'), datetime('now')),
('s00000000-0096-4000-0000-000960000000', 'Espirito Santo', 'ES', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 4100000, 46095, NULL, 'Vitoria', 'Southeastern coastal state', datetime('now'), datetime('now')),
('s00000000-0097-4000-0000-000970000000', 'Goias', 'GO', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 7100000, 340112, NULL, 'Goiania', 'Central-western state', datetime('now'), datetime('now')),
('s00000000-0098-4000-0000-000980000000', 'Maranhao', 'MA', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 7100000, 331937, NULL, 'Sao Luis', 'Northeastern state', datetime('now'), datetime('now')),
('s00000000-0099-4000-0000-000990000000', 'Mato Grosso', 'MT', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 3500000, 903366, NULL, 'Cuiaba', 'Central-western state', datetime('now'), datetime('now')),
('s00000000-009a-4000-0000-0009a0000000', 'Mato Grosso do Sul', 'MS', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 2800000, 357145, NULL, 'Campo Grande', 'Central-western state', datetime('now'), datetime('now')),
('s00000000-009b-4000-0000-0009b0000000', 'Minas Gerais', 'MG', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 21300000, 586528, NULL, 'Belo Horizonte', 'Southeastern state', datetime('now'), datetime('now')),
('s00000000-009c-4000-0000-0009c0000000', 'Para', 'PA', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 8700000, 1247954, NULL, 'Belem', 'Northern Amazonian state', datetime('now'), datetime('now')),
('s00000000-009d-4000-0000-0009d0000000', 'Paraiba', 'PB', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 4000000, 56469, NULL, 'Joao Pessoa', 'Northeastern state', datetime('now'), datetime('now')),
('s00000000-009e-4000-0000-0009e0000000', 'Parana', 'PR', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 11500000, 199307, NULL, 'Curitiba', 'Southern state', datetime('now'), datetime('now')),
('s00000000-009f-4000-0000-0009f0000000', 'Pernambuco', 'PE', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 9600000, 98311, NULL, 'Recife', 'Northeastern state', datetime('now'), datetime('now')),
('s00000000-00a0-4000-0000-000a00000000', 'Piaui', 'PI', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 3300000, 251529, NULL, 'Teresina', 'Northeastern state', datetime('now'), datetime('now')),
('s00000000-00a1-4000-0000-000a10000000', 'Rio de Janeiro', 'RJ', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 17400000, 43696, NULL, 'Rio de Janeiro', 'Southeastern coastal state', datetime('now'), datetime('now')),
('s00000000-00a2-4000-0000-000a20000000', 'Rio Grande do Norte', 'RN', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 3500000, 52811, NULL, 'Natal', 'Northeastern state', datetime('now'), datetime('now')),
('s00000000-00a3-4000-0000-000a30000000', 'Rio Grande do Sul', 'RS', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 11400000, 281730, NULL, 'Porto Alegre', 'Southernmost state', datetime('now'), datetime('now')),
('s00000000-00a4-4000-0000-000a40000000', 'Rondonia', 'RO', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 1800000, 237591, NULL, 'Porto Velho', 'Northwestern state', datetime('now'), datetime('now')),
('s00000000-00a5-4000-0000-000a50000000', 'Roraima', 'RR', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 630000, 224299, NULL, 'Boa Vista', 'Northern Amazonian state', datetime('now'), datetime('now')),
('s00000000-00a6-4000-0000-000a60000000', 'Santa Catarina', 'SC', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 7300000, 95736, NULL, 'Florianopolis', 'Southern state', datetime('now'), datetime('now')),
('s00000000-00a7-4000-0000-000a70000000', 'Sao Paulo', 'SP', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 46300000, 248219, NULL, 'Sao Paulo', 'Most populous state', datetime('now'), datetime('now')),
('s00000000-00a8-4000-0000-000a80000000', 'Sergipe', 'SE', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 2300000, 21915, NULL, 'Aracaju', 'Smallest northeastern state', datetime('now'), datetime('now')),
('s00000000-00a9-4000-0000-000a90000000', 'Tocantins', 'TO', 's1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 1600000, 277621, NULL, 'Palmas', 'Northern state', datetime('now'), datetime('now')),

-- ============================================
-- MEXICO (32 States)
-- ============================================
-- Country ID: n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b

-- States
('s00000000-00aa-4000-0000-000aa0000000', 'Aguascalientes', 'AG', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 1400000, 5618, NULL, 'Aguascalientes', 'Central highland state', datetime('now'), datetime('now')),
('s00000000-00ab-4000-0000-000ab0000000', 'Baja California', 'BC', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 3800000, 71446, NULL, 'Mexicali', 'Northwestern border state', datetime('now'), datetime('now')),
('s00000000-00ac-4000-0000-000ac0000000', 'Baja California Sur', 'BS', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 800000, 73922, NULL, 'La Paz', 'Northwestern peninsula state', datetime('now'), datetime('now')),
('s00000000-00ad-4000-0000-000ad0000000', 'Campeche', 'CM', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 930000, 57924, NULL, 'Campeche', 'Southeastern coastal state', datetime('now'), datetime('now')),
('s00000000-00ae-4000-0000-000ae0000000', 'Chiapas', 'CS', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 5500000, 73311, NULL, 'Tuxtla Gutierrez', 'Southern border state', datetime('now'), datetime('now')),
('s00000000-00af-4000-0000-000af0000000', 'Chihuahua', 'CH', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 3700000, 247455, NULL, 'Chihuahua', 'Northern border state', datetime('now'), datetime('now')),
('s00000000-00b0-4000-0000-000b00000000', 'Ciudad de Mexico', 'MX', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 9200000, 1495, NULL, 'Mexico City', 'Federal capital', datetime('now'), datetime('now')),
('s00000000-00b1-4000-0000-000b10000000', 'Coahuila', 'CO', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 3200000, 151563, NULL, 'Saltillo', 'Northern border state', datetime('now'), datetime('now')),
('s00000000-00b2-4000-0000-000b20000000', 'Colima', 'CL', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 730000, 5625, NULL, 'Colima', 'Western coastal state', datetime('now'), datetime('now')),
('s00000000-00b3-4000-0000-000b30000000', 'Durango', 'DG', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 1800000, 123451, NULL, 'Durango', 'Northern central state', datetime('now'), datetime('now')),
('s00000000-00b4-4000-0000-000b40000000', 'Guanajuato', 'GT', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 6200000, 30608, NULL, 'Guanajuato', 'Central highland state', datetime('now'), datetime('now')),
('s00000000-00b5-4000-0000-000b50000000', 'Guerrero', 'GR', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 3700000, 63621, NULL, 'Chilpancingo', 'Southern coastal state', datetime('now'), datetime('now')),
('s00000000-00b6-4000-0000-000b60000000', 'Hidalgo', 'HG', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 3100000, 20846, NULL, 'Pachuca', 'Central highland state', datetime('now'), datetime('now')),
('s00000000-00b7-4000-0000-000b70000000', 'Jalisco', 'JA', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 8300000, 78599, NULL, 'Guadalajara', 'Western state', datetime('now'), datetime('now')),
('s00000000-00b8-4000-0000-000b80000000', 'Mexico', 'EM', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 17400000, 22357, NULL, 'Toluca', 'Central state', datetime('now'), datetime('now')),
('s00000000-00b9-4000-0000-000b90000000', 'Michoacan', 'MI', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 4800000, 58643, NULL, 'Morelia', 'Western central state', datetime('now'), datetime('now')),
('s00000000-00ba-4000-0000-000ba0000000', 'Morelos', 'MO', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 2000000, 4893, NULL, 'Cuernavaca', 'Central state', datetime('now'), datetime('now')),
('s00000000-00bb-4000-0000-000bb0000000', 'Nayarit', 'NA', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 1200000, 27815, NULL, 'Tepic', 'Western coastal state', datetime('now'), datetime('now')),
('s00000000-00bc-4000-0000-000bc0000000', 'Nuevo Leon', 'NL', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 5500000, 64220, NULL, 'Monterrey', 'Northeastern state', datetime('now'), datetime('now')),
('s00000000-00bd-4000-0000-000bd0000000', 'Oaxaca', 'OA', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 4100000, 93793, NULL, 'Oaxaca', 'Southern state', datetime('now'), datetime('now')),
('s00000000-00be-4000-0000-000be0000000', 'Puebla', 'PU', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 6600000, 34290, NULL, 'Puebla', 'Central state', datetime('now'), datetime('now')),
('s00000000-00bf-4000-0000-000bf0000000', 'Queretaro', 'QE', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 2300000, 11684, NULL, 'Queretaro', 'Central highland state', datetime('now'), datetime('now')),
('s00000000-00c0-4000-0000-000c00000000', 'Quintana Roo', 'QR', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 1900000, 42361, NULL, 'Chetumal', 'Eastern coastal state', datetime('now'), datetime('now')),
('s00000000-00c1-4000-0000-000c10000000', 'San Luis Potosi', 'SL', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 2800000, 60983, NULL, 'San Luis Potosi', 'Central highland state', datetime('now'), datetime('now')),
('s00000000-00c2-4000-0000-000c20000000', 'Sinaloa', 'SI', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 3100000, 57377, NULL, 'Culiacan', 'Northwestern coastal state', datetime('now'), datetime('now')),
('s00000000-00c3-4000-0000-000c30000000', 'Sonora', 'SO', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 3000000, 179503, NULL, 'Hermosillo', 'Northwestern border state', datetime('now'), datetime('now')),
('s00000000-00c4-4000-0000-000c40000000', 'Tabasco', 'TB', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 2500000, 24738, NULL, 'Villahermosa', 'Southeastern coastal state', datetime('now'), datetime('now')),
('s00000000-00c5-4000-0000-000c50000000', 'Tamaulipas', 'TM', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 3700000, 80175, NULL, 'Ciudad Victoria', 'Northeastern border state', datetime('now'), datetime('now')),
('s00000000-00c6-4000-0000-000c60000000', 'Tlaxcala', 'TL', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 1300000, 3991, NULL, 'Tlaxcala', 'Central state', datetime('now'), datetime('now')),
('s00000000-00c7-4000-0000-000c70000000', 'Veracruz', 'VE', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 8100000, 71826, NULL, 'Xalapa', 'Eastern coastal state', datetime('now'), datetime('now')),
('s00000000-00c8-4000-0000-000c80000000', 'Yucatan', 'YU', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 2300000, 39524, NULL, 'Merida', 'Southeastern peninsula state', datetime('now'), datetime('now')),
('s00000000-00c9-4000-0000-000c90000000', 'Zacatecas', 'ZA', 'n3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 1600000, 75539, NULL, 'Zacatecas', 'Central highland state', datetime('now'), datetime('now')),

-- ============================================
-- GERMANY (16 States / Länder)
-- ============================================
-- Country ID: e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a

-- States
('s00000000-00ca-4000-0000-000ca0000000', 'Baden-Wurttemberg', 'BW', 'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 11100000, 35751, NULL, 'Stuttgart', 'Southwestern state', datetime('now'), datetime('now')),
('s00000000-00cb-4000-0000-000cb0000000', 'Bavaria', 'BY', 'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 13100000, 70550, NULL, 'Munich', 'Largest state by area', datetime('now'), datetime('now')),
('s00000000-00cc-4000-0000-000cc0000000', 'Berlin', 'BE', 'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 3700000, 892, NULL, 'Berlin', 'Capital city-state', datetime('now'), datetime('now')),
('s00000000-00cd-4000-0000-000cd0000000', 'Brandenburg', 'BB', 'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 2500000, 29654, NULL, 'Potsdam', 'Surrounds Berlin', datetime('now'), datetime('now')),
('s00000000-00ce-4000-0000-000ce0000000', 'Bremen', 'HB', 'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 680000, 419, NULL, 'Bremen', 'Smallest city-state', datetime('now'), datetime('now')),
('s00000000-00cf-4000-0000-000cf0000000', 'Hamburg', 'HH', 'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 1900000, 755, NULL, 'Hamburg', 'Port city-state', datetime('now'), datetime('now')),
('s00000000-00d0-4000-0000-000d00000000', 'Hesse', 'HE', 'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 6300000, 21115, NULL, 'Wiesbaden', 'Central state', datetime('now'), datetime('now')),
('s00000000-00d1-4000-0000-000d10000000', 'Lower Saxony', 'NI', 'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 8000000, 47709, NULL, 'Hanover', 'Northwestern state', datetime('now'), datetime('now')),
('s00000000-00d2-4000-0000-000d20000000', 'Mecklenburg-Vorpommern', 'MV', 'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 1610000, 23214, NULL, 'Schwerin', 'Northeastern coastal state', datetime('now'), datetime('now')),
('s00000000-00d3-4000-0000-000d30000000', 'North Rhine-Westphalia', 'NW', 'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 17900000, 34110, NULL, 'Dusseldorf', 'Most populous state', datetime('now'), datetime('now')),
('s00000000-00d4-4000-0000-000d40000000', 'Rhineland-Palatinate', 'RP', 'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 4100000, 19854, NULL, 'Mainz', 'Western state', datetime('now'), datetime('now')),
('s00000000-00d5-4000-0000-000d50000000', 'Saarland', 'SL', 'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 990000, 2569, NULL, 'Saarbrucken', 'Smallest territorial state', datetime('now'), datetime('now')),
('s00000000-00d6-4000-0000-000d60000000', 'Saxony', 'SN', 'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 4100000, 18450, NULL, 'Dresden', 'Eastern state', datetime('now'), datetime('now')),
('s00000000-00d7-4000-0000-000d70000000', 'Saxony-Anhalt', 'ST', 'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 2200000, 20452, NULL, 'Magdeburg', 'Central-eastern state', datetime('now'), datetime('now')),
('s00000000-00d8-4000-0000-000d80000000', 'Schleswig-Holstein', 'SH', 'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 2900000, 15804, NULL, 'Kiel', 'Northernmost state', datetime('now'), datetime('now')),
('s00000000-00d9-4000-0000-000d90000000', 'Thuringia', 'TH', 'e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 2100000, 16202, NULL, 'Erfurt', 'Central state', datetime('now'), datetime('now')),

-- ============================================
-- UNITED KINGDOM (4 Countries + Regions)
-- ============================================
-- Country ID: e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a

-- Countries
('s00000000-00da-4000-0000-000da0000000', 'England', 'ENG', 'e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a', 56500000, 130279, NULL, 'London', 'Largest country', datetime('now'), datetime('now')),
('s00000000-00db-4000-0000-000db0000000', 'Scotland', 'SCT', 'e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a', 5500000, 77933, NULL, 'Edinburgh', 'Northern country', datetime('now'), datetime('now')),
('s00000000-00dc-4000-0000-000dc0000000', 'Wales', 'WLS', 'e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a', 3200000, 20779, NULL, 'Cardiff', 'Western country', datetime('now'), datetime('now')),
('s00000000-00dd-4000-0000-000dd0000000', 'Northern Ireland', 'NIR', 'e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a', 1900000, 14130, NULL, 'Belfast', 'Northeastern country', datetime('now'), datetime('now'));
