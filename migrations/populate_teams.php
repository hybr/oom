<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../entities/PopularOrganizationDepartment.php';
require_once __DIR__ . '/../entities/PopularOrganizationTeam.php';

class TeamPopulator {
    private $db;
    private $teamTypes = ['Functional', 'Cross-functional', 'Project', 'Product', 'Scrum', 'DevOps', 'Support', 'Research'];
    private $teamNames = [
        'Leadership' => ['Executive Team', 'Leadership Council', 'Strategic Planning Team'],
        'Finance' => ['Accounting Team', 'Financial Planning Team', 'Audit Team', 'Treasury Team'],
        'Human Resources' => ['Recruitment Team', 'Training & Development Team', 'Employee Relations Team', 'Compensation Team'],
        'Technology' => ['Backend Development Team', 'Frontend Development Team', 'DevOps Team', 'QA Team', 'Infrastructure Team', 'Security Team'],
        'Operations' => ['Production Team', 'Quality Control Team', 'Supply Chain Team', 'Logistics Team'],
        'Sales' => ['Enterprise Sales Team', 'Inside Sales Team', 'Sales Operations Team', 'Account Management Team'],
        'Marketing' => ['Digital Marketing Team', 'Content Team', 'Brand Team', 'Growth Team', 'Marketing Operations Team'],
        'Legal' => ['Corporate Legal Team', 'Compliance Team', 'Contract Management Team'],
        'Research' => ['R&D Team', 'Innovation Lab', 'Data Science Team', 'Product Research Team'],
        'Support' => ['Customer Support Team', 'Technical Support Team', 'Help Desk Team', 'Success Team']
    ];

    public function __construct() {
        $this->db = DatabaseConfig::getInstance();
    }

    public function populate() {
        echo "Starting team population...\n\n";

        try {
            // Get all departments
            $departments = PopularOrganizationDepartment::all();

            if (empty($departments)) {
                echo "No departments found. Please populate departments first.\n";
                return;
            }

            echo "Found " . count($departments) . " departments.\n\n";

            $totalTeamsCreated = 0;

            foreach ($departments as $department) {
                echo "Processing Department: {$department->name} (ID: {$department->id})\n";

                $teamsCreated = $this->createTeamsForDepartment($department);
                $totalTeamsCreated += $teamsCreated;

                echo "  Created {$teamsCreated} teams\n\n";
            }

            echo "\n==============================================\n";
            echo "Team population completed successfully!\n";
            echo "Total teams created: {$totalTeamsCreated}\n";
            echo "==============================================\n";

        } catch (Exception $e) {
            echo "Population failed: " . $e->getMessage() . "\n";
            echo "Trace: " . $e->getTraceAsString() . "\n";
        }
    }

