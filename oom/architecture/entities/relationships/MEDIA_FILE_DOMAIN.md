# Media & File Management Domain - Entity Relationships

> **📚 Note:** This is a domain-specific relationship reference. For system-wide relationship rules, see `/architecture/entities/relationships/RELATIONSHIP_RULES.md`.

---

## Domain Overview

The Media & File Management domain provides centralized file upload, storage, versioning, and access control for all file-based assets across the system (images, documents, videos, etc.).

**Domain Code:** `MEDIA_FILE`

**Core Entities:** 2
- MEDIA_FILE
- MEDIA_FILE_ACCESS_LOG

**Reference Entities:** 3
- ENUM_STORAGE_PROVIDER
- ENUM_FILE_CATEGORY
- ENUM_MEDIA_CONTEXT

---

## Design Principles

### Why Centralized Media Management?

**Problems with Direct `_url` Fields:**
- ❌ No metadata (file size, mime type, uploader)
- ❌ No versioning or history
- ❌ No access control tracking
- ❌ No reusability across entities
- ❌ No audit trail
- ❌ Hard to manage lifecycle (archiving, cleanup)

**Benefits of MEDIA_FILE Entity:**
- ✅ Rich metadata tracking
- ✅ Complete version history
- ✅ Polymorphic relationships (works with any entity)
- ✅ Access control and audit logging
- ✅ Support multiple storage providers
- ✅ Easy migration between storage providers
- ✅ Centralized file management

---

## 1. MEDIA_FILE (Core Entity)

### Entity Structure
```
MEDIA_FILE
├─ id* (PK)
├─ file_name* [Stored filename, e.g., "abc123.jpg"]
├─ original_file_name* [User's original filename]
├─ file_path* [Path in storage, e.g., "uploads/2024/01/"]
├─ file_url* [Full accessible URL]
├─ storage_provider* (FK → ENUM_STORAGE_PROVIDER)
├─ storage_key* [Provider-specific identifier]
├─ mime_type* [e.g., "image/jpeg", "application/pdf"]
├─ file_size_bytes*
├─ file_category* (FK → ENUM_FILE_CATEGORY)
├─ entity_type* [Polymorphic: ORGANIZATION, PERSON, etc.]
├─ entity_id* [Polymorphic FK to entity_type]
├─ field_context* (FK → ENUM_MEDIA_CONTEXT)
├─ uploaded_by_id* (FK → PERSON)
├─ uploaded_at*
├─ is_public* [True = public URL, False = auth required]
├─ version_number* [Starts at 1, increments on replacement]
├─ replaces_file_id? (FK → MEDIA_FILE) [Previous version]
├─ metadata? (JSON) [width, height, duration, pages, etc.]
├─ is_active* [Current version = true, old versions = false]
├─ deleted_at? [Soft delete]
└─ deleted_by? (FK → PERSON)
```

### Relationships
```
MEDIA_FILE
  ← PERSON (Many:1) [via uploaded_by_id] - Uploader
  ← PERSON (Many:1) [via deleted_by] - Who deleted
  ← MEDIA_FILE (Many:1) [via replaces_file_id] - Previous version
  → MEDIA_FILE (1:Many) [reverse of replaces_file_id] - Version history
  ← ENUM_STORAGE_PROVIDER (Many:1)
  ← ENUM_FILE_CATEGORY (Many:1)
  ← ENUM_MEDIA_CONTEXT (Many:1)
  → MEDIA_FILE_ACCESS_LOG (1:Many) - Access tracking

  Polymorphic Relationships (via entity_type + entity_id):
  ← ORGANIZATION (Many:1) [when entity_type='ORGANIZATION']
  ← PERSON (Many:1) [when entity_type='PERSON']
  ← ORGANIZATION_VACANCY (Many:1) [when entity_type='ORGANIZATION_VACANCY']
  ← VACANCY_APPLICATION (Many:1) [when entity_type='VACANCY_APPLICATION']
  ← ... [any entity can have media files]
```

