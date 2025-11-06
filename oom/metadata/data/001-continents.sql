-- ============================================
-- DATA SEED: Continents
-- Initial data for continent entity
-- ============================================

INSERT OR IGNORE INTO continent (id, name, code, area_sq_km, population, gdp_usd, description, created_at, updated_at)
VALUES
('c1a2b3c4-d5e6-4f7a-8b9c-0d1e2f3a4b5c', 'Africa', 'AF', 30370000, 1340000000, 2960000000000, 'The second-largest and second-most populous continent', datetime('now'), datetime('now')),
('c2a3b4c5-d6e7-4f8a-9b0c-1d2e3f4a5b6c', 'Antarctica', 'AN', 14000000, 1000, 0, 'The southernmost continent containing the geographic South Pole', datetime('now'), datetime('now')),
('c3a4b5c6-d7e8-4f9a-0b1c-2d3e4f5a6b7c', 'Asia', 'AS', 44579000, 4640000000, 36000000000000, 'The largest and most populous continent', datetime('now'), datetime('now')),
('c4a5b6c7-d8e9-4f0a-1b2c-3d4e5f6a7b8c', 'Europe', 'EU', 10180000, 747000000, 23000000000000, 'A continent comprising the westernmost peninsulas of Eurasia', datetime('now'), datetime('now')),
('c5a6b7c8-d9e0-4f1a-2b3c-4d5e6f7a8b9c', 'North America', 'NA', 24709000, 592000000, 28000000000000, 'A continent in the Northern Hemisphere', datetime('now'), datetime('now')),
('c6a7b8c9-d0e1-4f2a-3b4c-5d6e7f8a9b0c', 'Oceania', 'OC', 8525989, 43000000, 1700000000000, 'A geographic region comprising Australasia, Melanesia, Micronesia, and Polynesia', datetime('now'), datetime('now')),
('c7a8b9c0-d1e2-4f3a-4b5c-6d7e8f9a0b1c', 'South America', 'SA', 17840000, 434000000, 3900000000000, 'A continent in the Western Hemisphere', datetime('now'), datetime('now'));
