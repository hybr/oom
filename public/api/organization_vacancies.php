<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../entities/OrganizationVacancy.php';
require_once __DIR__ . '/../../entities/OrganizationPosition.php';
require_once __DIR__ . '/../../entities/OrganizationWorkstation.php';
require_once __DIR__ . '/../../entities/Organization.php';

$method = $_SERVER['REQUEST_METHOD'];
$response = ['success' => false, 'message' => '', 'data' => null];

try {
    switch ($method) {
        case 'GET':
            if (isset($_GET['id'])) {
                // Get single vacancy
                $vacancy = OrganizationVacancy::find($_GET['id']);
                if ($vacancy) {
                    $response['success'] = true;
                    $response['data'] = $vacancy->toArray();
                } else {
                    $response['message'] = 'Vacancy not found';
                }
            } elseif (isset($_GET['action'])) {
                // Handle specific actions
                switch ($_GET['action']) {
                    case 'active':
                        $response['success'] = true;
                        $response['data'] = array_map(function($v) {
                            return $v->toArray();
                        }, OrganizationVacancy::findActive());
                        break;

                    case 'open':
                        $response['success'] = true;
                        $response['data'] = array_map(function($v) {
                            return $v->toArray();
                        }, OrganizationVacancy::findOpen());
                        break;

                    case 'published':
                        $response['success'] = true;
                        $response['data'] = array_map(function($v) {
                            return $v->toArray();
                        }, OrganizationVacancy::findPublished());
                        break;

                    case 'featured':
                        $response['success'] = true;
                        $response['data'] = array_map(function($v) {
                            return $v->toArray();
                        }, OrganizationVacancy::findFeatured());
                        break;

                    case 'urgent':
                        $response['success'] = true;
                        $response['data'] = array_map(function($v) {
                            return $v->toArray();
                        }, OrganizationVacancy::findUrgent());
                        break;

                    case 'expiring':
                        $days = isset($_GET['days']) ? intval($_GET['days']) : 7;
                        $response['success'] = true;
                        $response['data'] = array_map(function($v) {
                            return $v->toArray();
                        }, OrganizationVacancy::findExpiring($days));
                        break;

                    case 'by_organization':
                        if (isset($_GET['organization_id'])) {
                            $response['success'] = true;
                            $response['data'] = array_map(function($v) {
                                return $v->toArray();
                            }, OrganizationVacancy::findByOrganization($_GET['organization_id']));
                        } else {
                            $response['message'] = 'Organization ID required';
                        }
                        break;

                    case 'by_position':
                        if (isset($_GET['position_id'])) {
                            $response['success'] = true;
                            $response['data'] = array_map(function($v) {
                                return $v->toArray();
                            }, OrganizationVacancy::findByPosition($_GET['position_id']));
                        } else {
                            $response['message'] = 'Position ID required';
                        }
                        break;

                    case 'search':
                        if (isset($_GET['q'])) {
                            $response['success'] = true;
                            $response['data'] = array_map(function($v) {
                                return $v->toArray();
                            }, OrganizationVacancy::searchVacancies($_GET['q']));
                        } else {
                            $response['message'] = 'Search query required';
                        }
                        break;

                    case 'positions':
                        // Get all positions for dropdown
                        $response['success'] = true;
                        $response['data'] = array_map(function($p) {
                            return [
                                'id' => $p->id,
                                'position_title' => $p->position_title,
                                'position_code' => $p->position_code,
                                'department_name' => $p->getDepartmentName(),
                                'designation_name' => $p->getDesignationName()
                            ];
                        }, OrganizationPosition::all());
                        break;

                    case 'organizations':
                        // Get all organizations for dropdown
                        $response['success'] = true;
                        $response['data'] = array_map(function($o) {
                            return [
                                'id' => $o->id,
                                'name' => $o->name,
                                'code' => $o->code
                            ];
                        }, Organization::all());
                        break;

                    case 'workstations':
                        // Get available workstations
                        $buildingId = isset($_GET['building_id']) ? $_GET['building_id'] : null;
                        $response['success'] = true;
                        $response['data'] = array_map(function($w) {
                            return [
                                'id' => $w->id,
                                'seat_code' => $w->seat_code,
                                'workstation_type' => $w->workstation_type,
                                'floor_number' => $w->floor_number,
                                'zone_area' => $w->zone_area,
                                'occupancy_status' => $w->occupancy_status,
                                'location' => $w->getLocationDescription()
                            ];
                        }, OrganizationWorkstation::getAvailableWorkstations($buildingId));
                        break;

                    default:
                        $response['message'] = 'Unknown action';
                }
            } else {
                // Get all vacancies
                $response['success'] = true;
                $response['data'] = array_map(function($v) {
                    return $v->toArray();
                }, OrganizationVacancy::all());
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);

            if (isset($data['action'])) {
                // Handle actions on existing vacancies
                if (!isset($data['id'])) {
                    $response['message'] = 'Vacancy ID required';
                    break;
                }

                $vacancy = OrganizationVacancy::find($data['id']);
                if (!$vacancy) {
                    $response['message'] = 'Vacancy not found';
                    break;
                }

                switch ($data['action']) {
                    case 'activate':
                        if ($vacancy->activate()) {
                            $response['success'] = true;
                            $response['message'] = 'Vacancy activated successfully';
                            $response['data'] = $vacancy->toArray();
                        }
                        break;

                    case 'deactivate':
                        if ($vacancy->deactivate()) {
                            $response['success'] = true;
                            $response['message'] = 'Vacancy deactivated successfully';
                            $response['data'] = $vacancy->toArray();
                        }
                        break;

                    case 'approve':
                        if ($vacancy->approve()) {
                            $response['success'] = true;
                            $response['message'] = 'Vacancy approved successfully';
                            $response['data'] = $vacancy->toArray();
                        }
                        break;

                    case 'post':
                        if ($vacancy->post()) {
                            $response['success'] = true;
                            $response['message'] = 'Vacancy posted successfully';
                            $response['data'] = $vacancy->toArray();
                        }
                        break;

                    case 'hold':
                        if ($vacancy->hold()) {
                            $response['success'] = true;
                            $response['message'] = 'Vacancy put on hold';
                            $response['data'] = $vacancy->toArray();
                        }
                        break;

                    case 'close':
                        if ($vacancy->close()) {
                            $response['success'] = true;
                            $response['message'] = 'Vacancy closed successfully';
                            $response['data'] = $vacancy->toArray();
                        }
                        break;

                    case 'cancel':
                        if ($vacancy->cancel()) {
                            $response['success'] = true;
                            $response['message'] = 'Vacancy cancelled successfully';
                            $response['data'] = $vacancy->toArray();
                        }
                        break;

                    case 'mark_filled':
                        if ($vacancy->markAsFilled()) {
                            $response['success'] = true;
                            $response['message'] = 'Vacancy marked as filled';
                            $response['data'] = $vacancy->toArray();
                        }
                        break;

                    case 'publish':
                        if ($vacancy->publish()) {
                            $response['success'] = true;
                            $response['message'] = 'Vacancy published successfully';
                            $response['data'] = $vacancy->toArray();
                        }
                        break;

                    case 'unpublish':
                        if ($vacancy->unpublish()) {
                            $response['success'] = true;
                            $response['message'] = 'Vacancy unpublished successfully';
                            $response['data'] = $vacancy->toArray();
                        }
                        break;

                    case 'mark_featured':
                        if ($vacancy->markAsFeatured()) {
                            $response['success'] = true;
                            $response['message'] = 'Vacancy marked as featured';
                            $response['data'] = $vacancy->toArray();
                        }
                        break;

                    case 'mark_urgent':
                        if ($vacancy->markAsUrgent()) {
                            $response['success'] = true;
                            $response['message'] = 'Vacancy marked as urgent';
                            $response['data'] = $vacancy->toArray();
                        }
                        break;

                    case 'renew':
                        $days = isset($data['days']) ? intval($data['days']) : null;
                        if ($vacancy->renew($days)) {
                            $response['success'] = true;
                            $response['message'] = 'Vacancy renewed successfully';
                            $response['data'] = $vacancy->toArray();
                        }
                        break;

                    case 'increment_view':
                        if ($vacancy->incrementViewCount()) {
                            $response['success'] = true;
                            $response['data'] = $vacancy->toArray();
                        }
                        break;

                    case 'increment_click':
                        if ($vacancy->incrementClickCount()) {
                            $response['success'] = true;
                            $response['data'] = $vacancy->toArray();
                        }
                        break;

                    case 'increment_application':
                        if ($vacancy->incrementApplicationCount()) {
                            $response['success'] = true;
                            $response['message'] = 'Application count updated';
                            $response['data'] = $vacancy->toArray();
                        }
                        break;

                    case 'add_workstation':
                        if (isset($data['workstation_id'])) {
                            if ($vacancy->addWorkstation($data['workstation_id'])) {
                                $response['success'] = true;
                                $response['message'] = 'Workstation added successfully';
                                $response['data'] = $vacancy->toArray();
                            }
                        } else {
                            $response['message'] = 'Workstation ID required';
                        }
                        break;

                    case 'remove_workstation':
                        if (isset($data['workstation_id'])) {
                            if ($vacancy->removeWorkstation($data['workstation_id'])) {
                                $response['success'] = true;
                                $response['message'] = 'Workstation removed successfully';
                                $response['data'] = $vacancy->toArray();
                            }
                        } else {
                            $response['message'] = 'Workstation ID required';
                        }
                        break;

                    default:
                        $response['message'] = 'Unknown action';
                }
            } else {
                // Create new vacancy
                $vacancy = new OrganizationVacancy();

                // Validate required fields
                $errors = [];
                if (empty($data['vacancy_title'])) {
                    $errors[] = 'Vacancy title is required';
                }
                if (empty($data['organization_id'])) {
                    $errors[] = 'Organization is required';
                }
                if (empty($data['organization_position_id'])) {
                    $errors[] = 'Position is required';
                }

                if (!empty($errors)) {
                    $response['message'] = implode(', ', $errors);
                    break;
                }

                // Fill vacancy data
                foreach ($data as $key => $value) {
                    if (in_array($key, $vacancy->fillable)) {
                        $vacancy->$key = $value;
                    }
                }

                // Validate
                $validationErrors = $vacancy->validate();
                if (!empty($validationErrors)) {
                    $response['message'] = implode(', ', $validationErrors);
                } else {
                    if ($vacancy->save()) {
                        $response['success'] = true;
                        $response['message'] = 'Vacancy created successfully';
                        $response['data'] = $vacancy->toArray();
                    } else {
                        $response['message'] = 'Failed to create vacancy';
                    }
                }
            }
            break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);

            if (!isset($data['id'])) {
                $response['message'] = 'Vacancy ID is required';
                break;
            }

            $vacancy = OrganizationVacancy::find($data['id']);
            if (!$vacancy) {
                $response['message'] = 'Vacancy not found';
                break;
            }

            // Update vacancy data
            foreach ($data as $key => $value) {
                if ($key !== 'id' && in_array($key, $vacancy->fillable)) {
                    $vacancy->$key = $value;
                }
            }

            // Validate
            $validationErrors = $vacancy->validate();
            if (!empty($validationErrors)) {
                $response['message'] = implode(', ', $validationErrors);
            } else {
                if ($vacancy->save()) {
                    $response['success'] = true;
                    $response['message'] = 'Vacancy updated successfully';
                    $response['data'] = $vacancy->toArray();
                } else {
                    $response['message'] = 'Failed to update vacancy';
                }
            }
            break;

        case 'DELETE':
            if (!isset($_GET['id'])) {
                $response['message'] = 'Vacancy ID is required';
                break;
            }

            $vacancy = OrganizationVacancy::find($_GET['id']);
            if (!$vacancy) {
                $response['message'] = 'Vacancy not found';
                break;
            }

            if ($vacancy->delete()) {
                $response['success'] = true;
                $response['message'] = 'Vacancy deleted successfully';
            } else {
                $response['message'] = 'Failed to delete vacancy';
            }
            break;

        default:
            $response['message'] = 'Method not allowed';
    }
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
?>