### Polymorphic Relationship Pattern

This entity uses a **polymorphic relationship** pattern to connect to any entity type:

```sql
-- Example: Organization logo
entity_type = 'ORGANIZATION'
entity_id = '123-abc-org-id'
field_context = 'LOGO'

-- Example: Person profile picture
entity_type = 'PERSON'
entity_id = '456-def-person-id'
field_context = 'PROFILE_PICTURE'

-- Example: Application resume
entity_type = 'VACANCY_APPLICATION'
entity_id = '789-ghi-app-id'
field_context = 'RESUME'
```

**Note:** No direct FK constraint on `entity_id` (application-level validation required).

---

## 2. MEDIA_FILE_ACCESS_LOG (Audit Trail)

### Entity Structure
```
MEDIA_FILE_ACCESS_LOG
├─ id* (PK)
├─ media_file_id* (FK → MEDIA_FILE)
├─ accessed_by_id? (FK → PERSON) [Null for anonymous]
├─ accessed_at*
├─ access_type* [VIEW, DOWNLOAD, DELETE]
├─ ip_address?
├─ user_agent?
└─ success* [True/False]
```

### Relationships
```
MEDIA_FILE_ACCESS_LOG
  ← MEDIA_FILE (Many:1)
  ← PERSON (Many:1) [via accessed_by_id]
```

### Purpose
- Track who viewed/downloaded files
- Security audit trail
- Usage analytics
- Compliance requirements

---

## 3. ENUM_STORAGE_PROVIDER

### Values
```
- LOCAL (Local filesystem)
- S3 (Amazon S3)
- AZURE (Azure Blob Storage)
- CLOUDINARY (Cloudinary)
- GCS (Google Cloud Storage)
- DIGITALOCEAN (DigitalOcean Spaces)
```

### Purpose
- Support multiple storage backends
- Enable storage migration
- Provider-specific optimizations

---

## 4. ENUM_FILE_CATEGORY

### Values
```
- IMAGE (Photos, logos, avatars)
- DOCUMENT (PDFs, Word docs, resumes)
- VIDEO (MP4, AVI, MOV)
- AUDIO (MP3, WAV)
- ARCHIVE (ZIP, TAR, RAR)
- SPREADSHEET (Excel, CSV)
- OTHER (Miscellaneous)
```

### Purpose
- File type classification
- Processing rules (thumbnails for images)
- Validation rules
- Display rendering

---

## 5. ENUM_MEDIA_CONTEXT

### Values by Entity Type

**ORGANIZATION:**
```
- LOGO (Primary organization logo)
- COVER_PHOTO (Header/banner image)
- DOCUMENT (Legal docs, certifications)
```

**PERSON:**
```
- PROFILE_PICTURE (Avatar/photo)
- COVER_PHOTO (Profile banner)
- RESUME (CV/Resume document)
- PORTFOLIO (Work samples)
- CERTIFICATE (Education/training certificates)
- ID_DOCUMENT (National ID, passport)
```

**ORGANIZATION_VACANCY:**
```
- ATTACHMENT (Job description attachments)
- IMAGE (Job-related images)
```

**VACANCY_APPLICATION:**
```
- RESUME (Applicant CV)
- COVER_LETTER (Application letter)
- PORTFOLIO (Work samples)
- CERTIFICATE (Certifications)
- REFERENCE_LETTER (Recommendation letters)
```

**ORGANIZATION_BUILDING:**
```
- PHOTO (Building photos)
- FLOOR_PLAN (Layout diagrams)
```

### Purpose
- Semantic meaning of file
- Enforcement of file rules (e.g., one logo per org)
- Display logic
- Access control rules

---

## Versioning System

### How Versioning Works

1. **Initial Upload:**
   ```
   File A (version 1)
   - version_number = 1
   - replaces_file_id = NULL
   - is_active = true
   ```

2. **File Replaced:**
   ```
   File A (version 1) → OLD
   - version_number = 1
   - is_active = false

   File B (version 2) → CURRENT
   - version_number = 2
   - replaces_file_id = [File A id]
   - is_active = true
   ```

