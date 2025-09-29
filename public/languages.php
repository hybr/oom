<?php
require_once '../includes/header.php';
?>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">🗣️ Language Management</h1>
            <p class="text-muted">Manage languages with linguistic data and real-time updates</p>
        </div>
        <div class="col-md-4 text-end">
            <button id="newLanguageBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLanguageModal">
                ➕ Add Language
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-lg-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Total Languages</h6>
                            <h4 class="card-title mb-0" id="totalLanguages">0</h4>
                        </div>
                        <div class="stats-icon">🗣️</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-lg-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Living</h6>
                            <h4 class="card-title mb-0" id="livingLanguages">0</h4>
                        </div>
                        <div class="stats-icon">✅</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-md-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Endangered</h6>
                            <h4 class="card-title mb-0" id="endangeredLanguages">0</h4>
                        </div>
                        <div class="stats-icon">⚠️</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-md-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Extinct</h6>
                            <h4 class="card-title mb-0" id="extinctLanguages">0</h4>
                        </div>
                        <div class="stats-icon">💀</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3 mb-md-0">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Total Speakers</h6>
                            <h4 class="card-title mb-0" id="totalSpeakers">0</h4>
                        </div>
                        <div class="stats-icon">👥</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 small">Language Families</h6>
                            <h4 class="card-title mb-0" id="totalFamilies">0</h4>
                        </div>
                        <div class="stats-icon">🌳</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-3">
        <div class="col-lg-3 col-12 mb-2 mb-lg-0">
            <div class="input-group">
                <span class="input-group-text">🔍</span>
                <input type="text" id="searchLanguages" class="form-control" placeholder="Search languages...">
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-12 mb-2 mb-lg-0">
            <select id="statusFilter" class="form-select">
                <option value="">All Status</option>
                <option value="living">Living</option>
                <option value="endangered">Endangered</option>
                <option value="dormant">Dormant</option>
                <option value="extinct">Extinct</option>
                <option value="revitalized">Revitalized</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-4 col-12 mb-2 mb-lg-0">
            <select id="typeFilter" class="form-select">
                <option value="">All Types</option>
                <option value="natural">Natural</option>
                <option value="constructed">Constructed</option>
                <option value="sign">Sign</option>
                <option value="pidgin">Pidgin</option>
                <option value="creole">Creole</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-4 col-12 mb-2 mb-lg-0">
            <select id="familyFilter" class="form-select">
                <option value="">All Families</option>
            </select>
        </div>
        <div class="col-lg-2 col-12 mb-2 mb-lg-0">
            <select id="activeFilter" class="form-select">
                <option value="">All Active Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div class="col-lg-1 col-12">
            <select id="sortFilter" class="form-select">
                <option value="">Sort by...</option>
                <option value="name">Name A-Z</option>
                <option value="speakers">Speakers ↓</option>
                <option value="family">Family A-Z</option>
            </select>
        </div>
    </div>

    <!-- Languages Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Language List</h5>
                </div>
                <div class="card-body">
                    <div id="languagesTable">
                        <div class="text-center py-4">
                            <div class="loading-spinner"></div>
                            <p class="mt-2 text-muted">Loading languages...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Language Modal -->
