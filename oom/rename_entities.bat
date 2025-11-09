@echo off
REM Rename entity files with 4-digit sequence prefixes based on foreign key dependencies

cd "C:\Users\fwyog\oom\metadata\entities"

REM Level 0 - No Dependencies (Base Tables)
ren "continent.sql" "0001_continent.sql"
ren "language.sql" "0002_language.sql"
ren "currency.sql" "0003_currency.sql"
ren "timezone.sql" "0004_timezone.sql"
ren "enum_education_level.sql" "0005_enum_education_level.sql"
ren "enum_marks_type.sql" "0006_enum_marks_type.sql"
ren "enum_skill_level.sql" "0007_enum_skill_level.sql"
ren "popular_skill.sql" "0008_popular_skill.sql"
ren "enum_gender.sql" "0009_enum_gender.sql"
ren "enum_blood_group.sql" "0010_enum_blood_group.sql"
ren "popular_education_subject.sql" "0011_popular_education_subject.sql"
ren "popular_organization_departments.sql" "0012_popular_organization_departments.sql"
ren "popular_organization_designation.sql" "0013_popular_organization_designation.sql"
ren "popular_industry_category.sql" "0014_popular_industry_category.sql"
ren "interview_stage.sql" "0015_interview_stage.sql"
ren "enum_entity_permission_type.sql" "0016_enum_entity_permission_type.sql"
ren "enum_storage_provider.sql" "0017_enum_storage_provider.sql"
ren "enum_file_category.sql" "0018_enum_file_category.sql"
ren "enum_media_context.sql" "0019_enum_media_context.sql"

REM Level 1 - Depends on Level 0
ren "country.sql" "0020_country.sql"
ren "popular_organization_legal_types.sql" "0021_popular_organization_legal_types.sql"
ren "popular_organization_department_teams.sql" "0022_popular_organization_department_teams.sql"

REM Level 2 - Depends on Level 1
ren "state.sql" "0023_state.sql"
ren "popular_organization_position.sql" "0024_popular_organization_position.sql"

REM Level 3 - Depends on Level 2
ren "district.sql" "0025_district.sql"
ren "popular_organization_position_skill.sql" "0026_popular_organization_position_skill.sql"
ren "popular_organization_position_education.sql" "0027_popular_organization_position_education.sql"

REM Level 4 - Depends on Level 3
ren "city.sql" "0028_city.sql"
ren "popular_organization_position_education_subject.sql" "0029_popular_organization_position_education_subject.sql"

REM Level 5 - Depends on Level 4
ren "postal_address.sql" "0030_postal_address.sql"

REM Level 6 - Person Entity
ren "person.sql" "0031_person.sql"

REM Level 7 - Depends on Person
ren "person_credential.sql" "0032_person_credential.sql"
ren "person_skill.sql" "0033_person_skill.sql"
ren "organization.sql" "0034_organization.sql"
ren "person_education.sql" "0035_person_education.sql"

REM Level 8 - Depends on Organization and Person
ren "person_education_subject.sql" "0036_person_education_subject.sql"
ren "organization_admin.sql" "0037_organization_admin.sql"
ren "organization_branch.sql" "0038_organization_branch.sql"
ren "process_fallback_assignment.sql" "0039_process_fallback_assignment.sql"
ren "entity_permission_definition.sql" "0040_entity_permission_definition.sql"
ren "media_file.sql" "0041_media_file.sql"

REM Level 9 - Depends on Organization Branch
ren "organization_building.sql" "0042_organization_building.sql"
ren "media_file_access_log.sql" "0043_media_file_access_log.sql"
ren "process_graph.sql" "0044_process_graph.sql"

REM Level 10 - Depends on Building and Process Graph
ren "workstation.sql" "0045_workstation.sql"
ren "process_node.sql" "0046_process_node.sql"

REM Level 11 - Depends on Process Nodes and Vacancies
ren "organization_vacancy.sql" "0047_organization_vacancy.sql"
ren "process_edge.sql" "0048_process_edge.sql"
ren "task_flow_instance.sql" "0049_task_flow_instance.sql"

REM Level 12 - Depends on Edges and Vacancies
ren "process_edge_condition.sql" "0050_process_edge_condition.sql"
ren "organization_vacancy_workstation.sql" "0051_organization_vacancy_workstation.sql"
ren "vacancy_application.sql" "0052_vacancy_application.sql"
ren "task_instance.sql" "0053_task_instance.sql"

REM Level 13 - Depends on Applications and Tasks
ren "application_review.sql" "0054_application_review.sql"
ren "application_interview.sql" "0055_application_interview.sql"
ren "task_audit_log.sql" "0056_task_audit_log.sql"

REM Level 14 - Final Level
ren "job_offer.sql" "0057_job_offer.sql"
ren "employment_contract.sql" "0058_employment_contract.sql"

echo All entity files have been renamed with 4-digit sequence prefixes!