3. **File Replaced Again:**
   ```
   File A (version 1) → OLD
   File B (version 2) → OLD
   - is_active = false

   File C (version 3) → CURRENT
   - version_number = 3
   - replaces_file_id = [File B id]
   - is_active = true
   ```

### Version Chain Query
```sql
-- Get complete version history
WITH RECURSIVE version_chain AS (
  -- Current version
  SELECT * FROM media_file
  WHERE entity_type = ?
    AND entity_id = ?
    AND field_context = ?
    AND is_active = 1

  UNION ALL

  -- Previous versions
  SELECT mf.*
  FROM media_file mf
  JOIN version_chain vc ON mf.id = vc.replaces_file_id
)
SELECT * FROM version_chain
ORDER BY version_number DESC;
```

---

## Access Control Patterns

### Public Files
```sql
is_public = 1
-- Anyone can access via URL
-- No authentication required
-- Examples: public org logos, public profile pictures
```

### Private Files
```sql
is_public = 0
-- Authentication required
-- Permission checks needed
-- Examples: resumes, ID documents, internal docs
```

### Access Control Rules

**Organization Logo (PUBLIC):**
- Anyone can view
- Only org admins can replace

**Person Resume (PRIVATE):**
- Only person owner can view/download
- Hiring managers can view if applied to their vacancy
- System admins can view

**Application Documents (PRIVATE):**
- Applicant can view own documents
- Hiring managers of that vacancy can view
- Organization admins can view

---

## Entity-Specific Usage

### ORGANIZATION

**Fields Using Media:**
```
ORGANIZATION
├─ logo_url → MEDIA_FILE [context: LOGO]
├─ cover_photo_url → MEDIA_FILE [context: COVER_PHOTO]
└─ [documents] → MEDIA_FILE [context: DOCUMENT]
```

**Access Pattern:**
```sql
-- Get organization logo (current version)
SELECT * FROM media_file
WHERE entity_type = 'ORGANIZATION'
  AND entity_id = ?
  AND field_context = 'LOGO'
  AND is_active = 1;
```

### PERSON

**Fields Using Media:**
```
PERSON
├─ latest_photo → MEDIA_FILE [context: PROFILE_PICTURE]
├─ cover_photo_url → MEDIA_FILE [context: COVER_PHOTO]
├─ resume_url → MEDIA_FILE [context: RESUME]
└─ [certificates] → MEDIA_FILE [context: CERTIFICATE]
```

**Access Pattern:**
```sql
-- Get all person files
SELECT * FROM media_file
WHERE entity_type = 'PERSON'
  AND entity_id = ?
  AND is_active = 1
ORDER BY field_context, uploaded_at DESC;
```

### VACANCY_APPLICATION

**Fields Using Media:**
```
VACANCY_APPLICATION
├─ resume_file → MEDIA_FILE [context: RESUME]
├─ cover_letter → MEDIA_FILE [context: COVER_LETTER]
├─ [portfolio] → MEDIA_FILE [context: PORTFOLIO]
└─ [certificates] → MEDIA_FILE [context: CERTIFICATE]
```

**Access Pattern:**
```sql
-- Get all application files
SELECT * FROM media_file
WHERE entity_type = 'VACANCY_APPLICATION'
  AND entity_id = ?
  AND is_active = 1
ORDER BY field_context;
```

---

## Common Queries

### 1. Get Current File for Entity + Context
```sql
SELECT mf.*
FROM media_file mf
WHERE mf.entity_type = ?
  AND mf.entity_id = ?
  AND mf.field_context = ?
  AND mf.is_active = 1
  AND mf.deleted_at IS NULL
ORDER BY mf.version_number DESC
LIMIT 1;
```

