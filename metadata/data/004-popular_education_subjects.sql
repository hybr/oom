-- ============================================
-- DATA SEED: Popular Education Subjects
-- Common academic subjects across different fields
-- ============================================

INSERT OR IGNORE INTO popular_education_subject (id, name, created_at, updated_at)
VALUES
-- STEM - Science, Technology, Engineering, Mathematics
('a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Mathematics', datetime('now'), datetime('now')),
('a2b3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Physics', datetime('now'), datetime('now')),
('a3b4c5d6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Chemistry', datetime('now'), datetime('now')),
('a4b5c6d7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'Biology', datetime('now'), datetime('now')),
('a5b6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'Computer Science', datetime('now'), datetime('now')),
('a6b7c8d9-e0f1-42a3-4b5c-6d7e8f9a0b1c', 'Information Technology', datetime('now'), datetime('now')),
('a7b8c9d0-e1f2-43a4-5b6c-7d8e9f0a1b2c', 'Engineering', datetime('now'), datetime('now')),
('a8b9c0d1-e2f3-44a5-6b7c-8d9e0f1a2b3c', 'Statistics', datetime('now'), datetime('now')),
('a9b0c1d2-e3f4-45a6-7b8c-9d0e1f2a3b4c', 'Data Science', datetime('now'), datetime('now')),
('a0b1c2d3-e4f5-46a7-8b9c-0d1e2f3a4b5c', 'Astronomy', datetime('now'), datetime('now')),

-- Languages & Literature
('b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'English Language', datetime('now'), datetime('now')),
('b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'English Literature', datetime('now'), datetime('now')),
('b3a4c5d6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Spanish Language', datetime('now'), datetime('now')),
('b4a5c6d7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'French Language', datetime('now'), datetime('now')),
('b5a6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'German Language', datetime('now'), datetime('now')),
('b6a7c8d9-e0f1-42a3-4b5c-6d7e8f9a0b1c', 'Chinese Language', datetime('now'), datetime('now')),
('b7a8c9d0-e1f2-43a4-5b6c-7d8e9f0a1b2c', 'Japanese Language', datetime('now'), datetime('now')),
('b8a9c0d1-e2f3-44a5-6b7c-8d9e0f1a2b3c', 'Arabic Language', datetime('now'), datetime('now')),
('b9a0c1d2-e3f4-45a6-7b8c-9d0e1f2a3b4c', 'Creative Writing', datetime('now'), datetime('now')),
('b0a1c2d3-e4f5-46a7-8b9c-0d1e2f3a4b5c', 'Linguistics', datetime('now'), datetime('now')),

-- Social Sciences
('c1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 'History', datetime('now'), datetime('now')),
('c2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 'Geography', datetime('now'), datetime('now')),
('c3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 'Political Science', datetime('now'), datetime('now')),
('c4a5b6c7-d8e9-40f1-2a3b-4c5d6e7f8a9b', 'Sociology', datetime('now'), datetime('now')),
('c5a6b7c8-d9e0-41f2-3a4b-5c6d7e8f9a0b', 'Psychology', datetime('now'), datetime('now')),
('c6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'Anthropology', datetime('now'), datetime('now')),
('c7a8b9c0-d1e2-43f4-5a6b-7c8d9e0f1a2b', 'Economics', datetime('now'), datetime('now')),
('c8a9b0c1-d2e3-44f5-6a7b-8c9d0e1f2a3b', 'Philosophy', datetime('now'), datetime('now')),
('c9a0b1c2-d3e4-45f6-7a8b-9c0d1e2f3a4b', 'Ethics', datetime('now'), datetime('now')),
('c0a1b2c3-d4e5-46f7-8a9b-0c1d2e3f4a5b', 'International Relations', datetime('now'), datetime('now')),

-- Business & Commerce
('d1a2b3c4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Business Studies', datetime('now'), datetime('now')),
('d2a3b4c5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Accounting', datetime('now'), datetime('now')),
('d3a4b5c6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Finance', datetime('now'), datetime('now')),
('d4a5b6c7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'Marketing', datetime('now'), datetime('now')),
('d5a6b7c8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'Management', datetime('now'), datetime('now')),
('d6a7b8c9-e0f1-42a3-4b5c-6d7e8f9a0b1c', 'Entrepreneurship', datetime('now'), datetime('now')),
('d7a8b9c0-e1f2-43a4-5b6c-7d8e9f0a1b2c', 'Human Resources', datetime('now'), datetime('now')),
('d8a9c0d1-e2f3-44a5-6b7c-8d9e0f1a2b3c', 'Supply Chain Management', datetime('now'), datetime('now')),
('d9a0c1d2-e3f4-45a6-7b8c-9d0e1f2a3b4c', 'Operations Management', datetime('now'), datetime('now')),
('d0a1b2c3-e4f5-46a7-8b9c-0d1e2f3a4b5c', 'Business Analytics', datetime('now'), datetime('now')),

-- Arts & Humanities
('e1f2a3b4-c5d6-47e8-9f0a-1b2c3d4e5f6a', 'Art History', datetime('now'), datetime('now')),
('e2f3a4b5-c6d7-48e9-0f1a-2b3c4d5e6f7a', 'Fine Arts', datetime('now'), datetime('now')),
('e3f4a5b6-c7d8-49e0-1f2a-3b4c5d6e7f8a', 'Music Theory', datetime('now'), datetime('now')),
('e4f5a6b7-c8d9-40e1-2f3a-4b5c6d7e8f9a', 'Music Performance', datetime('now'), datetime('now')),
('e5f6a7b8-c9d0-41e2-3f4a-5b6c7d8e9f0a', 'Drama & Theatre', datetime('now'), datetime('now')),
('e6f7a8b9-c0d1-42e3-4f5a-6b7c8d9e0f1a', 'Film Studies', datetime('now'), datetime('now')),
('e7f8a9b0-c1d2-43e4-5f6a-7b8c9d0e1f2a', 'Dance', datetime('now'), datetime('now')),
('e8f9a0b1-c2d3-44e5-6f7a-8b9c0d1e2f3a', 'Visual Arts', datetime('now'), datetime('now')),
('e9f0a1b2-c3d4-45e6-7f8a-9b0c1d2e3f4a', 'Graphic Design', datetime('now'), datetime('now')),
('e0f1a2b3-c4d5-46e7-8f9a-0b1c2d3e4f5a', 'Photography', datetime('now'), datetime('now')),

-- Health & Medical Sciences
('f1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 'Anatomy', datetime('now'), datetime('now')),
('f2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 'Physiology', datetime('now'), datetime('now')),
('f3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 'Biochemistry', datetime('now'), datetime('now')),
('f4a5b6c7-d8e9-40f1-2a3b-4c5d6e7f8a9b', 'Pharmacology', datetime('now'), datetime('now')),
('f5a6b7c8-d9e0-41f2-3a4b-5c6d7e8f9a0b', 'Nursing', datetime('now'), datetime('now')),
('f6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'Public Health', datetime('now'), datetime('now')),
('f7a8b9c0-d1e2-43f4-5a6b-7c8d9e0f1a2b', 'Nutrition', datetime('now'), datetime('now')),
('f8a9b0c1-d2e3-44f5-6a7b-8c9d0e1f2a3b', 'Medical Science', datetime('now'), datetime('now')),
('f9a0b1c2-d3e4-45f6-7a8b-9c0d1e2f3a4b', 'Dentistry', datetime('now'), datetime('now')),
('f0a1b2c3-d4e5-46f7-8a9b-0c1d2e3f4a5b', 'Veterinary Science', datetime('now'), datetime('now')),

-- Environmental & Earth Sciences
('g1a2b3c4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Environmental Science', datetime('now'), datetime('now')),
('g2a3b4c5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Ecology', datetime('now'), datetime('now')),
('g3a4b5c6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Geology', datetime('now'), datetime('now')),
('g4a5b6c7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'Marine Biology', datetime('now'), datetime('now')),
('g5a6b7c8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'Meteorology', datetime('now'), datetime('now')),
('g6a7b8c9-e0f1-42a3-4b5c-6d7e8f9a0b1c', 'Climate Science', datetime('now'), datetime('now')),
('g7a8c9d0-e1f2-43a4-5b6c-7d8e9f0a1b2c', 'Oceanography', datetime('now'), datetime('now')),
('g8a9c0d1-e2f3-44a5-6b7c-8d9e0f1a2b3c', 'Agriculture', datetime('now'), datetime('now')),
('g9a0c1d2-e3f4-45a6-7b8c-9d0e1f2a3b4c', 'Forestry', datetime('now'), datetime('now')),
('g0a1c2d3-e4f5-46a7-8b9c-0d1e2f3a4b5c', 'Sustainability Studies', datetime('now'), datetime('now')),

-- Law & Legal Studies
('h1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 'Constitutional Law', datetime('now'), datetime('now')),
('h2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 'Criminal Law', datetime('now'), datetime('now')),
('h3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 'Civil Law', datetime('now'), datetime('now')),
('h4a5b6c7-d8e9-40f1-2a3b-4c5d6e7f8a9b', 'International Law', datetime('now'), datetime('now')),
('h5a6b7c8-d9e0-41f2-3a4b-5c6d7e8f9a0b', 'Corporate Law', datetime('now'), datetime('now')),
('h6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'Human Rights Law', datetime('now'), datetime('now')),
('h7a8b9c0-d1e2-43f4-5a6b-7c8d9e0f1a2b', 'Legal Studies', datetime('now'), datetime('now')),
('h8a9b0c1-d2e3-44f5-6a7b-8c9d0e1f2a3b', 'Jurisprudence', datetime('now'), datetime('now')),

-- Education & Teaching
('i1a2b3c4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Pedagogy', datetime('now'), datetime('now')),
('i2a3b4c5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Educational Psychology', datetime('now'), datetime('now')),
('i3a4b5c6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Curriculum Development', datetime('now'), datetime('now')),
('i4a5b6c7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'Special Education', datetime('now'), datetime('now')),
('i5a6b7c8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'Early Childhood Education', datetime('now'), datetime('now')),

-- Religious & Cultural Studies
('j1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 'Religious Studies', datetime('now'), datetime('now')),
('j2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 'Theology', datetime('now'), datetime('now')),
('j3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 'Cultural Studies', datetime('now'), datetime('now')),
('j4a5b6c7-d8e9-40f1-2a3b-4c5d6e7f8a9b', 'Gender Studies', datetime('now'), datetime('now')),
('j5a6b7c8-d9e0-41f2-3a4b-5c6d7e8f9a0b', 'Area Studies', datetime('now'), datetime('now')),

-- Physical Education & Sports
('k1a2b3c4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Physical Education', datetime('now'), datetime('now')),
('k2a3b4c5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Sports Science', datetime('now'), datetime('now')),
('k3a4b5c6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Kinesiology', datetime('now'), datetime('now')),
('k4a5b6c7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'Sports Management', datetime('now'), datetime('now')),

-- Media & Communication
('m1a2b3c4-d5e6-47f8-9a0b-1c2d3e4f5a6b', 'Journalism', datetime('now'), datetime('now')),
('m2a3b4c5-d6e7-48f9-0a1b-2c3d4e5f6a7b', 'Mass Communication', datetime('now'), datetime('now')),
('m3a4b5c6-d7e8-49f0-1a2b-3c4d5e6f7a8b', 'Public Relations', datetime('now'), datetime('now')),
('m4a5b6c7-d8e9-40f1-2a3b-4c5d6e7f8a9b', 'Media Studies', datetime('now'), datetime('now')),
('m5a6b7c8-d9e0-41f2-3a4b-5c6d7e8f9a0b', 'Digital Media', datetime('now'), datetime('now')),
('m6a7b8c9-d0e1-42f3-4a5b-6c7d8e9f0a1b', 'Broadcasting', datetime('now'), datetime('now')),

-- Architecture & Design
('n1a2b3c4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Architecture', datetime('now'), datetime('now')),
('n2a3b4c5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Interior Design', datetime('now'), datetime('now')),
('n3a4b5c6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Urban Planning', datetime('now'), datetime('now')),
('n4a5b6c7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'Industrial Design', datetime('now'), datetime('now')),
('n5a6b7c8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'Fashion Design', datetime('now'), datetime('now'));
