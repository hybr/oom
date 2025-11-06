-- =========================================================
-- POPULAR_SKILL DATA SEED
-- Entity: POPULAR_SKILL
-- Description: Common technical and soft skills
-- Total Records: 300 skills across multiple categories
-- =========================================================

-- Table: popular_skill
-- Columns: id (uuid), name (text), created_at (datetime), updated_at (datetime)

INSERT OR IGNORE INTO popular_skill (id, name, created_at, updated_at)
VALUES
-- =============================================================================
-- PROGRAMMING LANGUAGES (20 skills)
-- =============================================================================
('a1b2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Python', datetime('now'), datetime('now')),
('a2b3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Java', datetime('now'), datetime('now')),
('a3b4c5d6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'JavaScript', datetime('now'), datetime('now')),
('a4b5c6d7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'TypeScript', datetime('now'), datetime('now')),
('a5b6c7d8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'C++', datetime('now'), datetime('now')),
('a6b7c8d9-e0f1-42a3-4b5c-6d7e8f9a0b1c', 'C#', datetime('now'), datetime('now')),
('a7b8c9d0-e1f2-43a4-5b6c-7d8e9f0a1b2c', 'PHP', datetime('now'), datetime('now')),
('a8b9c0d1-e2f3-44a5-6b7c-8d9e0f1a2b3c', 'Ruby', datetime('now'), datetime('now')),
('a9b0c1d2-e3f4-45a6-7b8c-9d0e1f2a3b4c', 'Go', datetime('now'), datetime('now')),
('a0b1c2d3-e4f5-46a7-8b9c-0d1e2f3a4b5c', 'Rust', datetime('now'), datetime('now')),
('b1a2c3d4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Swift', datetime('now'), datetime('now')),
('b2a3c4d5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Kotlin', datetime('now'), datetime('now')),
('b3a4b5c6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Scala', datetime('now'), datetime('now')),
('b4a5b6c7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'R', datetime('now'), datetime('now')),
('b5a6b7c8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'MATLAB', datetime('now'), datetime('now')),
('b6a7b8c9-e0f1-42a3-4b5c-6d7e8f9a0b1c', 'Perl', datetime('now'), datetime('now')),
('b7a8b9c0-e1f2-43a4-5b6c-7d8e9f0a1b2c', 'Objective-C', datetime('now'), datetime('now')),
('b8a9b0c1-e2f3-44a5-6b7c-8d9e0f1a2b3c', 'Dart', datetime('now'), datetime('now')),
('b9a0b1c2-e3f4-45a6-7b8c-9d0e1f2a3b4c', 'Elixir', datetime('now'), datetime('now')),
('b0a1b2c3-e4f5-46a7-8b9c-0d1e2f3a4b5c', 'Haskell', datetime('now'), datetime('now')),

-- =============================================================================
-- WEB DEVELOPMENT & FRAMEWORKS (20 skills)
-- =============================================================================
('c1a2b3c4-d5e6-47a8-9b0c-1d2e3f4a5b6c', 'HTML', datetime('now'), datetime('now')),
('c2a3b4c5-d6e7-48a9-0b1c-2d3e4f5a6b7c', 'CSS', datetime('now'), datetime('now')),
('c3a4b5c6-d7e8-49a0-1b2c-3d4e5f6a7b8c', 'React', datetime('now'), datetime('now')),
('c4a5b6c7-d8e9-40a1-2b3c-4d5e6f7a8b9c', 'Angular', datetime('now'), datetime('now')),
('c5a6b7c8-d9e0-41a2-3b4c-5d6e7f8a9b0c', 'Vue.js', datetime('now'), datetime('now')),
('c6a7b8c9-d0e1-42a3-4b5c-6d7e8f9a0b1c', 'Node.js', datetime('now'), datetime('now')),
('c7a8b9c0-d1e2-43a4-5b6c-7d8e9f0a1b2c', 'Express.js', datetime('now'), datetime('now')),
('c8a9b0c1-d2e3-44a5-6b7c-8d9e0f1a2b3c', 'Django', datetime('now'), datetime('now')),
('c9a0b1c2-d3e4-45a6-7b8c-9d0e1f2a3b4c', 'Flask', datetime('now'), datetime('now')),
('c0a1b2c3-d4e5-46a7-8b9c-0d1e2f3a4b5c', 'Spring Boot', datetime('now'), datetime('now')),
('d1a2b3c4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Laravel', datetime('now'), datetime('now')),
('d2a3b4c5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Ruby on Rails', datetime('now'), datetime('now')),
('d3a4b5c6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'ASP.NET', datetime('now'), datetime('now')),
('d4a5b6c7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'Next.js', datetime('now'), datetime('now')),
('d5a6b7c8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'Nuxt.js', datetime('now'), datetime('now')),
('d6a7b8c9-e0f1-42a3-4b5c-6d7e8f9a0b1c', 'Svelte', datetime('now'), datetime('now')),
('d7a8b9c0-e1f2-43a4-5b6c-7d8e9f0a1b2c', 'FastAPI', datetime('now'), datetime('now')),
('d8a9b0c1-e2f3-44a5-6b7c-8d9e0f1a2b3c', 'Tailwind CSS', datetime('now'), datetime('now')),
('d9a0b1c2-e3f4-45a6-7b8c-9d0e1f2a3b4c', 'Bootstrap', datetime('now'), datetime('now')),
('d0a1b2c3-e4f5-46a7-8b9c-0d1e2f3a4b5c', 'SASS/SCSS', datetime('now'), datetime('now')),

-- =============================================================================
-- DATABASES & DATA MANAGEMENT (15 skills)
-- =============================================================================
('e1a2b3c4-f5e6-47a8-9b0c-1d2e3f4a5b6c', 'SQL', datetime('now'), datetime('now')),
('e2a3b4c5-f6e7-48a9-0b1c-2d3e4f5a6b7c', 'MySQL', datetime('now'), datetime('now')),
('e3a4b5c6-f7e8-49a0-1b2c-3d4e5f6a7b8c', 'PostgreSQL', datetime('now'), datetime('now')),
('e4a5b6c7-f8e9-40a1-2b3c-4d5e6f7a8b9c', 'MongoDB', datetime('now'), datetime('now')),
('e5a6b7c8-f9e0-41a2-3b4c-5d6e7f8a9b0c', 'Redis', datetime('now'), datetime('now')),
('e6a7b8c9-f0e1-42a3-4b5c-6d7e8f9a0b1c', 'Oracle Database', datetime('now'), datetime('now')),
('e7a8b9c0-f1e2-43a4-5b6c-7d8e9f0a1b2c', 'Microsoft SQL Server', datetime('now'), datetime('now')),
('e8a9b0c1-f2e3-44a5-6b7c-8d9e0f1a2b3c', 'SQLite', datetime('now'), datetime('now')),
('e9a0b1c2-f3e4-45a6-7b8c-9d0e1f2a3b4c', 'Cassandra', datetime('now'), datetime('now')),
('e0a1b2c3-f4e5-46a7-8b9c-0d1e2f3a4b5c', 'DynamoDB', datetime('now'), datetime('now')),
('f1a2b3c4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Elasticsearch', datetime('now'), datetime('now')),
('f2a3b4c5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Neo4j', datetime('now'), datetime('now')),
('f3a4b5c6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Firebase', datetime('now'), datetime('now')),
('f4a5b6c7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'CouchDB', datetime('now'), datetime('now')),
('f5a6b7c8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'MariaDB', datetime('now'), datetime('now')),

-- =============================================================================
-- CLOUD & DEVOPS (15 skills)
-- =============================================================================
('g1a2b3c4-d5e6-47a8-9b0c-1d2e3f4a5b6c', 'AWS', datetime('now'), datetime('now')),
('g2a3b4c5-d6e7-48a9-0b1c-2d3e4f5a6b7c', 'Azure', datetime('now'), datetime('now')),
('g3a4b5c6-d7e8-49a0-1b2c-3d4e5f6a7b8c', 'Google Cloud Platform', datetime('now'), datetime('now')),
('g4a5b6c7-d8e9-40a1-2b3c-4d5e6f7a8b9c', 'Docker', datetime('now'), datetime('now')),
('g5a6b7c8-d9e0-41a2-3b4c-5d6e7f8a9b0c', 'Kubernetes', datetime('now'), datetime('now')),
('g6a7b8c9-d0e1-42a3-4b5c-6d7e8f9a0b1c', 'CI/CD', datetime('now'), datetime('now')),
('g7a8b9c0-d1e2-43a4-5b6c-7d8e9f0a1b2c', 'Jenkins', datetime('now'), datetime('now')),
('g8a9b0c1-d2e3-44a5-6b7c-8d9e0f1a2b3c', 'GitLab CI', datetime('now'), datetime('now')),
('g9a0b1c2-d3e4-45a6-7b8c-9d0e1f2a3b4c', 'GitHub Actions', datetime('now'), datetime('now')),
('g0a1b2c3-d4e5-46a7-8b9c-0d1e2f3a4b5c', 'Terraform', datetime('now'), datetime('now')),
('h1a2b3c4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Ansible', datetime('now'), datetime('now')),
('h2a3b4c5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Puppet', datetime('now'), datetime('now')),
('h3a4b5c6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Chef', datetime('now'), datetime('now')),
('h4a5b6c7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'Nginx', datetime('now'), datetime('now')),
('h5a6b7c8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'Apache', datetime('now'), datetime('now')),

-- =============================================================================
-- DATA SCIENCE & AI/ML (15 skills)
-- =============================================================================
('i1a2b3c4-f5e6-47a8-9b0c-1d2e3f4a5b6c', 'Machine Learning', datetime('now'), datetime('now')),
('i2a3b4c5-f6e7-48a9-0b1c-2d3e4f5a6b7c', 'Deep Learning', datetime('now'), datetime('now')),
('i3a4b5c6-f7e8-49a0-1b2c-3d4e5f6a7b8c', 'Natural Language Processing', datetime('now'), datetime('now')),
('i4a5b6c7-f8e9-40a1-2b3c-4d5e6f7a8b9c', 'Computer Vision', datetime('now'), datetime('now')),
('i5a6b7c8-f9e0-41a2-3b4c-5d6e7f8a9b0c', 'TensorFlow', datetime('now'), datetime('now')),
('i6a7b8c9-f0e1-42a3-4b5c-6d7e8f9a0b1c', 'PyTorch', datetime('now'), datetime('now')),
('i7a8b9c0-f1e2-43a4-5b6c-7d8e9f0a1b2c', 'Scikit-learn', datetime('now'), datetime('now')),
('i8a9b0c1-f2e3-44a5-6b7c-8d9e0f1a2b3c', 'Pandas', datetime('now'), datetime('now')),
('i9a0b1c2-f3e4-45a6-7b8c-9d0e1f2a3b4c', 'NumPy', datetime('now'), datetime('now')),
('i0a1b2c3-f4e5-46a7-8b9c-0d1e2f3a4b5c', 'Data Visualization', datetime('now'), datetime('now')),
('j1a2b3c4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Statistical Analysis', datetime('now'), datetime('now')),
('j2a3b4c5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Data Mining', datetime('now'), datetime('now')),
('j3a4b5c6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Big Data', datetime('now'), datetime('now')),
('j4a5b6c7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'Apache Spark', datetime('now'), datetime('now')),
('j5a6b7c8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'Tableau', datetime('now'), datetime('now')),

-- =============================================================================
-- MOBILE DEVELOPMENT (10 skills)
-- =============================================================================
('k1a2b3c4-d5e6-47a8-9b0c-1d2e3f4a5b6c', 'iOS Development', datetime('now'), datetime('now')),
('k2a3b4c5-d6e7-48a9-0b1c-2d3e4f5a6b7c', 'Android Development', datetime('now'), datetime('now')),
('k3a4b5c6-d7e8-49a0-1b2c-3d4e5f6a7b8c', 'React Native', datetime('now'), datetime('now')),
('k4a5b6c7-d8e9-40a1-2b3c-4d5e6f7a8b9c', 'Flutter', datetime('now'), datetime('now')),
('k5a6b7c8-d9e0-41a2-3b4c-5d6e7f8a9b0c', 'Xamarin', datetime('now'), datetime('now')),
('k6a7b8c9-d0e1-42a3-4b5c-6d7e8f9a0b1c', 'Ionic', datetime('now'), datetime('now')),
('k7a8b9c0-d1e2-43a4-5b6c-7d8e9f0a1b2c', 'SwiftUI', datetime('now'), datetime('now')),
('k8a9b0c1-d2e3-44a5-6b7c-8d9e0f1a2b3c', 'Jetpack Compose', datetime('now'), datetime('now')),
('k9a0b1c2-d3e4-45a6-7b8c-9d0e1f2a3b4c', 'Cordova', datetime('now'), datetime('now')),
('k0a1b2c3-d4e5-46a7-8b9c-0d1e2f3a4b5c', 'Progressive Web Apps', datetime('now'), datetime('now')),

-- =============================================================================
-- TOOLS & VERSION CONTROL (10 skills)
-- =============================================================================
('l1a2b3c4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Git', datetime('now'), datetime('now')),
('l2a3b4c5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'GitHub', datetime('now'), datetime('now')),
('l3a4b5c6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'GitLab', datetime('now'), datetime('now')),
('l4a5b6c7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'Bitbucket', datetime('now'), datetime('now')),
('l5a6b7c8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'JIRA', datetime('now'), datetime('now')),
('l6a7b8c9-e0f1-42a3-4b5c-6d7e8f9a0b1c', 'Trello', datetime('now'), datetime('now')),
('l7a8b9c0-e1f2-43a4-5b6c-7d8e9f0a1b2c', 'Slack', datetime('now'), datetime('now')),
('l8a9b0c1-e2f3-44a5-6b7c-8d9e0f1a2b3c', 'VS Code', datetime('now'), datetime('now')),
('l9a0b1c2-e3f4-45a6-7b8c-9d0e1f2a3b4c', 'IntelliJ IDEA', datetime('now'), datetime('now')),
('l0a1b2c3-e4f5-46a7-8b9c-0d1e2f3a4b5c', 'Eclipse', datetime('now'), datetime('now')),

-- =============================================================================
-- SOFT SKILLS (20 skills)
-- =============================================================================
('m1a2b3c4-f5e6-47a8-9b0c-1d2e3f4a5b6c', 'Communication', datetime('now'), datetime('now')),
('m2a3b4c5-f6e7-48a9-0b1c-2d3e4f5a6b7c', 'Leadership', datetime('now'), datetime('now')),
('m3a4b5c6-f7e8-49a0-1b2c-3d4e5f6a7b8c', 'Team Collaboration', datetime('now'), datetime('now')),
('m4a5b6c7-f8e9-40a1-2b3c-4d5e6f7a8b9c', 'Problem Solving', datetime('now'), datetime('now')),
('m5a6b7c8-f9e0-41a2-3b4c-5d6e7f8a9b0c', 'Critical Thinking', datetime('now'), datetime('now')),
('m6a7b8c9-f0e1-42a3-4b5c-6d7e8f9a0b1c', 'Time Management', datetime('now'), datetime('now')),
('m7a8b9c0-f1e2-43a4-5b6c-7d8e9f0a1b2c', 'Project Management', datetime('now'), datetime('now')),
('m8a9b0c1-f2e3-44a5-6b7c-8d9e0f1a2b3c', 'Agile Methodologies', datetime('now'), datetime('now')),
('m9a0b1c2-f3e4-45a6-7b8c-9d0e1f2a3b4c', 'Scrum', datetime('now'), datetime('now')),
('m0a1b2c3-f4e5-46a7-8b9c-0d1e2f3a4b5c', 'Presentation Skills', datetime('now'), datetime('now')),
('n1a2b3c4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Negotiation', datetime('now'), datetime('now')),
('n2a3b4c5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Conflict Resolution', datetime('now'), datetime('now')),
('n3a4b5c6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Adaptability', datetime('now'), datetime('now')),
('n4a5b6c7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'Creativity', datetime('now'), datetime('now')),
('n5a6b7c8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'Emotional Intelligence', datetime('now'), datetime('now')),
('n6a7b8c9-e0f1-42a3-4b5c-6d7e8f9a0b1c', 'Decision Making', datetime('now'), datetime('now')),
('n7a8b9c0-e1f2-43a4-5b6c-7d8e9f0a1b2c', 'Mentoring', datetime('now'), datetime('now')),
('n8a9b0c1-e2f3-44a5-6b7c-8d9e0f1a2b3c', 'Public Speaking', datetime('now'), datetime('now')),
('n9a0b1c2-e3f4-45a6-7b8c-9d0e1f2a3b4c', 'Research Skills', datetime('now'), datetime('now')),
('n0a1b2c3-e4f5-46a7-8b9c-0d1e2f3a4b5c', 'Attention to Detail', datetime('now'), datetime('now')),

-- =============================================================================
-- SECURITY & TESTING (10 skills)
-- =============================================================================
('o1a2b3c4-d5e6-47a8-9b0c-1d2e3f4a5b6c', 'Cybersecurity', datetime('now'), datetime('now')),
('o2a3b4c5-d6e7-48a9-0b1c-2d3e4f5a6b7c', 'Penetration Testing', datetime('now'), datetime('now')),
('o3a4b5c6-d7e8-49a0-1b2c-3d4e5f6a7b8c', 'Network Security', datetime('now'), datetime('now')),
('o4a5b6c7-d8e9-40a1-2b3c-4d5e6f7a8b9c', 'Unit Testing', datetime('now'), datetime('now')),
('o5a6b7c8-d9e0-41a2-3b4c-5d6e7f8a9b0c', 'Integration Testing', datetime('now'), datetime('now')),
('o6a7b8c9-d0e1-42a3-4b5c-6d7e8f9a0b1c', 'Test Automation', datetime('now'), datetime('now')),
('o7a8b9c0-d1e2-43a4-5b6c-7d8e9f0a1b2c', 'Selenium', datetime('now'), datetime('now')),
('o8a9b0c1-d2e3-44a5-6b7c-8d9e0f1a2b3c', 'Jest', datetime('now'), datetime('now')),
('o9a0b1c2-d3e4-45a6-7b8c-9d0e1f2a3b4c', 'Pytest', datetime('now'), datetime('now')),
('o0a1b2c3-d4e5-46a7-8b9c-0d1e2f3a4b5c', 'JUnit', datetime('now'), datetime('now')),

-- =============================================================================
-- DESIGN & UX/UI (10 skills)
-- =============================================================================
('p1a2b3c4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'UI/UX Design', datetime('now'), datetime('now')),
('p2a3b4c5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Figma', datetime('now'), datetime('now')),
('p3a4b5c6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Adobe XD', datetime('now'), datetime('now')),
('p4a5b6c7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'Sketch', datetime('now'), datetime('now')),
('p5a6b7c8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'Wireframing', datetime('now'), datetime('now')),
('p6a7b8c9-e0f1-42a3-4b5c-6d7e8f9a0b1c', 'Prototyping', datetime('now'), datetime('now')),
('p7a8b9c0-e1f2-43a4-5b6c-7d8e9f0a1b2c', 'User Research', datetime('now'), datetime('now')),
('p8a9b0c1-e2f3-44a5-6b7c-8d9e0f1a2b3c', 'Graphic Design', datetime('now'), datetime('now')),
('p9a0b1c2-e3f4-45a6-7b8c-9d0e1f2a3b4c', 'Adobe Photoshop', datetime('now'), datetime('now')),
('p0a1b2c3-e4f5-46a7-8b9c-0d1e2f3a4b5c', 'Adobe Illustrator', datetime('now'), datetime('now')),

-- =============================================================================
-- BUSINESS & DOMAIN (5 skills)
-- =============================================================================
('q1a2b3c4-f5e6-47a8-9b0c-1d2e3f4a5b6c', 'Business Analysis', datetime('now'), datetime('now')),
('q2a3b4c5-f6e7-48a9-0b1c-2d3e4f5a6b7c', 'Product Management', datetime('now'), datetime('now')),
('q3a4b5c6-f7e8-49a0-1b2c-3d4e5f6a7b8c', 'Digital Marketing', datetime('now'), datetime('now')),
('q4a5b6c7-f8e9-40a1-2b3c-4d5e6f7a8b9c', 'SEO/SEM', datetime('now'), datetime('now')),
('q5a6b7c8-f9e0-41a2-3b4c-5d6e7f8a9b0c', 'Technical Writing', datetime('now'), datetime('now')),

-- =============================================================================
-- FINANCE & ACCOUNTING (15 skills)
-- =============================================================================
('r1a2b3c4-d5e6-47a8-9b0c-1d2e3f4a5b6c', 'Financial Analysis', datetime('now'), datetime('now')),
('r2a3b4c5-d6e7-48a9-0b1c-2d3e4f5a6b7c', 'Accounting', datetime('now'), datetime('now')),
('r3a4b5c6-d7e8-49a0-1b2c-3d4e5f6a7b8c', 'Bookkeeping', datetime('now'), datetime('now')),
('r4a5b6c7-d8e9-40a1-2b3c-4d5e6f7a8b9c', 'Financial Reporting', datetime('now'), datetime('now')),
('r5a6b7c8-d9e0-41a2-3b4c-5d6e7f8a9b0c', 'Budgeting', datetime('now'), datetime('now')),
('r6a7b8c9-d0e1-42a3-4b5c-6d7e8f9a0b1c', 'Cost Analysis', datetime('now'), datetime('now')),
('r7a8b9c0-d1e2-43a4-5b6c-7d8e9f0a1b2c', 'Tax Preparation', datetime('now'), datetime('now')),
('r8a9b0c1-d2e3-44a5-6b7c-8d9e0f1a2b3c', 'Auditing', datetime('now'), datetime('now')),
('r9a0b1c2-d3e4-45a6-7b8c-9d0e1f2a3b4c', 'Payroll Management', datetime('now'), datetime('now')),
('r0a1b2c3-d4e5-46a7-8b9c-0d1e2f3a4b5c', 'QuickBooks', datetime('now'), datetime('now')),
('s1a2b3c4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'SAP', datetime('now'), datetime('now')),
('s2a3b4c5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Oracle Financials', datetime('now'), datetime('now')),
('s3a4b5c6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Investment Analysis', datetime('now'), datetime('now')),
('s4a5b6c7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'Risk Management', datetime('now'), datetime('now')),
('s5a6b7c8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'Excel Advanced', datetime('now'), datetime('now')),

-- =============================================================================
-- SALES & MARKETING (15 skills)
-- =============================================================================
('t1a2b3c4-f5e6-47a8-9b0c-1d2e3f4a5b6c', 'Sales Strategy', datetime('now'), datetime('now')),
('t2a3b4c5-f6e7-48a9-0b1c-2d3e4f5a6b7c', 'Cold Calling', datetime('now'), datetime('now')),
('t3a4b5c6-f7e8-49a0-1b2c-3d4e5f6a7b8c', 'Lead Generation', datetime('now'), datetime('now')),
('t4a5b6c7-f8e9-40a1-2b3c-4d5e6f7a8b9c', 'Customer Relationship Management', datetime('now'), datetime('now')),
('t5a6b7c8-f9e0-41a2-3b4c-5d6e7f8a9b0c', 'Salesforce CRM', datetime('now'), datetime('now')),
('t6a7b8c9-f0e1-42a3-4b5c-6d7e8f9a0b1c', 'Brand Management', datetime('now'), datetime('now')),
('t7a8b9c0-f1e2-43a4-5b6c-7d8e9f0a1b2c', 'Content Marketing', datetime('now'), datetime('now')),
('t8a9b0c1-f2e3-44a5-6b7c-8d9e0f1a2b3c', 'Social Media Marketing', datetime('now'), datetime('now')),
('t9a0b1c2-f3e4-45a6-7b8c-9d0e1f2a3b4c', 'Email Marketing', datetime('now'), datetime('now')),
('t0a1b2c3-f4e5-46a7-8b9c-0d1e2f3a4b5c', 'Market Research', datetime('now'), datetime('now')),
('u1a2b3c4-d5e6-47a8-9b0c-1d2e3f4a5b6c', 'Advertising', datetime('now'), datetime('now')),
('u2a3b4c5-d6e7-48a9-0b1c-2d3e4f5a6b7c', 'Copywriting', datetime('now'), datetime('now')),
('u3a4b5c6-d7e8-49a0-1b2c-3d4e5f6a7b8c', 'Customer Service', datetime('now'), datetime('now')),
('u4a5b6c7-d8e9-40a1-2b3c-4d5e6f7a8b9c', 'B2B Sales', datetime('now'), datetime('now')),
('u5a6b7c8-d9e0-41a2-3b4c-5d6e7f8a9b0c', 'B2C Sales', datetime('now'), datetime('now')),

-- =============================================================================
-- HEALTHCARE & MEDICAL (15 skills)
-- =============================================================================
('v1a2b3c4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Patient Care', datetime('now'), datetime('now')),
('v2a3b4c5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Medical Terminology', datetime('now'), datetime('now')),
('v3a4b5c6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Clinical Documentation', datetime('now'), datetime('now')),
('v4a5b6c7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'EMR/EHR Systems', datetime('now'), datetime('now')),
('v5a6b7c8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'Medical Coding', datetime('now'), datetime('now')),
('v6a7b8c9-e0f1-42a3-4b5c-6d7e8f9a0b1c', 'ICD-10', datetime('now'), datetime('now')),
('v7a8b9c0-e1f2-43a4-5b6c-7d8e9f0a1b2c', 'CPT Coding', datetime('now'), datetime('now')),
('v8a9b0c1-e2f3-44a5-6b7c-8d9e0f1a2b3c', 'Pharmacy Knowledge', datetime('now'), datetime('now')),
('v9a0b1c2-e3f4-45a6-7b8c-9d0e1f2a3b4c', 'Nursing Care', datetime('now'), datetime('now')),
('v0a1b2c3-e4f5-46a7-8b9c-0d1e2f3a4b5c', 'First Aid/CPR', datetime('now'), datetime('now')),
('w1a2b3c4-f5e6-47a8-9b0c-1d2e3f4a5b6c', 'Medical Billing', datetime('now'), datetime('now')),
('w2a3b4c5-f6e7-48a9-0b1c-2d3e4f5a6b7c', 'Healthcare Compliance', datetime('now'), datetime('now')),
('w3a4b5c6-f7e8-49a0-1b2c-3d4e5f6a7b8c', 'HIPAA Compliance', datetime('now'), datetime('now')),
('w4a5b6c7-f8e9-40a1-2b3c-4d5e6f7a8b9c', 'Phlebotomy', datetime('now'), datetime('now')),
('w5a6b7c8-f9e0-41a2-3b4c-5d6e7f8a9b0c', 'Laboratory Skills', datetime('now'), datetime('now')),

-- =============================================================================
-- LEGAL & COMPLIANCE (10 skills)
-- =============================================================================
('x1a2b3c4-d5e6-47a8-9b0c-1d2e3f4a5b6c', 'Legal Research', datetime('now'), datetime('now')),
('x2a3b4c5-d6e7-48a9-0b1c-2d3e4f5a6b7c', 'Contract Law', datetime('now'), datetime('now')),
('x3a4b5c6-d7e8-49a0-1b2c-3d4e5f6a7b8c', 'Contract Drafting', datetime('now'), datetime('now')),
('x4a5b6c7-d8e9-40a1-2b3c-4d5e6f7a8b9c', 'Regulatory Compliance', datetime('now'), datetime('now')),
('x5a6b7c8-d9e0-41a2-3b4c-5d6e7f8a9b0c', 'Corporate Governance', datetime('now'), datetime('now')),
('x6a7b8c9-d0e1-42a3-4b5c-6d7e8f9a0b1c', 'Intellectual Property', datetime('now'), datetime('now')),
('x7a8b9c0-d1e2-43a4-5b6c-7d8e9f0a1b2c', 'Labor Law', datetime('now'), datetime('now')),
('x8a9b0c1-d2e3-44a5-6b7c-8d9e0f1a2b3c', 'Paralegal Skills', datetime('now'), datetime('now')),
('x9a0b1c2-d3e4-45a6-7b8c-9d0e1f2a3b4c', 'Litigation Support', datetime('now'), datetime('now')),
('x0a1b2c3-d4e5-46a7-8b9c-0d1e2f3a4b5c', 'Legal Writing', datetime('now'), datetime('now')),

-- =============================================================================
-- HUMAN RESOURCES (15 skills)
-- =============================================================================
('y1a2b3c4-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Recruitment', datetime('now'), datetime('now')),
('y2a3b4c5-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Talent Acquisition', datetime('now'), datetime('now')),
('y3a4b5c6-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Interviewing', datetime('now'), datetime('now')),
('y4a5b6c7-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'Onboarding', datetime('now'), datetime('now')),
('y5a6b7c8-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'Performance Management', datetime('now'), datetime('now')),
('y6a7b8c9-e0f1-42a3-4b5c-6d7e8f9a0b1c', 'Employee Relations', datetime('now'), datetime('now')),
('y7a8b9c0-e1f2-43a4-5b6c-7d8e9f0a1b2c', 'Training & Development', datetime('now'), datetime('now')),
('y8a9b0c1-e2f3-44a5-6b7c-8d9e0f1a2b3c', 'Compensation & Benefits', datetime('now'), datetime('now')),
('y9a0b1c2-e3f4-45a6-7b8c-9d0e1f2a3b4c', 'HR Policies', datetime('now'), datetime('now')),
('y0a1b2c3-e4f5-46a7-8b9c-0d1e2f3a4b5c', 'Workforce Planning', datetime('now'), datetime('now')),
('z1a2b3c4-f5e6-47a8-9b0c-1d2e3f4a5b6c', 'Employee Engagement', datetime('now'), datetime('now')),
('z2a3b4c5-f6e7-48a9-0b1c-2d3e4f5a6b7c', 'HRIS Systems', datetime('now'), datetime('now')),
('z3a4b5c6-f7e8-49a0-1b2c-3d4e5f6a7b8c', 'Diversity & Inclusion', datetime('now'), datetime('now')),
('z4a5b6c7-f8e9-40a1-2b3c-4d5e6f7a8b9c', 'Change Management', datetime('now'), datetime('now')),
('z5a6b7c8-f9e0-41a2-3b4c-5d6e7f8a9b0c', 'Organizational Development', datetime('now'), datetime('now')),

-- =============================================================================
-- OPERATIONS & LOGISTICS (15 skills)
-- =============================================================================
('aa1a2b3c-d5e6-47a8-9b0c-1d2e3f4a5b6c', 'Supply Chain Management', datetime('now'), datetime('now')),
('aa2a3b4c-d6e7-48a9-0b1c-2d3e4f5a6b7c', 'Inventory Management', datetime('now'), datetime('now')),
('aa3a4b5c-d7e8-49a0-1b2c-3d4e5f6a7b8c', 'Logistics Coordination', datetime('now'), datetime('now')),
('aa4a5b6c-d8e9-40a1-2b3c-4d5e6f7a8b9c', 'Warehouse Management', datetime('now'), datetime('now')),
('aa5a6b7c-d9e0-41a2-3b4c-5d6e7f8a9b0c', 'Procurement', datetime('now'), datetime('now')),
('aa6a7b8c-d0e1-42a3-4b5c-6d7e8f9a0b1c', 'Vendor Management', datetime('now'), datetime('now')),
('aa7a8b9c-d1e2-43a4-5b6c-7d8e9f0a1b2c', 'Quality Control', datetime('now'), datetime('now')),
('aa8a9b0c-d2e3-44a5-6b7c-8d9e0f1a2b3c', 'Process Improvement', datetime('now'), datetime('now')),
('aa9a0b1c-d3e4-45a6-7b8c-9d0e1f2a3b4c', 'Lean Manufacturing', datetime('now'), datetime('now')),
('aa0a1b2c-d4e5-46a7-8b9c-0d1e2f3a4b5c', 'Six Sigma', datetime('now'), datetime('now')),
('ab1a2b3c-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Transportation Management', datetime('now'), datetime('now')),
('ab2a3b4c-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'ERP Systems', datetime('now'), datetime('now')),
('ab3a4b5c-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Scheduling', datetime('now'), datetime('now')),
('ab4a5b6c-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'Distribution Management', datetime('now'), datetime('now')),
('ab5a6b7c-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'Forecasting', datetime('now'), datetime('now')),

-- =============================================================================
-- EDUCATION & TRAINING (10 skills)
-- =============================================================================
('ac1a2b3c-f5e6-47a8-9b0c-1d2e3f4a5b6c', 'Curriculum Development', datetime('now'), datetime('now')),
('ac2a3b4c-f6e7-48a9-0b1c-2d3e4f5a6b7c', 'Instructional Design', datetime('now'), datetime('now')),
('ac3a4b5c-f7e8-49a0-1b2c-3d4e5f6a7b8c', 'Classroom Management', datetime('now'), datetime('now')),
('ac4a5b6c-f8e9-40a1-2b3c-4d5e6f7a8b9c', 'E-Learning', datetime('now'), datetime('now')),
('ac5a6b7c-f9e0-41a2-3b4c-5d6e7f8a9b0c', 'Corporate Training', datetime('now'), datetime('now')),
('ac6a7b8c-f0e1-42a3-4b5c-6d7e8f9a0b1c', 'Tutoring', datetime('now'), datetime('now')),
('ac7a8b9c-f1e2-43a4-5b6c-7d8e9f0a1b2c', 'Educational Assessment', datetime('now'), datetime('now')),
('ac8a9b0c-f2e3-44a5-6b7c-8d9e0f1a2b3c', 'Special Education', datetime('now'), datetime('now')),
('ac9a0b1c-f3e4-45a6-7b8c-9d0e1f2a3b4c', 'Learning Management Systems', datetime('now'), datetime('now')),
('ac0a1b2c-f4e5-46a7-8b9c-0d1e2f3a4b5c', 'Adult Education', datetime('now'), datetime('now')),

-- =============================================================================
-- HOSPITALITY & TOURISM (10 skills)
-- =============================================================================
('ad1a2b3c-d5e6-47a8-9b0c-1d2e3f4a5b6c', 'Hotel Management', datetime('now'), datetime('now')),
('ad2a3b4c-d6e7-48a9-0b1c-2d3e4f5a6b7c', 'Restaurant Management', datetime('now'), datetime('now')),
('ad3a4b5c-d7e8-49a0-1b2c-3d4e5f6a7b8c', 'Food & Beverage Service', datetime('now'), datetime('now')),
('ad4a5b6c-d8e9-40a1-2b3c-4d5e6f7a8b9c', 'Event Planning', datetime('now'), datetime('now')),
('ad5a6b7c-d9e0-41a2-3b4c-5d6e7f8a9b0c', 'Hospitality', datetime('now'), datetime('now')),
('ad6a7b8c-d0e1-42a3-4b5c-6d7e8f9a0b1c', 'Travel Planning', datetime('now'), datetime('now')),
('ad7a8b9c-d1e2-43a4-5b6c-7d8e9f0a1b2c', 'Tourism Management', datetime('now'), datetime('now')),
('ad8a9b0c-d2e3-44a5-6b7c-8d9e0f1a2b3c', 'Culinary Arts', datetime('now'), datetime('now')),
('ad9a0b1c-d3e4-45a6-7b8c-9d0e1f2a3b4c', 'Front Desk Operations', datetime('now'), datetime('now')),
('ad0a1b2c-d4e5-46a7-8b9c-0d1e2f3a4b5c', 'Housekeeping Management', datetime('now'), datetime('now')),

-- =============================================================================
-- CONSTRUCTION & TRADES (15 skills)
-- =============================================================================
('ae1a2b3c-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Carpentry', datetime('now'), datetime('now')),
('ae2a3b4c-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Electrical Work', datetime('now'), datetime('now')),
('ae3a4b5c-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Plumbing', datetime('now'), datetime('now')),
('ae4a5b6c-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'Welding', datetime('now'), datetime('now')),
('ae5a6b7c-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'HVAC', datetime('now'), datetime('now')),
('ae6a7b8c-e0f1-42a3-4b5c-6d7e8f9a0b1c', 'Construction Management', datetime('now'), datetime('now')),
('ae7a8b9c-e1f2-43a4-5b6c-7d8e9f0a1b2c', 'Blueprint Reading', datetime('now'), datetime('now')),
('ae8a9b0c-e2f3-44a5-6b7c-8d9e0f1a2b3c', 'Heavy Equipment Operation', datetime('now'), datetime('now')),
('ae9a0b1c-e3f4-45a6-7b8c-9d0e1f2a3b4c', 'Masonry', datetime('now'), datetime('now')),
('ae0a1b2c-e4f5-46a7-8b9c-0d1e2f3a4b5c', 'Painting & Finishing', datetime('now'), datetime('now')),
('af1a2b3c-f5e6-47a8-9b0c-1d2e3f4a5b6c', 'Roofing', datetime('now'), datetime('now')),
('af2a3b4c-f6e7-48a9-0b1c-2d3e4f5a6b7c', 'Safety Compliance', datetime('now'), datetime('now')),
('af3a4b5c-f7e8-49a0-1b2c-3d4e5f6a7b8c', 'Project Estimation', datetime('now'), datetime('now')),
('af4a5b6c-f8e9-40a1-2b3c-4d5e6f7a8b9c', 'CAD for Construction', datetime('now'), datetime('now')),
('af5a6b7c-f9e0-41a2-3b4c-5d6e7f8a9b0c', 'Building Codes Knowledge', datetime('now'), datetime('now')),

-- =============================================================================
-- LANGUAGE & COMMUNICATION (10 skills)
-- =============================================================================
('ag1a2b3c-d5e6-47a8-9b0c-1d2e3f4a5b6c', 'Translation', datetime('now'), datetime('now')),
('ag2a3b4c-d6e7-48a9-0b1c-2d3e4f5a6b7c', 'Interpretation', datetime('now'), datetime('now')),
('ag3a4b5c-d7e8-49a0-1b2c-3d4e5f6a7b8c', 'Bilingual Communication', datetime('now'), datetime('now')),
('ag4a5b6c-d8e9-40a1-2b3c-4d5e6f7a8b9c', 'Multilingual', datetime('now'), datetime('now')),
('ag5a6b7c-d9e0-41a2-3b4c-5d6e7f8a9b0c', 'Business Writing', datetime('now'), datetime('now')),
('ag6a7b8c-d0e1-42a3-4b5c-6d7e8f9a0b1c', 'Report Writing', datetime('now'), datetime('now')),
('ag7a8b9c-d1e2-43a4-5b6c-7d8e9f0a1b2c', 'Editing & Proofreading', datetime('now'), datetime('now')),
('ag8a9b0c-d2e3-44a5-6b7c-8d9e0f1a2b3c', 'Grant Writing', datetime('now'), datetime('now')),
('ag9a0b1c-d3e4-45a6-7b8c-9d0e1f2a3b4c', 'Journalism', datetime('now'), datetime('now')),
('ag0a1b2c-d4e5-46a7-8b9c-0d1e2f3a4b5c', 'Broadcasting', datetime('now'), datetime('now')),

-- =============================================================================
-- ADMINISTRATIVE & CLERICAL (10 skills)
-- =============================================================================
('ah1a2b3c-e5f6-47a8-9b0c-1d2e3f4a5b6c', 'Data Entry', datetime('now'), datetime('now')),
('ah2a3b4c-e6f7-48a9-0b1c-2d3e4f5a6b7c', 'Office Management', datetime('now'), datetime('now')),
('ah3a4b5c-e7f8-49a0-1b2c-3d4e5f6a7b8c', 'Typing Speed', datetime('now'), datetime('now')),
('ah4a5b6c-e8f9-40a1-2b3c-4d5e6f7a8b9c', 'Filing & Documentation', datetime('now'), datetime('now')),
('ah5a6b7c-e9f0-41a2-3b4c-5d6e7f8a9b0c', 'Calendar Management', datetime('now'), datetime('now')),
('ah6a7b8c-e0f1-42a3-4b5c-6d7e8f9a0b1c', 'Meeting Coordination', datetime('now'), datetime('now')),
('ah7a8b9c-e1f2-43a4-5b6c-7d8e9f0a1b2c', 'Travel Arrangements', datetime('now'), datetime('now')),
('ah8a9b0c-e2f3-44a5-6b7c-8d9e0f1a2b3c', 'Reception', datetime('now'), datetime('now')),
('ah9a0b1c-e3f4-45a6-7b8c-9d0e1f2a3b4c', 'Microsoft Office Suite', datetime('now'), datetime('now')),
('ah0a1b2c-e4f5-46a7-8b9c-0d1e2f3a4b5c', 'Records Management', datetime('now'), datetime('now')),

-- =============================================================================
-- REAL ESTATE (10 skills)
-- =============================================================================
('ai1a2b3c-f5e6-47a8-9b0c-1d2e3f4a5b6c', 'Property Management', datetime('now'), datetime('now')),
('ai2a3b4c-f6e7-48a9-0b1c-2d3e4f5a6b7c', 'Real Estate Sales', datetime('now'), datetime('now')),
('ai3a4b5c-f7e8-49a0-1b2c-3d4e5f6a7b8c', 'Leasing', datetime('now'), datetime('now')),
('ai4a5b6c-f8e9-40a1-2b3c-4d5e6f7a8b9c', 'Property Valuation', datetime('now'), datetime('now')),
('ai5a6b7c-f9e0-41a2-3b4c-5d6e7f8a9b0c', 'Real Estate Law', datetime('now'), datetime('now')),
('ai6a7b8c-f0e1-42a3-4b5c-6d7e8f9a0b1c', 'Tenant Relations', datetime('now'), datetime('now')),
('ai7a8b9c-f1e2-43a4-5b6c-7d8e9f0a1b2c', 'Facilities Management', datetime('now'), datetime('now')),
('ai8a9b0c-f2e3-44a5-6b7c-8d9e0f1a2b3c', 'Real Estate Marketing', datetime('now'), datetime('now')),
('ai9a0b1c-f3e4-45a6-7b8c-9d0e1f2a3b4c', 'Property Inspection', datetime('now'), datetime('now')),
('ai0a1b2c-f4e5-46a7-8b9c-0d1e2f3a4b5c', 'Contract Negotiation', datetime('now'), datetime('now'));