### 2. Get All Files for an Entity
```sql
SELECT mf.*, p.first_name || ' ' || p.last_name as uploader_name
FROM media_file mf
LEFT JOIN person p ON mf.uploaded_by_id = p.id
WHERE mf.entity_type = ?
  AND mf.entity_id = ?
  AND mf.is_active = 1
  AND mf.deleted_at IS NULL
ORDER BY mf.field_context, mf.uploaded_at DESC;
```

### 3. Get Version History for a File
```sql
WITH RECURSIVE version_history AS (
  -- Start with current version
  SELECT * FROM media_file
  WHERE id = ?

  UNION ALL

  -- Get previous versions
  SELECT mf.*
  FROM media_file mf
  JOIN version_history vh ON mf.id = vh.replaces_file_id
)
SELECT
  vh.*,
  p.first_name || ' ' || p.last_name as uploader_name
FROM version_history vh
LEFT JOIN person p ON vh.uploaded_by_id = p.id
ORDER BY vh.version_number DESC;
```

### 4. Search Files by Uploader
```sql
SELECT mf.*
FROM media_file mf
WHERE mf.uploaded_by_id = ?
  AND mf.is_active = 1
  AND mf.deleted_at IS NULL
ORDER BY mf.uploaded_at DESC;
```

### 5. Get Large Files (Cleanup Candidates)
```sql
SELECT
  mf.*,
  ROUND(mf.file_size_bytes / 1024.0 / 1024.0, 2) as size_mb
FROM media_file mf
WHERE mf.file_size_bytes > ? -- e.g., 10MB = 10485760
  AND mf.deleted_at IS NULL
ORDER BY mf.file_size_bytes DESC;
```

### 6. Get Orphaned Files (Entity Deleted)
```sql
-- For organizations
SELECT mf.*
FROM media_file mf
LEFT JOIN organization o ON mf.entity_id = o.id
WHERE mf.entity_type = 'ORGANIZATION'
  AND o.id IS NULL;

-- For persons
SELECT mf.*
FROM media_file mf
LEFT JOIN person p ON mf.entity_id = p.id
WHERE mf.entity_type = 'PERSON'
  AND p.id IS NULL;
```

### 7. Get Access Log for a File
```sql
SELECT
  mfal.*,
  p.first_name || ' ' || p.last_name as accessor_name
FROM media_file_access_log mfal
LEFT JOIN person p ON mfal.accessed_by_id = p.id
WHERE mfal.media_file_id = ?
ORDER BY mfal.accessed_at DESC;
```

### 8. Storage Usage by Entity Type
```sql
SELECT
  entity_type,
  COUNT(*) as file_count,
  SUM(file_size_bytes) as total_bytes,
  ROUND(SUM(file_size_bytes) / 1024.0 / 1024.0 / 1024.0, 2) as total_gb
FROM media_file
WHERE is_active = 1
  AND deleted_at IS NULL
GROUP BY entity_type
ORDER BY total_bytes DESC;
```

### 9. Storage Usage by Provider
```sql
SELECT
  esp.provider_name,
  COUNT(mf.id) as file_count,
  ROUND(SUM(mf.file_size_bytes) / 1024.0 / 1024.0 / 1024.0, 2) as total_gb
FROM media_file mf
JOIN enum_storage_provider esp ON mf.storage_provider = esp.id
WHERE mf.is_active = 1
  AND mf.deleted_at IS NULL
GROUP BY esp.provider_name;
```

### 10. Replace File with New Version
```sql
-- Step 1: Mark old version as inactive
UPDATE media_file
SET is_active = 0
WHERE id = ?;

-- Step 2: Insert new version
INSERT INTO media_file (
  id, file_name, original_file_name, file_path, file_url,
  storage_provider, storage_key, mime_type, file_size_bytes,
  file_category, entity_type, entity_id, field_context,
  uploaded_by_id, uploaded_at, is_public, version_number,
  replaces_file_id, is_active
) VALUES (
  ?, ?, ?, ?, ?,
  ?, ?, ?, ?,
  ?, ?, ?, ?,
  ?, datetime('now'), ?,
  (SELECT version_number + 1 FROM media_file WHERE id = ?),
  ?, -- replaces_file_id = old file id
  1
);
```

