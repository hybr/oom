<?php

require_once 'config/database.php';
require_once 'entities/Order.php';
require_once 'entities/OrderItem.php';
require_once 'entities/Person.php';
require_once 'entities/PersonCredential.php';
require_once 'entities/Continent.php';
require_once 'entities/Language.php';
require_once 'entities/Country.php';
require_once 'entities/IndustryCategory.php';
require_once 'entities/OrganizationLegalType.php';
require_once 'entities/PostalAddress.php';
require_once 'entities/OrganizationBranch.php';
require_once 'entities/OrganizationBuilding.php';
require_once 'entities/OrganizationWorkstation.php';
require_once 'entities/PopularOrganizationDepartment.php';
require_once 'entities/PopularOrganizationTeam.php';
require_once 'entities/PopularOrganizationDesignation.php';
require_once 'entities/PopularSkills.php';
require_once 'entities/PopularEducationSubject.php';
require_once 'entities/PersonEducation.php';
require_once 'entities/PersonSkill.php';
require_once 'entities/Organization.php';
require_once 'entities/OrganizationPosition.php';
require_once 'entities/OrganizationVacancy.php';
require_once 'process/BaseProcess.php';

class DatabaseMigration {
    private $db;

    public function __construct() {
        $this->db = DatabaseConfig::getInstance();
    }

    public function migrate() {
        echo "Starting database migration...\n";

        try {
            if (!file_exists('database')) {
                mkdir('database', 0755, true);
            }

            $this->createProcessTables();
            $this->createEntityTables();

            echo "Migration completed successfully!\n";
        } catch (Exception $e) {
            echo "Migration failed: " . $e->getMessage() . "\n";
        }
    }

    private function createProcessTables() {
        echo "Creating process tables...\n";
        BaseProcess::createTables();
    }

    private function createEntityTables() {
        echo "Creating entity tables...\n";
        Order::createTable();
        OrderItem::createTable();
        Person::createTable();
        PersonCredential::createTable();
        Continent::createTable();
        Language::createTable();
        Country::createTable();
        IndustryCategory::createTable();
        OrganizationLegalType::createTable();
        PostalAddress::createTable();
        Organization::createTable();
        OrganizationBranch::createTable();
        OrganizationBuilding::createTable();
        OrganizationWorkstation::createTable();
        PopularOrganizationDepartment::createTable();
        PopularOrganizationTeam::createTable();
        PopularOrganizationDesignation::createTable();
        PopularSkills::createTable();
        PopularEducationSubject::createTable();
        PersonEducation::createTable();
        PersonSkill::createTable();
        OrganizationPosition::createTable();
        OrganizationVacancy::createTable();
    }

    public function seed() {
        echo "Seeding sample data...\n";

        $this->seedContinents();
        $this->seedLanguages();
        $this->seedCountries();
        $this->seedIndustryCategories();
        $this->seedOrganizationLegalTypes();
        $this->seedPopularOrganizationDepartments();
        $this->seedOrders();
    }

    private function seedContinents() {
        echo "Seeding continents...\n";

        $continents = [
            [
                'name' => 'Asia',
                'code' => 'AS',
                'area_km2' => 44579000,
                'population' => 4641054775,
                'countries_count' => 48,
                'largest_country' => 'Russia',
                'description' => 'Asia is the largest and most populous continent, located primarily in the Eastern and Northern Hemispheres. It covers 8.7% of the Earth\'s surface area and comprises 30% of its land area.'
            ],
            [
                'name' => 'Africa',
                'code' => 'AF',
                'area_km2' => 30370000,
                'population' => 1340598147,
                'countries_count' => 54,
                'largest_country' => 'Algeria',
                'description' => 'Africa is the second-largest and second-most populous continent. It covers about 20% of Earth\'s total land area and 6% of its total surface area.'
            ],
            [
                'name' => 'North America',
                'code' => 'NA',
                'area_km2' => 24709000,
                'population' => 579024000,
                'countries_count' => 23,
                'largest_country' => 'Canada',
                'description' => 'North America is a continent in the Northern Hemisphere and almost entirely within the Western Hemisphere. It covers about 16.5% of Earth\'s total land area.'
            ],
            [
                'name' => 'South America',
                'code' => 'SA',
                'area_km2' => 17840000,
                'population' => 434254119,
                'countries_count' => 12,
                'largest_country' => 'Brazil',
                'description' => 'South America is a continent entirely in the Western Hemisphere and mostly in the Southern Hemisphere. It covers about 12% of Earth\'s total land area.'
            ],
            [
                'name' => 'Antarctica',
                'code' => 'AN',
                'area_km2' => 14200000,
                'population' => 4400,
                'countries_count' => 0,
                'largest_country' => null,
                'description' => 'Antarctica is Earth\'s southernmost continent. It contains the geographic South Pole and is situated in the Antarctic region of the Southern Hemisphere.'
            ],
            [
                'name' => 'Europe',
                'code' => 'EU',
                'area_km2' => 10180000,
                'population' => 747636026,
                'countries_count' => 44,
                'largest_country' => 'Russia',
                'description' => 'Europe is a landmass variously recognized as part of Eurasia or a continent in its own right, located entirely in the Northern Hemisphere and mostly in the Eastern Hemisphere.'
            ],
            [
                'name' => 'Oceania',
                'code' => 'OC',
                'area_km2' => 8600000,
                'population' => 45036343,
                'countries_count' => 14,
                'largest_country' => 'Australia',
                'description' => 'Oceania is a geographic region that includes Australasia, Melanesia, Micronesia, and Polynesia. It spans the Eastern and Western hemispheres.'
            ]
        ];

        foreach ($continents as $continentData) {
            // Check if continent already exists
            $existing = Continent::findByCode($continentData['code']);
            if (!$existing) {
                $continent = new Continent();
                $continent->fill($continentData);
                $continent->save();
                echo "Created continent: {$continent->name} ({$continent->code})\n";
            } else {
                echo "Continent {$continentData['name']} already exists, skipping...\n";
            }
        }
    }

    private function seedLanguages() {
        echo "Seeding languages...\n";

        $languages = [
            [
                'name' => 'English',
                'native_name' => 'English',
                'iso_639_1' => 'en',
                'iso_639_2' => 'eng',
                'iso_639_3' => 'eng',
                'language_family' => 'Indo-European',
                'writing_system' => 'Latin',
                'speakers_native' => 380000000,
                'speakers_total' => 1500000000,
                'language_type' => 'natural',
                'status' => 'living',
                'description' => 'English is a West Germanic language that originated in medieval England and is now a global lingua franca.'
            ],
            [
                'name' => 'Mandarin Chinese',
                'native_name' => '普通话',
                'iso_639_1' => 'zh',
                'iso_639_2' => 'chi',
                'iso_639_3' => 'cmn',
                'language_family' => 'Sino-Tibetan',
                'writing_system' => 'Chinese characters',
                'speakers_native' => 918000000,
                'speakers_total' => 1100000000,
                'language_type' => 'natural',
                'status' => 'living',
                'description' => 'Mandarin Chinese is the most widely spoken Chinese language and the official language of China and Taiwan.'
            ],
            [
                'name' => 'Hindi',
                'native_name' => 'हिन्दी',
                'iso_639_1' => 'hi',
                'iso_639_2' => 'hin',
                'iso_639_3' => 'hin',
                'language_family' => 'Indo-European',
                'writing_system' => 'Devanagari',
                'speakers_native' => 341000000,
                'speakers_total' => 600000000,
                'language_type' => 'natural',
                'status' => 'living',
                'description' => 'Hindi is an Indo-Aryan language spoken primarily in North India and is one of the official languages of India.'
            ],
            [
                'name' => 'Spanish',
                'native_name' => 'Español',
                'iso_639_1' => 'es',
                'iso_639_2' => 'spa',
                'iso_639_3' => 'spa',
                'language_family' => 'Indo-European',
                'writing_system' => 'Latin',
                'speakers_native' => 460000000,
                'speakers_total' => 500000000,
                'language_type' => 'natural',
                'status' => 'living',
                'description' => 'Spanish is a Romance language that originated in the Iberian Peninsula and is now spoken in many countries worldwide.'
            ],
            [
                'name' => 'Arabic',
                'native_name' => 'العربية',
                'iso_639_1' => 'ar',
                'iso_639_2' => 'ara',
                'iso_639_3' => 'ara',
                'language_family' => 'Afro-Asiatic',
                'writing_system' => 'Arabic',
                'speakers_native' => 310000000,
                'speakers_total' => 422000000,
                'language_type' => 'natural',
                'status' => 'living',
                'description' => 'Arabic is a Semitic language spoken across the Arab world and is the liturgical language of Islam.'
            ],
            [
                'name' => 'Bengali',
                'native_name' => 'বাংলা',
                'iso_639_1' => 'bn',
                'iso_639_2' => 'ben',
                'iso_639_3' => 'ben',
                'language_family' => 'Indo-European',
                'writing_system' => 'Bengali',
                'speakers_native' => 230000000,
                'speakers_total' => 300000000,
                'language_type' => 'natural',
                'status' => 'living',
                'description' => 'Bengali is an Indo-Aryan language spoken primarily in Bangladesh and the Indian state of West Bengal.'
            ],
            [
                'name' => 'Portuguese',
                'native_name' => 'Português',
                'iso_639_1' => 'pt',
                'iso_639_2' => 'por',
                'iso_639_3' => 'por',
                'language_family' => 'Indo-European',
                'writing_system' => 'Latin',
                'speakers_native' => 230000000,
                'speakers_total' => 260000000,
                'language_type' => 'natural',
                'status' => 'living',
                'description' => 'Portuguese is a Romance language that originated in Portugal and is now spoken in several countries including Brazil.'
            ],
            [
                'name' => 'Russian',
                'native_name' => 'Русский язык',
                'iso_639_1' => 'ru',
                'iso_639_2' => 'rus',
                'iso_639_3' => 'rus',
                'language_family' => 'Indo-European',
                'writing_system' => 'Cyrillic',
                'speakers_native' => 150000000,
                'speakers_total' => 258000000,
                'language_type' => 'natural',
                'status' => 'living',
                'description' => 'Russian is an East Slavic language primarily spoken in Russia and several former Soviet republics.'
            ],
            [
                'name' => 'Japanese',
                'native_name' => '日本語',
                'iso_639_1' => 'ja',
                'iso_639_2' => 'jpn',
                'iso_639_3' => 'jpn',
                'language_family' => 'Japonic',
                'writing_system' => 'Hiragana, Katakana, Kanji',
                'speakers_native' => 125000000,
                'speakers_total' => 128000000,
                'language_type' => 'natural',
                'status' => 'living',
                'description' => 'Japanese is spoken primarily in Japan and uses a complex writing system with three scripts.'
            ],
            [
                'name' => 'French',
                'native_name' => 'Français',
                'iso_639_1' => 'fr',
                'iso_639_2' => 'fre',
                'iso_639_3' => 'fra',
                'language_family' => 'Indo-European',
                'writing_system' => 'Latin',
                'speakers_native' => 76000000,
                'speakers_total' => 280000000,
                'language_type' => 'natural',
                'status' => 'living',
                'description' => 'French is a Romance language spoken in France and many other countries across different continents.'
            ],
            [
                'name' => 'German',
                'native_name' => 'Deutsch',
                'iso_639_1' => 'de',
                'iso_639_2' => 'ger',
                'iso_639_3' => 'deu',
                'language_family' => 'Indo-European',
                'writing_system' => 'Latin',
                'speakers_native' => 76000000,
                'speakers_total' => 132000000,
                'language_type' => 'natural',
                'status' => 'living',
                'description' => 'German is a West Germanic language primarily spoken in Central Europe.'
            ],
            [
                'name' => 'Korean',
                'native_name' => '한국어',
                'iso_639_1' => 'ko',
                'iso_639_2' => 'kor',
                'iso_639_3' => 'kor',
                'language_family' => 'Koreanic',
                'writing_system' => 'Hangul, Hanja',
                'speakers_native' => 77000000,
                'speakers_total' => 77000000,
                'language_type' => 'natural',
                'status' => 'living',
                'description' => 'Korean is spoken primarily in North and South Korea and uses the unique Hangul writing system.'
            ],
            [
                'name' => 'Italian',
                'native_name' => 'Italiano',
                'iso_639_1' => 'it',
                'iso_639_2' => 'ita',
                'iso_639_3' => 'ita',
                'language_family' => 'Indo-European',
                'writing_system' => 'Latin',
                'speakers_native' => 65000000,
                'speakers_total' => 85000000,
                'language_type' => 'natural',
                'status' => 'living',
                'description' => 'Italian is a Romance language spoken primarily in Italy and parts of Switzerland.'
            ],
            [
                'name' => 'Turkish',
                'native_name' => 'Türkçe',
                'iso_639_1' => 'tr',
                'iso_639_2' => 'tur',
                'iso_639_3' => 'tur',
                'language_family' => 'Turkic',
                'writing_system' => 'Latin',
                'speakers_native' => 76000000,
                'speakers_total' => 88000000,
                'language_type' => 'natural',
                'status' => 'living',
                'description' => 'Turkish is a Turkic language spoken primarily in Turkey and Cyprus.'
            ],
            [
                'name' => 'Vietnamese',
                'native_name' => 'Tiếng Việt',
                'iso_639_1' => 'vi',
                'iso_639_2' => 'vie',
                'iso_639_3' => 'vie',
                'language_family' => 'Austroasiatic',
                'writing_system' => 'Latin',
                'speakers_native' => 76000000,
                'speakers_total' => 95000000,
                'language_type' => 'natural',
                'status' => 'living',
                'description' => 'Vietnamese is the national and official language of Vietnam.'
            ],
            [
                'name' => 'Latin',
                'native_name' => 'Lingua Latina',
                'iso_639_1' => 'la',
                'iso_639_2' => 'lat',
                'iso_639_3' => 'lat',
                'language_family' => 'Indo-European',
                'writing_system' => 'Latin',
                'speakers_native' => 0,
                'speakers_total' => 1000000,
                'language_type' => 'natural',
                'status' => 'extinct',
                'description' => 'Latin was the language of ancient Rome and is the ancestor of the Romance languages. Still used in scientific nomenclature and religious contexts.'
            ],
            [
                'name' => 'Esperanto',
                'native_name' => 'Esperanto',
                'iso_639_1' => 'eo',
                'iso_639_2' => 'epo',
                'iso_639_3' => 'epo',
                'language_family' => 'Constructed',
                'writing_system' => 'Latin',
                'speakers_native' => 1000,
                'speakers_total' => 2000000,
                'language_type' => 'constructed',
                'status' => 'living',
                'description' => 'Esperanto is the most widely spoken constructed international auxiliary language, created by L. L. Zamenhof in 1887.'
            ],
            [
                'name' => 'American Sign Language',
                'native_name' => 'ASL',
                'iso_639_1' => null,
                'iso_639_2' => null,
                'iso_639_3' => 'ase',
                'language_family' => 'Sign language',
                'writing_system' => 'SignWriting (rarely used)',
                'speakers_native' => 250000,
                'speakers_total' => 500000,
                'language_type' => 'sign',
                'status' => 'living',
                'description' => 'American Sign Language is a complete, natural language used by the Deaf community in the United States and parts of Canada.'
            ]
        ];

        foreach ($languages as $languageData) {
            // Check if language already exists
            $existing = Language::findByName($languageData['name']);
            if (!$existing) {
                $language = new Language();
                $language->fill($languageData);
                $language->save();
                echo "Created language: {$language->name}" . ($language->native_name !== $language->name ? " ({$language->native_name})" : '') . "\n";
            } else {
                echo "Language {$languageData['name']} already exists, skipping...\n";
            }
        }
    }