<div class="modal fade" id="addLanguageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Language</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addLanguageForm">
                <div class="modal-body">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">🗣️ Basic Information</h6>
                            <div class="mb-3">
                                <label for="name" class="form-label">Language Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="native_name" class="form-label">Native Name</label>
                                <input type="text" class="form-control" id="native_name" name="native_name" placeholder="Language name in its own script">
                            </div>
                            <div class="mb-3">
                                <label for="language_family" class="form-label">Language Family</label>
                                <input type="text" class="form-control" id="language_family" name="language_family" placeholder="e.g., Indo-European, Sino-Tibetan">
                            </div>
                            <div class="mb-3">
                                <label for="writing_system" class="form-label">Writing System</label>
                                <input type="text" class="form-control" id="writing_system" name="writing_system" placeholder="e.g., Latin, Cyrillic, Arabic">
                            </div>
                        </div>

                        <!-- ISO Codes & Classification -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">🏷️ Codes & Classification</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="iso_639_1" class="form-label">ISO 639-1</label>
                                        <input type="text" class="form-control" id="iso_639_1" name="iso_639_1" maxlength="2" placeholder="en">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="iso_639_2" class="form-label">ISO 639-2</label>
                                        <input type="text" class="form-control" id="iso_639_2" name="iso_639_2" maxlength="3" placeholder="eng">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="iso_639_3" class="form-label">ISO 639-3</label>
                                        <input type="text" class="form-control" id="iso_639_3" name="iso_639_3" maxlength="3" placeholder="eng">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="language_type" class="form-label">Language Type</label>
                                <select class="form-select" id="language_type" name="language_type">
                                    <option value="natural" selected>Natural</option>
                                    <option value="constructed">Constructed</option>
                                    <option value="sign">Sign Language</option>
                                    <option value="pidgin">Pidgin</option>
                                    <option value="creole">Creole</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Language Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="living" selected>Living</option>
                                    <option value="endangered">Endangered</option>
                                    <option value="dormant">Dormant</option>
                                    <option value="extinct">Extinct</option>
                                    <option value="revitalized">Revitalized</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Speaker Statistics -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">👥 Speaker Statistics</h6>
                            <div class="mb-3">
                                <label for="speakers_native" class="form-label">Native Speakers</label>
                                <input type="number" class="form-control" id="speakers_native" name="speakers_native" min="0" placeholder="Number of native speakers">
                            </div>
                            <div class="mb-3">
                                <label for="speakers_total" class="form-label">Total Speakers</label>
                                <input type="number" class="form-control" id="speakers_total" name="speakers_total" min="0" placeholder="Including second language speakers">
                            </div>
                        </div>

                        <!-- Description & Status -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">📝 Additional Information</h6>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Brief description of the language"></textarea>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                    <label class="form-check-label" for="is_active">
                                        Active (visible in system)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Language</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Language Modal -->
<div class="modal fade" id="editLanguageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Language</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editLanguageForm">
                <input type="hidden" id="editLanguageId" name="id">
                <div class="modal-body">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">🗣️ Basic Information</h6>
                            <div class="mb-3">
                                <label for="editName" class="form-label">Language Name *</label>
                                <input type="text" class="form-control" id="editName" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="editNative_name" class="form-label">Native Name</label>
                                <input type="text" class="form-control" id="editNative_name" name="native_name">
                            </div>
                            <div class="mb-3">
                                <label for="editLanguage_family" class="form-label">Language Family</label>
                                <input type="text" class="form-control" id="editLanguage_family" name="language_family">
                            </div>
                            <div class="mb-3">
                                <label for="editWriting_system" class="form-label">Writing System</label>
                                <input type="text" class="form-control" id="editWriting_system" name="writing_system">
                            </div>
                        </div>

                        <!-- ISO Codes & Classification -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">🏷️ Codes & Classification</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editIso_639_1" class="form-label">ISO 639-1</label>
                                        <input type="text" class="form-control" id="editIso_639_1" name="iso_639_1" maxlength="2">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editIso_639_2" class="form-label">ISO 639-2</label>
                                        <input type="text" class="form-control" id="editIso_639_2" name="iso_639_2" maxlength="3">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="editIso_639_3" class="form-label">ISO 639-3</label>
                                        <input type="text" class="form-control" id="editIso_639_3" name="iso_639_3" maxlength="3">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="editLanguage_type" class="form-label">Language Type</label>
                                <select class="form-select" id="editLanguage_type" name="language_type">
                                    <option value="natural">Natural</option>
                                    <option value="constructed">Constructed</option>
                                    <option value="sign">Sign Language</option>
                                    <option value="pidgin">Pidgin</option>
                                    <option value="creole">Creole</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editStatus" class="form-label">Language Status</label>
                                <select class="form-select" id="editStatus" name="status">
                                    <option value="living">Living</option>
                                    <option value="endangered">Endangered</option>
                                    <option value="dormant">Dormant</option>
                                    <option value="extinct">Extinct</option>
                                    <option value="revitalized">Revitalized</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Speaker Statistics -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">👥 Speaker Statistics</h6>
                            <div class="mb-3">
                                <label for="editSpeakers_native" class="form-label">Native Speakers</label>
                                <input type="number" class="form-control" id="editSpeakers_native" name="speakers_native" min="0">
                            </div>
                            <div class="mb-3">
                                <label for="editSpeakers_total" class="form-label">Total Speakers</label>
                                <input type="number" class="form-control" id="editSpeakers_total" name="speakers_total" min="0">
                            </div>
                        </div>

                        <!-- Description & Status -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">📝 Additional Information</h6>
                            <div class="mb-3">
                                <label for="editDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="editIs_active" name="is_active">
                                    <label class="form-check-label" for="editIs_active">
                                        Active (visible in system)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Language</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Language Detail Modal -->