---

## Upload Workflow

### 1. Standard Upload Process

```
1. User uploads file via API
   ↓
2. Validate file (type, size, permissions)
   ↓
3. Generate unique file_name (UUID + extension)
   ↓
4. Upload to storage provider
   ↓
5. Get storage_key and file_url
   ↓
6. Extract metadata (image dimensions, etc.)
   ↓
7. Create MEDIA_FILE record
   ↓
8. Return file info to user
```

### 2. Replace File Process

```
1. User uploads replacement file
   ↓
2. Validate file and permissions
   ↓
3. Find current file (entity_type + entity_id + field_context)
   ↓
4. Upload new file to storage
   ↓
5. Mark old file as is_active=0
   ↓
6. Create new MEDIA_FILE with version_number++
   ↓
7. Set replaces_file_id to old file
   ↓
8. Return new file info
```

### 3. Delete File Process

```
1. User requests file deletion
   ↓
2. Validate permissions
   ↓
3. Soft delete: SET deleted_at, deleted_by
   ↓
4. Keep physical file (for audit/recovery)
   ↓
5. Optional: Schedule for hard delete after retention period
```

---

## Migration Strategy

### From `_url` Fields to MEDIA_FILE

**Phase 1: Parallel Mode**
- Keep existing `_url` fields
- Add MEDIA_FILE entity
- New uploads go to MEDIA_FILE
- Copy URL to legacy field (backward compat)
- Reads check MEDIA_FILE first, fallback to `_url`

**Phase 2: Migration**
```sql
-- Example: Migrate organization logos
INSERT INTO media_file (
  id, file_name, original_file_name, file_url,
  entity_type, entity_id, field_context,
  uploaded_by_id, is_active, version_number
)
SELECT
  generate_id(),
  extract_filename(o.logo_url),
  extract_filename(o.logo_url),
  o.logo_url,
  'ORGANIZATION',
  o.id,
  'LOGO',
  o.main_admin_id,
  1,
  1
FROM organization o
WHERE o.logo_url IS NOT NULL
  AND o.logo_url != ''
  AND NOT EXISTS (
    SELECT 1 FROM media_file mf
    WHERE mf.entity_type = 'ORGANIZATION'
      AND mf.entity_id = o.id
      AND mf.field_context = 'LOGO'
  );
```

**Phase 3: Deprecation**
- All code reads from MEDIA_FILE
- Stop writing to `_url` fields
- Eventually drop `_url` columns

---

## Performance Considerations

### Indexes

```sql
-- Primary access pattern: entity + context + active
CREATE INDEX idx_media_file_entity_lookup
ON media_file(entity_type, entity_id, field_context, is_active)
WHERE deleted_at IS NULL;

-- Uploader lookup
CREATE INDEX idx_media_file_uploaded_by
ON media_file(uploaded_by_id, uploaded_at)
WHERE deleted_at IS NULL;

-- Version chain
CREATE INDEX idx_media_file_replaces
ON media_file(replaces_file_id);

-- Storage analytics
CREATE INDEX idx_media_file_storage
ON media_file(storage_provider, file_size_bytes)
WHERE is_active = 1 AND deleted_at IS NULL;

-- Access logs
CREATE INDEX idx_media_access_file_time
ON media_file_access_log(media_file_id, accessed_at);

CREATE INDEX idx_media_access_person
ON media_file_access_log(accessed_by_id, accessed_at);
```

### Caching Strategy

**File Metadata Caching:**
- Cache MEDIA_FILE records for frequently accessed files
- Cache key: `media:${entity_type}:${entity_id}:${field_context}`
- TTL: 1 hour (or until file replaced)

**URL Caching:**
- For private files with signed URLs, cache signed URL
- Short TTL (5-15 minutes)

---

## Data Integrity Rules

1. **Polymorphic Validation:**
   - entity_type must be valid entity code
   - entity_id must exist in referenced entity table
   - Enforce at application level