    private function seedCountries() {
        echo "Seeding countries...\n";

        // Get continent IDs for reference
        $continents = [
            'Asia' => Continent::findByCode('AS'),
            'Africa' => Continent::findByCode('AF'),
            'North America' => Continent::findByCode('NA'),
            'South America' => Continent::findByCode('SA'),
            'Europe' => Continent::findByCode('EU'),
            'Oceania' => Continent::findByCode('OC')
        ];

        $countries = [
            // ASIA (48 countries)
            ['name' => 'Afghanistan', 'official_name' => 'Islamic Emirate of Afghanistan', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'AF', 'iso_alpha_3' => 'AFG', 'iso_numeric' => '004', 'capital' => 'Kabul', 'area_km2' => 652867, 'population' => 38928346, 'gdp_usd' => 19000000000, 'gdp_per_capita' => 488, 'currency_code' => 'AFN', 'currency_name' => 'Afghani', 'calling_code' => '+93', 'government_type' => 'islamic emirate', 'flag_emoji' => '🇦🇫', 'region' => 'Southern Asia', 'subregion' => 'Southern Asia', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Armenia', 'official_name' => 'Republic of Armenia', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'AM', 'iso_alpha_3' => 'ARM', 'iso_numeric' => '051', 'capital' => 'Yerevan', 'area_km2' => 29743, 'population' => 2963243, 'gdp_usd' => 13900000000, 'gdp_per_capita' => 4692, 'currency_code' => 'AMD', 'currency_name' => 'Dram', 'calling_code' => '+374', 'government_type' => 'republic', 'flag_emoji' => '🇦🇲', 'region' => 'Western Asia', 'subregion' => 'Western Asia', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Azerbaijan', 'official_name' => 'Republic of Azerbaijan', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'AZ', 'iso_alpha_3' => 'AZE', 'iso_numeric' => '031', 'capital' => 'Baku', 'area_km2' => 86600, 'population' => 10139177, 'gdp_usd' => 48100000000, 'gdp_per_capita' => 4747, 'currency_code' => 'AZN', 'currency_name' => 'Manat', 'calling_code' => '+994', 'government_type' => 'republic', 'flag_emoji' => '🇦🇿', 'region' => 'Western Asia', 'subregion' => 'Western Asia', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Bahrain', 'official_name' => 'Kingdom of Bahrain', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'BH', 'iso_alpha_3' => 'BHR', 'iso_numeric' => '048', 'capital' => 'Manama', 'area_km2' => 765, 'population' => 1701575, 'gdp_usd' => 38570000000, 'gdp_per_capita' => 22669, 'currency_code' => 'BHD', 'currency_name' => 'Dinar', 'calling_code' => '+973', 'government_type' => 'monarchy', 'flag_emoji' => '🇧🇭', 'region' => 'Western Asia', 'subregion' => 'Western Asia', 'is_developed' => 1],
            ['name' => 'Bangladesh', 'official_name' => 'People\'s Republic of Bangladesh', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'BD', 'iso_alpha_3' => 'BGD', 'iso_numeric' => '050', 'capital' => 'Dhaka', 'area_km2' => 147570, 'population' => 164689383, 'gdp_usd' => 416000000000, 'gdp_per_capita' => 2503, 'currency_code' => 'BDT', 'currency_name' => 'Taka', 'calling_code' => '+880', 'government_type' => 'republic', 'flag_emoji' => '🇧🇩', 'region' => 'Southern Asia', 'subregion' => 'Southern Asia', 'is_developed' => 0],
            ['name' => 'Bhutan', 'official_name' => 'Kingdom of Bhutan', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'BT', 'iso_alpha_3' => 'BTN', 'iso_numeric' => '064', 'capital' => 'Thimphu', 'area_km2' => 38394, 'population' => 771608, 'gdp_usd' => 2540000000, 'gdp_per_capita' => 3293, 'currency_code' => 'BTN', 'currency_name' => 'Ngultrum', 'calling_code' => '+975', 'government_type' => 'monarchy', 'flag_emoji' => '🇧🇹', 'region' => 'Southern Asia', 'subregion' => 'Southern Asia', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Brunei', 'official_name' => 'Nation of Brunei', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'BN', 'iso_alpha_3' => 'BRN', 'iso_numeric' => '096', 'capital' => 'Bandar Seri Begawan', 'area_km2' => 5765, 'population' => 437479, 'gdp_usd' => 12100000000, 'gdp_per_capita' => 27466, 'currency_code' => 'BND', 'currency_name' => 'Dollar', 'calling_code' => '+673', 'government_type' => 'monarchy', 'flag_emoji' => '🇧🇳', 'region' => 'South-Eastern Asia', 'subregion' => 'South-Eastern Asia', 'is_developed' => 1],
            ['name' => 'Cambodia', 'official_name' => 'Kingdom of Cambodia', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'KH', 'iso_alpha_3' => 'KHM', 'iso_numeric' => '116', 'capital' => 'Phnom Penh', 'area_km2' => 181035, 'population' => 16718965, 'gdp_usd' => 27090000000, 'gdp_per_capita' => 1621, 'currency_code' => 'KHR', 'currency_name' => 'Riel', 'calling_code' => '+855', 'government_type' => 'monarchy', 'flag_emoji' => '🇰🇭', 'region' => 'South-Eastern Asia', 'subregion' => 'South-Eastern Asia', 'is_developed' => 0],
            ['name' => 'China', 'official_name' => 'People\'s Republic of China', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'CN', 'iso_alpha_3' => 'CHN', 'iso_numeric' => '156', 'capital' => 'Beijing', 'area_km2' => 9596960, 'population' => 1439323776, 'gdp_usd' => 17734000000000, 'gdp_per_capita' => 12318, 'currency_code' => 'CNY', 'currency_name' => 'Yuan', 'calling_code' => '+86', 'government_type' => 'communist', 'flag_emoji' => '🇨🇳', 'region' => 'Eastern Asia', 'subregion' => 'Eastern Asia', 'is_developed' => 0],
            ['name' => 'Cyprus', 'official_name' => 'Republic of Cyprus', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'CY', 'iso_alpha_3' => 'CYP', 'iso_numeric' => '196', 'capital' => 'Nicosia', 'area_km2' => 9251, 'population' => 1207359, 'gdp_usd' => 25000000000, 'gdp_per_capita' => 29322, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+357', 'government_type' => 'republic', 'flag_emoji' => '🇨🇾', 'region' => 'Western Asia', 'subregion' => 'Western Asia', 'is_developed' => 1],
            ['name' => 'Georgia', 'official_name' => 'Georgia', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'GE', 'iso_alpha_3' => 'GEO', 'iso_numeric' => '268', 'capital' => 'Tbilisi', 'area_km2' => 69700, 'population' => 3989167, 'gdp_usd' => 18600000000, 'gdp_per_capita' => 4743, 'currency_code' => 'GEL', 'currency_name' => 'Lari', 'calling_code' => '+995', 'government_type' => 'republic', 'flag_emoji' => '🇬🇪', 'region' => 'Western Asia', 'subregion' => 'Western Asia', 'is_developed' => 0],
            ['name' => 'India', 'official_name' => 'Republic of India', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'IN', 'iso_alpha_3' => 'IND', 'iso_numeric' => '356', 'capital' => 'New Delhi', 'area_km2' => 3287263, 'population' => 1380004385, 'gdp_usd' => 3737000000000, 'gdp_per_capita' => 2709, 'currency_code' => 'INR', 'currency_name' => 'Rupee', 'calling_code' => '+91', 'government_type' => 'republic', 'flag_emoji' => '🇮🇳', 'region' => 'Southern Asia', 'subregion' => 'Southern Asia', 'is_developed' => 0],
            ['name' => 'Indonesia', 'official_name' => 'Republic of Indonesia', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'ID', 'iso_alpha_3' => 'IDN', 'iso_numeric' => '360', 'capital' => 'Jakarta', 'area_km2' => 1904569, 'population' => 273523615, 'gdp_usd' => 1289000000000, 'gdp_per_capita' => 4256, 'currency_code' => 'IDR', 'currency_name' => 'Rupiah', 'calling_code' => '+62', 'government_type' => 'republic', 'flag_emoji' => '🇮🇩', 'region' => 'South-Eastern Asia', 'subregion' => 'South-Eastern Asia', 'is_developed' => 0],
            ['name' => 'Iran', 'official_name' => 'Islamic Republic of Iran', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'IR', 'iso_alpha_3' => 'IRN', 'iso_numeric' => '364', 'capital' => 'Tehran', 'area_km2' => 1648195, 'population' => 83992949, 'gdp_usd' => 231000000000, 'gdp_per_capita' => 2751, 'currency_code' => 'IRR', 'currency_name' => 'Rial', 'calling_code' => '+98', 'government_type' => 'republic', 'flag_emoji' => '🇮🇷', 'region' => 'Southern Asia', 'subregion' => 'Southern Asia', 'is_developed' => 0],
            ['name' => 'Iraq', 'official_name' => 'Republic of Iraq', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'IQ', 'iso_alpha_3' => 'IRQ', 'iso_numeric' => '368', 'capital' => 'Baghdad', 'area_km2' => 438317, 'population' => 40222493, 'gdp_usd' => 225000000000, 'gdp_per_capita' => 5594, 'currency_code' => 'IQD', 'currency_name' => 'Dinar', 'calling_code' => '+964', 'government_type' => 'republic', 'flag_emoji' => '🇮🇶', 'region' => 'Western Asia', 'subregion' => 'Western Asia', 'is_developed' => 0],
            ['name' => 'Israel', 'official_name' => 'State of Israel', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'IL', 'iso_alpha_3' => 'ISR', 'iso_numeric' => '376', 'capital' => 'Jerusalem', 'area_km2' => 20770, 'population' => 8655535, 'gdp_usd' => 481000000000, 'gdp_per_capita' => 47603, 'currency_code' => 'ILS', 'currency_name' => 'Shekel', 'calling_code' => '+972', 'government_type' => 'republic', 'flag_emoji' => '🇮🇱', 'region' => 'Western Asia', 'subregion' => 'Western Asia', 'is_developed' => 1],
            ['name' => 'Japan', 'official_name' => 'Japan', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'JP', 'iso_alpha_3' => 'JPN', 'iso_numeric' => '392', 'capital' => 'Tokyo', 'area_km2' => 377930, 'population' => 125836021, 'gdp_usd' => 4937000000000, 'gdp_per_capita' => 39243, 'currency_code' => 'JPY', 'currency_name' => 'Yen', 'calling_code' => '+81', 'government_type' => 'parliamentary', 'flag_emoji' => '🇯🇵', 'region' => 'Eastern Asia', 'subregion' => 'Eastern Asia', 'is_developed' => 1],
            ['name' => 'Jordan', 'official_name' => 'Hashemite Kingdom of Jordan', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'JO', 'iso_alpha_3' => 'JOR', 'iso_numeric' => '400', 'capital' => 'Amman', 'area_km2' => 89342, 'population' => 10203134, 'gdp_usd' => 45200000000, 'gdp_per_capita' => 4731, 'currency_code' => 'JOD', 'currency_name' => 'Dinar', 'calling_code' => '+962', 'government_type' => 'monarchy', 'flag_emoji' => '🇯🇴', 'region' => 'Western Asia', 'subregion' => 'Western Asia', 'is_developed' => 0],
            ['name' => 'Kazakhstan', 'official_name' => 'Republic of Kazakhstan', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'KZ', 'iso_alpha_3' => 'KAZ', 'iso_numeric' => '398', 'capital' => 'Nur-Sultan', 'area_km2' => 2724900, 'population' => 18776707, 'gdp_usd' => 197000000000, 'gdp_per_capita' => 10482, 'currency_code' => 'KZT', 'currency_name' => 'Tenge', 'calling_code' => '+7', 'government_type' => 'republic', 'flag_emoji' => '🇰🇿', 'region' => 'Central Asia', 'subregion' => 'Central Asia', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Kuwait', 'official_name' => 'State of Kuwait', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'KW', 'iso_alpha_3' => 'KWT', 'iso_numeric' => '414', 'capital' => 'Kuwait City', 'area_km2' => 17818, 'population' => 4270571, 'gdp_usd' => 135000000000, 'gdp_per_capita' => 31640, 'currency_code' => 'KWD', 'currency_name' => 'Dinar', 'calling_code' => '+965', 'government_type' => 'monarchy', 'flag_emoji' => '🇰🇼', 'region' => 'Western Asia', 'subregion' => 'Western Asia', 'is_developed' => 1],
            ['name' => 'Kyrgyzstan', 'official_name' => 'Kyrgyz Republic', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'KG', 'iso_alpha_3' => 'KGZ', 'iso_numeric' => '417', 'capital' => 'Bishkek', 'area_km2' => 199951, 'population' => 6524195, 'gdp_usd' => 8500000000, 'gdp_per_capita' => 1309, 'currency_code' => 'KGS', 'currency_name' => 'Som', 'calling_code' => '+996', 'government_type' => 'republic', 'flag_emoji' => '🇰🇬', 'region' => 'Central Asia', 'subregion' => 'Central Asia', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Laos', 'official_name' => 'Lao People\'s Democratic Republic', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'LA', 'iso_alpha_3' => 'LAO', 'iso_numeric' => '418', 'capital' => 'Vientiane', 'area_km2' => 236800, 'population' => 7275560, 'gdp_usd' => 18170000000, 'gdp_per_capita' => 2497, 'currency_code' => 'LAK', 'currency_name' => 'Kip', 'calling_code' => '+856', 'government_type' => 'communist', 'flag_emoji' => '🇱🇦', 'region' => 'South-Eastern Asia', 'subregion' => 'South-Eastern Asia', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Lebanon', 'official_name' => 'Lebanese Republic', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'LB', 'iso_alpha_3' => 'LBN', 'iso_numeric' => '422', 'capital' => 'Beirut', 'area_km2' => 10452, 'population' => 6825445, 'gdp_usd' => 18080000000, 'gdp_per_capita' => 2648, 'currency_code' => 'LBP', 'currency_name' => 'Pound', 'calling_code' => '+961', 'government_type' => 'republic', 'flag_emoji' => '🇱🇧', 'region' => 'Western Asia', 'subregion' => 'Western Asia', 'is_developed' => 0],
            ['name' => 'Malaysia', 'official_name' => 'Malaysia', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'MY', 'iso_alpha_3' => 'MYS', 'iso_numeric' => '458', 'capital' => 'Kuala Lumpur', 'area_km2' => 330803, 'population' => 32365999, 'gdp_usd' => 401000000000, 'gdp_per_capita' => 12387, 'currency_code' => 'MYR', 'currency_name' => 'Ringgit', 'calling_code' => '+60', 'government_type' => 'federal monarchy', 'flag_emoji' => '🇲🇾', 'region' => 'South-Eastern Asia', 'subregion' => 'South-Eastern Asia', 'is_developed' => 0],
            ['name' => 'Maldives', 'official_name' => 'Republic of Maldives', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'MV', 'iso_alpha_3' => 'MDV', 'iso_numeric' => '462', 'capital' => 'Malé', 'area_km2' => 300, 'population' => 540544, 'gdp_usd' => 5600000000, 'gdp_per_capita' => 10366, 'currency_code' => 'MVR', 'currency_name' => 'Rufiyaa', 'calling_code' => '+960', 'government_type' => 'republic', 'flag_emoji' => '🇲🇻', 'region' => 'Southern Asia', 'subregion' => 'Southern Asia', 'is_developed' => 0],
            ['name' => 'Mongolia', 'official_name' => 'Mongolia', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'MN', 'iso_alpha_3' => 'MNG', 'iso_numeric' => '496', 'capital' => 'Ulaanbaatar', 'area_km2' => 1564110, 'population' => 3278290, 'gdp_usd' => 14000000000, 'gdp_per_capita' => 4339, 'currency_code' => 'MNT', 'currency_name' => 'Tugrik', 'calling_code' => '+976', 'government_type' => 'republic', 'flag_emoji' => '🇲🇳', 'region' => 'Eastern Asia', 'subregion' => 'Eastern Asia', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Myanmar', 'official_name' => 'Republic of the Union of Myanmar', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'MM', 'iso_alpha_3' => 'MMR', 'iso_numeric' => '104', 'capital' => 'Naypyidaw', 'area_km2' => 676578, 'population' => 54409800, 'gdp_usd' => 76100000000, 'gdp_per_capita' => 1400, 'currency_code' => 'MMK', 'currency_name' => 'Kyat', 'calling_code' => '+95', 'government_type' => 'military', 'flag_emoji' => '🇲🇲', 'region' => 'South-Eastern Asia', 'subregion' => 'South-Eastern Asia', 'is_developed' => 0],
            ['name' => 'Nepal', 'official_name' => 'Nepal', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'NP', 'iso_alpha_3' => 'NPL', 'iso_numeric' => '524', 'capital' => 'Kathmandu', 'area_km2' => 147181, 'population' => 29136808, 'gdp_usd' => 36290000000, 'gdp_per_capita' => 1247, 'currency_code' => 'NPR', 'currency_name' => 'Rupee', 'calling_code' => '+977', 'government_type' => 'republic', 'flag_emoji' => '🇳🇵', 'region' => 'Southern Asia', 'subregion' => 'Southern Asia', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'North Korea', 'official_name' => 'Democratic People\'s Republic of Korea', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'KP', 'iso_alpha_3' => 'PRK', 'iso_numeric' => '408', 'capital' => 'Pyongyang', 'area_km2' => 120538, 'population' => 25778816, 'gdp_usd' => 18000000000, 'gdp_per_capita' => 698, 'currency_code' => 'KPW', 'currency_name' => 'Won', 'calling_code' => '+850', 'government_type' => 'communist', 'flag_emoji' => '🇰🇵', 'region' => 'Eastern Asia', 'subregion' => 'Eastern Asia', 'is_developed' => 0],
            ['name' => 'Oman', 'official_name' => 'Sultanate of Oman', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'OM', 'iso_alpha_3' => 'OMN', 'iso_numeric' => '512', 'capital' => 'Muscat', 'area_km2' => 309500, 'population' => 5106626, 'gdp_usd' => 76000000000, 'gdp_per_capita' => 14877, 'currency_code' => 'OMR', 'currency_name' => 'Rial', 'calling_code' => '+968', 'government_type' => 'monarchy', 'flag_emoji' => '🇴🇲', 'region' => 'Western Asia', 'subregion' => 'Western Asia', 'is_developed' => 1],
            ['name' => 'Pakistan', 'official_name' => 'Islamic Republic of Pakistan', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'PK', 'iso_alpha_3' => 'PAK', 'iso_numeric' => '586', 'capital' => 'Islamabad', 'area_km2' => 881912, 'population' => 220892340, 'gdp_usd' => 348000000000, 'gdp_per_capita' => 1543, 'currency_code' => 'PKR', 'currency_name' => 'Rupee', 'calling_code' => '+92', 'government_type' => 'republic', 'flag_emoji' => '🇵🇰', 'region' => 'Southern Asia', 'subregion' => 'Southern Asia', 'is_developed' => 0],
            ['name' => 'Palestine', 'official_name' => 'State of Palestine', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'PS', 'iso_alpha_3' => 'PSE', 'iso_numeric' => '275', 'capital' => 'East Jerusalem', 'area_km2' => 6020, 'population' => 5101414, 'gdp_usd' => 18100000000, 'gdp_per_capita' => 3664, 'currency_code' => 'ILS', 'currency_name' => 'Shekel', 'calling_code' => '+970', 'government_type' => 'republic', 'flag_emoji' => '🇵🇸', 'region' => 'Western Asia', 'subregion' => 'Western Asia', 'is_developed' => 0],
            ['name' => 'Philippines', 'official_name' => 'Republic of the Philippines', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'PH', 'iso_alpha_3' => 'PHL', 'iso_numeric' => '608', 'capital' => 'Manila', 'area_km2' => 300000, 'population' => 109581078, 'gdp_usd' => 377000000000, 'gdp_per_capita' => 3499, 'currency_code' => 'PHP', 'currency_name' => 'Peso', 'calling_code' => '+63', 'government_type' => 'republic', 'flag_emoji' => '🇵🇭', 'region' => 'South-Eastern Asia', 'subregion' => 'South-Eastern Asia', 'is_developed' => 0],
            ['name' => 'Qatar', 'official_name' => 'State of Qatar', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'QA', 'iso_alpha_3' => 'QAT', 'iso_numeric' => '634', 'capital' => 'Doha', 'area_km2' => 11586, 'population' => 2881053, 'gdp_usd' => 183000000000, 'gdp_per_capita' => 63506, 'currency_code' => 'QAR', 'currency_name' => 'Riyal', 'calling_code' => '+974', 'government_type' => 'monarchy', 'flag_emoji' => '🇶🇦', 'region' => 'Western Asia', 'subregion' => 'Western Asia', 'is_developed' => 1],
            ['name' => 'Russia', 'official_name' => 'Russian Federation', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'RU', 'iso_alpha_3' => 'RUS', 'iso_numeric' => '643', 'capital' => 'Moscow', 'area_km2' => 17098242, 'population' => 145934462, 'gdp_usd' => 1777000000000, 'gdp_per_capita' => 12194, 'currency_code' => 'RUB', 'currency_name' => 'Ruble', 'calling_code' => '+7', 'government_type' => 'federal republic', 'flag_emoji' => '🇷🇺', 'region' => 'Eastern Europe', 'subregion' => 'Eastern Europe', 'is_developed' => 0],
            ['name' => 'Saudi Arabia', 'official_name' => 'Kingdom of Saudi Arabia', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'SA', 'iso_alpha_3' => 'SAU', 'iso_numeric' => '682', 'capital' => 'Riyadh', 'area_km2' => 2149690, 'population' => 34813871, 'gdp_usd' => 793000000000, 'gdp_per_capita' => 22865, 'currency_code' => 'SAR', 'currency_name' => 'Riyal', 'calling_code' => '+966', 'government_type' => 'monarchy', 'flag_emoji' => '🇸🇦', 'region' => 'Western Asia', 'subregion' => 'Western Asia', 'is_developed' => 1],
            ['name' => 'Singapore', 'official_name' => 'Republic of Singapore', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'SG', 'iso_alpha_3' => 'SGP', 'iso_numeric' => '702', 'capital' => 'Singapore', 'area_km2' => 719, 'population' => 5850342, 'gdp_usd' => 372000000000, 'gdp_per_capita' => 65233, 'currency_code' => 'SGD', 'currency_name' => 'Dollar', 'calling_code' => '+65', 'government_type' => 'republic', 'flag_emoji' => '🇸🇬', 'region' => 'South-Eastern Asia', 'subregion' => 'South-Eastern Asia', 'is_developed' => 1],
            ['name' => 'South Korea', 'official_name' => 'Republic of Korea', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'KR', 'iso_alpha_3' => 'KOR', 'iso_numeric' => '410', 'capital' => 'Seoul', 'area_km2' => 100210, 'population' => 51269185, 'gdp_usd' => 1811000000000, 'gdp_per_capita' => 35196, 'currency_code' => 'KRW', 'currency_name' => 'Won', 'calling_code' => '+82', 'government_type' => 'republic', 'flag_emoji' => '🇰🇷', 'region' => 'Eastern Asia', 'subregion' => 'Eastern Asia', 'is_developed' => 1],
            ['name' => 'Sri Lanka', 'official_name' => 'Democratic Socialist Republic of Sri Lanka', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'LK', 'iso_alpha_3' => 'LKA', 'iso_numeric' => '144', 'capital' => 'Sri Jayawardenepura Kotte', 'area_km2' => 65610, 'population' => 21413249, 'gdp_usd' => 84000000000, 'gdp_per_capita' => 3682, 'currency_code' => 'LKR', 'currency_name' => 'Rupee', 'calling_code' => '+94', 'government_type' => 'republic', 'flag_emoji' => '🇱🇰', 'region' => 'Southern Asia', 'subregion' => 'Southern Asia', 'is_developed' => 0],
            ['name' => 'Syria', 'official_name' => 'Syrian Arab Republic', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'SY', 'iso_alpha_3' => 'SYR', 'iso_numeric' => '760', 'capital' => 'Damascus', 'area_km2' => 185180, 'population' => 17500658, 'gdp_usd' => 40000000000, 'gdp_per_capita' => 2033, 'currency_code' => 'SYP', 'currency_name' => 'Pound', 'calling_code' => '+963', 'government_type' => 'republic', 'flag_emoji' => '🇸🇾', 'region' => 'Western Asia', 'subregion' => 'Western Asia', 'is_developed' => 0],
            ['name' => 'Taiwan', 'official_name' => 'Republic of China', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'TW', 'iso_alpha_3' => 'TWN', 'iso_numeric' => '158', 'capital' => 'Taipei', 'area_km2' => 36193, 'population' => 23816775, 'gdp_usd' => 669000000000, 'gdp_per_capita' => 28180, 'currency_code' => 'TWD', 'currency_name' => 'Dollar', 'calling_code' => '+886', 'government_type' => 'republic', 'flag_emoji' => '🇹🇼', 'region' => 'Eastern Asia', 'subregion' => 'Eastern Asia', 'is_developed' => 1],
            ['name' => 'Tajikistan', 'official_name' => 'Republic of Tajikistan', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'TJ', 'iso_alpha_3' => 'TJK', 'iso_numeric' => '762', 'capital' => 'Dushanbe', 'area_km2' => 143100, 'population' => 9537645, 'gdp_usd' => 8100000000, 'gdp_per_capita' => 857, 'currency_code' => 'TJS', 'currency_name' => 'Somoni', 'calling_code' => '+992', 'government_type' => 'republic', 'flag_emoji' => '🇹🇯', 'region' => 'Central Asia', 'subregion' => 'Central Asia', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Thailand', 'official_name' => 'Kingdom of Thailand', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'TH', 'iso_alpha_3' => 'THA', 'iso_numeric' => '764', 'capital' => 'Bangkok', 'area_km2' => 513120, 'population' => 69799978, 'gdp_usd' => 544000000000, 'gdp_per_capita' => 7806, 'currency_code' => 'THB', 'currency_name' => 'Baht', 'calling_code' => '+66', 'government_type' => 'monarchy', 'flag_emoji' => '🇹🇭', 'region' => 'South-Eastern Asia', 'subregion' => 'South-Eastern Asia', 'is_developed' => 0],
            ['name' => 'Timor-Leste', 'official_name' => 'Democratic Republic of Timor-Leste', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'TL', 'iso_alpha_3' => 'TLS', 'iso_numeric' => '626', 'capital' => 'Dili', 'area_km2' => 14874, 'population' => 1318445, 'gdp_usd' => 1700000000, 'gdp_per_capita' => 1381, 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'calling_code' => '+670', 'government_type' => 'republic', 'flag_emoji' => '🇹🇱', 'region' => 'South-Eastern Asia', 'subregion' => 'South-Eastern Asia', 'is_developed' => 0],
            ['name' => 'Turkey', 'official_name' => 'Republic of Turkey', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'TR', 'iso_alpha_3' => 'TUR', 'iso_numeric' => '792', 'capital' => 'Ankara', 'area_km2' => 783562, 'population' => 84339067, 'gdp_usd' => 761000000000, 'gdp_per_capita' => 9020, 'currency_code' => 'TRY', 'currency_name' => 'Lira', 'calling_code' => '+90', 'government_type' => 'republic', 'flag_emoji' => '🇹🇷', 'region' => 'Western Asia', 'subregion' => 'Western Asia', 'is_developed' => 0],
            ['name' => 'Turkmenistan', 'official_name' => 'Turkmenistan', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'TM', 'iso_alpha_3' => 'TKM', 'iso_numeric' => '795', 'capital' => 'Ashgabat', 'area_km2' => 488100, 'population' => 6031200, 'gdp_usd' => 40760000000, 'gdp_per_capita' => 6966, 'currency_code' => 'TMT', 'currency_name' => 'Manat', 'calling_code' => '+993', 'government_type' => 'republic', 'flag_emoji' => '🇹🇲', 'region' => 'Central Asia', 'subregion' => 'Central Asia', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'United Arab Emirates', 'official_name' => 'United Arab Emirates', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'AE', 'iso_alpha_3' => 'ARE', 'iso_numeric' => '784', 'capital' => 'Abu Dhabi', 'area_km2' => 83600, 'population' => 9890402, 'gdp_usd' => 421000000000, 'gdp_per_capita' => 43103, 'currency_code' => 'AED', 'currency_name' => 'Dirham', 'calling_code' => '+971', 'government_type' => 'federation', 'flag_emoji' => '🇦🇪', 'region' => 'Western Asia', 'subregion' => 'Western Asia', 'is_developed' => 1],
            ['name' => 'Uzbekistan', 'official_name' => 'Republic of Uzbekistan', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'UZ', 'iso_alpha_3' => 'UZB', 'iso_numeric' => '860', 'capital' => 'Tashkent', 'area_km2' => 447400, 'population' => 33469203, 'gdp_usd' => 57900000000, 'gdp_per_capita' => 1724, 'currency_code' => 'UZS', 'currency_name' => 'Som', 'calling_code' => '+998', 'government_type' => 'republic', 'flag_emoji' => '🇺🇿', 'region' => 'Central Asia', 'subregion' => 'Central Asia', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Vietnam', 'official_name' => 'Socialist Republic of Vietnam', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'VN', 'iso_alpha_3' => 'VNM', 'iso_numeric' => '704', 'capital' => 'Hanoi', 'area_km2' => 331212, 'population' => 97338579, 'gdp_usd' => 362000000000, 'gdp_per_capita' => 3694, 'currency_code' => 'VND', 'currency_name' => 'Dong', 'calling_code' => '+84', 'government_type' => 'communist', 'flag_emoji' => '🇻🇳', 'region' => 'South-Eastern Asia', 'subregion' => 'South-Eastern Asia', 'is_developed' => 0],
            ['name' => 'Yemen', 'official_name' => 'Republic of Yemen', 'continent_id' => $continents['Asia']->id, 'iso_alpha_2' => 'YE', 'iso_alpha_3' => 'YEM', 'iso_numeric' => '887', 'capital' => 'Sanaa', 'area_km2' => 527968, 'population' => 29825964, 'gdp_usd' => 21600000000, 'gdp_per_capita' => 824, 'currency_code' => 'YER', 'currency_name' => 'Rial', 'calling_code' => '+967', 'government_type' => 'republic', 'flag_emoji' => '🇾🇪', 'region' => 'Western Asia', 'subregion' => 'Western Asia', 'is_developed' => 0],

            // EUROPE (44 countries)
            ['name' => 'Albania', 'official_name' => 'Republic of Albania', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'AL', 'iso_alpha_3' => 'ALB', 'iso_numeric' => '008', 'capital' => 'Tirana', 'area_km2' => 28748, 'population' => 2877797, 'gdp_usd' => 15280000000, 'gdp_per_capita' => 5353, 'currency_code' => 'ALL', 'currency_name' => 'Lek', 'calling_code' => '+355', 'government_type' => 'republic', 'flag_emoji' => '🇦🇱', 'region' => 'Southern Europe', 'subregion' => 'Southern Europe', 'is_developed' => 0],
            ['name' => 'Andorra', 'official_name' => 'Principality of Andorra', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'AD', 'iso_alpha_3' => 'AND', 'iso_numeric' => '020', 'capital' => 'Andorra la Vella', 'area_km2' => 468, 'population' => 77265, 'gdp_usd' => 3200000000, 'gdp_per_capita' => 42035, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+376', 'government_type' => 'parliamentary', 'flag_emoji' => '🇦🇩', 'region' => 'Southern Europe', 'subregion' => 'Southern Europe', 'is_landlocked' => 1, 'is_developed' => 1],
            ['name' => 'Austria', 'official_name' => 'Republic of Austria', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'AT', 'iso_alpha_3' => 'AUT', 'iso_numeric' => '040', 'capital' => 'Vienna', 'area_km2' => 83879, 'population' => 9006398, 'gdp_usd' => 433000000000, 'gdp_per_capita' => 48104, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+43', 'government_type' => 'republic', 'flag_emoji' => '🇦🇹', 'region' => 'Western Europe', 'subregion' => 'Western Europe', 'is_landlocked' => 1, 'is_developed' => 1],
            ['name' => 'Belarus', 'official_name' => 'Republic of Belarus', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'BY', 'iso_alpha_3' => 'BLR', 'iso_numeric' => '112', 'capital' => 'Minsk', 'area_km2' => 207600, 'population' => 9449323, 'gdp_usd' => 60300000000, 'gdp_per_capita' => 6374, 'currency_code' => 'BYN', 'currency_name' => 'Ruble', 'calling_code' => '+375', 'government_type' => 'republic', 'flag_emoji' => '🇧🇾', 'region' => 'Eastern Europe', 'subregion' => 'Eastern Europe', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Belgium', 'official_name' => 'Kingdom of Belgium', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'BE', 'iso_alpha_3' => 'BEL', 'iso_numeric' => '056', 'capital' => 'Brussels', 'area_km2' => 30528, 'population' => 11589623, 'gdp_usd' => 521000000000, 'gdp_per_capita' => 46553, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+32', 'government_type' => 'monarchy', 'flag_emoji' => '🇧🇪', 'region' => 'Western Europe', 'subregion' => 'Western Europe', 'is_developed' => 1],
            ['name' => 'Bosnia and Herzegovina', 'official_name' => 'Bosnia and Herzegovina', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'BA', 'iso_alpha_3' => 'BIH', 'iso_numeric' => '070', 'capital' => 'Sarajevo', 'area_km2' => 51197, 'population' => 3280819, 'gdp_usd' => 20100000000, 'gdp_per_capita' => 6090, 'currency_code' => 'BAM', 'currency_name' => 'Convertible Mark', 'calling_code' => '+387', 'government_type' => 'republic', 'flag_emoji' => '🇧🇦', 'region' => 'Southern Europe', 'subregion' => 'Southern Europe', 'is_developed' => 0],
            ['name' => 'Bulgaria', 'official_name' => 'Republic of Bulgaria', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'BG', 'iso_alpha_3' => 'BGR', 'iso_numeric' => '100', 'capital' => 'Sofia', 'area_km2' => 110879, 'population' => 6948445, 'gdp_usd' => 69100000000, 'gdp_per_capita' => 9975, 'currency_code' => 'BGN', 'currency_name' => 'Lev', 'calling_code' => '+359', 'government_type' => 'republic', 'flag_emoji' => '🇧🇬', 'region' => 'Eastern Europe', 'subregion' => 'Eastern Europe', 'is_developed' => 0],
            ['name' => 'Croatia', 'official_name' => 'Republic of Croatia', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'HR', 'iso_alpha_3' => 'HRV', 'iso_numeric' => '191', 'capital' => 'Zagreb', 'area_km2' => 56594, 'population' => 4105267, 'gdp_usd' => 60400000000, 'gdp_per_capita' => 15230, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+385', 'government_type' => 'republic', 'flag_emoji' => '🇭🇷', 'region' => 'Southern Europe', 'subregion' => 'Southern Europe', 'is_developed' => 1],
            ['name' => 'Czech Republic', 'official_name' => 'Czech Republic', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'CZ', 'iso_alpha_3' => 'CZE', 'iso_numeric' => '203', 'capital' => 'Prague', 'area_km2' => 78867, 'population' => 10708981, 'gdp_usd' => 246000000000, 'gdp_per_capita' => 23111, 'currency_code' => 'CZK', 'currency_name' => 'Koruna', 'calling_code' => '+420', 'government_type' => 'republic', 'flag_emoji' => '🇨🇿', 'region' => 'Eastern Europe', 'subregion' => 'Eastern Europe', 'is_landlocked' => 1, 'is_developed' => 1],
            ['name' => 'Denmark', 'official_name' => 'Kingdom of Denmark', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'DK', 'iso_alpha_3' => 'DNK', 'iso_numeric' => '208', 'capital' => 'Copenhagen', 'area_km2' => 43094, 'population' => 5792202, 'gdp_usd' => 356000000000, 'gdp_per_capita' => 61063, 'currency_code' => 'DKK', 'currency_name' => 'Krone', 'calling_code' => '+45', 'government_type' => 'monarchy', 'flag_emoji' => '🇩🇰', 'region' => 'Northern Europe', 'subregion' => 'Northern Europe', 'is_developed' => 1],
            ['name' => 'Estonia', 'official_name' => 'Republic of Estonia', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'EE', 'iso_alpha_3' => 'EST', 'iso_numeric' => '233', 'capital' => 'Tallinn', 'area_km2' => 45228, 'population' => 1326535, 'gdp_usd' => 31400000000, 'gdp_per_capita' => 23723, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+372', 'government_type' => 'republic', 'flag_emoji' => '🇪🇪', 'region' => 'Northern Europe', 'subregion' => 'Northern Europe', 'is_developed' => 1],
            ['name' => 'Finland', 'official_name' => 'Republic of Finland', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'FI', 'iso_alpha_3' => 'FIN', 'iso_numeric' => '246', 'capital' => 'Helsinki', 'area_km2' => 338424, 'population' => 5540720, 'gdp_usd' => 270000000000, 'gdp_per_capita' => 48810, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+358', 'government_type' => 'republic', 'flag_emoji' => '🇫🇮', 'region' => 'Northern Europe', 'subregion' => 'Northern Europe', 'is_developed' => 1],
            ['name' => 'France', 'official_name' => 'French Republic', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'FR', 'iso_alpha_3' => 'FRA', 'iso_numeric' => '250', 'capital' => 'Paris', 'area_km2' => 643801, 'population' => 65273511, 'gdp_usd' => 2940000000000, 'gdp_per_capita' => 45029, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+33', 'government_type' => 'republic', 'flag_emoji' => '🇫🇷', 'region' => 'Western Europe', 'subregion' => 'Western Europe', 'is_developed' => 1],
            ['name' => 'Germany', 'official_name' => 'Federal Republic of Germany', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'DE', 'iso_alpha_3' => 'DEU', 'iso_numeric' => '276', 'capital' => 'Berlin', 'area_km2' => 357114, 'population' => 83783942, 'gdp_usd' => 4260000000000, 'gdp_per_capita' => 50869, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+49', 'government_type' => 'federal republic', 'flag_emoji' => '🇩🇪', 'region' => 'Western Europe', 'subregion' => 'Western Europe', 'is_developed' => 1],
            ['name' => 'Greece', 'official_name' => 'Hellenic Republic', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'GR', 'iso_alpha_3' => 'GRC', 'iso_numeric' => '300', 'capital' => 'Athens', 'area_km2' => 131957, 'population' => 10423054, 'gdp_usd' => 189000000000, 'gdp_per_capita' => 18090, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+30', 'government_type' => 'republic', 'flag_emoji' => '🇬🇷', 'region' => 'Southern Europe', 'subregion' => 'Southern Europe', 'is_developed' => 1],
            ['name' => 'Hungary', 'official_name' => 'Hungary', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'HU', 'iso_alpha_3' => 'HUN', 'iso_numeric' => '348', 'capital' => 'Budapest', 'area_km2' => 93028, 'population' => 9660351, 'gdp_usd' => 163000000000, 'gdp_per_capita' => 16731, 'currency_code' => 'HUF', 'currency_name' => 'Forint', 'calling_code' => '+36', 'government_type' => 'republic', 'flag_emoji' => '🇭🇺', 'region' => 'Eastern Europe', 'subregion' => 'Eastern Europe', 'is_landlocked' => 1, 'is_developed' => 1],
            ['name' => 'Iceland', 'official_name' => 'Republic of Iceland', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'IS', 'iso_alpha_3' => 'ISL', 'iso_numeric' => '352', 'capital' => 'Reykjavik', 'area_km2' => 103000, 'population' => 341243, 'gdp_usd' => 24000000000, 'gdp_per_capita' => 70333, 'currency_code' => 'ISK', 'currency_name' => 'Krona', 'calling_code' => '+354', 'government_type' => 'republic', 'flag_emoji' => '🇮🇸', 'region' => 'Northern Europe', 'subregion' => 'Northern Europe', 'is_developed' => 1],
            ['name' => 'Ireland', 'official_name' => 'Republic of Ireland', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'IE', 'iso_alpha_3' => 'IRL', 'iso_numeric' => '372', 'capital' => 'Dublin', 'area_km2' => 70273, 'population' => 4937786, 'gdp_usd' => 426000000000, 'gdp_per_capita' => 85420, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+353', 'government_type' => 'republic', 'flag_emoji' => '🇮🇪', 'region' => 'Northern Europe', 'subregion' => 'Northern Europe', 'is_developed' => 1],
            ['name' => 'Italy', 'official_name' => 'Italian Republic', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'IT', 'iso_alpha_3' => 'ITA', 'iso_numeric' => '380', 'capital' => 'Rome', 'area_km2' => 301340, 'population' => 60461826, 'gdp_usd' => 2110000000000, 'gdp_per_capita' => 35220, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+39', 'government_type' => 'republic', 'flag_emoji' => '🇮🇹', 'region' => 'Southern Europe', 'subregion' => 'Southern Europe', 'is_developed' => 1],
            ['name' => 'Kosovo', 'official_name' => 'Republic of Kosovo', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'XK', 'iso_alpha_3' => 'UNK', 'iso_numeric' => '000', 'capital' => 'Pristina', 'area_km2' => 10908, 'population' => 1873160, 'gdp_usd' => 7900000000, 'gdp_per_capita' => 4440, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+383', 'government_type' => 'republic', 'flag_emoji' => '🇽🇰', 'region' => 'Southern Europe', 'subregion' => 'Southern Europe', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Latvia', 'official_name' => 'Republic of Latvia', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'LV', 'iso_alpha_3' => 'LVA', 'iso_numeric' => '428', 'capital' => 'Riga', 'area_km2' => 64589, 'population' => 1886198, 'gdp_usd' => 34100000000, 'gdp_per_capita' => 18090, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+371', 'government_type' => 'republic', 'flag_emoji' => '🇱🇻', 'region' => 'Northern Europe', 'subregion' => 'Northern Europe', 'is_developed' => 1],
            ['name' => 'Liechtenstein', 'official_name' => 'Principality of Liechtenstein', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'LI', 'iso_alpha_3' => 'LIE', 'iso_numeric' => '438', 'capital' => 'Vaduz', 'area_km2' => 160, 'population' => 38128, 'gdp_usd' => 6550000000, 'gdp_per_capita' => 171719, 'currency_code' => 'CHF', 'currency_name' => 'Franc', 'calling_code' => '+423', 'government_type' => 'monarchy', 'flag_emoji' => '🇱🇮', 'region' => 'Western Europe', 'subregion' => 'Western Europe', 'is_landlocked' => 1, 'is_developed' => 1],
            ['name' => 'Lithuania', 'official_name' => 'Republic of Lithuania', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'LT', 'iso_alpha_3' => 'LTU', 'iso_numeric' => '440', 'capital' => 'Vilnius', 'area_km2' => 65300, 'population' => 2722289, 'gdp_usd' => 56500000000, 'gdp_per_capita' => 20234, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+370', 'government_type' => 'republic', 'flag_emoji' => '🇱🇹', 'region' => 'Northern Europe', 'subregion' => 'Northern Europe', 'is_developed' => 1],
            ['name' => 'Luxembourg', 'official_name' => 'Grand Duchy of Luxembourg', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'LU', 'iso_alpha_3' => 'LUX', 'iso_numeric' => '442', 'capital' => 'Luxembourg', 'area_km2' => 2586, 'population' => 625978, 'gdp_usd' => 71100000000, 'gdp_per_capita' => 115839, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+352', 'government_type' => 'monarchy', 'flag_emoji' => '🇱🇺', 'region' => 'Western Europe', 'subregion' => 'Western Europe', 'is_landlocked' => 1, 'is_developed' => 1],
            ['name' => 'Malta', 'official_name' => 'Republic of Malta', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'MT', 'iso_alpha_3' => 'MLT', 'iso_numeric' => '470', 'capital' => 'Valletta', 'area_km2' => 316, 'population' => 441543, 'gdp_usd' => 14800000000, 'gdp_per_capita' => 33514, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+356', 'government_type' => 'republic', 'flag_emoji' => '🇲🇹', 'region' => 'Southern Europe', 'subregion' => 'Southern Europe', 'is_developed' => 1],
            ['name' => 'Moldova', 'official_name' => 'Republic of Moldova', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'MD', 'iso_alpha_3' => 'MDA', 'iso_numeric' => '498', 'capital' => 'Chisinau', 'area_km2' => 33851, 'population' => 4033963, 'gdp_usd' => 12000000000, 'gdp_per_capita' => 3300, 'currency_code' => 'MDL', 'currency_name' => 'Leu', 'calling_code' => '+373', 'government_type' => 'republic', 'flag_emoji' => '🇲🇩', 'region' => 'Eastern Europe', 'subregion' => 'Eastern Europe', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Monaco', 'official_name' => 'Principality of Monaco', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'MC', 'iso_alpha_3' => 'MCO', 'iso_numeric' => '492', 'capital' => 'Monaco', 'area_km2' => 2, 'population' => 39242, 'gdp_usd' => 6800000000, 'gdp_per_capita' => 173688, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+377', 'government_type' => 'monarchy', 'flag_emoji' => '🇲🇨', 'region' => 'Western Europe', 'subregion' => 'Western Europe', 'is_developed' => 1],
            ['name' => 'Montenegro', 'official_name' => 'Montenegro', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'ME', 'iso_alpha_3' => 'MNE', 'iso_numeric' => '499', 'capital' => 'Podgorica', 'area_km2' => 13812, 'population' => 628066, 'gdp_usd' => 5450000000, 'gdp_per_capita' => 8722, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+382', 'government_type' => 'republic', 'flag_emoji' => '🇲🇪', 'region' => 'Southern Europe', 'subregion' => 'Southern Europe', 'is_developed' => 0],
            ['name' => 'Netherlands', 'official_name' => 'Kingdom of the Netherlands', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'NL', 'iso_alpha_3' => 'NLD', 'iso_numeric' => '528', 'capital' => 'Amsterdam', 'area_km2' => 41543, 'population' => 17134872, 'gdp_usd' => 909000000000, 'gdp_per_capita' => 52877, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+31', 'government_type' => 'monarchy', 'flag_emoji' => '🇳🇱', 'region' => 'Western Europe', 'subregion' => 'Western Europe', 'is_developed' => 1],
            ['name' => 'North Macedonia', 'official_name' => 'Republic of North Macedonia', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'MK', 'iso_alpha_3' => 'MKD', 'iso_numeric' => '807', 'capital' => 'Skopje', 'area_km2' => 25713, 'population' => 2083374, 'gdp_usd' => 12700000000, 'gdp_per_capita' => 6094, 'currency_code' => 'MKD', 'currency_name' => 'Denar', 'calling_code' => '+389', 'government_type' => 'republic', 'flag_emoji' => '🇲🇰', 'region' => 'Southern Europe', 'subregion' => 'Southern Europe', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Norway', 'official_name' => 'Kingdom of Norway', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'NO', 'iso_alpha_3' => 'NOR', 'iso_numeric' => '578', 'capital' => 'Oslo', 'area_km2' => 323802, 'population' => 5421241, 'gdp_usd' => 363000000000, 'gdp_per_capita' => 75420, 'currency_code' => 'NOK', 'currency_name' => 'Krone', 'calling_code' => '+47', 'government_type' => 'monarchy', 'flag_emoji' => '🇳🇴', 'region' => 'Northern Europe', 'subregion' => 'Northern Europe', 'is_developed' => 1],
            ['name' => 'Poland', 'official_name' => 'Republic of Poland', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'PL', 'iso_alpha_3' => 'POL', 'iso_numeric' => '616', 'capital' => 'Warsaw', 'area_km2' => 312696, 'population' => 37846611, 'gdp_usd' => 679000000000, 'gdp_per_capita' => 17840, 'currency_code' => 'PLN', 'currency_name' => 'Zloty', 'calling_code' => '+48', 'government_type' => 'republic', 'flag_emoji' => '🇵🇱', 'region' => 'Eastern Europe', 'subregion' => 'Eastern Europe', 'is_developed' => 1],
            ['name' => 'Portugal', 'official_name' => 'Portuguese Republic', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'PT', 'iso_alpha_3' => 'PRT', 'iso_numeric' => '620', 'capital' => 'Lisbon', 'area_km2' => 92090, 'population' => 10196709, 'gdp_usd' => 239000000000, 'gdp_per_capita' => 23440, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+351', 'government_type' => 'republic', 'flag_emoji' => '🇵🇹', 'region' => 'Southern Europe', 'subregion' => 'Southern Europe', 'is_developed' => 1],
            ['name' => 'Romania', 'official_name' => 'Romania', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'RO', 'iso_alpha_3' => 'ROU', 'iso_numeric' => '642', 'capital' => 'Bucharest', 'area_km2' => 238391, 'population' => 19237691, 'gdp_usd' => 249000000000, 'gdp_per_capita' => 12919, 'currency_code' => 'RON', 'currency_name' => 'Leu', 'calling_code' => '+40', 'government_type' => 'republic', 'flag_emoji' => '🇷🇴', 'region' => 'Eastern Europe', 'subregion' => 'Eastern Europe', 'is_developed' => 0],
            ['name' => 'San Marino', 'official_name' => 'Republic of San Marino', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'SM', 'iso_alpha_3' => 'SMR', 'iso_numeric' => '674', 'capital' => 'San Marino', 'area_km2' => 61, 'population' => 33931, 'gdp_usd' => 1600000000, 'gdp_per_capita' => 48495, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+378', 'government_type' => 'republic', 'flag_emoji' => '🇸🇲', 'region' => 'Southern Europe', 'subregion' => 'Southern Europe', 'is_landlocked' => 1, 'is_developed' => 1],
            ['name' => 'Serbia', 'official_name' => 'Republic of Serbia', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'RS', 'iso_alpha_3' => 'SRB', 'iso_numeric' => '688', 'capital' => 'Belgrade', 'area_km2' => 77474, 'population' => 8737371, 'gdp_usd' => 53000000000, 'gdp_per_capita' => 7243, 'currency_code' => 'RSD', 'currency_name' => 'Dinar', 'calling_code' => '+381', 'government_type' => 'republic', 'flag_emoji' => '🇷🇸', 'region' => 'Southern Europe', 'subregion' => 'Southern Europe', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Slovakia', 'official_name' => 'Slovak Republic', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'SK', 'iso_alpha_3' => 'SVK', 'iso_numeric' => '703', 'capital' => 'Bratislava', 'area_km2' => 49035, 'population' => 5459642, 'gdp_usd' => 105000000000, 'gdp_per_capita' => 19266, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+421', 'government_type' => 'republic', 'flag_emoji' => '🇸🇰', 'region' => 'Eastern Europe', 'subregion' => 'Eastern Europe', 'is_landlocked' => 1, 'is_developed' => 1],
            ['name' => 'Slovenia', 'official_name' => 'Republic of Slovenia', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'SI', 'iso_alpha_3' => 'SVN', 'iso_numeric' => '705', 'capital' => 'Ljubljana', 'area_km2' => 20273, 'population' => 2078938, 'gdp_usd' => 54200000000, 'gdp_per_capita' => 26124, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+386', 'government_type' => 'republic', 'flag_emoji' => '🇸🇮', 'region' => 'Southern Europe', 'subregion' => 'Southern Europe', 'is_developed' => 1],
            ['name' => 'Spain', 'official_name' => 'Kingdom of Spain', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'ES', 'iso_alpha_3' => 'ESP', 'iso_numeric' => '724', 'capital' => 'Madrid', 'area_km2' => 505370, 'population' => 46754778, 'gdp_usd' => 1390000000000, 'gdp_per_capita' => 29565, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+34', 'government_type' => 'monarchy', 'flag_emoji' => '🇪🇸', 'region' => 'Southern Europe', 'subregion' => 'Southern Europe', 'is_developed' => 1],
            ['name' => 'Sweden', 'official_name' => 'Kingdom of Sweden', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'SE', 'iso_alpha_3' => 'SWE', 'iso_numeric' => '752', 'capital' => 'Stockholm', 'area_km2' => 450295, 'population' => 10099265, 'gdp_usd' => 541000000000, 'gdp_per_capita' => 51925, 'currency_code' => 'SEK', 'currency_name' => 'Krona', 'calling_code' => '+46', 'government_type' => 'monarchy', 'flag_emoji' => '🇸🇪', 'region' => 'Northern Europe', 'subregion' => 'Northern Europe', 'is_developed' => 1],
            ['name' => 'Switzerland', 'official_name' => 'Swiss Confederation', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'CH', 'iso_alpha_3' => 'CHE', 'iso_numeric' => '756', 'capital' => 'Bern', 'area_km2' => 41285, 'population' => 8654622, 'gdp_usd' => 752000000000, 'gdp_per_capita' => 86849, 'currency_code' => 'CHF', 'currency_name' => 'Franc', 'calling_code' => '+41', 'government_type' => 'confederation', 'flag_emoji' => '🇨🇭', 'region' => 'Western Europe', 'subregion' => 'Western Europe', 'is_landlocked' => 1, 'is_developed' => 1],
            ['name' => 'Ukraine', 'official_name' => 'Ukraine', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'UA', 'iso_alpha_3' => 'UKR', 'iso_numeric' => '804', 'capital' => 'Kiev', 'area_km2' => 603550, 'population' => 43733762, 'gdp_usd' => 200000000000, 'gdp_per_capita' => 4835, 'currency_code' => 'UAH', 'currency_name' => 'Hryvnia', 'calling_code' => '+380', 'government_type' => 'republic', 'flag_emoji' => '🇺🇦', 'region' => 'Eastern Europe', 'subregion' => 'Eastern Europe', 'is_developed' => 0],
            ['name' => 'United Kingdom', 'official_name' => 'United Kingdom of Great Britain and Northern Ireland', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'GB', 'iso_alpha_3' => 'GBR', 'iso_numeric' => '826', 'capital' => 'London', 'area_km2' => 243610, 'population' => 67886011, 'gdp_usd' => 3131000000000, 'gdp_per_capita' => 46125, 'currency_code' => 'GBP', 'currency_name' => 'Pound', 'calling_code' => '+44', 'government_type' => 'parliamentary monarchy', 'flag_emoji' => '🇬🇧', 'region' => 'Northern Europe', 'subregion' => 'Northern Europe', 'is_developed' => 1],
            ['name' => 'Vatican City', 'official_name' => 'Vatican City State', 'continent_id' => $continents['Europe']->id, 'iso_alpha_2' => 'VA', 'iso_alpha_3' => 'VAT', 'iso_numeric' => '336', 'capital' => 'Vatican City', 'area_km2' => 0, 'population' => 801, 'gdp_usd' => null, 'gdp_per_capita' => null, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+39', 'government_type' => 'monarchy', 'flag_emoji' => '🇻🇦', 'region' => 'Southern Europe', 'subregion' => 'Southern Europe', 'is_landlocked' => 1, 'is_developed' => 1],

            // AFRICA (54 countries)
            ['name' => 'Algeria', 'official_name' => 'People\'s Democratic Republic of Algeria', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'DZ', 'iso_alpha_3' => 'DZA', 'iso_numeric' => '012', 'capital' => 'Algiers', 'area_km2' => 2381741, 'population' => 43851044, 'gdp_usd' => 169000000000, 'gdp_per_capita' => 3853, 'currency_code' => 'DZD', 'currency_name' => 'Dinar', 'calling_code' => '+213', 'government_type' => 'republic', 'flag_emoji' => '🇩🇿', 'region' => 'Northern Africa', 'subregion' => 'Northern Africa', 'is_developed' => 0],
            ['name' => 'Angola', 'official_name' => 'Republic of Angola', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'AO', 'iso_alpha_3' => 'AGO', 'iso_numeric' => '024', 'capital' => 'Luanda', 'area_km2' => 1246700, 'population' => 32866272, 'gdp_usd' => 94600000000, 'gdp_per_capita' => 2879, 'currency_code' => 'AOA', 'currency_name' => 'Kwanza', 'calling_code' => '+244', 'government_type' => 'republic', 'flag_emoji' => '🇦🇴', 'region' => 'Middle Africa', 'subregion' => 'Middle Africa', 'is_developed' => 0],
            ['name' => 'Benin', 'official_name' => 'Republic of Benin', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'BJ', 'iso_alpha_3' => 'BEN', 'iso_numeric' => '204', 'capital' => 'Porto-Novo', 'area_km2' => 112622, 'population' => 12123200, 'gdp_usd' => 15650000000, 'gdp_per_capita' => 1291, 'currency_code' => 'XOF', 'currency_name' => 'Franc', 'calling_code' => '+229', 'government_type' => 'republic', 'flag_emoji' => '🇧🇯', 'region' => 'Western Africa', 'subregion' => 'Western Africa', 'is_developed' => 0],
            ['name' => 'Botswana', 'official_name' => 'Republic of Botswana', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'BW', 'iso_alpha_3' => 'BWA', 'iso_numeric' => '072', 'capital' => 'Gaborone', 'area_km2' => 581730, 'population' => 2351627, 'gdp_usd' => 18610000000, 'gdp_per_capita' => 7918, 'currency_code' => 'BWP', 'currency_name' => 'Pula', 'calling_code' => '+267', 'government_type' => 'republic', 'flag_emoji' => '🇧🇼', 'region' => 'Southern Africa', 'subregion' => 'Southern Africa', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Burkina Faso', 'official_name' => 'Burkina Faso', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'BF', 'iso_alpha_3' => 'BFA', 'iso_numeric' => '854', 'capital' => 'Ouagadougou', 'area_km2' => 274200, 'population' => 20903273, 'gdp_usd' => 17270000000, 'gdp_per_capita' => 826, 'currency_code' => 'XOF', 'currency_name' => 'Franc', 'calling_code' => '+226', 'government_type' => 'republic', 'flag_emoji' => '🇧🇫', 'region' => 'Western Africa', 'subregion' => 'Western Africa', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Burundi', 'official_name' => 'Republic of Burundi', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'BI', 'iso_alpha_3' => 'BDI', 'iso_numeric' => '108', 'capital' => 'Gitega', 'area_km2' => 27834, 'population' => 11890784, 'gdp_usd' => 3130000000, 'gdp_per_capita' => 263, 'currency_code' => 'BIF', 'currency_name' => 'Franc', 'calling_code' => '+257', 'government_type' => 'republic', 'flag_emoji' => '🇧🇮', 'region' => 'Eastern Africa', 'subregion' => 'Eastern Africa', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Cameroon', 'official_name' => 'Republic of Cameroon', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'CM', 'iso_alpha_3' => 'CMR', 'iso_numeric' => '120', 'capital' => 'Yaoundé', 'area_km2' => 475442, 'population' => 26545863, 'gdp_usd' => 39000000000, 'gdp_per_capita' => 1469, 'currency_code' => 'XAF', 'currency_name' => 'Franc', 'calling_code' => '+237', 'government_type' => 'republic', 'flag_emoji' => '🇨🇲', 'region' => 'Middle Africa', 'subregion' => 'Middle Africa', 'is_developed' => 0],
            ['name' => 'Cape Verde', 'official_name' => 'Republic of Cabo Verde', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'CV', 'iso_alpha_3' => 'CPV', 'iso_numeric' => '132', 'capital' => 'Praia', 'area_km2' => 4033, 'population' => 555987, 'gdp_usd' => 2000000000, 'gdp_per_capita' => 3598, 'currency_code' => 'CVE', 'currency_name' => 'Escudo', 'calling_code' => '+238', 'government_type' => 'republic', 'flag_emoji' => '🇨🇻', 'region' => 'Western Africa', 'subregion' => 'Western Africa', 'is_developed' => 0],
            ['name' => 'Central African Republic', 'official_name' => 'Central African Republic', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'CF', 'iso_alpha_3' => 'CAF', 'iso_numeric' => '140', 'capital' => 'Bangui', 'area_km2' => 622984, 'population' => 4829767, 'gdp_usd' => 2220000000, 'gdp_per_capita' => 460, 'currency_code' => 'XAF', 'currency_name' => 'Franc', 'calling_code' => '+236', 'government_type' => 'republic', 'flag_emoji' => '🇨🇫', 'region' => 'Middle Africa', 'subregion' => 'Middle Africa', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Chad', 'official_name' => 'Republic of Chad', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'TD', 'iso_alpha_3' => 'TCD', 'iso_numeric' => '148', 'capital' => 'N\'Djamena', 'area_km2' => 1284000, 'population' => 16425864, 'gdp_usd' => 11300000000, 'gdp_per_capita' => 688, 'currency_code' => 'XAF', 'currency_name' => 'Franc', 'calling_code' => '+235', 'government_type' => 'republic', 'flag_emoji' => '🇹🇩', 'region' => 'Middle Africa', 'subregion' => 'Middle Africa', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Comoros', 'official_name' => 'Union of the Comoros', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'KM', 'iso_alpha_3' => 'COM', 'iso_numeric' => '174', 'capital' => 'Moroni', 'area_km2' => 2235, 'population' => 869601, 'gdp_usd' => 1200000000, 'gdp_per_capita' => 1380, 'currency_code' => 'KMF', 'currency_name' => 'Franc', 'calling_code' => '+269', 'government_type' => 'republic', 'flag_emoji' => '🇰🇲', 'region' => 'Eastern Africa', 'subregion' => 'Eastern Africa', 'is_developed' => 0],
            ['name' => 'Democratic Republic of the Congo', 'official_name' => 'Democratic Republic of the Congo', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'CD', 'iso_alpha_3' => 'COD', 'iso_numeric' => '180', 'capital' => 'Kinshasa', 'area_km2' => 2344858, 'population' => 89561403, 'gdp_usd' => 50420000000, 'gdp_per_capita' => 563, 'currency_code' => 'CDF', 'currency_name' => 'Franc', 'calling_code' => '+243', 'government_type' => 'republic', 'flag_emoji' => '🇨🇩', 'region' => 'Middle Africa', 'subregion' => 'Middle Africa', 'is_developed' => 0],
            ['name' => 'Republic of the Congo', 'official_name' => 'Republic of the Congo', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'CG', 'iso_alpha_3' => 'COG', 'iso_numeric' => '178', 'capital' => 'Brazzaville', 'area_km2' => 342000, 'population' => 5518087, 'gdp_usd' => 10820000000, 'gdp_per_capita' => 1961, 'currency_code' => 'XAF', 'currency_name' => 'Franc', 'calling_code' => '+242', 'government_type' => 'republic', 'flag_emoji' => '🇨🇬', 'region' => 'Middle Africa', 'subregion' => 'Middle Africa', 'is_developed' => 0],
            ['name' => 'Djibouti', 'official_name' => 'Republic of Djibouti', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'DJ', 'iso_alpha_3' => 'DJI', 'iso_numeric' => '262', 'capital' => 'Djibouti', 'area_km2' => 23200, 'population' => 988000, 'gdp_usd' => 3640000000, 'gdp_per_capita' => 3686, 'currency_code' => 'DJF', 'currency_name' => 'Franc', 'calling_code' => '+253', 'government_type' => 'republic', 'flag_emoji' => '🇩🇯', 'region' => 'Eastern Africa', 'subregion' => 'Eastern Africa', 'is_developed' => 0],
            ['name' => 'Egypt', 'official_name' => 'Arab Republic of Egypt', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'EG', 'iso_alpha_3' => 'EGY', 'iso_numeric' => '818', 'capital' => 'Cairo', 'area_km2' => 1001449, 'population' => 102334404, 'gdp_usd' => 404000000000, 'gdp_per_capita' => 3948, 'currency_code' => 'EGP', 'currency_name' => 'Pound', 'calling_code' => '+20', 'government_type' => 'republic', 'flag_emoji' => '🇪🇬', 'region' => 'Northern Africa', 'subregion' => 'Northern Africa', 'is_developed' => 0],
            ['name' => 'Equatorial Guinea', 'official_name' => 'Republic of Equatorial Guinea', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'GQ', 'iso_alpha_3' => 'GNQ', 'iso_numeric' => '226', 'capital' => 'Malabo', 'area_km2' => 28051, 'population' => 1402985, 'gdp_usd' => 10680000000, 'gdp_per_capita' => 7614, 'currency_code' => 'XAF', 'currency_name' => 'Franc', 'calling_code' => '+240', 'government_type' => 'republic', 'flag_emoji' => '🇬🇶', 'region' => 'Middle Africa', 'subregion' => 'Middle Africa', 'is_developed' => 0],
            ['name' => 'Eritrea', 'official_name' => 'State of Eritrea', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'ER', 'iso_alpha_3' => 'ERI', 'iso_numeric' => '232', 'capital' => 'Asmara', 'area_km2' => 117600, 'population' => 3546421, 'gdp_usd' => 2060000000, 'gdp_per_capita' => 581, 'currency_code' => 'ERN', 'currency_name' => 'Nakfa', 'calling_code' => '+291', 'government_type' => 'republic', 'flag_emoji' => '🇪🇷', 'region' => 'Eastern Africa', 'subregion' => 'Eastern Africa', 'is_developed' => 0],
            ['name' => 'Eswatini', 'official_name' => 'Kingdom of Eswatini', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'SZ', 'iso_alpha_3' => 'SWZ', 'iso_numeric' => '748', 'capital' => 'Mbabane', 'area_km2' => 17364, 'population' => 1160164, 'gdp_usd' => 4700000000, 'gdp_per_capita' => 4051, 'currency_code' => 'SZL', 'currency_name' => 'Lilangeni', 'calling_code' => '+268', 'government_type' => 'monarchy', 'flag_emoji' => '🇸🇿', 'region' => 'Southern Africa', 'subregion' => 'Southern Africa', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Ethiopia', 'official_name' => 'Federal Democratic Republic of Ethiopia', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'ET', 'iso_alpha_3' => 'ETH', 'iso_numeric' => '231', 'capital' => 'Addis Ababa', 'area_km2' => 1104300, 'population' => 114963588, 'gdp_usd' => 96100000000, 'gdp_per_capita' => 836, 'currency_code' => 'ETB', 'currency_name' => 'Birr', 'calling_code' => '+251', 'government_type' => 'federal republic', 'flag_emoji' => '🇪🇹', 'region' => 'Eastern Africa', 'subregion' => 'Eastern Africa', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Gabon', 'official_name' => 'Gabonese Republic', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'GA', 'iso_alpha_3' => 'GAB', 'iso_numeric' => '266', 'capital' => 'Libreville', 'area_km2' => 267668, 'population' => 2225734, 'gdp_usd' => 16660000000, 'gdp_per_capita' => 7486, 'currency_code' => 'XAF', 'currency_name' => 'Franc', 'calling_code' => '+241', 'government_type' => 'republic', 'flag_emoji' => '🇬🇦', 'region' => 'Middle Africa', 'subregion' => 'Middle Africa', 'is_developed' => 0],
            ['name' => 'Gambia', 'official_name' => 'Republic of The Gambia', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'GM', 'iso_alpha_3' => 'GMB', 'iso_numeric' => '270', 'capital' => 'Banjul', 'area_km2' => 11295, 'population' => 2416668, 'gdp_usd' => 1800000000, 'gdp_per_capita' => 746, 'currency_code' => 'GMD', 'currency_name' => 'Dalasi', 'calling_code' => '+220', 'government_type' => 'republic', 'flag_emoji' => '🇬🇲', 'region' => 'Western Africa', 'subregion' => 'Western Africa', 'is_developed' => 0],
            ['name' => 'Ghana', 'official_name' => 'Republic of Ghana', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'GH', 'iso_alpha_3' => 'GHA', 'iso_numeric' => '288', 'capital' => 'Accra', 'area_km2' => 238533, 'population' => 31072940, 'gdp_usd' => 67300000000, 'gdp_per_capita' => 2167, 'currency_code' => 'GHS', 'currency_name' => 'Cedi', 'calling_code' => '+233', 'government_type' => 'republic', 'flag_emoji' => '🇬🇭', 'region' => 'Western Africa', 'subregion' => 'Western Africa', 'is_developed' => 0],
            ['name' => 'Guinea', 'official_name' => 'Republic of Guinea', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'GN', 'iso_alpha_3' => 'GIN', 'iso_numeric' => '324', 'capital' => 'Conakry', 'area_km2' => 245857, 'population' => 13132795, 'gdp_usd' => 13590000000, 'gdp_per_capita' => 1035, 'currency_code' => 'GNF', 'currency_name' => 'Franc', 'calling_code' => '+224', 'government_type' => 'republic', 'flag_emoji' => '🇬🇳', 'region' => 'Western Africa', 'subregion' => 'Western Africa', 'is_developed' => 0],
            ['name' => 'Guinea-Bissau', 'official_name' => 'Republic of Guinea-Bissau', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'GW', 'iso_alpha_3' => 'GNB', 'iso_numeric' => '624', 'capital' => 'Bissau', 'area_km2' => 36125, 'population' => 1968001, 'gdp_usd' => 1340000000, 'gdp_per_capita' => 681, 'currency_code' => 'XOF', 'currency_name' => 'Franc', 'calling_code' => '+245', 'government_type' => 'republic', 'flag_emoji' => '🇬🇼', 'region' => 'Western Africa', 'subregion' => 'Western Africa', 'is_developed' => 0],
            ['name' => 'Ivory Coast', 'official_name' => 'Republic of Côte d\'Ivoire', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'CI', 'iso_alpha_3' => 'CIV', 'iso_numeric' => '384', 'capital' => 'Yamoussoukro', 'area_km2' => 322463, 'population' => 26378274, 'gdp_usd' => 61500000000, 'gdp_per_capita' => 2332, 'currency_code' => 'XOF', 'currency_name' => 'Franc', 'calling_code' => '+225', 'government_type' => 'republic', 'flag_emoji' => '🇨🇮', 'region' => 'Western Africa', 'subregion' => 'Western Africa', 'is_developed' => 0],
            ['name' => 'Kenya', 'official_name' => 'Republic of Kenya', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'KE', 'iso_alpha_3' => 'KEN', 'iso_numeric' => '404', 'capital' => 'Nairobi', 'area_km2' => 580367, 'population' => 53771296, 'gdp_usd' => 101000000000, 'gdp_per_capita' => 1878, 'currency_code' => 'KES', 'currency_name' => 'Shilling', 'calling_code' => '+254', 'government_type' => 'republic', 'flag_emoji' => '🇰🇪', 'region' => 'Eastern Africa', 'subregion' => 'Eastern Africa', 'is_developed' => 0],
            ['name' => 'Lesotho', 'official_name' => 'Kingdom of Lesotho', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'LS', 'iso_alpha_3' => 'LSO', 'iso_numeric' => '426', 'capital' => 'Maseru', 'area_km2' => 30355, 'population' => 2142249, 'gdp_usd' => 2460000000, 'gdp_per_capita' => 1149, 'currency_code' => 'LSL', 'currency_name' => 'Loti', 'calling_code' => '+266', 'government_type' => 'monarchy', 'flag_emoji' => '🇱🇸', 'region' => 'Southern Africa', 'subregion' => 'Southern Africa', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Liberia', 'official_name' => 'Republic of Liberia', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'LR', 'iso_alpha_3' => 'LBR', 'iso_numeric' => '430', 'capital' => 'Monrovia', 'area_km2' => 111369, 'population' => 5057681, 'gdp_usd' => 3290000000, 'gdp_per_capita' => 651, 'currency_code' => 'LRD', 'currency_name' => 'Dollar', 'calling_code' => '+231', 'government_type' => 'republic', 'flag_emoji' => '🇱🇷', 'region' => 'Western Africa', 'subregion' => 'Western Africa', 'is_developed' => 0],
            ['name' => 'Libya', 'official_name' => 'State of Libya', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'LY', 'iso_alpha_3' => 'LBY', 'iso_numeric' => '434', 'capital' => 'Tripoli', 'area_km2' => 1759540, 'population' => 6871292, 'gdp_usd' => 52000000000, 'gdp_per_capita' => 7570, 'currency_code' => 'LYD', 'currency_name' => 'Dinar', 'calling_code' => '+218', 'government_type' => 'republic', 'flag_emoji' => '🇱🇾', 'region' => 'Northern Africa', 'subregion' => 'Northern Africa', 'is_developed' => 0],
            ['name' => 'Madagascar', 'official_name' => 'Republic of Madagascar', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'MG', 'iso_alpha_3' => 'MDG', 'iso_numeric' => '450', 'capital' => 'Antananarivo', 'area_km2' => 587041, 'population' => 27691018, 'gdp_usd' => 14080000000, 'gdp_per_capita' => 509, 'currency_code' => 'MGA', 'currency_name' => 'Ariary', 'calling_code' => '+261', 'government_type' => 'republic', 'flag_emoji' => '🇲🇬', 'region' => 'Eastern Africa', 'subregion' => 'Eastern Africa', 'is_developed' => 0],
            ['name' => 'Malawi', 'official_name' => 'Republic of Malawi', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'MW', 'iso_alpha_3' => 'MWI', 'iso_numeric' => '454', 'capital' => 'Lilongwe', 'area_km2' => 118484, 'population' => 19129952, 'gdp_usd' => 7670000000, 'gdp_per_capita' => 401, 'currency_code' => 'MWK', 'currency_name' => 'Kwacha', 'calling_code' => '+265', 'government_type' => 'republic', 'flag_emoji' => '🇲🇼', 'region' => 'Eastern Africa', 'subregion' => 'Eastern Africa', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Mali', 'official_name' => 'Republic of Mali', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'ML', 'iso_alpha_3' => 'MLI', 'iso_numeric' => '466', 'capital' => 'Bamako', 'area_km2' => 1240192, 'population' => 20250833, 'gdp_usd' => 17510000000, 'gdp_per_capita' => 865, 'currency_code' => 'XOF', 'currency_name' => 'Franc', 'calling_code' => '+223', 'government_type' => 'republic', 'flag_emoji' => '🇲🇱', 'region' => 'Western Africa', 'subregion' => 'Western Africa', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Mauritania', 'official_name' => 'Islamic Republic of Mauritania', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'MR', 'iso_alpha_3' => 'MRT', 'iso_numeric' => '478', 'capital' => 'Nouakchott', 'area_km2' => 1030700, 'population' => 4649658, 'gdp_usd' => 7600000000, 'gdp_per_capita' => 1635, 'currency_code' => 'MRU', 'currency_name' => 'Ouguiya', 'calling_code' => '+222', 'government_type' => 'republic', 'flag_emoji' => '🇲🇷', 'region' => 'Western Africa', 'subregion' => 'Western Africa', 'is_developed' => 0],
            ['name' => 'Mauritius', 'official_name' => 'Republic of Mauritius', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'MU', 'iso_alpha_3' => 'MUS', 'iso_numeric' => '480', 'capital' => 'Port Louis', 'area_km2' => 2040, 'population' => 1271768, 'gdp_usd' => 14220000000, 'gdp_per_capita' => 11186, 'currency_code' => 'MUR', 'currency_name' => 'Rupee', 'calling_code' => '+230', 'government_type' => 'republic', 'flag_emoji' => '🇲🇺', 'region' => 'Eastern Africa', 'subregion' => 'Eastern Africa', 'is_developed' => 0],
            ['name' => 'Morocco', 'official_name' => 'Kingdom of Morocco', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'MA', 'iso_alpha_3' => 'MAR', 'iso_numeric' => '504', 'capital' => 'Rabat', 'area_km2' => 446550, 'population' => 36910560, 'gdp_usd' => 124000000000, 'gdp_per_capita' => 3359, 'currency_code' => 'MAD', 'currency_name' => 'Dirham', 'calling_code' => '+212', 'government_type' => 'monarchy', 'flag_emoji' => '🇲🇦', 'region' => 'Northern Africa', 'subregion' => 'Northern Africa', 'is_developed' => 0],
            ['name' => 'Mozambique', 'official_name' => 'Republic of Mozambique', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'MZ', 'iso_alpha_3' => 'MOZ', 'iso_numeric' => '508', 'capital' => 'Maputo', 'area_km2' => 801590, 'population' => 31255435, 'gdp_usd' => 15940000000, 'gdp_per_capita' => 510, 'currency_code' => 'MZN', 'currency_name' => 'Metical', 'calling_code' => '+258', 'government_type' => 'republic', 'flag_emoji' => '🇲🇿', 'region' => 'Eastern Africa', 'subregion' => 'Eastern Africa', 'is_developed' => 0],
            ['name' => 'Namibia', 'official_name' => 'Republic of Namibia', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'NA', 'iso_alpha_3' => 'NAM', 'iso_numeric' => '516', 'capital' => 'Windhoek', 'area_km2' => 825615, 'population' => 2540905, 'gdp_usd' => 12370000000, 'gdp_per_capita' => 4869, 'currency_code' => 'NAD', 'currency_name' => 'Dollar', 'calling_code' => '+264', 'government_type' => 'republic', 'flag_emoji' => '🇳🇦', 'region' => 'Southern Africa', 'subregion' => 'Southern Africa', 'is_developed' => 0],
            ['name' => 'Niger', 'official_name' => 'Republic of Niger', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'NE', 'iso_alpha_3' => 'NER', 'iso_numeric' => '562', 'capital' => 'Niamey', 'area_km2' => 1267000, 'population' => 24206644, 'gdp_usd' => 13970000000, 'gdp_per_capita' => 577, 'currency_code' => 'XOF', 'currency_name' => 'Franc', 'calling_code' => '+227', 'government_type' => 'republic', 'flag_emoji' => '🇳🇪', 'region' => 'Western Africa', 'subregion' => 'Western Africa', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Nigeria', 'official_name' => 'Federal Republic of Nigeria', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'NG', 'iso_alpha_3' => 'NGA', 'iso_numeric' => '566', 'capital' => 'Abuja', 'area_km2' => 923768, 'population' => 206139589, 'gdp_usd' => 432000000000, 'gdp_per_capita' => 2097, 'currency_code' => 'NGN', 'currency_name' => 'Naira', 'calling_code' => '+234', 'government_type' => 'federal republic', 'flag_emoji' => '🇳🇬', 'region' => 'Western Africa', 'subregion' => 'Western Africa', 'is_developed' => 0],
            ['name' => 'Rwanda', 'official_name' => 'Republic of Rwanda', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'RW', 'iso_alpha_3' => 'RWA', 'iso_numeric' => '646', 'capital' => 'Kigali', 'area_km2' => 26338, 'population' => 12952218, 'gdp_usd' => 10900000000, 'gdp_per_capita' => 842, 'currency_code' => 'RWF', 'currency_name' => 'Franc', 'calling_code' => '+250', 'government_type' => 'republic', 'flag_emoji' => '🇷🇼', 'region' => 'Eastern Africa', 'subregion' => 'Eastern Africa', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'São Tomé and Príncipe', 'official_name' => 'Democratic Republic of São Tomé and Príncipe', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'ST', 'iso_alpha_3' => 'STP', 'iso_numeric' => '678', 'capital' => 'São Tomé', 'area_km2' => 964, 'population' => 219159, 'gdp_usd' => 430000000, 'gdp_per_capita' => 1963, 'currency_code' => 'STN', 'currency_name' => 'Dobra', 'calling_code' => '+239', 'government_type' => 'republic', 'flag_emoji' => '🇸🇹', 'region' => 'Middle Africa', 'subregion' => 'Middle Africa', 'is_developed' => 0],
            ['name' => 'Senegal', 'official_name' => 'Republic of Senegal', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'SN', 'iso_alpha_3' => 'SEN', 'iso_numeric' => '686', 'capital' => 'Dakar', 'area_km2' => 196722, 'population' => 16743927, 'gdp_usd' => 25720000000, 'gdp_per_capita' => 1536, 'currency_code' => 'XOF', 'currency_name' => 'Franc', 'calling_code' => '+221', 'government_type' => 'republic', 'flag_emoji' => '🇸🇳', 'region' => 'Western Africa', 'subregion' => 'Western Africa', 'is_developed' => 0],
            ['name' => 'Seychelles', 'official_name' => 'Republic of Seychelles', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'SC', 'iso_alpha_3' => 'SYC', 'iso_numeric' => '690', 'capital' => 'Victoria', 'area_km2' => 452, 'population' => 98347, 'gdp_usd' => 1700000000, 'gdp_per_capita' => 17291, 'currency_code' => 'SCR', 'currency_name' => 'Rupee', 'calling_code' => '+248', 'government_type' => 'republic', 'flag_emoji' => '🇸🇨', 'region' => 'Eastern Africa', 'subregion' => 'Eastern Africa', 'is_developed' => 0],
            ['name' => 'Sierra Leone', 'official_name' => 'Republic of Sierra Leone', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'SL', 'iso_alpha_3' => 'SLE', 'iso_numeric' => '694', 'capital' => 'Freetown', 'area_km2' => 71740, 'population' => 7976983, 'gdp_usd' => 4140000000, 'gdp_per_capita' => 519, 'currency_code' => 'SLE', 'currency_name' => 'Leone', 'calling_code' => '+232', 'government_type' => 'republic', 'flag_emoji' => '🇸🇱', 'region' => 'Western Africa', 'subregion' => 'Western Africa', 'is_developed' => 0],
            ['name' => 'Somalia', 'official_name' => 'Federal Republic of Somalia', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'SO', 'iso_alpha_3' => 'SOM', 'iso_numeric' => '706', 'capital' => 'Mogadishu', 'area_km2' => 637657, 'population' => 15893222, 'gdp_usd' => 8340000000, 'gdp_per_capita' => 525, 'currency_code' => 'SOS', 'currency_name' => 'Shilling', 'calling_code' => '+252', 'government_type' => 'federal republic', 'flag_emoji' => '🇸🇴', 'region' => 'Eastern Africa', 'subregion' => 'Eastern Africa', 'is_developed' => 0],
            ['name' => 'South Africa', 'official_name' => 'Republic of South Africa', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'ZA', 'iso_alpha_3' => 'ZAF', 'iso_numeric' => '710', 'capital' => 'Cape Town', 'area_km2' => 1221037, 'population' => 59308690, 'gdp_usd' => 419000000000, 'gdp_per_capita' => 7055, 'currency_code' => 'ZAR', 'currency_name' => 'Rand', 'calling_code' => '+27', 'government_type' => 'republic', 'flag_emoji' => '🇿🇦', 'region' => 'Southern Africa', 'subregion' => 'Southern Africa', 'is_developed' => 0],
            ['name' => 'South Sudan', 'official_name' => 'Republic of South Sudan', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'SS', 'iso_alpha_3' => 'SSD', 'iso_numeric' => '728', 'capital' => 'Juba', 'area_km2' => 644329, 'population' => 11193725, 'gdp_usd' => 3090000000, 'gdp_per_capita' => 276, 'currency_code' => 'SSP', 'currency_name' => 'Pound', 'calling_code' => '+211', 'government_type' => 'republic', 'flag_emoji' => '🇸🇸', 'region' => 'Middle Africa', 'subregion' => 'Middle Africa', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Sudan', 'official_name' => 'Republic of the Sudan', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'SD', 'iso_alpha_3' => 'SDN', 'iso_numeric' => '729', 'capital' => 'Khartoum', 'area_km2' => 1861484, 'population' => 43849260, 'gdp_usd' => 34320000000, 'gdp_per_capita' => 783, 'currency_code' => 'SDG', 'currency_name' => 'Pound', 'calling_code' => '+249', 'government_type' => 'republic', 'flag_emoji' => '🇸🇩', 'region' => 'Northern Africa', 'subregion' => 'Northern Africa', 'is_developed' => 0],
            ['name' => 'Tanzania', 'official_name' => 'United Republic of Tanzania', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'TZ', 'iso_alpha_3' => 'TZA', 'iso_numeric' => '834', 'capital' => 'Dodoma', 'area_km2' => 945087, 'population' => 59734218, 'gdp_usd' => 64400000000, 'gdp_per_capita' => 1078, 'currency_code' => 'TZS', 'currency_name' => 'Shilling', 'calling_code' => '+255', 'government_type' => 'republic', 'flag_emoji' => '🇹🇿', 'region' => 'Eastern Africa', 'subregion' => 'Eastern Africa', 'is_developed' => 0],
            ['name' => 'Togo', 'official_name' => 'Togolese Republic', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'TG', 'iso_alpha_3' => 'TGO', 'iso_numeric' => '768', 'capital' => 'Lomé', 'area_km2' => 56785, 'population' => 8278724, 'gdp_usd' => 5460000000, 'gdp_per_capita' => 659, 'currency_code' => 'XOF', 'currency_name' => 'Franc', 'calling_code' => '+228', 'government_type' => 'republic', 'flag_emoji' => '🇹🇬', 'region' => 'Western Africa', 'subregion' => 'Western Africa', 'is_developed' => 0],
            ['name' => 'Tunisia', 'official_name' => 'Tunisian Republic', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'TN', 'iso_alpha_3' => 'TUN', 'iso_numeric' => '788', 'capital' => 'Tunis', 'area_km2' => 163610, 'population' => 11818619, 'gdp_usd' => 39860000000, 'gdp_per_capita' => 3375, 'currency_code' => 'TND', 'currency_name' => 'Dinar', 'calling_code' => '+216', 'government_type' => 'republic', 'flag_emoji' => '🇹🇳', 'region' => 'Northern Africa', 'subregion' => 'Northern Africa', 'is_developed' => 0],
            ['name' => 'Uganda', 'official_name' => 'Republic of Uganda', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'UG', 'iso_alpha_3' => 'UGA', 'iso_numeric' => '800', 'capital' => 'Kampala', 'area_km2' => 241038, 'population' => 45741007, 'gdp_usd' => 42860000000, 'gdp_per_capita' => 937, 'currency_code' => 'UGX', 'currency_name' => 'Shilling', 'calling_code' => '+256', 'government_type' => 'republic', 'flag_emoji' => '🇺🇬', 'region' => 'Eastern Africa', 'subregion' => 'Eastern Africa', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Zambia', 'official_name' => 'Republic of Zambia', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'ZM', 'iso_alpha_3' => 'ZMB', 'iso_numeric' => '894', 'capital' => 'Lusaka', 'area_km2' => 752618, 'population' => 18383955, 'gdp_usd' => 21060000000, 'gdp_per_capita' => 1146, 'currency_code' => 'ZMW', 'currency_name' => 'Kwacha', 'calling_code' => '+260', 'government_type' => 'republic', 'flag_emoji' => '🇿🇲', 'region' => 'Eastern Africa', 'subregion' => 'Eastern Africa', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Zimbabwe', 'official_name' => 'Republic of Zimbabwe', 'continent_id' => $continents['Africa']->id, 'iso_alpha_2' => 'ZW', 'iso_alpha_3' => 'ZWE', 'iso_numeric' => '716', 'capital' => 'Harare', 'area_km2' => 390757, 'population' => 14862924, 'gdp_usd' => 21440000000, 'gdp_per_capita' => 1443, 'currency_code' => 'ZWL', 'currency_name' => 'Dollar', 'calling_code' => '+263', 'government_type' => 'republic', 'flag_emoji' => '🇿🇼', 'region' => 'Eastern Africa', 'subregion' => 'Eastern Africa', 'is_landlocked' => 1, 'is_developed' => 0],

            // NORTH AMERICA (23 countries)
            ['name' => 'Antigua and Barbuda', 'official_name' => 'Antigua and Barbuda', 'continent_id' => $continents['North America']->id, 'iso_alpha_2' => 'AG', 'iso_alpha_3' => 'ATG', 'iso_numeric' => '028', 'capital' => 'Saint John\'s', 'area_km2' => 442, 'population' => 97929, 'gdp_usd' => 1720000000, 'gdp_per_capita' => 17561, 'currency_code' => 'XCD', 'currency_name' => 'Dollar', 'calling_code' => '+1268', 'government_type' => 'parliamentary monarchy', 'flag_emoji' => '🇦🇬', 'region' => 'Caribbean', 'subregion' => 'Caribbean', 'is_developed' => 0],
            ['name' => 'Bahamas', 'official_name' => 'Commonwealth of the Bahamas', 'continent_id' => $continents['North America']->id, 'iso_alpha_2' => 'BS', 'iso_alpha_3' => 'BHS', 'iso_numeric' => '044', 'capital' => 'Nassau', 'area_km2' => 13943, 'population' => 393244, 'gdp_usd' => 12840000000, 'gdp_per_capita' => 32647, 'currency_code' => 'BSD', 'currency_name' => 'Dollar', 'calling_code' => '+1242', 'government_type' => 'parliamentary monarchy', 'flag_emoji' => '🇧🇸', 'region' => 'Caribbean', 'subregion' => 'Caribbean', 'is_developed' => 1],
            ['name' => 'Barbados', 'official_name' => 'Barbados', 'continent_id' => $continents['North America']->id, 'iso_alpha_2' => 'BB', 'iso_alpha_3' => 'BRB', 'iso_numeric' => '052', 'capital' => 'Bridgetown', 'area_km2' => 430, 'population' => 287375, 'gdp_usd' => 5200000000, 'gdp_per_capita' => 18148, 'currency_code' => 'BBD', 'currency_name' => 'Dollar', 'calling_code' => '+1246', 'government_type' => 'republic', 'flag_emoji' => '🇧🇧', 'region' => 'Caribbean', 'subregion' => 'Caribbean', 'is_developed' => 1],
            ['name' => 'Belize', 'official_name' => 'Belize', 'continent_id' => $continents['North America']->id, 'iso_alpha_2' => 'BZ', 'iso_alpha_3' => 'BLZ', 'iso_numeric' => '084', 'capital' => 'Belmopan', 'area_km2' => 22966, 'population' => 397628, 'gdp_usd' => 1900000000, 'gdp_per_capita' => 4776, 'currency_code' => 'BZD', 'currency_name' => 'Dollar', 'calling_code' => '+501', 'government_type' => 'parliamentary monarchy', 'flag_emoji' => '🇧🇿', 'region' => 'Central America', 'subregion' => 'Central America', 'is_developed' => 0],
            ['name' => 'Canada', 'official_name' => 'Canada', 'continent_id' => $continents['North America']->id, 'iso_alpha_2' => 'CA', 'iso_alpha_3' => 'CAN', 'iso_numeric' => '124', 'capital' => 'Ottawa', 'area_km2' => 9984670, 'population' => 37742154, 'gdp_usd' => 1988000000000, 'gdp_per_capita' => 52686, 'currency_code' => 'CAD', 'currency_name' => 'Dollar', 'calling_code' => '+1', 'government_type' => 'parliamentary', 'flag_emoji' => '🇨🇦', 'region' => 'Northern America', 'subregion' => 'Northern America', 'is_developed' => 1],
            ['name' => 'Costa Rica', 'official_name' => 'Republic of Costa Rica', 'continent_id' => $continents['North America']->id, 'iso_alpha_2' => 'CR', 'iso_alpha_3' => 'CRI', 'iso_numeric' => '188', 'capital' => 'San José', 'area_km2' => 51100, 'population' => 5094118, 'gdp_usd' => 64280000000, 'gdp_per_capita' => 12617, 'currency_code' => 'CRC', 'currency_name' => 'Colon', 'calling_code' => '+506', 'government_type' => 'republic', 'flag_emoji' => '🇨🇷', 'region' => 'Central America', 'subregion' => 'Central America', 'is_developed' => 0],
            ['name' => 'Cuba', 'official_name' => 'Republic of Cuba', 'continent_id' => $continents['North America']->id, 'iso_alpha_2' => 'CU', 'iso_alpha_3' => 'CUB', 'iso_numeric' => '192', 'capital' => 'Havana', 'area_km2' => 110860, 'population' => 11326616, 'gdp_usd' => 107000000000, 'gdp_per_capita' => 9478, 'currency_code' => 'CUP', 'currency_name' => 'Peso', 'calling_code' => '+53', 'government_type' => 'communist', 'flag_emoji' => '🇨🇺', 'region' => 'Caribbean', 'subregion' => 'Caribbean', 'is_developed' => 0],
            ['name' => 'Dominica', 'official_name' => 'Commonwealth of Dominica', 'continent_id' => $continents['North America']->id, 'iso_alpha_2' => 'DM', 'iso_alpha_3' => 'DMA', 'iso_numeric' => '212', 'capital' => 'Roseau', 'area_km2' => 751, 'population' => 71986, 'gdp_usd' => 560000000, 'gdp_per_capita' => 7787, 'currency_code' => 'XCD', 'currency_name' => 'Dollar', 'calling_code' => '+1767', 'government_type' => 'republic', 'flag_emoji' => '🇩🇲', 'region' => 'Caribbean', 'subregion' => 'Caribbean', 'is_developed' => 0],
            ['name' => 'Dominican Republic', 'official_name' => 'Dominican Republic', 'continent_id' => $continents['North America']->id, 'iso_alpha_2' => 'DO', 'iso_alpha_3' => 'DOM', 'iso_numeric' => '214', 'capital' => 'Santo Domingo', 'area_km2' => 48671, 'population' => 10847910, 'gdp_usd' => 94240000000, 'gdp_per_capita' => 8681, 'currency_code' => 'DOP', 'currency_name' => 'Peso', 'calling_code' => '+1809', 'government_type' => 'republic', 'flag_emoji' => '🇩🇴', 'region' => 'Caribbean', 'subregion' => 'Caribbean', 'is_developed' => 0],
            ['name' => 'El Salvador', 'official_name' => 'Republic of El Salvador', 'continent_id' => $continents['North America']->id, 'iso_alpha_2' => 'SV', 'iso_alpha_3' => 'SLV', 'iso_numeric' => '222', 'capital' => 'San Salvador', 'area_km2' => 21041, 'population' => 6486205, 'gdp_usd' => 28370000000, 'gdp_per_capita' => 4371, 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'calling_code' => '+503', 'government_type' => 'republic', 'flag_emoji' => '🇸🇻', 'region' => 'Central America', 'subregion' => 'Central America', 'is_developed' => 0],
            ['name' => 'Grenada', 'official_name' => 'Grenada', 'continent_id' => $continents['North America']->id, 'iso_alpha_2' => 'GD', 'iso_alpha_3' => 'GRD', 'iso_numeric' => '308', 'capital' => 'Saint George\'s', 'area_km2' => 344, 'population' => 112523, 'gdp_usd' => 1230000000, 'gdp_per_capita' => 10939, 'currency_code' => 'XCD', 'currency_name' => 'Dollar', 'calling_code' => '+1473', 'government_type' => 'parliamentary monarchy', 'flag_emoji' => '🇬🇩', 'region' => 'Caribbean', 'subregion' => 'Caribbean', 'is_developed' => 0],
            ['name' => 'Guatemala', 'official_name' => 'Republic of Guatemala', 'continent_id' => $continents['North America']->id, 'iso_alpha_2' => 'GT', 'iso_alpha_3' => 'GTM', 'iso_numeric' => '320', 'capital' => 'Guatemala City', 'area_km2' => 108889, 'population' => 17915568, 'gdp_usd' => 85990000000, 'gdp_per_capita' => 4799, 'currency_code' => 'GTQ', 'currency_name' => 'Quetzal', 'calling_code' => '+502', 'government_type' => 'republic', 'flag_emoji' => '🇬🇹', 'region' => 'Central America', 'subregion' => 'Central America', 'is_developed' => 0],
            ['name' => 'Haiti', 'official_name' => 'Republic of Haiti', 'continent_id' => $continents['North America']->id, 'iso_alpha_2' => 'HT', 'iso_alpha_3' => 'HTI', 'iso_numeric' => '332', 'capital' => 'Port-au-Prince', 'area_km2' => 27750, 'population' => 11402528, 'gdp_usd' => 8500000000, 'gdp_per_capita' => 745, 'currency_code' => 'HTG', 'currency_name' => 'Gourde', 'calling_code' => '+509', 'government_type' => 'republic', 'flag_emoji' => '🇭🇹', 'region' => 'Caribbean', 'subregion' => 'Caribbean', 'is_developed' => 0],
            ['name' => 'Honduras', 'official_name' => 'Republic of Honduras', 'continent_id' => $continents['North America']->id, 'iso_alpha_2' => 'HN', 'iso_alpha_3' => 'HND', 'iso_numeric' => '340', 'capital' => 'Tegucigalpa', 'area_km2' => 112492, 'population' => 9904607, 'gdp_usd' => 25950000000, 'gdp_per_capita' => 2621, 'currency_code' => 'HNL', 'currency_name' => 'Lempira', 'calling_code' => '+504', 'government_type' => 'republic', 'flag_emoji' => '🇭🇳', 'region' => 'Central America', 'subregion' => 'Central America', 'is_developed' => 0],
            ['name' => 'Jamaica', 'official_name' => 'Jamaica', 'continent_id' => $continents['North America']->id, 'iso_alpha_2' => 'JM', 'iso_alpha_3' => 'JAM', 'iso_numeric' => '388', 'capital' => 'Kingston', 'area_km2' => 10991, 'population' => 2961167, 'gdp_usd' => 15720000000, 'gdp_per_capita' => 5310, 'currency_code' => 'JMD', 'currency_name' => 'Dollar', 'calling_code' => '+1876', 'government_type' => 'parliamentary monarchy', 'flag_emoji' => '🇯🇲', 'region' => 'Caribbean', 'subregion' => 'Caribbean', 'is_developed' => 0],
            ['name' => 'Mexico', 'official_name' => 'United Mexican States', 'continent_id' => $continents['North America']->id, 'iso_alpha_2' => 'MX', 'iso_alpha_3' => 'MEX', 'iso_numeric' => '484', 'capital' => 'Mexico City', 'area_km2' => 1964375, 'population' => 128932753, 'gdp_usd' => 1689000000000, 'gdp_per_capita' => 13099, 'currency_code' => 'MXN', 'currency_name' => 'Peso', 'calling_code' => '+52', 'government_type' => 'federal republic', 'flag_emoji' => '🇲🇽', 'region' => 'Central America', 'subregion' => 'Central America', 'is_developed' => 0],
            ['name' => 'Nicaragua', 'official_name' => 'Republic of Nicaragua', 'continent_id' => $continents['North America']->id, 'iso_alpha_2' => 'NI', 'iso_alpha_3' => 'NIC', 'iso_numeric' => '558', 'capital' => 'Managua', 'area_km2' => 130373, 'population' => 6624554, 'gdp_usd' => 13814000000, 'gdp_per_capita' => 2085, 'currency_code' => 'NIO', 'currency_name' => 'Córdoba', 'calling_code' => '+505', 'government_type' => 'republic', 'flag_emoji' => '🇳🇮', 'region' => 'Central America', 'subregion' => 'Central America', 'is_developed' => 0],
            ['name' => 'Panama', 'official_name' => 'Republic of Panama', 'continent_id' => $continents['North America']->id, 'iso_alpha_2' => 'PA', 'iso_alpha_3' => 'PAN', 'iso_numeric' => '591', 'capital' => 'Panama City', 'area_km2' => 75420, 'population' => 4314767, 'gdp_usd' => 68540000000, 'gdp_per_capita' => 15877, 'currency_code' => 'PAB', 'currency_name' => 'Balboa', 'calling_code' => '+507', 'government_type' => 'republic', 'flag_emoji' => '🇵🇦', 'region' => 'Central America', 'subregion' => 'Central America', 'is_developed' => 0],
            ['name' => 'Saint Kitts and Nevis', 'official_name' => 'Federation of Saint Christopher and Nevis', 'continent_id' => $continents['North America']->id, 'iso_alpha_2' => 'KN', 'iso_alpha_3' => 'KNA', 'iso_numeric' => '659', 'capital' => 'Basseterre', 'area_km2' => 261, 'population' => 53199, 'gdp_usd' => 1050000000, 'gdp_per_capita' => 19738, 'currency_code' => 'XCD', 'currency_name' => 'Dollar', 'calling_code' => '+1869', 'government_type' => 'parliamentary monarchy', 'flag_emoji' => '🇰🇳', 'region' => 'Caribbean', 'subregion' => 'Caribbean', 'is_developed' => 1],
            ['name' => 'Saint Lucia', 'official_name' => 'Saint Lucia', 'continent_id' => $continents['North America']->id, 'iso_alpha_2' => 'LC', 'iso_alpha_3' => 'LCA', 'iso_numeric' => '662', 'capital' => 'Castries', 'area_km2' => 616, 'population' => 183627, 'gdp_usd' => 2000000000, 'gdp_per_capita' => 10900, 'currency_code' => 'XCD', 'currency_name' => 'Dollar', 'calling_code' => '+1758', 'government_type' => 'parliamentary monarchy', 'flag_emoji' => '🇱🇨', 'region' => 'Caribbean', 'subregion' => 'Caribbean', 'is_developed' => 0],
            ['name' => 'Saint Vincent and the Grenadines', 'official_name' => 'Saint Vincent and the Grenadines', 'continent_id' => $continents['North America']->id, 'iso_alpha_2' => 'VC', 'iso_alpha_3' => 'VCT', 'iso_numeric' => '670', 'capital' => 'Kingstown', 'area_km2' => 389, 'population' => 110940, 'gdp_usd' => 850000000, 'gdp_per_capita' => 7661, 'currency_code' => 'XCD', 'currency_name' => 'Dollar', 'calling_code' => '+1784', 'government_type' => 'parliamentary monarchy', 'flag_emoji' => '🇻🇨', 'region' => 'Caribbean', 'subregion' => 'Caribbean', 'is_developed' => 0],
            ['name' => 'Trinidad and Tobago', 'official_name' => 'Republic of Trinidad and Tobago', 'continent_id' => $continents['North America']->id, 'iso_alpha_2' => 'TT', 'iso_alpha_3' => 'TTO', 'iso_numeric' => '780', 'capital' => 'Port of Spain', 'area_km2' => 5128, 'population' => 1399488, 'gdp_usd' => 23790000000, 'gdp_per_capita' => 16994, 'currency_code' => 'TTD', 'currency_name' => 'Dollar', 'calling_code' => '+1868', 'government_type' => 'republic', 'flag_emoji' => '🇹🇹', 'region' => 'Caribbean', 'subregion' => 'Caribbean', 'is_developed' => 1],
            ['name' => 'United States', 'official_name' => 'United States of America', 'continent_id' => $continents['North America']->id, 'iso_alpha_2' => 'US', 'iso_alpha_3' => 'USA', 'iso_numeric' => '840', 'capital' => 'Washington, D.C.', 'area_km2' => 9833517, 'population' => 331002651, 'gdp_usd' => 25462000000000, 'gdp_per_capita' => 76949, 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'calling_code' => '+1', 'government_type' => 'federal republic', 'flag_emoji' => '🇺🇸', 'region' => 'Northern America', 'subregion' => 'Northern America', 'is_developed' => 1],

            // SOUTH AMERICA (12 countries)
            ['name' => 'Argentina', 'official_name' => 'Argentine Republic', 'continent_id' => $continents['South America']->id, 'iso_alpha_2' => 'AR', 'iso_alpha_3' => 'ARG', 'iso_numeric' => '032', 'capital' => 'Buenos Aires', 'area_km2' => 2780400, 'population' => 45195774, 'gdp_usd' => 490000000000, 'gdp_per_capita' => 10846, 'currency_code' => 'ARS', 'currency_name' => 'Peso', 'calling_code' => '+54', 'government_type' => 'federal republic', 'flag_emoji' => '🇦🇷', 'region' => 'South America', 'subregion' => 'South America', 'is_developed' => 0],
            ['name' => 'Bolivia', 'official_name' => 'Plurinational State of Bolivia', 'continent_id' => $continents['South America']->id, 'iso_alpha_2' => 'BO', 'iso_alpha_3' => 'BOL', 'iso_numeric' => '068', 'capital' => 'La Paz', 'area_km2' => 1098581, 'population' => 11673021, 'gdp_usd' => 40900000000, 'gdp_per_capita' => 3505, 'currency_code' => 'BOB', 'currency_name' => 'Boliviano', 'calling_code' => '+591', 'government_type' => 'republic', 'flag_emoji' => '🇧🇴', 'region' => 'South America', 'subregion' => 'South America', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Brazil', 'official_name' => 'Federative Republic of Brazil', 'continent_id' => $continents['South America']->id, 'iso_alpha_2' => 'BR', 'iso_alpha_3' => 'BRA', 'iso_numeric' => '076', 'capital' => 'Brasília', 'area_km2' => 8514877, 'population' => 212559417, 'gdp_usd' => 2055000000000, 'gdp_per_capita' => 9673, 'currency_code' => 'BRL', 'currency_name' => 'Real', 'calling_code' => '+55', 'government_type' => 'federal republic', 'flag_emoji' => '🇧🇷', 'region' => 'South America', 'subregion' => 'South America', 'is_developed' => 0],
            ['name' => 'Chile', 'official_name' => 'Republic of Chile', 'continent_id' => $continents['South America']->id, 'iso_alpha_2' => 'CL', 'iso_alpha_3' => 'CHL', 'iso_numeric' => '152', 'capital' => 'Santiago', 'area_km2' => 756096, 'population' => 19116201, 'gdp_usd' => 317000000000, 'gdp_per_capita' => 16590, 'currency_code' => 'CLP', 'currency_name' => 'Peso', 'calling_code' => '+56', 'government_type' => 'republic', 'flag_emoji' => '🇨🇱', 'region' => 'South America', 'subregion' => 'South America', 'is_developed' => 1],
            ['name' => 'Colombia', 'official_name' => 'Republic of Colombia', 'continent_id' => $continents['South America']->id, 'iso_alpha_2' => 'CO', 'iso_alpha_3' => 'COL', 'iso_numeric' => '170', 'capital' => 'Bogotá', 'area_km2' => 1141748, 'population' => 50882891, 'gdp_usd' => 324000000000, 'gdp_per_capita' => 6667, 'currency_code' => 'COP', 'currency_name' => 'Peso', 'calling_code' => '+57', 'government_type' => 'republic', 'flag_emoji' => '🇨🇴', 'region' => 'South America', 'subregion' => 'South America', 'is_developed' => 0],
            ['name' => 'Ecuador', 'official_name' => 'Republic of Ecuador', 'continent_id' => $continents['South America']->id, 'iso_alpha_2' => 'EC', 'iso_alpha_3' => 'ECU', 'iso_numeric' => '218', 'capital' => 'Quito', 'area_km2' => 283561, 'population' => 17643054, 'gdp_usd' => 107400000000, 'gdp_per_capita' => 6087, 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'calling_code' => '+593', 'government_type' => 'republic', 'flag_emoji' => '🇪🇨', 'region' => 'South America', 'subregion' => 'South America', 'is_developed' => 0],
            ['name' => 'French Guiana', 'official_name' => 'Guiana', 'continent_id' => $continents['South America']->id, 'iso_alpha_2' => 'GF', 'iso_alpha_3' => 'GUF', 'iso_numeric' => '254', 'capital' => 'Cayenne', 'area_km2' => 83534, 'population' => 298682, 'gdp_usd' => 4900000000, 'gdp_per_capita' => 16412, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'calling_code' => '+594', 'government_type' => 'overseas territory', 'flag_emoji' => '🇬🇫', 'region' => 'South America', 'subregion' => 'South America', 'is_developed' => 1],
            ['name' => 'Guyana', 'official_name' => 'Co-operative Republic of Guyana', 'continent_id' => $continents['South America']->id, 'iso_alpha_2' => 'GY', 'iso_alpha_3' => 'GUY', 'iso_numeric' => '328', 'capital' => 'Georgetown', 'area_km2' => 214969, 'population' => 786552, 'gdp_usd' => 6300000000, 'gdp_per_capita' => 8004, 'currency_code' => 'GYD', 'currency_name' => 'Dollar', 'calling_code' => '+592', 'government_type' => 'republic', 'flag_emoji' => '🇬🇾', 'region' => 'South America', 'subregion' => 'South America', 'is_developed' => 0],
            ['name' => 'Paraguay', 'official_name' => 'Republic of Paraguay', 'continent_id' => $continents['South America']->id, 'iso_alpha_2' => 'PY', 'iso_alpha_3' => 'PRY', 'iso_numeric' => '600', 'capital' => 'Asunción', 'area_km2' => 406752, 'population' => 7132538, 'gdp_usd' => 38940000000, 'gdp_per_capita' => 5461, 'currency_code' => 'PYG', 'currency_name' => 'Guarani', 'calling_code' => '+595', 'government_type' => 'republic', 'flag_emoji' => '🇵🇾', 'region' => 'South America', 'subregion' => 'South America', 'is_landlocked' => 1, 'is_developed' => 0],
            ['name' => 'Peru', 'official_name' => 'Republic of Peru', 'continent_id' => $continents['South America']->id, 'iso_alpha_2' => 'PE', 'iso_alpha_3' => 'PER', 'iso_numeric' => '604', 'capital' => 'Lima', 'area_km2' => 1285216, 'population' => 32971854, 'gdp_usd' => 228000000000, 'gdp_per_capita' => 6692, 'currency_code' => 'PEN', 'currency_name' => 'Sol', 'calling_code' => '+51', 'government_type' => 'republic', 'flag_emoji' => '🇵🇪', 'region' => 'South America', 'subregion' => 'South America', 'is_developed' => 0],
            ['name' => 'Suriname', 'official_name' => 'Republic of Suriname', 'continent_id' => $continents['South America']->id, 'iso_alpha_2' => 'SR', 'iso_alpha_3' => 'SUR', 'iso_numeric' => '740', 'capital' => 'Paramaribo', 'area_km2' => 163820, 'population' => 586632, 'gdp_usd' => 3790000000, 'gdp_per_capita' => 6461, 'currency_code' => 'SRD', 'currency_name' => 'Dollar', 'calling_code' => '+597', 'government_type' => 'republic', 'flag_emoji' => '🇸🇷', 'region' => 'South America', 'subregion' => 'South America', 'is_developed' => 0],
            ['name' => 'Uruguay', 'official_name' => 'Oriental Republic of Uruguay', 'continent_id' => $continents['South America']->id, 'iso_alpha_2' => 'UY', 'iso_alpha_3' => 'URY', 'iso_numeric' => '858', 'capital' => 'Montevideo', 'area_km2' => 176215, 'population' => 3473730, 'gdp_usd' => 59180000000, 'gdp_per_capita' => 17030, 'currency_code' => 'UYU', 'currency_name' => 'Peso', 'calling_code' => '+598', 'government_type' => 'republic', 'flag_emoji' => '🇺🇾', 'region' => 'South America', 'subregion' => 'South America', 'is_developed' => 1],
            ['name' => 'Venezuela', 'official_name' => 'Bolivarian Republic of Venezuela', 'continent_id' => $continents['South America']->id, 'iso_alpha_2' => 'VE', 'iso_alpha_3' => 'VEN', 'iso_numeric' => '862', 'capital' => 'Caracas', 'area_km2' => 912050, 'population' => 28435940, 'gdp_usd' => 482000000000, 'gdp_per_capita' => 16745, 'currency_code' => 'VES', 'currency_name' => 'Bolívar', 'calling_code' => '+58', 'government_type' => 'federal republic', 'flag_emoji' => '🇻🇪', 'region' => 'South America', 'subregion' => 'South America', 'is_developed' => 0],

            // OCEANIA (14 countries)
            ['name' => 'Australia', 'official_name' => 'Commonwealth of Australia', 'continent_id' => $continents['Oceania']->id, 'iso_alpha_2' => 'AU', 'iso_alpha_3' => 'AUS', 'iso_numeric' => '036', 'capital' => 'Canberra', 'area_km2' => 7692024, 'population' => 25499884, 'gdp_usd' => 1553000000000, 'gdp_per_capita' => 60925, 'currency_code' => 'AUD', 'currency_name' => 'Dollar', 'calling_code' => '+61', 'government_type' => 'parliamentary monarchy', 'flag_emoji' => '🇦🇺', 'region' => 'Australia and New Zealand', 'subregion' => 'Australia and New Zealand', 'is_developed' => 1],
            ['name' => 'Fiji', 'official_name' => 'Republic of Fiji', 'continent_id' => $continents['Oceania']->id, 'iso_alpha_2' => 'FJ', 'iso_alpha_3' => 'FJI', 'iso_numeric' => '242', 'capital' => 'Suva', 'area_km2' => 18274, 'population' => 896445, 'gdp_usd' => 5540000000, 'gdp_per_capita' => 6181, 'currency_code' => 'FJD', 'currency_name' => 'Dollar', 'calling_code' => '+679', 'government_type' => 'republic', 'flag_emoji' => '🇫🇯', 'region' => 'Melanesia', 'subregion' => 'Melanesia', 'is_developed' => 0],
            ['name' => 'Kiribati', 'official_name' => 'Republic of Kiribati', 'continent_id' => $continents['Oceania']->id, 'iso_alpha_2' => 'KI', 'iso_alpha_3' => 'KIR', 'iso_numeric' => '296', 'capital' => 'Tarawa', 'area_km2' => 811, 'population' => 119449, 'gdp_usd' => 200000000, 'gdp_per_capita' => 1674, 'currency_code' => 'AUD', 'currency_name' => 'Dollar', 'calling_code' => '+686', 'government_type' => 'republic', 'flag_emoji' => '🇰🇮', 'region' => 'Micronesia', 'subregion' => 'Micronesia', 'is_developed' => 0],
            ['name' => 'Marshall Islands', 'official_name' => 'Republic of the Marshall Islands', 'continent_id' => $continents['Oceania']->id, 'iso_alpha_2' => 'MH', 'iso_alpha_3' => 'MHL', 'iso_numeric' => '584', 'capital' => 'Majuro', 'area_km2' => 181, 'population' => 59190, 'gdp_usd' => 220000000, 'gdp_per_capita' => 3717, 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'calling_code' => '+692', 'government_type' => 'republic', 'flag_emoji' => '🇲🇭', 'region' => 'Micronesia', 'subregion' => 'Micronesia', 'is_developed' => 0],
            ['name' => 'Micronesia', 'official_name' => 'Federated States of Micronesia', 'continent_id' => $continents['Oceania']->id, 'iso_alpha_2' => 'FM', 'iso_alpha_3' => 'FSM', 'iso_numeric' => '583', 'capital' => 'Palikir', 'area_km2' => 702, 'population' => 548914, 'gdp_usd' => 400000000, 'gdp_per_capita' => 3560, 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'calling_code' => '+691', 'government_type' => 'federation', 'flag_emoji' => '🇫🇲', 'region' => 'Micronesia', 'subregion' => 'Micronesia', 'is_developed' => 0],
            ['name' => 'Nauru', 'official_name' => 'Republic of Nauru', 'continent_id' => $continents['Oceania']->id, 'iso_alpha_2' => 'NR', 'iso_alpha_3' => 'NRU', 'iso_numeric' => '520', 'capital' => 'Yaren', 'area_km2' => 21, 'population' => 10824, 'gdp_usd' => 118000000, 'gdp_per_capita' => 10900, 'currency_code' => 'AUD', 'currency_name' => 'Dollar', 'calling_code' => '+674', 'government_type' => 'republic', 'flag_emoji' => '🇳🇷', 'region' => 'Micronesia', 'subregion' => 'Micronesia', 'is_developed' => 0],
            ['name' => 'New Zealand', 'official_name' => 'New Zealand', 'continent_id' => $continents['Oceania']->id, 'iso_alpha_2' => 'NZ', 'iso_alpha_3' => 'NZL', 'iso_numeric' => '554', 'capital' => 'Wellington', 'area_km2' => 268838, 'population' => 4822233, 'gdp_usd' => 249000000000, 'gdp_per_capita' => 51643, 'currency_code' => 'NZD', 'currency_name' => 'Dollar', 'calling_code' => '+64', 'government_type' => 'parliamentary monarchy', 'flag_emoji' => '🇳🇿', 'region' => 'Australia and New Zealand', 'subregion' => 'Australia and New Zealand', 'is_developed' => 1],
            ['name' => 'Palau', 'official_name' => 'Republic of Palau', 'continent_id' => $continents['Oceania']->id, 'iso_alpha_2' => 'PW', 'iso_alpha_3' => 'PLW', 'iso_numeric' => '585', 'capital' => 'Ngerulmud', 'area_km2' => 459, 'population' => 18094, 'gdp_usd' => 270000000, 'gdp_per_capita' => 14929, 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'calling_code' => '+680', 'government_type' => 'republic', 'flag_emoji' => '🇵🇼', 'region' => 'Micronesia', 'subregion' => 'Micronesia', 'is_developed' => 1],
            ['name' => 'Papua New Guinea', 'official_name' => 'Independent State of Papua New Guinea', 'continent_id' => $continents['Oceania']->id, 'iso_alpha_2' => 'PG', 'iso_alpha_3' => 'PNG', 'iso_numeric' => '598', 'capital' => 'Port Moresby', 'area_km2' => 462840, 'population' => 8947024, 'gdp_usd' => 25900000000, 'gdp_per_capita' => 2897, 'currency_code' => 'PGK', 'currency_name' => 'Kina', 'calling_code' => '+675', 'government_type' => 'parliamentary monarchy', 'flag_emoji' => '🇵🇬', 'region' => 'Melanesia', 'subregion' => 'Melanesia', 'is_developed' => 0],
            ['name' => 'Samoa', 'official_name' => 'Independent State of Samoa', 'continent_id' => $continents['Oceania']->id, 'iso_alpha_2' => 'WS', 'iso_alpha_3' => 'WSM', 'iso_numeric' => '882', 'capital' => 'Apia', 'area_km2' => 2831, 'population' => 198414, 'gdp_usd' => 860000000, 'gdp_per_capita' => 4338, 'currency_code' => 'WST', 'currency_name' => 'Tala', 'calling_code' => '+685', 'government_type' => 'parliamentary', 'flag_emoji' => '🇼🇸', 'region' => 'Polynesia', 'subregion' => 'Polynesia', 'is_developed' => 0],
            ['name' => 'Solomon Islands', 'official_name' => 'Solomon Islands', 'continent_id' => $continents['Oceania']->id, 'iso_alpha_2' => 'SB', 'iso_alpha_3' => 'SLB', 'iso_numeric' => '090', 'capital' => 'Honiara', 'area_km2' => 28896, 'population' => 686884, 'gdp_usd' => 1550000000, 'gdp_per_capita' => 2256, 'currency_code' => 'SBD', 'currency_name' => 'Dollar', 'calling_code' => '+677', 'government_type' => 'parliamentary monarchy', 'flag_emoji' => '🇸🇧', 'region' => 'Melanesia', 'subregion' => 'Melanesia', 'is_developed' => 0],
            ['name' => 'Tonga', 'official_name' => 'Kingdom of Tonga', 'continent_id' => $continents['Oceania']->id, 'iso_alpha_2' => 'TO', 'iso_alpha_3' => 'TON', 'iso_numeric' => '776', 'capital' => 'Nuku\'alofa', 'area_km2' => 747, 'population' => 105695, 'gdp_usd' => 510000000, 'gdp_per_capita' => 4826, 'currency_code' => 'TOP', 'currency_name' => 'Pa\'anga', 'calling_code' => '+676', 'government_type' => 'monarchy', 'flag_emoji' => '🇹🇴', 'region' => 'Polynesia', 'subregion' => 'Polynesia', 'is_developed' => 0],
            ['name' => 'Tuvalu', 'official_name' => 'Tuvalu', 'continent_id' => $continents['Oceania']->id, 'iso_alpha_2' => 'TV', 'iso_alpha_3' => 'TUV', 'iso_numeric' => '798', 'capital' => 'Funafuti', 'area_km2' => 26, 'population' => 11792, 'gdp_usd' => 50000000, 'gdp_per_capita' => 4241, 'currency_code' => 'AUD', 'currency_name' => 'Dollar', 'calling_code' => '+688', 'government_type' => 'parliamentary monarchy', 'flag_emoji' => '🇹🇻', 'region' => 'Polynesia', 'subregion' => 'Polynesia', 'is_developed' => 0],
            ['name' => 'Vanuatu', 'official_name' => 'Republic of Vanuatu', 'continent_id' => $continents['Oceania']->id, 'iso_alpha_2' => 'VU', 'iso_alpha_3' => 'VUT', 'iso_numeric' => '548', 'capital' => 'Port Vila', 'area_km2' => 12189, 'population' => 307145, 'gdp_usd' => 920000000, 'gdp_per_capita' => 2996, 'currency_code' => 'VUV', 'currency_name' => 'Vatu', 'calling_code' => '+678', 'government_type' => 'republic', 'flag_emoji' => '🇻🇺', 'region' => 'Melanesia', 'subregion' => 'Melanesia', 'is_developed' => 0]
        ];

        foreach ($countries as $countryData) {
            // Check if country already exists
            $existing = Country::findByISO2($countryData['iso_alpha_2']);
            if (!$existing) {
                $country = new Country();
                $country->fill($countryData);
                $country->updatePopulationDensity();
                $country->save();
                echo "Created country: {$country->flag_emoji} {$country->name}\n";
            } else {
                echo "Country {$countryData['name']} already exists, skipping...\n";
            }
        }
    }

    private function seedIndustryCategories() {
        echo "Seeding industry categories...\n";

        // Check if already seeded
        $existing = IndustryCategory::all();
        if (!empty($existing)) {
            echo "Industry categories already exist, skipping seeding...\n";
            return;
        }

        IndustryCategory::seedIndustryTaxonomy();
    }

    private function seedOrganizationLegalTypes() {
        echo "Seeding organization legal types...\n";

        // Check if already seeded
        $existing = OrganizationLegalType::all();
        if (!empty($existing)) {
            echo "Organization legal types already exist, skipping seeding...\n";
            return;
        }

        OrganizationLegalType::seedOrganizationLegalTypes();
    }

    private function seedPopularOrganizationDepartments() {
        echo "Seeding popular organization departments...\n";

        // Check if already seeded
        $existing = PopularOrganizationDepartment::all();
        if (!empty($existing)) {
            echo "Popular organization departments already exist, skipping seeding...\n";
            return;
        }

        // Popular organizational departments
        $departments = [
            // Executive Leadership
            ['name' => 'Executive Office', 'code' => 'EXEC', 'department_type' => 'Functional', 'function_category' => 'Leadership', 'description' => 'Chief Executive Officer and executive leadership team', 'priority_level' => 'Critical'],
            ['name' => 'Board of Directors', 'code' => 'BOD', 'department_type' => 'Functional', 'function_category' => 'Governance', 'description' => 'Corporate governance and strategic oversight', 'priority_level' => 'Critical'],

            // Finance & Accounting
            ['name' => 'Finance', 'code' => 'FIN', 'department_type' => 'Functional', 'function_category' => 'Finance', 'description' => 'Financial planning, analysis, and treasury management', 'priority_level' => 'Critical'],
            ['name' => 'Accounting', 'code' => 'ACC', 'department_type' => 'Functional', 'function_category' => 'Finance', 'description' => 'Financial reporting, bookkeeping, and compliance', 'priority_level' => 'High'],
            ['name' => 'Audit', 'code' => 'AUD', 'department_type' => 'Functional', 'function_category' => 'Finance', 'description' => 'Internal audit and risk assessment', 'priority_level' => 'High'],
            ['name' => 'Tax', 'code' => 'TAX', 'department_type' => 'Functional', 'function_category' => 'Finance', 'description' => 'Tax planning, compliance, and reporting', 'priority_level' => 'Medium'],
            ['name' => 'Treasury', 'code' => 'TRES', 'department_type' => 'Functional', 'function_category' => 'Finance', 'description' => 'Cash management and investment oversight', 'priority_level' => 'High'],

            // Human Resources
            ['name' => 'Human Resources', 'code' => 'HR', 'department_type' => 'Functional', 'function_category' => 'People', 'description' => 'Employee relations, benefits, and HR strategy', 'priority_level' => 'High'],
            ['name' => 'Talent Acquisition', 'code' => 'TA', 'department_type' => 'Functional', 'function_category' => 'People', 'description' => 'Recruitment and hiring processes', 'priority_level' => 'High'],
            ['name' => 'Learning & Development', 'code' => 'L&D', 'department_type' => 'Functional', 'function_category' => 'People', 'description' => 'Employee training and career development', 'priority_level' => 'Medium'],
            ['name' => 'Compensation & Benefits', 'code' => 'C&B', 'department_type' => 'Functional', 'function_category' => 'People', 'description' => 'Salary administration and benefits management', 'priority_level' => 'High'],
            ['name' => 'Employee Relations', 'code' => 'ER', 'department_type' => 'Functional', 'function_category' => 'People', 'description' => 'Workplace culture and employee engagement', 'priority_level' => 'Medium'],

            // Technology & IT
            ['name' => 'Information Technology', 'code' => 'IT', 'department_type' => 'Functional', 'function_category' => 'Technology', 'description' => 'IT infrastructure, systems, and support', 'priority_level' => 'Critical'],
            ['name' => 'Software Development', 'code' => 'DEV', 'department_type' => 'Functional', 'function_category' => 'Technology', 'description' => 'Application development and programming', 'priority_level' => 'High'],
            ['name' => 'Data & Analytics', 'code' => 'DATA', 'department_type' => 'Functional', 'function_category' => 'Technology', 'description' => 'Data management, analysis, and business intelligence', 'priority_level' => 'High'],
            ['name' => 'Cybersecurity', 'code' => 'SEC', 'department_type' => 'Functional', 'function_category' => 'Technology', 'description' => 'Information security and risk management', 'priority_level' => 'Critical'],
            ['name' => 'DevOps', 'code' => 'OPS', 'department_type' => 'Functional', 'function_category' => 'Technology', 'description' => 'Development operations and infrastructure automation', 'priority_level' => 'High'],
            ['name' => 'Quality Assurance', 'code' => 'QA', 'department_type' => 'Functional', 'function_category' => 'Technology', 'description' => 'Software testing and quality control', 'priority_level' => 'Medium'],

            // Operations
            ['name' => 'Operations', 'code' => 'OPS', 'department_type' => 'Functional', 'function_category' => 'Operations', 'description' => 'Daily business operations and process management', 'priority_level' => 'Critical'],
            ['name' => 'Supply Chain', 'code' => 'SCM', 'department_type' => 'Functional', 'function_category' => 'Operations', 'description' => 'Procurement, logistics, and vendor management', 'priority_level' => 'High'],
            ['name' => 'Manufacturing', 'code' => 'MFG', 'department_type' => 'Functional', 'function_category' => 'Operations', 'description' => 'Production and manufacturing operations', 'priority_level' => 'High'],
            ['name' => 'Quality Control', 'code' => 'QC', 'department_type' => 'Functional', 'function_category' => 'Operations', 'description' => 'Product quality assurance and testing', 'priority_level' => 'High'],
            ['name' => 'Logistics', 'code' => 'LOG', 'department_type' => 'Functional', 'function_category' => 'Operations', 'description' => 'Transportation and distribution management', 'priority_level' => 'Medium'],
            ['name' => 'Facilities', 'code' => 'FAC', 'department_type' => 'Functional', 'function_category' => 'Operations', 'description' => 'Building maintenance and workplace management', 'priority_level' => 'Medium'],

            // Sales & Marketing
            ['name' => 'Sales', 'code' => 'SAL', 'department_type' => 'Functional', 'function_category' => 'Revenue', 'description' => 'Sales strategy, execution, and customer acquisition', 'priority_level' => 'Critical'],
            ['name' => 'Marketing', 'code' => 'MKT', 'department_type' => 'Functional', 'function_category' => 'Revenue', 'description' => 'Brand management, advertising, and market research', 'priority_level' => 'High'],
            ['name' => 'Business Development', 'code' => 'BD', 'department_type' => 'Functional', 'function_category' => 'Revenue', 'description' => 'Partnership development and strategic growth', 'priority_level' => 'High'],
            ['name' => 'Customer Success', 'code' => 'CS', 'department_type' => 'Functional', 'function_category' => 'Revenue', 'description' => 'Customer retention and satisfaction', 'priority_level' => 'High'],
            ['name' => 'Digital Marketing', 'code' => 'DMK', 'department_type' => 'Functional', 'function_category' => 'Revenue', 'description' => 'Online marketing and digital campaigns', 'priority_level' => 'Medium'],
            ['name' => 'Product Marketing', 'code' => 'PMK', 'department_type' => 'Functional', 'function_category' => 'Revenue', 'description' => 'Product positioning and go-to-market strategy', 'priority_level' => 'Medium'],

            // Customer Service
            ['name' => 'Customer Service', 'code' => 'CS', 'department_type' => 'Functional', 'function_category' => 'Customer', 'description' => 'Customer support and service delivery', 'priority_level' => 'High'],
            ['name' => 'Technical Support', 'code' => 'TS', 'department_type' => 'Functional', 'function_category' => 'Customer', 'description' => 'Technical assistance and troubleshooting', 'priority_level' => 'Medium'],
            ['name' => 'Customer Experience', 'code' => 'CX', 'department_type' => 'Functional', 'function_category' => 'Customer', 'description' => 'Customer journey optimization and experience design', 'priority_level' => 'Medium'],

            // Product & Engineering
            ['name' => 'Product Management', 'code' => 'PM', 'department_type' => 'Functional', 'function_category' => 'Product', 'description' => 'Product strategy, roadmap, and lifecycle management', 'priority_level' => 'High'],
            ['name' => 'Engineering', 'code' => 'ENG', 'department_type' => 'Functional', 'function_category' => 'Product', 'description' => 'Technical product development and engineering', 'priority_level' => 'High'],
            ['name' => 'Research & Development', 'code' => 'R&D', 'department_type' => 'Functional', 'function_category' => 'Product', 'description' => 'Innovation, research, and new product development', 'priority_level' => 'Medium'],
            ['name' => 'Design', 'code' => 'DES', 'department_type' => 'Functional', 'function_category' => 'Product', 'description' => 'User experience and product design', 'priority_level' => 'Medium'],

            // Legal & Compliance
            ['name' => 'Legal', 'code' => 'LEG', 'department_type' => 'Functional', 'function_category' => 'Legal', 'description' => 'Legal counsel, contracts, and corporate law', 'priority_level' => 'High'],
            ['name' => 'Compliance', 'code' => 'COM', 'department_type' => 'Functional', 'function_category' => 'Legal', 'description' => 'Regulatory compliance and risk management', 'priority_level' => 'High'],
            ['name' => 'Risk Management', 'code' => 'RISK', 'department_type' => 'Functional', 'function_category' => 'Legal', 'description' => 'Enterprise risk assessment and mitigation', 'priority_level' => 'Medium'],

            // Communications & PR
            ['name' => 'Communications', 'code' => 'COMM', 'department_type' => 'Functional', 'function_category' => 'Communications', 'description' => 'Internal and external communications', 'priority_level' => 'Medium'],
            ['name' => 'Public Relations', 'code' => 'PR', 'department_type' => 'Functional', 'function_category' => 'Communications', 'description' => 'Media relations and public messaging', 'priority_level' => 'Medium'],
            ['name' => 'Investor Relations', 'code' => 'IR', 'department_type' => 'Functional', 'function_category' => 'Communications', 'description' => 'Shareholder communications and financial disclosure', 'priority_level' => 'Medium'],

            // Strategy & Planning
            ['name' => 'Strategy', 'code' => 'STRAT', 'department_type' => 'Functional', 'function_category' => 'Strategy', 'description' => 'Corporate strategy and business planning', 'priority_level' => 'High'],
            ['name' => 'Business Analysis', 'code' => 'BA', 'department_type' => 'Functional', 'function_category' => 'Strategy', 'description' => 'Business process analysis and optimization', 'priority_level' => 'Medium'],
            ['name' => 'Project Management Office', 'code' => 'PMO', 'department_type' => 'Functional', 'function_category' => 'Strategy', 'description' => 'Project governance and portfolio management', 'priority_level' => 'Medium'],

            // Specialized Functions
            ['name' => 'Procurement', 'code' => 'PROC', 'department_type' => 'Functional', 'function_category' => 'Operations', 'description' => 'Vendor management and purchasing', 'priority_level' => 'Medium'],
            ['name' => 'Real Estate', 'code' => 'RE', 'department_type' => 'Functional', 'function_category' => 'Operations', 'description' => 'Property management and real estate operations', 'priority_level' => 'Low'],
            ['name' => 'Environmental Health & Safety', 'code' => 'EHS', 'department_type' => 'Functional', 'function_category' => 'Operations', 'description' => 'Workplace safety and environmental compliance', 'priority_level' => 'Medium'],
            ['name' => 'Sustainability', 'code' => 'SUST', 'department_type' => 'Functional', 'function_category' => 'Operations', 'description' => 'Environmental sustainability and corporate responsibility', 'priority_level' => 'Low'],

            // Industry-Specific Examples
            ['name' => 'Clinical Research', 'code' => 'CR', 'department_type' => 'Functional', 'function_category' => 'Research', 'description' => 'Medical and pharmaceutical research operations', 'priority_level' => 'Medium'],
            ['name' => 'Regulatory Affairs', 'code' => 'RA', 'department_type' => 'Functional', 'function_category' => 'Legal', 'description' => 'Industry-specific regulatory compliance', 'priority_level' => 'Medium'],
            ['name' => 'Medical Affairs', 'code' => 'MA', 'department_type' => 'Functional', 'function_category' => 'Research', 'description' => 'Medical science and clinical strategy', 'priority_level' => 'Medium'],
            ['name' => 'Pharmacovigilance', 'code' => 'PV', 'department_type' => 'Functional', 'function_category' => 'Research', 'description' => 'Drug safety monitoring and reporting', 'priority_level' => 'Medium'],

            // Financial Services
            ['name' => 'Credit Risk', 'code' => 'CRED', 'department_type' => 'Functional', 'function_category' => 'Finance', 'description' => 'Credit analysis and risk assessment', 'priority_level' => 'Medium'],
            ['name' => 'Investment Management', 'code' => 'IM', 'department_type' => 'Functional', 'function_category' => 'Finance', 'description' => 'Portfolio management and investment strategy', 'priority_level' => 'Medium'],
            ['name' => 'Wealth Management', 'code' => 'WM', 'department_type' => 'Functional', 'function_category' => 'Finance', 'description' => 'Private client services and wealth advisory', 'priority_level' => 'Medium'],

            // Technology Companies
            ['name' => 'Platform Engineering', 'code' => 'PE', 'department_type' => 'Functional', 'function_category' => 'Technology', 'description' => 'Infrastructure platforms and developer tools', 'priority_level' => 'Medium'],
            ['name' => 'Machine Learning', 'code' => 'ML', 'department_type' => 'Functional', 'function_category' => 'Technology', 'description' => 'AI and machine learning development', 'priority_level' => 'Medium'],
            ['name' => 'Site Reliability Engineering', 'code' => 'SRE', 'department_type' => 'Functional', 'function_category' => 'Technology', 'description' => 'System reliability and performance optimization', 'priority_level' => 'Medium'],

            // Regional/Geographic Departments
            ['name' => 'North America Operations', 'code' => 'NAO', 'department_type' => 'Divisional', 'function_category' => 'Regional', 'description' => 'North American regional operations', 'priority_level' => 'Medium'],
            ['name' => 'Europe Operations', 'code' => 'EUR', 'department_type' => 'Divisional', 'function_category' => 'Regional', 'description' => 'European regional operations', 'priority_level' => 'Medium'],
            ['name' => 'Asia Pacific Operations', 'code' => 'APAC', 'department_type' => 'Divisional', 'function_category' => 'Regional', 'description' => 'Asia Pacific regional operations', 'priority_level' => 'Medium'],

            // Support Functions
            ['name' => 'Administrative Services', 'code' => 'ADMIN', 'department_type' => 'Functional', 'function_category' => 'Support', 'description' => 'General administrative support and office management', 'priority_level' => 'Low'],
            ['name' => 'Travel & Expense', 'code' => 'T&E', 'department_type' => 'Functional', 'function_category' => 'Support', 'description' => 'Travel coordination and expense management', 'priority_level' => 'Low'],
            ['name' => 'Records Management', 'code' => 'RM', 'department_type' => 'Functional', 'function_category' => 'Support', 'description' => 'Document and information management', 'priority_level' => 'Low']
        ];

        foreach ($departments as $deptData) {
            $department = new PopularOrganizationDepartment();
            $department->fill($deptData);
            $department->save();
            echo "Created popular department: {$department->name} ({$department->code})\n";
        }

        echo "Successfully seeded " . count($departments) . " departments.\n";
    }

    private function seedOrders() {
        echo "Seeding sample orders...\n";

        $sampleOrders = [
            ['customer' => 'John Doe', 'status' => 'draft', 'total' => 99.99],
            ['customer' => 'Jane Smith', 'status' => 'pending', 'total' => 149.50],
            ['customer' => 'Bob Johnson', 'status' => 'paid', 'total' => 75.25],
            ['customer' => 'Alice Brown', 'status' => 'shipped', 'total' => 200.00],
            ['customer' => 'Charlie Wilson', 'status' => 'delivered', 'total' => 125.75]
        ];

        foreach ($sampleOrders as $orderData) {
            $order = new Order();
            $order->fill($orderData);
            $order->save();

            $order->addItem('Product A', 2, 25.00);
            $order->addItem('Product B', 1, $orderData['total'] - 50.00);

            echo "Created order #{$order->id} for {$order->customer}\n";
        }
    }
}

if (php_sapi_name() == 'cli') {
    $migration = new DatabaseMigration();
    $migration->migrate();

    if (isset($argv[1]) && $argv[1] === '--seed') {
        $migration->seed();
    }
}