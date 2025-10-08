<?php

require_once __DIR__ . '/BaseEntity.php';

/**
 * Language Entity
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
    }

    /**
     * Get languages by country
     */
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
        return $this->queryOne($sql);
    }

    /**
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
    }

    /**
     * Validate language data
     */
    public function validateData($data, $id = null) {
        $rules = [
            'name' => 'required|min:2|max:100',
            'country_id' => 'required|exists:countries,id',
            'iso_code_2' => 'max:2',
            'iso_code_3' => 'max:3',
            'direction' => 'in:LTR,RTL',
            'speaker_count' => 'numeric|min:0',
        ];

        return $this->validate($data, $rules);
    }

    /**
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
    }
}