2. **Version Chain Integrity:**
   - replaces_file_id must point to same entity_type/entity_id/field_context
   - version_number must increment sequentially
   - Only one is_active=1 per entity+context

3. **Context Validation:**
   - field_context must be valid for entity_type
   - Example: 'RESUME' context only valid for PERSON or VACANCY_APPLICATION

4. **File Lifecycle:**
   - Soft delete only (never hard delete)
   - Keep deleted files for audit period
   - Schedule physical deletion after retention period

5. **Access Logging:**
   - Log all access to private files
   - Anonymous access OK for public files (optional logging)

---

## Security Considerations

### 1. File Upload Validation

```javascript
// Pseudo-code validation
function validateUpload(file, context) {
  // File size limits
  const maxSizes = {
    'IMAGE': 5 * 1024 * 1024,      // 5MB
    'DOCUMENT': 10 * 1024 * 1024,  // 10MB
    'VIDEO': 100 * 1024 * 1024,    // 100MB
  };

  // Allowed mime types
  const allowedTypes = {
    'IMAGE': ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
    'DOCUMENT': ['application/pdf', 'application/msword', ...],
  };

  // Validate
  if (file.size > maxSizes[file.category]) {
    throw new Error('File too large');
  }

  if (!allowedTypes[file.category].includes(file.mimeType)) {
    throw new Error('Invalid file type');
  }

  // Scan for malware (virus scanning)
  await scanForMalware(file);
}
```

### 2. Access Control

```javascript
// Pseudo-code permission check
function canAccessFile(user, mediaFile) {
  // Public files
  if (mediaFile.is_public) {
    return true;
  }

  // Owner can always access
  if (mediaFile.entity_type === 'PERSON' &&
      mediaFile.entity_id === user.id) {
    return true;
  }

  // Organization admins can access org files
  if (mediaFile.entity_type === 'ORGANIZATION') {
    const isAdmin = checkOrgAdmin(user.id, mediaFile.entity_id);
    if (isAdmin) return true;
  }

  // Hiring managers can access application files
  if (mediaFile.entity_type === 'VACANCY_APPLICATION') {
    const application = getApplication(mediaFile.entity_id);
    const canView = canViewApplication(user.id, application.vacancy_id);
    if (canView) return true;
  }

  return false;
}
```

### 3. Signed URLs (Private Files)

```javascript
// Generate temporary signed URL
function generateSignedUrl(mediaFile, expiresIn = 900) {
  // 900 seconds = 15 minutes
  const signature = createSignature({
    fileId: mediaFile.id,
    expiresAt: Date.now() + (expiresIn * 1000),
    secret: process.env.FILE_SIGNATURE_SECRET
  });

  return `${mediaFile.file_url}?sig=${signature}&exp=${expiresIn}`;
}
```

---

## Cross-Domain Relationships

### To Person Domain
```
MEDIA_FILE ← PERSON (via uploaded_by_id) - Uploader
MEDIA_FILE ← PERSON (via deleted_by) - Deleter
MEDIA_FILE ← PERSON (via entity_id when entity_type='PERSON') - Owner
```

### To Organization Domain
```
MEDIA_FILE ← ORGANIZATION (via entity_id when entity_type='ORGANIZATION')
MEDIA_FILE ← ORGANIZATION_BUILDING (via entity_id when entity_type='ORGANIZATION_BUILDING')
```

### To Hiring Domain
```
MEDIA_FILE ← ORGANIZATION_VACANCY (via entity_id when entity_type='ORGANIZATION_VACANCY')
MEDIA_FILE ← VACANCY_APPLICATION (via entity_id when entity_type='VACANCY_APPLICATION')
```

---

## Use Cases & Examples

### Example 1: Organization Logo Management

