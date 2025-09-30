<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../entities/PopularOrganizationDesignation.php';

class DesignationPopulator {
    private $db;

    // Comprehensive list of designations for departments and teams
    private $designations = [
        // Executive/Leadership
        ['name' => 'Chief Executive Officer', 'code' => 'CEO', 'level' => 'Executive', 'category' => 'Leadership'],
        ['name' => 'Chief Operating Officer', 'code' => 'COO', 'level' => 'Executive', 'category' => 'Leadership'],
        ['name' => 'Chief Financial Officer', 'code' => 'CFO', 'level' => 'Executive', 'category' => 'Leadership'],
        ['name' => 'Chief Technology Officer', 'code' => 'CTO', 'level' => 'Executive', 'category' => 'Leadership'],
        ['name' => 'Chief Information Officer', 'code' => 'CIO', 'level' => 'Executive', 'category' => 'Leadership'],
        ['name' => 'Chief Marketing Officer', 'code' => 'CMO', 'level' => 'Executive', 'category' => 'Leadership'],
        ['name' => 'Chief Human Resources Officer', 'code' => 'CHRO', 'level' => 'Executive', 'category' => 'Leadership'],
        ['name' => 'Chief Legal Officer', 'code' => 'CLO', 'level' => 'Executive', 'category' => 'Leadership'],
        ['name' => 'Chief Data Officer', 'code' => 'CDO', 'level' => 'Executive', 'category' => 'Leadership'],
        ['name' => 'Chief Security Officer', 'code' => 'CSO', 'level' => 'Executive', 'category' => 'Leadership'],

        // Senior Management
        ['name' => 'Vice President', 'code' => 'VP', 'level' => 'Senior Management', 'category' => 'Management'],
        ['name' => 'Senior Vice President', 'code' => 'SVP', 'level' => 'Senior Management', 'category' => 'Management'],
        ['name' => 'Executive Vice President', 'code' => 'EVP', 'level' => 'Senior Management', 'category' => 'Management'],
        ['name' => 'Director', 'code' => 'DIR', 'level' => 'Senior Management', 'category' => 'Management'],
        ['name' => 'Senior Director', 'code' => 'SDIR', 'level' => 'Senior Management', 'category' => 'Management'],
        ['name' => 'General Manager', 'code' => 'GM', 'level' => 'Senior Management', 'category' => 'Management'],

        // Middle Management
        ['name' => 'Manager', 'code' => 'MGR', 'level' => 'Middle Management', 'category' => 'Management'],
        ['name' => 'Senior Manager', 'code' => 'SMGR', 'level' => 'Middle Management', 'category' => 'Management'],
        ['name' => 'Team Lead', 'code' => 'TL', 'level' => 'Middle Management', 'category' => 'Management'],
        ['name' => 'Group Lead', 'code' => 'GL', 'level' => 'Middle Management', 'category' => 'Management'],
        ['name' => 'Project Manager', 'code' => 'PM', 'level' => 'Middle Management', 'category' => 'Management'],
        ['name' => 'Program Manager', 'code' => 'PGM', 'level' => 'Middle Management', 'category' => 'Management'],
        ['name' => 'Product Manager', 'code' => 'PRODM', 'level' => 'Middle Management', 'category' => 'Management'],
        ['name' => 'Scrum Master', 'code' => 'SM', 'level' => 'Middle Management', 'category' => 'Management'],

        // Technical/Engineering
        ['name' => 'Chief Architect', 'code' => 'CARCH', 'level' => 'Senior Professional', 'category' => 'Technical'],
        ['name' => 'Principal Engineer', 'code' => 'PENG', 'level' => 'Senior Professional', 'category' => 'Technical'],
        ['name' => 'Senior Principal Engineer', 'code' => 'SPENG', 'level' => 'Senior Professional', 'category' => 'Technical'],
        ['name' => 'Distinguished Engineer', 'code' => 'DENG', 'level' => 'Senior Professional', 'category' => 'Technical'],
        ['name' => 'Staff Engineer', 'code' => 'SENG', 'level' => 'Senior Professional', 'category' => 'Technical'],
        ['name' => 'Senior Software Engineer', 'code' => 'SSE', 'level' => 'Senior Professional', 'category' => 'Technical'],
        ['name' => 'Software Engineer', 'code' => 'SE', 'level' => 'Professional', 'category' => 'Technical'],
        ['name' => 'Junior Software Engineer', 'code' => 'JSE', 'level' => 'Junior Professional', 'category' => 'Technical'],
        ['name' => 'Associate Software Engineer', 'code' => 'ASE', 'level' => 'Entry Level', 'category' => 'Technical'],
        ['name' => 'Technical Lead', 'code' => 'TECHLEAD', 'level' => 'Senior Professional', 'category' => 'Technical'],
        ['name' => 'Solution Architect', 'code' => 'SARCH', 'level' => 'Senior Professional', 'category' => 'Technical'],
        ['name' => 'Enterprise Architect', 'code' => 'EARCH', 'level' => 'Senior Professional', 'category' => 'Technical'],
        ['name' => 'Cloud Architect', 'code' => 'CARCH', 'level' => 'Senior Professional', 'category' => 'Technical'],
        ['name' => 'DevOps Engineer', 'code' => 'DEVOPS', 'level' => 'Professional', 'category' => 'Technical'],
        ['name' => 'Senior DevOps Engineer', 'code' => 'SDEVOPS', 'level' => 'Senior Professional', 'category' => 'Technical'],
        ['name' => 'Site Reliability Engineer', 'code' => 'SRE', 'level' => 'Professional', 'category' => 'Technical'],
        ['name' => 'Senior SRE', 'code' => 'SSRE', 'level' => 'Senior Professional', 'category' => 'Technical'],
        ['name' => 'QA Engineer', 'code' => 'QA', 'level' => 'Professional', 'category' => 'Technical'],
        ['name' => 'Senior QA Engineer', 'code' => 'SQA', 'level' => 'Senior Professional', 'category' => 'Technical'],
        ['name' => 'Test Automation Engineer', 'code' => 'TAE', 'level' => 'Professional', 'category' => 'Technical'],
        ['name' => 'Security Engineer', 'code' => 'SECENG', 'level' => 'Professional', 'category' => 'Technical'],
        ['name' => 'Senior Security Engineer', 'code' => 'SSECENG', 'level' => 'Senior Professional', 'category' => 'Technical'],
        ['name' => 'Data Engineer', 'code' => 'DE', 'level' => 'Professional', 'category' => 'Technical'],
        ['name' => 'Senior Data Engineer', 'code' => 'SDE', 'level' => 'Senior Professional', 'category' => 'Technical'],
        ['name' => 'ML Engineer', 'code' => 'MLE', 'level' => 'Professional', 'category' => 'Technical'],
        ['name' => 'Senior ML Engineer', 'code' => 'SMLE', 'level' => 'Senior Professional', 'category' => 'Technical'],
        ['name' => 'Data Scientist', 'code' => 'DS', 'level' => 'Professional', 'category' => 'Specialist'],
        ['name' => 'Senior Data Scientist', 'code' => 'SDS', 'level' => 'Senior Professional', 'category' => 'Specialist'],
        ['name' => 'Principal Data Scientist', 'code' => 'PDS', 'level' => 'Senior Professional', 'category' => 'Specialist'],

        // Product/Design
        ['name' => 'Product Owner', 'code' => 'PO', 'level' => 'Professional', 'category' => 'Specialist'],
        ['name' => 'Senior Product Owner', 'code' => 'SPO', 'level' => 'Senior Professional', 'category' => 'Specialist'],
        ['name' => 'UX Designer', 'code' => 'UXD', 'level' => 'Professional', 'category' => 'Specialist'],
        ['name' => 'Senior UX Designer', 'code' => 'SUXD', 'level' => 'Senior Professional', 'category' => 'Specialist'],
        ['name' => 'UI Designer', 'code' => 'UID', 'level' => 'Professional', 'category' => 'Specialist'],
        ['name' => 'Senior UI Designer', 'code' => 'SUID', 'level' => 'Senior Professional', 'category' => 'Specialist'],
        ['name' => 'Product Designer', 'code' => 'PRODD', 'level' => 'Professional', 'category' => 'Specialist'],
        ['name' => 'Senior Product Designer', 'code' => 'SPRODD', 'level' => 'Senior Professional', 'category' => 'Specialist'],
        ['name' => 'UX Researcher', 'code' => 'UXR', 'level' => 'Professional', 'category' => 'Specialist'],

        // Business/Operations
        ['name' => 'Business Analyst', 'code' => 'BA', 'level' => 'Professional', 'category' => 'Specialist'],
        ['name' => 'Senior Business Analyst', 'code' => 'SBA', 'level' => 'Senior Professional', 'category' => 'Specialist'],
        ['name' => 'Business Systems Analyst', 'code' => 'BSA', 'level' => 'Professional', 'category' => 'Specialist'],
        ['name' => 'Operations Manager', 'code' => 'OPSMGR', 'level' => 'Middle Management', 'category' => 'Management'],
        ['name' => 'Operations Specialist', 'code' => 'OPSSPEC', 'level' => 'Professional', 'category' => 'Operational'],
        ['name' => 'Process Specialist', 'code' => 'PROCSPEC', 'level' => 'Professional', 'category' => 'Operational'],

        // Sales/Marketing
        ['name' => 'Sales Director', 'code' => 'SALDIR', 'level' => 'Senior Management', 'category' => 'Management'],
        ['name' => 'Sales Manager', 'code' => 'SALMGR', 'level' => 'Middle Management', 'category' => 'Management'],
        ['name' => 'Account Executive', 'code' => 'AE', 'level' => 'Professional', 'category' => 'Specialist'],
        ['name' => 'Senior Account Executive', 'code' => 'SAE', 'level' => 'Senior Professional', 'category' => 'Specialist'],
        ['name' => 'Sales Representative', 'code' => 'SALESREP', 'level' => 'Professional', 'category' => 'Operational'],
        ['name' => 'Marketing Manager', 'code' => 'MKTMGR', 'level' => 'Middle Management', 'category' => 'Management'],
        ['name' => 'Marketing Specialist', 'code' => 'MKTSPEC', 'level' => 'Professional', 'category' => 'Specialist'],
        ['name' => 'Content Manager', 'code' => 'CONTMGR', 'level' => 'Professional', 'category' => 'Specialist'],
        ['name' => 'Digital Marketing Specialist', 'code' => 'DIGMKT', 'level' => 'Professional', 'category' => 'Specialist'],
        ['name' => 'SEO Specialist', 'code' => 'SEO', 'level' => 'Professional', 'category' => 'Specialist'],
        ['name' => 'Social Media Manager', 'code' => 'SMMGR', 'level' => 'Professional', 'category' => 'Specialist'],

        // HR/Finance/Legal
        ['name' => 'HR Manager', 'code' => 'HRMGR', 'level' => 'Middle Management', 'category' => 'Management'],
        ['name' => 'HR Business Partner', 'code' => 'HRBP', 'level' => 'Professional', 'category' => 'Specialist'],
        ['name' => 'Recruiter', 'code' => 'REC', 'level' => 'Professional', 'category' => 'Operational'],
        ['name' => 'Senior Recruiter', 'code' => 'SREC', 'level' => 'Senior Professional', 'category' => 'Operational'],
        ['name' => 'Talent Acquisition Specialist', 'code' => 'TAS', 'level' => 'Professional', 'category' => 'Specialist'],
        ['name' => 'Finance Manager', 'code' => 'FINMGR', 'level' => 'Middle Management', 'category' => 'Management'],
        ['name' => 'Financial Analyst', 'code' => 'FA', 'level' => 'Professional', 'category' => 'Specialist'],
        ['name' => 'Senior Financial Analyst', 'code' => 'SFA', 'level' => 'Senior Professional', 'category' => 'Specialist'],
        ['name' => 'Accountant', 'code' => 'ACC', 'level' => 'Professional', 'category' => 'Operational'],
        ['name' => 'Senior Accountant', 'code' => 'SACC', 'level' => 'Senior Professional', 'category' => 'Operational'],
        ['name' => 'Controller', 'code' => 'CTRL', 'level' => 'Senior Management', 'category' => 'Management'],
        ['name' => 'Legal Counsel', 'code' => 'LC', 'level' => 'Professional', 'category' => 'Specialist'],
        ['name' => 'Senior Legal Counsel', 'code' => 'SLC', 'level' => 'Senior Professional', 'category' => 'Specialist'],
        ['name' => 'Compliance Officer', 'code' => 'CO', 'level' => 'Professional', 'category' => 'Specialist'],

        // Support/Admin
        ['name' => 'Customer Support Manager', 'code' => 'CSMGR', 'level' => 'Middle Management', 'category' => 'Management'],
        ['name' => 'Customer Success Manager', 'code' => 'CSM', 'level' => 'Professional', 'category' => 'Specialist'],
        ['name' => 'Technical Support Engineer', 'code' => 'TSE', 'level' => 'Professional', 'category' => 'Technical'],
        ['name' => 'Senior Technical Support Engineer', 'code' => 'STSE', 'level' => 'Senior Professional', 'category' => 'Technical'],
        ['name' => 'Support Specialist', 'code' => 'SUPP', 'level' => 'Professional', 'category' => 'Support'],
        ['name' => 'Administrative Assistant', 'code' => 'AA', 'level' => 'Entry Level', 'category' => 'Support'],
        ['name' => 'Executive Assistant', 'code' => 'EA', 'level' => 'Professional', 'category' => 'Support'],
        ['name' => 'Office Manager', 'code' => 'OFFMGR', 'level' => 'Professional', 'category' => 'Management'],

        // Analyst/Consultant
        ['name' => 'Consultant', 'code' => 'CONS', 'level' => 'Professional', 'category' => 'Specialist'],
        ['name' => 'Senior Consultant', 'code' => 'SCONS', 'level' => 'Senior Professional', 'category' => 'Specialist'],
        ['name' => 'Principal Consultant', 'code' => 'PCONS', 'level' => 'Senior Professional', 'category' => 'Specialist'],
        ['name' => 'Systems Analyst', 'code' => 'SA', 'level' => 'Professional', 'category' => 'Technical'],
        ['name' => 'Senior Systems Analyst', 'code' => 'SSA', 'level' => 'Senior Professional', 'category' => 'Technical'],

        // Specialist Roles
        ['name' => 'Specialist', 'code' => 'SPEC', 'level' => 'Professional', 'category' => 'Specialist'],
        ['name' => 'Senior Specialist', 'code' => 'SSPEC', 'level' => 'Senior Professional', 'category' => 'Specialist'],
        ['name' => 'Subject Matter Expert', 'code' => 'SME', 'level' => 'Senior Professional', 'category' => 'Specialist'],
        ['name' => 'Technical Specialist', 'code' => 'TECHSPEC', 'level' => 'Professional', 'category' => 'Technical'],
        ['name' => 'Senior Technical Specialist', 'code' => 'STECHSPEC', 'level' => 'Senior Professional', 'category' => 'Technical'],

        // Entry Level
        ['name' => 'Associate', 'code' => 'ASSOC', 'level' => 'Entry Level', 'category' => 'Operational'],
        ['name' => 'Analyst', 'code' => 'ANLST', 'level' => 'Entry Level', 'category' => 'Specialist'],
        ['name' => 'Coordinator', 'code' => 'COORD', 'level' => 'Entry Level', 'category' => 'Operational'],
        ['name' => 'Junior Associate', 'code' => 'JA', 'level' => 'Entry Level', 'category' => 'Operational'],
        ['name' => 'Trainee', 'code' => 'TRN', 'level' => 'Entry Level', 'category' => 'Operational'],
        ['name' => 'Intern', 'code' => 'INT', 'level' => 'Entry Level', 'category' => 'Operational'],
    ];

