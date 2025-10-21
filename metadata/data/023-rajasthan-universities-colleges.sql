-- Major Universities and Colleges of Rajasthan, India
-- This file populates the organization table with renowned educational institutions in Rajasthan

-- Using valid foreign keys:
-- legal_category_id: l00000000-0006-4000-0000-000060000000 (Non-Profit Corporation)
-- industry_id: ind-006-education (Education & Training)
-- admin_id: 00000000-0000-4000-8000-000000000001 (Valid person ID)

-- ============================================================================
-- RAJASTHAN - State Government Universities
-- ============================================================================

INSERT INTO organization (id, short_name, legal_category_id, tag_line, description, website, admin_id, industry_id, established_date, status, is_verified, is_featured, rating, created_at, updated_at)
VALUES
('c1d2e3f4-3001-4a5b-8c9d-0e1f2a3b4c5d', 'University of Rajasthan', 'l00000000-0006-4000-0000-000060000000', 'Dharmam Sarvam Samyati', 'University of Rajasthan is the oldest and premier state university in Jaipur', 'https://www.uniraj.ac.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '1947-01-08', 'ACTIVE', 1, 1, 4.3, datetime('now'), datetime('now')),
('c1d2e3f4-3002-4a5b-8c9d-0e1f2a3b4c5d', 'Rajasthan Technical University', 'l00000000-0006-4000-0000-000060000000', 'Technical Education Excellence', 'Rajasthan Technical University (RTU) is the premier technical university in Kota', 'https://www.rtu.ac.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '2006-01-01', 'ACTIVE', 1, 1, 4.1, datetime('now'), datetime('now')),
('c1d2e3f4-3003-4a5b-8c9d-0e1f2a3b4c5d', 'Maharaja Ganga Singh University', 'l00000000-0006-4000-0000-000060000000', 'Knowledge and Wisdom', 'MGSU is a premier state university in Bikaner, Rajasthan', 'https://www.mgsubikaner.ac.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '2003-11-11', 'ACTIVE', 1, 1, 4.0, datetime('now'), datetime('now')),
('c1d2e3f4-3004-4a5b-8c9d-0e1f2a3b4c5d', 'Jai Narain Vyas University', 'l00000000-0006-4000-0000-000060000000', 'Education for All', 'JNVU is a state university in Jodhpur serving western Rajasthan', 'https://www.jnvu.edu.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '1962-08-24', 'ACTIVE', 1, 1, 4.0, datetime('now'), datetime('now')),
('c1d2e3f4-3005-4a5b-8c9d-0e1f2a3b4c5d', 'Mohanlal Sukhadia University', 'l00000000-0006-4000-0000-000060000000', 'Serving Southern Rajasthan', 'MLSU is a premier state university in Udaipur', 'https://www.mlsu.ac.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '1962-07-17', 'ACTIVE', 1, 1, 4.1, datetime('now'), datetime('now')),
('c1d2e3f4-3006-4a5b-8c9d-0e1f2a3b4c5d', 'Kota University', 'l00000000-0006-4000-0000-000060000000', 'Quality Education', 'University of Kota serves the educational needs of Kota and Baran districts', 'https://www.uok.ac.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '2003-05-21', 'ACTIVE', 1, 0, 3.9, datetime('now'), datetime('now')),
('c1d2e3f4-3007-4a5b-8c9d-0e1f2a3b4c5d', 'Banasthali Vidyapith', 'l00000000-0006-4000-0000-000060000000', 'Women Empowerment Through Education', 'Banasthali Vidyapith is a premier deemed university for women in Tonk district', 'https://www.banasthali.org', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '1935-10-06', 'ACTIVE', 1, 1, 4.4, datetime('now'), datetime('now')),
('c1d2e3f4-3008-4a5b-8c9d-0e1f2a3b4c5d', 'Maharshi Dayanand Saraswati University', 'l00000000-0006-4000-0000-000060000000', 'Promoting Higher Education', 'MDSU is a state university in Ajmer', 'https://www.mdsuajmer.ac.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '1987-08-01', 'ACTIVE', 1, 0, 3.9, datetime('now'), datetime('now'));

-- ============================================================================
-- RAJASTHAN - Central Universities
-- ============================================================================

INSERT INTO organization (id, short_name, legal_category_id, tag_line, description, website, admin_id, industry_id, established_date, status, is_verified, is_featured, rating, created_at, updated_at)
VALUES
('c1d2e3f4-3009-4a5b-8c9d-0e1f2a3b4c5d', 'Central University of Rajasthan', 'l00000000-0006-4000-0000-000060000000', 'Knowledge Leads to Service', 'CURAJ is a central university established in Ajmer district', 'https://www.curaj.ac.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '2009-03-20', 'ACTIVE', 1, 1, 4.2, datetime('now'), datetime('now'));

-- ============================================================================
-- RAJASTHAN - Engineering & Technical Institutions
-- ============================================================================

INSERT INTO organization (id, short_name, legal_category_id, tag_line, description, website, admin_id, industry_id, established_date, status, is_verified, is_featured, rating, created_at, updated_at)
VALUES
('c1d2e3f4-3010-4a5b-8c9d-0e1f2a3b4c5d', 'Malaviya National Institute of Technology Jaipur', 'l00000000-0006-4000-0000-000060000000', 'MNIT - National Institute of Excellence', 'MNIT Jaipur is one of the premier NITs in India, formerly MBM Engineering College', 'https://www.mnit.ac.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '1963-07-26', 'ACTIVE', 1, 1, 4.6, datetime('now'), datetime('now')),
('c1d2e3f4-3011-4a5b-8c9d-0e1f2a3b4c5d', 'IIT Jodhpur', 'l00000000-0006-4000-0000-000060000000', 'Innovation and Excellence', 'Indian Institute of Technology Jodhpur is among the newer IITs', 'https://www.iitj.ac.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '2008-08-01', 'ACTIVE', 1, 1, 4.5, datetime('now'), datetime('now')),
('c1d2e3f4-3012-4a5b-8c9d-0e1f2a3b4c5d', 'College of Technology and Engineering', 'l00000000-0006-4000-0000-000060000000', 'CTAE Udaipur', 'CTAE is a premier engineering college affiliated with MLSU, Udaipur', 'https://www.ctae.ac.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '2012-01-01', 'ACTIVE', 1, 0, 4.0, datetime('now'), datetime('now')),
('c1d2e3f4-3013-4a5b-8c9d-0e1f2a3b4c5d', 'Government Engineering College Ajmer', 'l00000000-0006-4000-0000-000060000000', 'Technical Excellence', 'GEC Ajmer is a premier government engineering college', 'https://www.ecajmer.ac.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '1997-01-01', 'ACTIVE', 1, 0, 4.0, datetime('now'), datetime('now')),
('c1d2e3f4-3014-4a5b-8c9d-0e1f2a3b4c5d', 'Government Engineering College Bikaner', 'l00000000-0006-4000-0000-000060000000', 'Engineering for Progress', 'GEC Bikaner is a government engineering college in Bikaner', 'https://www.gecbikaner.ac.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '2011-08-01', 'ACTIVE', 1, 0, 3.9, datetime('now'), datetime('now')),
('c1d2e3f4-3015-4a5b-8c9d-0e1f2a3b4c5d', 'Poornima University', 'l00000000-0006-4000-0000-000060000000', 'Knowledge Wisdom Service', 'Poornima University is a private university in Jaipur', 'https://www.poornima.edu.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '2012-01-01', 'ACTIVE', 1, 0, 4.1, datetime('now'), datetime('now')),
('c1d2e3f4-3041-4a5b-8c9d-0e1f2a3b4c5d', 'MBM University', 'l00000000-0006-4000-0000-000060000000', 'An institute with a remarkable legacy, most diverse offerings and a huge family!', 'At MBM University, we strive to keep the quality of our academics, research and innovation at priority to keep up with the pace of the technological advancement.', 'https://mbm.ac.in/', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '2012-01-01', 'ACTIVE', 1, 0, 4.1, datetime('now'), datetime('now'));


-- ============================================================================
-- RAJASTHAN - Medical Institutions
-- ============================================================================

INSERT INTO organization (id, short_name, legal_category_id, tag_line, description, website, admin_id, industry_id, established_date, status, is_verified, is_featured, rating, created_at, updated_at)
VALUES
('c1d2e3f4-3016-4a5b-8c9d-0e1f2a3b4c5d', 'AIIMS Jodhpur', 'l00000000-0006-4000-0000-000060000000', 'Premier Medical Institute', 'All India Institute of Medical Sciences Jodhpur', 'https://www.aiimsjodhpur.edu.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '2012-09-27', 'ACTIVE', 1, 1, 4.7, datetime('now'), datetime('now')),
('c1d2e3f4-3017-4a5b-8c9d-0e1f2a3b4c5d', 'SMS Medical College', 'l00000000-0006-4000-0000-000060000000', 'Excellence in Medical Education', 'Sawai Man Singh Medical College is a premier medical college in Jaipur', 'https://www.smsmedicalcollege.com', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '1947-01-01', 'ACTIVE', 1, 1, 4.4, datetime('now'), datetime('now')),
('c1d2e3f4-3018-4a5b-8c9d-0e1f2a3b4c5d', 'RNT Medical College', 'l00000000-0006-4000-0000-000060000000', 'Medical Excellence', 'Rabindranath Tagore Medical College is a premier medical college in Udaipur', 'https://www.rntmc.org', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '1963-01-01', 'ACTIVE', 1, 0, 4.2, datetime('now'), datetime('now')),
('c1d2e3f4-3019-4a5b-8c9d-0e1f2a3b4c5d', 'Dr. S.N. Medical College', 'l00000000-0006-4000-0000-000060000000', 'Healthcare Education', 'Dr. Sampurnanand Medical College is a government medical college in Jodhpur', 'https://www.snmc.org.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '1965-01-01', 'ACTIVE', 1, 0, 4.1, datetime('now'), datetime('now')),
('c1d2e3f4-3020-4a5b-8c9d-0e1f2a3b4c5d', 'Jhalawar Medical College', 'l00000000-0006-4000-0000-000060000000', 'Serving Rural Healthcare', 'Government Medical College Jhalawar', 'https://www.jmcjhalawar.org', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '2008-01-01', 'ACTIVE', 1, 0, 3.9, datetime('now'), datetime('now'));

-- ============================================================================
-- RAJASTHAN - Management Institutions
-- ============================================================================

INSERT INTO organization (id, short_name, legal_category_id, tag_line, description, website, admin_id, industry_id, established_date, status, is_verified, is_featured, rating, created_at, updated_at)
VALUES
('c1d2e3f4-3021-4a5b-8c9d-0e1f2a3b4c5d', 'IIM Udaipur', 'l00000000-0006-4000-0000-000060000000', 'Transcending Boundaries', 'Indian Institute of Management Udaipur is among the newer IIMs', 'https://www.iimu.ac.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '2011-09-01', 'ACTIVE', 1, 1, 4.6, datetime('now'), datetime('now')),
('c1d2e3f4-3022-4a5b-8c9d-0e1f2a3b4c5d', 'Department of Management Studies MLSU', 'l00000000-0006-4000-0000-000060000000', 'Management Education', 'DMS MLSU offers MBA programs under MLSU Udaipur', 'https://www.mlsu.ac.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '1995-01-01', 'ACTIVE', 1, 0, 3.8, datetime('now'), datetime('now'));

-- ============================================================================
-- RAJASTHAN - Law Colleges
-- ============================================================================

INSERT INTO organization (id, short_name, legal_category_id, tag_line, description, website, admin_id, industry_id, established_date, status, is_verified, is_featured, rating, created_at, updated_at)
VALUES
('c1d2e3f4-3023-4a5b-8c9d-0e1f2a3b4c5d', 'University Law College Jodhpur', 'l00000000-0006-4000-0000-000060000000', 'Legal Education Excellence', 'ULC Jodhpur is the premier law college in western Rajasthan', 'https://www.jnvu.edu.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '1978-01-01', 'ACTIVE', 1, 0, 4.0, datetime('now'), datetime('now')),
('c1d2e3f4-3024-4a5b-8c9d-0e1f2a3b4c5d', 'Rajasthan University Law College', 'l00000000-0006-4000-0000-000060000000', 'Justice Through Education', 'RULC is a premier law college affiliated with University of Rajasthan', 'https://www.uniraj.ac.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '1964-01-01', 'ACTIVE', 1, 0, 4.1, datetime('now'), datetime('now'));

-- ============================================================================
-- RAJASTHAN - Private Universities
-- ============================================================================

INSERT INTO organization (id, short_name, legal_category_id, tag_line, description, website, admin_id, industry_id, established_date, status, is_verified, is_featured, rating, created_at, updated_at)
VALUES
('c1d2e3f4-3025-4a5b-8c9d-0e1f2a3b4c5d', 'BITS Pilani Pilani Campus', 'l00000000-0006-4000-0000-000060000000', 'Knowledge is Power', 'Birla Institute of Technology and Science main campus in Pilani', 'https://www.bits-pilani.ac.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '1964-07-15', 'ACTIVE', 1, 1, 4.7, datetime('now'), datetime('now')),
('c1d2e3f4-3026-4a5b-8c9d-0e1f2a3b4c5d', 'Manipal University Jaipur', 'l00000000-0006-4000-0000-000060000000', 'Inspiring Excellence', 'Manipal University Jaipur is a premier private university', 'https://www.jaipur.manipal.edu', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '2011-07-06', 'ACTIVE', 1, 0, 4.2, datetime('now'), datetime('now')),
('c1d2e3f4-3027-4a5b-8c9d-0e1f2a3b4c5d', 'LNM Institute of Information Technology', 'l00000000-0006-4000-0000-000060000000', 'Innovation and Technology', 'LNMIIT is a premier private engineering and management institute in Jaipur', 'https://www.lnmiit.ac.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '2003-01-01', 'ACTIVE', 1, 1, 4.4, datetime('now'), datetime('now')),
('c1d2e3f4-3028-4a5b-8c9d-0e1f2a3b4c5d', 'Jaipur National University', 'l00000000-0006-4000-0000-000060000000', 'Shaping Future Leaders', 'JNU Jaipur is a private university offering diverse programs', 'https://www.jnujaipur.ac.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '2007-01-01', 'ACTIVE', 1, 0, 4.0, datetime('now'), datetime('now')),
('c1d2e3f4-3029-4a5b-8c9d-0e1f2a3b4c5d', 'The IIS University', 'l00000000-0006-4000-0000-000060000000', 'Women Education Pioneer', 'IIS University is a premier private women university in Jaipur', 'https://www.iisuniv.ac.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '1995-01-01', 'ACTIVE', 1, 0, 4.1, datetime('now'), datetime('now')),
('c1d2e3f4-3030-4a5b-8c9d-0e1f2a3b4c5d', 'Jagannath University', 'l00000000-0006-4000-0000-000060000000', 'Quality Education for All', 'Jagannath University is a private university in Jaipur', 'https://www.jagannathuniversity.org', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '2008-01-01', 'ACTIVE', 1, 0, 3.9, datetime('now'), datetime('now')),
('c1d2e3f4-3031-4a5b-8c9d-0e1f2a3b4c5d', 'Suresh Gyan Vihar University', 'l00000000-0006-4000-0000-000060000000', 'Excellence in Education', 'SGVU is a private university in Jaipur offering diverse programs', 'https://www.gyanvihar.org', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '2008-01-01', 'ACTIVE', 1, 0, 3.8, datetime('now'), datetime('now')),
('c1d2e3f4-3032-4a5b-8c9d-0e1f2a3b4c5d', 'Amity University Rajasthan', 'l00000000-0006-4000-0000-000060000000', 'Knowledge for Life', 'Amity University Rajasthan campus in Jaipur', 'https://www.amity.edu/jaipur', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '2008-01-01', 'ACTIVE', 1, 0, 4.0, datetime('now'), datetime('now'));

-- ============================================================================
-- RAJASTHAN - Deemed Universities
-- ============================================================================

INSERT INTO organization (id, short_name, legal_category_id, tag_line, description, website, admin_id, industry_id, established_date, status, is_verified, is_featured, rating, created_at, updated_at)
VALUES
('c1d2e3f4-3033-4a5b-8c9d-0e1f2a3b4c5d', 'NIMS University', 'l00000000-0006-4000-0000-000060000000', 'Health Sciences Education', 'NIMS University is a deemed university focused on medical and health sciences in Jaipur', 'https://www.nimsuniversity.org', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '2008-01-01', 'ACTIVE', 1, 0, 4.0, datetime('now'), datetime('now')),
('c1d2e3f4-3034-4a5b-8c9d-0e1f2a3b4c5d', 'Jayoti Vidyapeeth Women University', 'l00000000-0006-4000-0000-000060000000', 'Women Empowerment', 'JVWU is a women university in Jaipur', 'https://www.jvwu.ac.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '2008-01-01', 'ACTIVE', 1, 0, 3.8, datetime('now'), datetime('now'));

-- ============================================================================
-- RAJASTHAN - Agriculture Universities
-- ============================================================================

INSERT INTO organization (id, short_name, legal_category_id, tag_line, description, website, admin_id, industry_id, established_date, status, is_verified, is_featured, rating, created_at, updated_at)
VALUES
('c1d2e3f4-3035-4a5b-8c9d-0e1f2a3b4c5d', 'Maharana Pratap University of Agriculture and Technology', 'l00000000-0006-4000-0000-000060000000', 'Agricultural Excellence', 'MPUAT is the premier agriculture university in Rajasthan, located in Udaipur', 'https://www.mpuat.ac.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '1999-11-01', 'ACTIVE', 1, 1, 4.2, datetime('now'), datetime('now')),
('c1d2e3f4-3036-4a5b-8c9d-0e1f2a3b4c5d', 'Swami Keshwanand Rajasthan Agricultural University', 'l00000000-0006-4000-0000-000060000000', 'Agriculture for Development', 'SKRAU is an agriculture university in Bikaner', 'https://www.raubikaner.org', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '2013-02-14', 'ACTIVE', 1, 0, 4.0, datetime('now'), datetime('now'));

-- ============================================================================
-- RAJASTHAN - Veterinary Science
-- ============================================================================

INSERT INTO organization (id, short_name, legal_category_id, tag_line, description, website, admin_id, industry_id, established_date, status, is_verified, is_featured, rating, created_at, updated_at)
VALUES
('c1d2e3f4-3037-4a5b-8c9d-0e1f2a3b4c5d', 'Rajasthan University of Veterinary and Animal Sciences', 'l00000000-0006-4000-0000-000060000000', 'Veterinary Excellence', 'RAJUVAS is the state veterinary university in Bikaner', 'https://www.rajuvas.org', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '2010-12-01', 'ACTIVE', 1, 0, 4.1, datetime('now'), datetime('now'));

-- ============================================================================
-- RAJASTHAN - Sanskrit Universities
-- ============================================================================

INSERT INTO organization (id, short_name, legal_category_id, tag_line, description, website, admin_id, industry_id, established_date, status, is_verified, is_featured, rating, created_at, updated_at)
VALUES
('c1d2e3f4-3038-4a5b-8c9d-0e1f2a3b4c5d', 'Maharshi Dayanand Saraswati University Sanskrit', 'l00000000-0006-4000-0000-000060000000', 'Sanskrit and Vedic Studies', 'Rashtriya Sanskrit Sansthan Jaipur Campus', 'https://www.sanskrit.nic.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '1956-10-15', 'ACTIVE', 1, 0, 3.9, datetime('now'), datetime('now'));

-- ============================================================================
-- RAJASTHAN - Ayurveda & Alternative Medicine
-- ============================================================================

INSERT INTO organization (id, short_name, legal_category_id, tag_line, description, website, admin_id, industry_id, established_date, status, is_verified, is_featured, rating, created_at, updated_at)
VALUES
('c1d2e3f4-3039-4a5b-8c9d-0e1f2a3b4c5d', 'National Institute of Ayurveda', 'l00000000-0006-4000-0000-000060000000', 'Ayurveda Excellence', 'NIA is a deemed university for Ayurveda in Jaipur', 'https://www.nia.nic.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '1976-02-07', 'ACTIVE', 1, 1, 4.3, datetime('now'), datetime('now'));

-- ============================================================================
-- RAJASTHAN - Open Universities
-- ============================================================================

INSERT INTO organization (id, short_name, legal_category_id, tag_line, description, website, admin_id, industry_id, established_date, status, is_verified, is_featured, rating, created_at, updated_at)
VALUES
('c1d2e3f4-3040-4a5b-8c9d-0e1f2a3b4c5d', 'Vardhman Mahaveer Open University', 'l00000000-0006-4000-0000-000060000000', 'Education for All', 'VMOU is the state open university providing distance education', 'https://www.vmou.ac.in', '00000000-0000-4000-8000-000000000001', 'ind-006-education', '1987-07-23', 'ACTIVE', 1, 0, 3.9, datetime('now'), datetime('now'));
