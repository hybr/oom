<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * Language Entity
<<<<<<< HEAD
 * Represents languages spoken in different countries
 */
class Language extends BaseEntity {
    protected $table = 'languages';
    protected $fillable = ['name', 'country_id'];

    /**
     * Get country for this language
     */
    public function getCountry($languageId) {
        $sql = "SELECT c.* FROM countries c
                JOIN languages l ON l.country_id = c.id
                WHERE l.id = ? AND c.deleted_at IS NULL";
        return $this->queryOne($sql, [$languageId]);
=======
 *
 * Represents languages spoken in different countries.
 * Used for localization, communication preferences, and language proficiency tracking.
 */
class Language extends BaseEntity {
    protected $table = 'languages';
    protected $fillable = [
        'name',
        'country_id',
        'iso_code_2',
        'iso_code_3',
        'native_name',
        'direction',
        'is_official',
        'speaker_count',
        'language_family',
        'writing_system',
        'is_active',
        'is_endangered',
        'endangerment_level',
        'alternative_names',
        'description',
        'unicode_support',
        'keyboard_layout',
        'font_family',
        'sample_text',
        'grammar_rules_url',
        'dictionary_url',
        'learning_resources_url',
        'translation_available',
        'speech_recognition_support',
        'text_to_speech_support',
        'sort_order',
        'usage_context',
        'regional_variants',
        'created_by',
        'updated_by'
    ];

    /**
     * Get the country this language belongs to
     */
    public function getCountry($languageId) {
        $language = $this->find($languageId);
        if (!$language || !$language['country_id']) {
            return null;
        }

        $sql = "SELECT * FROM countries WHERE id = ? AND deleted_at IS NULL";
        return $this->queryOne($sql, [$language['country_id']]);
    }

    /**
     * Get all active languages
     */
    public function getActiveLanguages() {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 AND deleted_at IS NULL ORDER BY name";
        return $this->query($sql);
    }

    /**
     * Get official languages only
     */
    public function getOfficialLanguages() {
        $sql = "SELECT * FROM {$this->table} WHERE is_official = 1 AND deleted_at IS NULL ORDER BY name";
        return $this->query($sql);
>>>>>>> 8bd537ad194530da99b171400a95cf65ef7bf454
    }

    /**
     * Get languages by country
     */
<<<<<<< HEAD
    public function getByCountry($countryId) {
        return $this->all(['country_id' => $countryId], 'name ASC');
    }

    /**
     * Get most spoken languages (by number of countries)
     */
    public function getMostSpoken($limit = 10) {
        $sql = "SELECT l.name, COUNT(DISTINCT l.country_id) as country_count
                FROM languages l
                WHERE l.deleted_at IS NULL
                GROUP BY l.name
                ORDER BY country_count DESC, l.name ASC
                LIMIT ?";
        return $this->query($sql, [$limit]);
    }

    /**
     * Get languages starting with letter
     */
    public function getByFirstLetter($letter) {
        $sql = "SELECT * FROM languages
                WHERE name LIKE ? AND deleted_at IS NULL
                ORDER BY name ASC";
        return $this->query($sql, [$letter . '%']);
    }

    /**
     * Check if language exists in country
     */
    public function existsInCountry($languageName, $countryId) {
        $sql = "SELECT COUNT(*) as count FROM languages
                WHERE name = ? AND country_id = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$languageName, $countryId]);
        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Get all unique language names
     */
    public function getUniqueLanguages() {
        $sql = "SELECT DISTINCT name FROM languages
                WHERE deleted_at IS NULL
                ORDER BY name ASC";
        return $this->query($sql);
    }

    /**
     * Get country count for a language name
     */
    public function getCountryCountByName($languageName) {
        $sql = "SELECT COUNT(DISTINCT country_id) as count
                FROM languages
                WHERE name = ? AND deleted_at IS NULL";
        $result = $this->queryOne($sql, [$languageName]);
        return $result['count'] ?? 0;
    }

    /**
     * Get languages with country information
     */
    public function getWithCountryInfo($languageId) {
        $sql = "SELECT l.*, c.name as country_name, cont.name as continent_name
                FROM languages l
                LEFT JOIN countries c ON l.country_id = c.id
                LEFT JOIN continents cont ON c.continent_id = cont.id
                WHERE l.id = ? AND l.deleted_at IS NULL";
        return $this->queryOne($sql, [$languageId]);
    }

    /**
     * Search languages by name
     */
    public function searchByName($term, $limit = 50) {
        return $this->search('name', $term, $limit);
    }

    /**
     * Get languages by continent
     */
    public function getByContinent($continentId) {
        $sql = "SELECT DISTINCT l.*
                FROM languages l
                JOIN countries c ON l.country_id = c.id
                WHERE c.continent_id = ? AND l.deleted_at IS NULL
                ORDER BY l.name ASC";
        return $this->query($sql, [$continentId]);
    }

    /**
     * Get statistics for languages
     */
    public function getStatistics() {
        $sql = "SELECT
                    COUNT(DISTINCT id) as total_language_records,
                    COUNT(DISTINCT name) as unique_languages,
                    COUNT(DISTINCT country_id) as countries_with_languages
                FROM languages
                WHERE deleted_at IS NULL";
=======
    public function getLanguagesByCountry($countryId) {
        $sql = "SELECT * FROM {$this->table} WHERE country_id = ? AND deleted_at IS NULL ORDER BY is_official DESC, speaker_count DESC";
        return $this->query($sql, [$countryId]);
    }

    /**
     * Get official languages by country
     */
    public function getOfficialLanguagesByCountry($countryId) {
        $sql = "SELECT * FROM {$this->table} WHERE country_id = ? AND is_official = 1 AND deleted_at IS NULL ORDER BY speaker_count DESC";
        return $this->query($sql, [$countryId]);
    }

    /**
     * Search languages by name or ISO code
     */
    public function searchLanguages($term, $limit = 50) {
        $sql = "SELECT * FROM {$this->table}
                WHERE (name LIKE ? OR native_name LIKE ? OR iso_code_2 LIKE ? OR iso_code_3 LIKE ?)
                AND deleted_at IS NULL
                ORDER BY name
                LIMIT ?";
        return $this->query($sql, ["%$term%", "%$term%", "%$term%", "%$term%", $limit]);
    }

    /**
     * Get languages by language family
     */
    public function getLanguagesByFamily($family) {
        $sql = "SELECT * FROM {$this->table} WHERE language_family = ? AND deleted_at IS NULL ORDER BY name";
        return $this->query($sql, [$family]);
    }

    /**
     * Get endangered languages
     */
    public function getEndangeredLanguages($level = null) {
        if ($level) {
            $sql = "SELECT * FROM {$this->table} WHERE is_endangered = 1 AND endangerment_level = ? AND deleted_at IS NULL ORDER BY speaker_count ASC";
            return $this->query($sql, [$level]);
        } else {
            $sql = "SELECT * FROM {$this->table} WHERE is_endangered = 1 AND deleted_at IS NULL ORDER BY speaker_count ASC";
            return $this->query($sql);
        }
    }

    /**
     * Get languages with translation support
     */
    public function getTranslationEnabledLanguages() {
        $sql = "SELECT * FROM {$this->table} WHERE translation_available = 1 AND deleted_at IS NULL ORDER BY name";
        return $this->query($sql);
    }

    /**
     * Get languages with speech recognition
     */
    public function getSpeechRecognitionLanguages() {
        $sql = "SELECT * FROM {$this->table} WHERE speech_recognition_support = 1 AND deleted_at IS NULL ORDER BY name";
        return $this->query($sql);
    }

    /**
     * Get languages with text-to-speech support
     */
    public function getTextToSpeechLanguages() {
        $sql = "SELECT * FROM {$this->table} WHERE text_to_speech_support = 1 AND deleted_at IS NULL ORDER BY name";
        return $this->query($sql);
    }

    /**
     * Get language by ISO code
     */
    public function getByIsoCode($code) {
        $sql = "SELECT * FROM {$this->table} WHERE (iso_code_2 = ? OR iso_code_3 = ?) AND deleted_at IS NULL";
        return $this->queryOne($sql, [$code, $code]);
    }

    /**
     * Get speaker count statistics
     */
    public function getSpeakerStatistics() {
        $sql = "SELECT
                    COUNT(*) as total_languages,
                    SUM(speaker_count) as total_speakers,
                    AVG(speaker_count) as avg_speakers,
                    MAX(speaker_count) as max_speakers,
                    MIN(speaker_count) as min_speakers
                FROM {$this->table}
                WHERE deleted_at IS NULL AND speaker_count > 0";
>>>>>>> 8bd537ad194530da99b171400a95cf65ef7bf454
        return $this->queryOne($sql);
    }

    /**
<<<<<<< HEAD
     * Bulk import languages for a country
     */
    public function bulkImportForCountry($countryId, $languageNames) {
        $imported = 0;
        foreach ($languageNames as $name) {
            if (!$this->existsInCountry($name, $countryId)) {
                $this->create(['name' => $name, 'country_id' => $countryId]);
                $imported++;
            }
        }
        return $imported;
=======
     * Get most spoken languages
     */
    public function getMostSpokenLanguages($limit = 10) {
        $sql = "SELECT * FROM {$this->table}
                WHERE speaker_count > 0 AND deleted_at IS NULL
                ORDER BY speaker_count DESC
                LIMIT ?";
        return $this->query($sql, [$limit]);
    }

    /**
     * Get languages by direction (LTR/RTL)
     */
    public function getLanguagesByDirection($direction = 'LTR') {
        $sql = "SELECT * FROM {$this->table} WHERE direction = ? AND deleted_at IS NULL ORDER BY name";
        return $this->query($sql, [$direction]);
    }

    /**
     * Get languages by writing system
     */
    public function getLanguagesByWritingSystem($writingSystem) {
        $sql = "SELECT * FROM {$this->table} WHERE writing_system = ? AND deleted_at IS NULL ORDER BY name";
        return $this->query($sql, [$writingSystem]);
    }

    /**
     * Toggle language active status
     */
    public function toggleActiveStatus($languageId) {
        $language = $this->find($languageId);
        if (!$language) {
            return false;
        }

        $newStatus = $language['is_active'] ? 0 : 1;
        $sql = "UPDATE {$this->table} SET is_active = ?, updated_at = datetime('now') WHERE id = ?";
        return $this->db->update($sql, [$newStatus, $languageId]);
    }

    /**
     * Mark language as official
     */
    public function markAsOfficial($languageId) {
        $sql = "UPDATE {$this->table} SET is_official = 1, updated_at = datetime('now') WHERE id = ?";
        return $this->db->update($sql, [$languageId]);
    }

    /**
     * Update speaker count
     */
    public function updateSpeakerCount($languageId, $count) {
        $sql = "UPDATE {$this->table} SET speaker_count = ?, updated_at = datetime('now') WHERE id = ?";
        return $this->db->update($sql, [$count, $languageId]);
>>>>>>> 8bd537ad194530da99b171400a95cf65ef7bf454
    }

    /**
     * Validate language data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'name' => 'required|min:2|max:100',
<<<<<<< HEAD
            'country_id' => 'required|integer',
=======
            'country_id' => 'required|exists:countries,id',
            'iso_code_2' => 'max:2',
            'iso_code_3' => 'max:3',
            'direction' => 'in:LTR,RTL',
            'speaker_count' => 'numeric|min:0',
>>>>>>> 8bd537ad194530da99b171400a95cf65ef7bf454
        ];

        return $this->validate($data, $rules);
    }

    /**
<<<<<<< HEAD
     * Override getLabel to return language name with country
     */
    public function getLabel($id) {
        $language = $this->getWithCountryInfo($id);
        if (!$language) {
            return 'N/A';
        }
        return $language['name'] . ' (' . ($language['country_name'] ?? 'Unknown Country') . ')';
=======
     * Get regional variants
     */
    public function getRegionalVariants($languageId) {
        $language = $this->find($languageId);
        if (!$language || !$language['regional_variants']) {
            return [];
        }

        // Assuming regional_variants is stored as JSON
        return json_decode($language['regional_variants'], true) ?? [];
    }

    /**
     * Check if Unicode is supported
     */
    public function hasUnicodeSupport($languageId) {
        $language = $this->find($languageId);
        return $language && $language['unicode_support'] == 1;
    }

    /**
     * Get learning resources
     */
    public function getLearningResources($languageId) {
        $language = $this->find($languageId);
        if (!$language) {
            return [];
        }

        return [
            'grammar_rules' => $language['grammar_rules_url'],
            'dictionary' => $language['dictionary_url'],
            'learning_resources' => $language['learning_resources_url']
        ];
    }

    /**
     * Export language data for localization
     */
    public function exportForLocalization($languageId) {
        $language = $this->find($languageId);
        if (!$language) {
            return null;
        }

        return [
            'code' => $language['iso_code_2'] ?? $language['iso_code_3'],
            'name' => $language['name'],
            'native_name' => $language['native_name'],
            'direction' => $language['direction'],
            'font_family' => $language['font_family']
        ];
    }

    /**
     * Override getLabel
     */
    public function getLabel($id) {
        $language = $this->find($id);
        if (!$language) {
            return 'N/A';
        }

        $label = $language['name'];
        if (!empty($language['iso_code_2'])) {
            $label .= ' (' . $language['iso_code_2'] . ')';
        }
        return $label;
>>>>>>> 8bd537ad194530da99b171400a95cf65ef7bf454
    }
}