```sql
-- Upload new logo
INSERT INTO media_file (...) VALUES (...);

-- Get current logo
SELECT * FROM media_file
WHERE entity_type = 'ORGANIZATION'
  AND entity_id = 'org-123'
  AND field_context = 'LOGO'
  AND is_active = 1;

-- Replace logo (mark old as inactive, insert new)
UPDATE media_file SET is_active = 0 WHERE id = 'old-logo-id';
INSERT INTO media_file (replaces_file_id='old-logo-id', version_number=2, ...);

-- View logo history
SELECT * FROM media_file
WHERE entity_type = 'ORGANIZATION'
  AND entity_id = 'org-123'
  AND field_context = 'LOGO'
ORDER BY version_number DESC;
```

### Example 2: Application Resume Upload

```sql
-- Applicant uploads resume
INSERT INTO media_file (
  entity_type = 'VACANCY_APPLICATION',
  entity_id = 'app-456',
  field_context = 'RESUME',
  is_public = 0,  -- Private
  uploaded_by_id = 'person-789',
  ...
);

-- Hiring manager views resume (with permission check)
SELECT mf.*
FROM media_file mf
JOIN vacancy_application va ON va.id = mf.entity_id
JOIN organization_vacancy ov ON ov.id = va.organization_vacancy_id
WHERE mf.entity_type = 'VACANCY_APPLICATION'
  AND mf.entity_id = 'app-456'
  AND mf.field_context = 'RESUME'
  AND mf.is_active = 1
  AND ov.organization_id IN (
    -- Check if current user is admin of this org
    SELECT organization_id FROM organization_admin
    WHERE person_id = ? AND is_active = 1
  );

-- Log access
INSERT INTO media_file_access_log (
  media_file_id = 'resume-file-id',
  accessed_by_id = 'hiring-manager-id',
  access_type = 'VIEW',
  success = 1
);
```

### Example 3: Person Profile Picture

```sql
-- Upload profile picture
INSERT INTO media_file (
  entity_type = 'PERSON',
  entity_id = 'person-123',
  field_context = 'PROFILE_PICTURE',
  is_public = 1,  -- Public (anyone can view)
  uploaded_by_id = 'person-123',
  ...
);

-- Get profile picture URL for display
SELECT file_url
FROM media_file
WHERE entity_type = 'PERSON'
  AND entity_id = 'person-123'
  AND field_context = 'PROFILE_PICTURE'
  AND is_active = 1
  AND deleted_at IS NULL;
```

### Example 4: Bulk File Management

```sql
-- Get all files for a person (profile, resume, certificates)
SELECT
  mf.field_context,
  mf.file_name,
  mf.file_size_bytes,
  mf.uploaded_at,
  mf.is_public
FROM media_file mf
WHERE mf.entity_type = 'PERSON'
  AND mf.entity_id = 'person-123'
  AND mf.is_active = 1
  AND mf.deleted_at IS NULL
ORDER BY mf.field_context, mf.uploaded_at DESC;

-- Calculate total storage used by person
SELECT
  SUM(file_size_bytes) as total_bytes,
  ROUND(SUM(file_size_bytes) / 1024.0 / 1024.0, 2) as total_mb,
  COUNT(*) as file_count
FROM media_file
WHERE entity_type = 'PERSON'
  AND entity_id = 'person-123'
  AND is_active = 1
  AND deleted_at IS NULL;
```

---

## Related Documentation

- **Entity Creation Rules:** [/architecture/entities/ENTITY_CREATION_RULES.md](../ENTITY_CREATION_RULES.md)
- **Relationship Rules:** [RELATIONSHIP_RULES.md](RELATIONSHIP_RULES.md)
- **Person Domain:** [PERSON_IDENTITY_DOMAIN.md](PERSON_IDENTITY_DOMAIN.md)
- **Organization Domain:** [ORGANIZATION_DOMAIN.md](ORGANIZATION_DOMAIN.md)
- **Hiring Domain:** [HIRING_VACANCY_DOMAIN.md](HIRING_VACANCY_DOMAIN.md)
- **All Domain Relationships:** [README.md](README.md)

---

**Last Updated:** 2025-11-05
**Domain:** Media & File Management