    private function createTeamsForDepartment($department) {
        $functionCategory = $department->function_category ?? 'General';
        $teamNames = $this->getTeamNamesForFunction($functionCategory);

        $teamsCreated = 0;

        foreach ($teamNames as $teamName) {
            try {
                // Check if team already exists
                $existingTeams = PopularOrganizationTeam::all();
                $exists = false;
                foreach ($existingTeams as $existingTeam) {
                    if ($existingTeam->name === $teamName &&
                        $existingTeam->popular_organization_department_id == $department->id) {
                        $exists = true;
                        break;
                    }
                }

                if ($exists) {
                    echo "  - Skipping '{$teamName}' (already exists)\n";
                    continue;
                }

                $team = new PopularOrganizationTeam();

                // Generate team code from name
                $code = strtoupper(substr(preg_replace('/[^A-Z]/', '', $teamName), 0, 6));
                if (strlen($code) < 3) {
                    $code = strtoupper(substr(str_replace(' ', '', $teamName), 0, 6));
                }

                // Basic information
                $team->popular_organization_department_id = $department->id;
                $team->name = $teamName;
                $team->code = $code . '-' . str_pad($department->id, 3, '0', STR_PAD_LEFT);
                $team->description = "Responsible for {$teamName} activities within the {$department->name} department";

                // Team type based on name
                $team->team_type = $this->determineTeamType($teamName, $functionCategory);
                $team->function_category = $functionCategory;

                // Team characteristics
                $team->specialization = $this->determineSpecialization($teamName);
                $team->operational_status = 'Active';
                $team->priority_level = $this->determinePriorityLevel($teamName);

                // Team size
                $team->current_size = rand(3, 15);
                $team->target_size = $team->current_size + rand(0, 5);
                $team->min_size = max(2, $team->current_size - 2);
                $team->max_size = $team->current_size + 10;

                // Budget
                $team->annual_budget = rand(100000, 2000000);
                $team->budget_currency = 'USD';
                $team->budget_status = 'Approved';

                // Dates
                $team->establishment_date = date('Y-m-d', strtotime('-' . rand(30, 365) . ' days'));

                // Agile/Scrum settings for applicable teams
                if ($team->team_type === 'Scrum' || strpos($teamName, 'Development') !== false) {
                    $team->sprint_duration = '2 weeks';
                    $team->agile_framework = 'Scrum';
                    $team->standup_schedule = 'Daily 9:00 AM';
                    $team->retrospective_frequency = 'Every 2 weeks';
                }

                // Working arrangements
                $team->working_hours = '9:00 AM - 5:00 PM';
                $team->remote_work_policy = rand(0, 1) ? 'Hybrid' : 'Remote-first';
                $team->time_zone = 'UTC-5';

                // Tools and technologies
                $team->collaboration_tools = $this->getCollaborationTools();
                $team->communication_channels = 'Slack, Email, Microsoft Teams';
                $team->project_management_tools = $this->getProjectManagementTools($team->team_type);

                // Objectives and KPIs
                $team->objectives = $this->generateObjectives($teamName, $functionCategory);
                $team->kpis = $this->generateKPIs($teamName);

                // Performance metrics (random values between 0.7 and 1.0 for active teams)
                $team->performance_rating = rand(70, 100) / 100;
                $team->efficiency_ratio = rand(75, 95) / 100;
                $team->quality_excellence = rand(80, 98) / 100;

                // Culture and values
                $team->collaboration = rand(80, 100) / 100;
                $team->innovation = rand(70, 95) / 100;
                $team->accountability = rand(85, 100) / 100;

                // Metadata
                $team->maturity_level = $this->determineMaturityLevel();
                $team->complexity_level = 'Medium';
                $team->risk_level = 'Low';

                // Save team
                if ($team->save()) {
                    echo "  ✓ Created: {$teamName} (Size: {$team->current_size}, Type: {$team->team_type})\n";
                    $teamsCreated++;
                } else {
                    echo "  ✗ Failed to save: {$teamName}\n";
                }

            } catch (Exception $e) {
                echo "  ✗ Error creating team '{$teamName}': " . $e->getMessage() . "\n";
            }
        }

        return $teamsCreated;
    }

    private function getTeamNamesForFunction($functionCategory) {
        // Return specific teams based on function category
        if (isset($this->teamNames[$functionCategory])) {
            return array_slice($this->teamNames[$functionCategory], 0, rand(2, min(4, count($this->teamNames[$functionCategory]))));
        }

        // Default teams for unknown categories
        return ['Core Team', 'Operations Team'];
    }

    private function determineTeamType($teamName, $functionCategory) {
        if (strpos($teamName, 'Development') !== false || strpos($teamName, 'Engineering') !== false) {
            return 'Scrum';
        } elseif (strpos($teamName, 'DevOps') !== false) {
            return 'DevOps';
        } elseif (strpos($teamName, 'Support') !== false || strpos($teamName, 'Help') !== false) {
            return 'Support';
        } elseif (strpos($teamName, 'Research') !== false || strpos($teamName, 'Innovation') !== false) {
            return 'Research';
        } elseif (strpos($teamName, 'Product') !== false) {
            return 'Product';
        } elseif (strpos($teamName, 'Project') !== false) {
            return 'Project';
        } elseif (in_array($functionCategory, ['Technology', 'Operations'])) {
            return 'Cross-functional';
        }

        return 'Functional';
    }

    private function determineSpecialization($teamName) {
        $specializations = [
            'Development' => 'Software Development',
            'DevOps' => 'Infrastructure & Automation',
            'QA' => 'Quality Assurance & Testing',
            'Security' => 'Information Security',
            'Support' => 'Customer Service',
            'Sales' => 'Revenue Generation',
            'Marketing' => 'Brand & Growth',
            'Finance' => 'Financial Management',
            'HR' => 'Talent Management',
            'Legal' => 'Legal & Compliance'
        ];

        foreach ($specializations as $key => $value) {
            if (strpos($teamName, $key) !== false) {
                return $value;
            }
        }

        return 'General Operations';
    }