    public function __construct() {
        $this->db = DatabaseConfig::getInstance();
    }

    public function populate() {
        echo "Starting designation population...\n\n";

        try {
            $totalCreated = 0;
            $totalSkipped = 0;

            foreach ($this->designations as $designationData) {
                try {
                    // Check if designation already exists
                    $existing = PopularOrganizationDesignation::all();
                    $exists = false;
                    foreach ($existing as $existingDes) {
                        if ($existingDes->name === $designationData['name']) {
                            $exists = true;
                            break;
                        }
                    }

                    if ($exists) {
                        echo "  - Skipping '{$designationData['name']}' (already exists)\n";
                        $totalSkipped++;
                        continue;
                    }

                    $designation = new PopularOrganizationDesignation();

                    // Basic info
                    $designation->name = $designationData['name'];
                    $designation->code = $designationData['code'];
                    $designation->designation_level = $designationData['level'];
                    $designation->category = $designationData['category'];
                    $designation->status = 'Active';
                    $designation->is_active = 1;

                    // Set hierarchy level based on designation level
                    $hierarchyMap = [
                        'Executive' => 1,
                        'Senior Management' => 2,
                        'Middle Management' => 3,
                        'Junior Management' => 4,
                        'Senior Professional' => 5,
                        'Professional' => 6,
                        'Junior Professional' => 7,
                        'Entry Level' => 8
                    ];
                    $designation->hierarchy_level = $hierarchyMap[$designationData['level']] ?? 6;

                    // Experience requirements based on level
                    $experienceMap = [
                        'Executive' => ['min' => 15, 'max' => 30],
                        'Senior Management' => ['min' => 10, 'max' => 20],
                        'Middle Management' => ['min' => 5, 'max' => 12],
                        'Junior Management' => ['min' => 3, 'max' => 8],
                        'Senior Professional' => ['min' => 7, 'max' => 15],
                        'Professional' => ['min' => 2, 'max' => 7],
                        'Junior Professional' => ['min' => 1, 'max' => 3],
                        'Entry Level' => ['min' => 0, 'max' => 2]
                    ];
                    $expRange = $experienceMap[$designationData['level']] ?? ['min' => 0, 'max' => 5];
                    $designation->min_experience_years = $expRange['min'];
                    $designation->max_experience_years = $expRange['max'];

                    // Salary ranges (in USD) based on level
                    $salaryMap = [
                        'Executive' => ['min' => 200000, 'max' => 500000],
                        'Senior Management' => ['min' => 150000, 'max' => 300000],
                        'Middle Management' => ['min' => 80000, 'max' => 150000],
                        'Junior Management' => ['min' => 60000, 'max' => 100000],
                        'Senior Professional' => ['min' => 100000, 'max' => 180000],
                        'Professional' => ['min' => 60000, 'max' => 120000],
                        'Junior Professional' => ['min' => 45000, 'max' => 80000],
                        'Entry Level' => ['min' => 35000, 'max' => 60000]
                    ];
                    $salRange = $salaryMap[$designationData['level']] ?? ['min' => 50000, 'max' => 100000];
                    $designation->salary_range_min = $salRange['min'];
                    $designation->salary_range_max = $salRange['max'];
                    $designation->salary_currency = 'USD';

                    // Employment details
                    $designation->employment_type = 'Full-time';
                    $designation->remote_work_eligible = rand(0, 1);

                    // Compensation details
                    $designation->bonus_eligible = in_array($designationData['level'], ['Executive', 'Senior Management', 'Middle Management']) ? 1 : rand(0, 1);
                    $designation->equity_eligible = in_array($designationData['level'], ['Executive', 'Senior Management']) ? 1 : 0;

                    // Probation and notice periods (in days)
                    $designation->probation_period = in_array($designationData['level'], ['Executive', 'Senior Management']) ? 180 : 90;
                    $designation->notice_period = in_array($designationData['level'], ['Executive', 'Senior Management']) ? 90 : 30;

                    // Effective date
                    $designation->effective_date = date('Y-m-d');

                    // Save designation
                    if ($designation->save()) {
                        echo "  ✓ Created: {$designationData['name']} ({$designationData['code']})\n";
                        $totalCreated++;
                    } else {
                        echo "  ✗ Failed to save: {$designationData['name']}\n";
                    }

                } catch (Exception $e) {
                    echo "  ✗ Error creating designation '{$designationData['name']}': " . $e->getMessage() . "\n";
                }
            }

            echo "\n==============================================\n";
            echo "Designation population completed successfully!\n";
            echo "Total designations created: {$totalCreated}\n";
            echo "Total designations skipped: {$totalSkipped}\n";
            echo "==============================================\n";

        } catch (Exception $e) {
            echo "Population failed: " . $e->getMessage() . "\n";
            echo "Trace: " . $e->getTraceAsString() . "\n";
        }
    }
}

// Run the populator
echo "\n==============================================\n";
echo "POPULAR ORGANIZATION DESIGNATIONS POPULATOR\n";
echo "==============================================\n\n";

$populator = new DesignationPopulator();
$populator->populate();

echo "\nDone!\n";