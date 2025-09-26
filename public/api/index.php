<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../../config/database.php';
require_once '../../services/entity/EntityService.php';
require_once '../../services/process/ProcessService.php';
require_once '../../services/notifications/NotificationService.php';
require_once '../../services/reports/ReportService.php';

class ApiRouter {
    private $entityService;
    private $processService;
    private $notificationService;
    private $reportService;

    public function __construct() {
        $this->entityService = new EntityService();
        $this->processService = new ProcessService();
        $this->notificationService = new NotificationService();
        $this->reportService = new ReportService();
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = trim($_SERVER['PATH_INFO'] ?? $_SERVER['REQUEST_URI'], '/');
        $pathParts = explode('/', $path);

        try {
            switch ($pathParts[0]) {
                case 'entities':
                    return $this->handleEntityRequest($method, array_slice($pathParts, 1));

                case 'processes':
                    return $this->handleProcessRequest($method, array_slice($pathParts, 1));

                case 'notifications':
                    return $this->handleNotificationRequest($method, array_slice($pathParts, 1));

                case 'reports':
                    return $this->handleReportRequest($method, array_slice($pathParts, 1));

                default:
                    throw new Exception('Unknown endpoint', 404);
            }
        } catch (Exception $e) {
            $this->sendError($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    private function handleEntityRequest($method, $pathParts) {
        $entityType = $pathParts[0] ?? null;
        $id = $pathParts[1] ?? null;
        $action = $pathParts[2] ?? null;

        if (!$entityType) {
            throw new Exception('Entity type required', 400);
        }

        // Handle PersonCredential specific actions
        if ($entityType === 'PersonCredential' && $id && $action && $method === 'POST') {
            return $this->handlePersonCredentialAction($id, $action);
        }

        switch ($method) {
            case 'GET':
                if ($id) {
                    $result = $this->entityService->read($entityType, $id);
                    if (!$result) {
                        throw new Exception('Entity not found', 404);
                    }
                    $this->sendSuccess($result->toArray());
                } else {
                    $filters = $_GET;
                    $result = $this->entityService->list($entityType, $filters);
                    $this->sendSuccess(array_map(function($entity) {
                        return $entity->toArray();
                    }, $result));
                }
                break;

            case 'POST':
                $data = $this->getJsonInput();
                $result = $this->entityService->create($entityType, $data);
                $this->sendSuccess($result->toArray(), 201);
                break;

            case 'PUT':
                if (!$id) {
                    throw new Exception('ID required for update', 400);
                }
                $data = $this->getJsonInput();
                $result = $this->entityService->update($entityType, $id, $data);
                $this->sendSuccess($result->toArray());
                break;

            case 'DELETE':
                if (!$id) {
                    throw new Exception('ID required for delete', 400);
                }
                $this->entityService->delete($entityType, $id);
                $this->sendSuccess(['message' => 'Entity deleted successfully']);
                break;

            default:
                throw new Exception('Method not allowed', 405);
        }
    }

    private function handlePersonCredentialAction($id, $action) {
        require_once __DIR__ . '/../../entities/PersonCredential.php';
        $credential = PersonCredential::find($id);

        if (!$credential) {
            throw new Exception('User account not found', 404);
        }

        $data = $this->getJsonInput();

        switch ($action) {
            case 'reset-password':
                if (!isset($data['new_password'])) {
                    throw new Exception('New password required', 400);
                }
                $credential->setPassword($data['new_password']);
                $credential->resetLoginAttempts();
                $credential->save();
                $this->sendSuccess(['message' => 'Password reset successfully']);
                break;

            case 'activate':
                $credential->activate();
                $this->sendSuccess(['message' => 'Account activated successfully']);
                break;

            case 'deactivate':
                $credential->deactivate();
                $this->sendSuccess(['message' => 'Account deactivated successfully']);
                break;

            case 'unlock':
                $credential->resetLoginAttempts();
                $credential->save();
                $this->sendSuccess(['message' => 'Account unlocked successfully']);
                break;

            default:
                throw new Exception('Unknown action', 400);
        }
    }

    private function handleProcessRequest($method, $pathParts) {
        $processType = $pathParts[0] ?? null;
        $entityId = $pathParts[1] ?? null;
        $action = $pathParts[2] ?? null;

        if (!$processType || !$entityId) {
            throw new Exception('Process type and entity ID required', 400);
        }

        switch ($method) {
            case 'GET':
                switch ($action) {
                    case 'state':
                        $state = $this->processService->getCurrentState($processType, $entityId);
                        $this->sendSuccess(['state' => $state]);
                        break;

                    case 'history':
                        $history = $this->processService->getHistory($processType, $entityId);
                        $this->sendSuccess($history);
                        break;

                    case 'transitions':
                        $role = $_GET['role'] ?? null;
                        $transitions = $this->processService->getAvailableTransitions($processType, $entityId, $role);
                        $this->sendSuccess(['transitions' => $transitions]);
                        break;

                    default:
                        $state = $this->processService->getCurrentState($processType, $entityId);
                        $this->sendSuccess(['state' => $state]);
                }
                break;

            case 'POST':
                $data = $this->getJsonInput();
                $toState = $data['to_state'] ?? null;
                $role = $data['role'] ?? null;
                $note = $data['note'] ?? '';

                if (!$toState) {
                    throw new Exception('Target state required', 400);
                }

                if ($action === 'rollback') {
                    $steps = $data['steps'] ?? 1;
                    $this->processService->rollback($processType, $entityId, $steps);
                    $this->sendSuccess(['message' => 'Rollback successful']);
                } else {
                    $this->processService->transition($processType, $entityId, $toState, $role, $note);
                    $this->sendSuccess(['message' => 'Transition successful']);
                }
                break;

            default:
                throw new Exception('Method not allowed', 405);
        }
    }

    private function handleNotificationRequest($method, $pathParts) {
        switch ($method) {
            case 'GET':
                $limit = $_GET['limit'] ?? 50;
                $entityId = $_GET['entity_id'] ?? null;

                if ($entityId) {
                    $entityType = $_GET['entity_type'] ?? 'order';
                    $notifications = $this->notificationService->getNotificationsForEntity($entityType, $entityId, $limit);
                } else {
                    $notifications = $this->notificationService->getRecentNotifications($limit);
                }

                $this->sendSuccess($notifications);
                break;

            default:
                throw new Exception('Method not allowed', 405);
        }
    }

    private function handleReportRequest($method, $pathParts) {
        switch ($method) {
            case 'GET':
                $reportType = $pathParts[0] ?? null;

                if (!$reportType) {
                    $reports = $this->reportService->getAvailableReports();
                    $this->sendSuccess(['reports' => $reports]);
                } else {
                    $params = $_GET;
                    $report = $this->reportService->generateReport($reportType, $params);
                    $this->sendSuccess($report);
                }
                break;

            default:
                throw new Exception('Method not allowed', 405);
        }
    }

    private function getJsonInput() {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON input', 400);
        }

        return $data;
    }

    private function sendSuccess($data, $code = 200) {
        http_response_code($code);
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
        exit();
    }

    private function sendError($message, $code = 500) {
        http_response_code($code);
        echo json_encode([
            'success' => false,
            'error' => $message
        ]);
        exit();
    }
}

$router = new ApiRouter();
$router->handleRequest();