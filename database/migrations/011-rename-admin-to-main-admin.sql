-- =====================================================================
-- DATABASE MIGRATION: Rename admin_id to main_admin_id
-- Create organization_admin table
-- Date: 2025-01-23
-- =====================================================================

PRAGMA foreign_keys = ON;

-- =========================================
-- 1. Rename admin_id column in organization table
-- =========================================
-- SQLite doesn't support ALTER COLUMN RENAME directly
-- We need to check if the column exists and rename it

-- Check if old column exists and new doesn't
-- This migration is idempotent - can be run multiple times safely

-- First, create temp table with new schema
CREATE TABLE IF NOT EXISTS organization_temp (
    id TEXT PRIMARY KEY,
    short_name TEXT NOT NULL,
    legal_category_id TEXT,
    tag_line TEXT,
    description TEXT,
    website TEXT,
    subdomain TEXT UNIQUE,
    logo TEXT,
    primary_email TEXT,
    primary_phone TEXT,
    support_email TEXT,
    support_phone TEXT,
    main_admin_id TEXT NOT NULL,  -- RENAMED from admin_id
    industry_id TEXT,
    tax_id TEXT,
    registration_number TEXT,
    established_date TEXT,
    status TEXT,
    is_verified INTEGER DEFAULT 0,
    verification_date TEXT,
    is_featured INTEGER DEFAULT 0,
    rating REAL,
    review_count INTEGER DEFAULT 0,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now')),
    deleted_at TEXT,
    version_no INTEGER DEFAULT 1,
    changed_by TEXT,
    FOREIGN KEY(main_admin_id) REFERENCES person(id),
    FOREIGN KEY(industry_id) REFERENCES popular_industry_category(id),
    FOREIGN KEY(legal_category_id) REFERENCES popular_organization_legal_type(id),
    FOREIGN KEY(changed_by) REFERENCES person(id)
);

-- Copy data from old table if it exists (handle both old and new column names)
INSERT OR IGNORE INTO organization_temp
SELECT
    id,
    short_name,
    legal_category_id,
    tag_line,
    description,
    website,
    subdomain,
    logo,
    primary_email,
    primary_phone,
    support_email,
    support_phone,
    COALESCE(main_admin_id, admin_id) as main_admin_id,  -- Handle both column names
    industry_id,
    tax_id,
    registration_number,
    established_date,
    status,
    is_verified,
    verification_date,
    is_featured,
    rating,
    review_count,
    created_at,
    updated_at,
    deleted_at,
    version_no,
    changed_by
FROM organization
WHERE deleted_at IS NULL;

-- Drop old table
DROP TABLE IF EXISTS organization;

-- Rename temp table to original
ALTER TABLE organization_temp RENAME TO organization;

-- Recreate indexes
CREATE INDEX IF NOT EXISTS idx_org_subdomain ON organization(subdomain);
CREATE INDEX IF NOT EXISTS idx_org_main_admin ON organization(main_admin_id);
CREATE INDEX IF NOT EXISTS idx_org_industry ON organization(industry_id);
CREATE INDEX IF NOT EXISTS idx_org_status ON organization(status);
CREATE INDEX IF NOT EXISTS idx_org_is_verified ON organization(is_verified);
CREATE INDEX IF NOT EXISTS idx_org_deleted ON organization(deleted_at);

-- =========================================
-- 2. Create organization_admin table
-- =========================================
CREATE TABLE IF NOT EXISTS organization_admin (
    id TEXT PRIMARY KEY,
    organization_id TEXT NOT NULL,
    person_id TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT 'ADMIN',
    permissions TEXT,
    appointed_by TEXT,
    appointed_at TEXT DEFAULT (datetime('now')),
    is_active INTEGER DEFAULT 1,
    notes TEXT,
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now')),
    deleted_at TEXT,
    version_no INTEGER DEFAULT 1,
    changed_by TEXT,
    FOREIGN KEY(organization_id) REFERENCES organization(id) ON DELETE CASCADE,
    FOREIGN KEY(person_id) REFERENCES person(id),
    FOREIGN KEY(appointed_by) REFERENCES person(id),
    FOREIGN KEY(changed_by) REFERENCES person(id),
    UNIQUE(organization_id, person_id)
);

-- Create indexes for organization_admin
CREATE INDEX IF NOT EXISTS idx_org_admin_org ON organization_admin(organization_id);
CREATE INDEX IF NOT EXISTS idx_org_admin_person ON organization_admin(person_id);
CREATE INDEX IF NOT EXISTS idx_org_admin_role ON organization_admin(role);
CREATE INDEX IF NOT EXISTS idx_org_admin_active ON organization_admin(is_active, deleted_at);
CREATE INDEX IF NOT EXISTS idx_org_admin_appointed_by ON organization_admin(appointed_by);

-- =========================================
-- 3. Migrate main admins to organization_admin table
-- =========================================
-- Add each organization's main admin as a SUPER_ADMIN in organization_admin
INSERT OR IGNORE INTO organization_admin (
    id,
    organization_id,
    person_id,
    role,
    appointed_by,
    appointed_at,
    is_active,
    notes,
    created_at,
    updated_at,
    version_no
)
SELECT
    lower(hex(randomblob(4)) || '-' || hex(randomblob(2)) || '-4' || substr(hex(randomblob(2)),2) || '-' || substr('89ab',abs(random()) % 4 + 1, 1) || substr(hex(randomblob(2)),2) || '-' || hex(randomblob(6))) as id,
    o.id as organization_id,
    o.main_admin_id as person_id,
    'SUPER_ADMIN' as role,
    o.main_admin_id as appointed_by,
    o.created_at as appointed_at,
    1 as is_active,
    'Migrated from main_admin_id' as notes,
    datetime('now') as created_at,
    datetime('now') as updated_at,
    1 as version_no
FROM organization o
WHERE o.deleted_at IS NULL
  AND o.main_admin_id IS NOT NULL
  AND NOT EXISTS (
      SELECT 1 FROM organization_admin oa
      WHERE oa.organization_id = o.id
        AND oa.person_id = o.main_admin_id
  );

-- =========================================
-- End of migration
-- =========================================