<div class="modal fade" id="viewLanguageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🗣️ Language Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="card-title mb-0">🗣️ Basic Information</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-sm-4">Name:</dt>
                                    <dd class="col-sm-8" id="viewLanguageName">-</dd>

                                    <dt class="col-sm-4">Native Name:</dt>
                                    <dd class="col-sm-8" id="viewNativeName">-</dd>

                                    <dt class="col-sm-4">Family:</dt>
                                    <dd class="col-sm-8" id="viewLanguageFamily">-</dd>

                                    <dt class="col-sm-4">Writing System:</dt>
                                    <dd class="col-sm-8" id="viewWritingSystem">-</dd>

                                    <dt class="col-sm-4">Type:</dt>
                                    <dd class="col-sm-8" id="viewLanguageType">-</dd>

                                    <dt class="col-sm-4">Description:</dt>
                                    <dd class="col-sm-8" id="viewDescription">-</dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- ISO Codes & Statistics -->
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="card-title mb-0">🏷️ Codes & Statistics</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-sm-5">ISO 639-1:</dt>
                                    <dd class="col-sm-7" id="viewIso639_1">-</dd>

                                    <dt class="col-sm-5">ISO 639-2:</dt>
                                    <dd class="col-sm-7" id="viewIso639_2">-</dd>

                                    <dt class="col-sm-5">ISO 639-3:</dt>
                                    <dd class="col-sm-7" id="viewIso639_3">-</dd>

                                    <dt class="col-sm-5">Native Speakers:</dt>
                                    <dd class="col-sm-7" id="viewNativeSpeakers">-</dd>

                                    <dt class="col-sm-5">Total Speakers:</dt>
                                    <dd class="col-sm-7" id="viewTotalSpeakers">-</dd>

                                    <dt class="col-sm-5">Status:</dt>
                                    <dd class="col-sm-7" id="viewLanguageStatus">-</dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- System Information -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">📊 System Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <dl class="row">
                                            <dt class="col-sm-4">Active Status:</dt>
                                            <dd class="col-sm-8" id="viewActiveStatus">-</dd>

                                            <dt class="col-sm-4">Language ID:</dt>
                                            <dd class="col-sm-8" id="viewLanguageId">-</dd>

                                            <dt class="col-sm-4">Created:</dt>
                                            <dd class="col-sm-8" id="viewCreatedAt">-</dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-6">
                                        <dl class="row">
                                            <dt class="col-sm-4">Updated:</dt>
                                            <dd class="col-sm-8" id="viewUpdatedAt">-</dd>
                                        </dl>

                                        <div class="mt-3">
                                            <div class="d-grid gap-2">
                                                <button type="button" class="btn btn-primary btn-sm" id="viewEditBtn">
                                                    ✏️ Edit Language
                                                </button>
                                                <button type="button" class="btn btn-outline-danger btn-sm" id="viewDeleteBtn">
                                                    🗑️ Delete Language
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
require_once '../includes/scripts.php';
?>