    private function determinePriorityLevel($teamName) {
        $criticalKeywords = ['Executive', 'Leadership', 'Security', 'Compliance', 'Audit'];
        $highKeywords = ['Development', 'Sales', 'Engineering', 'Operations'];

        foreach ($criticalKeywords as $keyword) {
            if (strpos($teamName, $keyword) !== false) {
                return 'Critical';
            }
        }

        foreach ($highKeywords as $keyword) {
            if (strpos($teamName, $keyword) !== false) {
                return 'High';
            }
        }

        return rand(0, 1) ? 'Medium' : 'High';
    }

    private function getCollaborationTools() {
        $tools = ['Slack', 'Microsoft Teams', 'Zoom', 'Google Meet', 'Confluence', 'Notion'];
        $selected = array_rand(array_flip($tools), rand(2, 4));
        return is_array($selected) ? implode(', ', $selected) : $selected;
    }

    private function getProjectManagementTools($teamType) {
        $tools = [
            'Scrum' => 'Jira, Azure DevOps',
            'DevOps' => 'Jira, Jenkins, GitLab',
            'Support' => 'Zendesk, Freshdesk, ServiceNow',
            'default' => 'Asana, Monday.com, Trello'
        ];

        return $tools[$teamType] ?? $tools['default'];
    }

    private function generateObjectives($teamName, $functionCategory) {
        $objectives = [
            "Deliver high-quality results aligned with organizational goals",
            "Foster innovation and continuous improvement",
            "Maintain excellent collaboration with stakeholders",
            "Achieve operational excellence and efficiency"
        ];

        // Add specific objectives based on team name
        if (strpos($teamName, 'Development') !== false) {
            $objectives[] = "Deliver features on time with minimal bugs";
            $objectives[] = "Maintain high code quality and test coverage";
        } elseif (strpos($teamName, 'Support') !== false) {
            $objectives[] = "Maintain customer satisfaction above 90%";
            $objectives[] = "Reduce average response time";
        } elseif (strpos($teamName, 'Sales') !== false) {
            $objectives[] = "Meet quarterly revenue targets";
            $objectives[] = "Expand customer base";
        }

        return implode("\n", array_slice($objectives, 0, rand(3, 5)));
    }

    private function generateKPIs($teamName) {
        $kpis = [];

        if (strpos($teamName, 'Development') !== false) {
            $kpis = [
                "Sprint Velocity: 40-50 story points",
                "Code Quality: 90% test coverage",
                "Bug Rate: < 5% per release",
                "Deployment Frequency: Weekly"
            ];
        } elseif (strpos($teamName, 'Support') !== false) {
            $kpis = [
                "Customer Satisfaction: > 90%",
                "First Response Time: < 2 hours",
                "Resolution Time: < 24 hours",
                "Ticket Volume: 100-150 per week"
            ];
        } elseif (strpos($teamName, 'Sales') !== false) {
            $kpis = [
                "Monthly Revenue: Target achievement",
                "Conversion Rate: > 20%",
                "Lead Response Time: < 1 hour",
                "Customer Retention: > 85%"
            ];
        } else {
            $kpis = [
                "Project Completion Rate: > 95%",
                "Stakeholder Satisfaction: > 85%",
                "Budget Adherence: ± 5%",
                "Quality Metrics: > 90%"
            ];
        }

        return implode("\n", $kpis);
    }

    private function determineMaturityLevel() {
        $levels = ['Initial', 'Developing', 'Defined', 'Managed', 'Optimizing'];
        $weights = [5, 15, 30, 35, 15]; // Weight towards higher maturity

        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($weights as $index => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $levels[$index];
            }
        }

        return 'Managed';
    }
}

// Run the populator
echo "\n==============================================\n";
echo "POPULAR ORGANIZATION TEAMS POPULATOR\n";
echo "==============================================\n\n";

$populator = new TeamPopulator();
$populator->populate();

echo "\nDone!\n";