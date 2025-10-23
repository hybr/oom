<?php
/**
 * WebSocket Message Handler
 * Routes and processes incoming WebSocket messages
 */

namespace V4L\WebSocket;

class MessageHandler
{
    private ConnectionManager $connectionManager;

    public function __construct(ConnectionManager $connectionManager)
    {
        $this->connectionManager = $connectionManager;
    }

    /**
     * Process incoming message from a client
     *
     * @param \Ratchet\ConnectionInterface $conn
     * @param string $message
     * @return void
     */
    public function handleMessage($conn, string $message): void
    {
        try {
            $data = json_decode($message, true);

            if (!$data || !isset($data['type'])) {
                $this->sendError($conn, 'Invalid message format');
                return;
            }

            // Route message by type
            switch ($data['type']) {
                case 'auth':
                    $this->handleAuth($conn, $data);
                    break;

                case 'entity_update':
                    $this->handleEntityUpdate($conn, $data);
                    break;

                case 'notification':
                    $this->handleNotification($conn, $data);
                    break;

                case 'presence':
                    $this->handlePresence($conn, $data);
                    break;

                case 'ping':
                    $this->handlePing($conn);
                    break;

                default:
                    $this->sendError($conn, 'Unknown message type: ' . $data['type']);
            }

        } catch (\Exception $e) {
            $this->log("Error handling message: " . $e->getMessage());
            $this->sendError($conn, 'Internal server error');
        }
    }

    /**
     * Handle authentication message
     */
    private function handleAuth($conn, array $data): void
    {
        $token = $data['token'] ?? null;

        if (!$token) {
            $this->sendError($conn, 'Authentication token required');
            return;
        }

        // Validate token and get user info
        $userInfo = $this->validateToken($token);

        if (!$userInfo) {
            $this->sendError($conn, 'Invalid authentication token');
            return;
        }

        // Store user info with connection
        $this->connectionManager->authenticateConnection($conn, $userInfo);

        // Send success response
        $this->send($conn, [
            'type' => 'auth_success',
            'user_id' => $userInfo['user_id'],
            'username' => $userInfo['username']
        ]);

        $this->log("User authenticated: {$userInfo['username']} (ID: {$userInfo['user_id']})");
    }

    /**
     * Handle entity update broadcast
     */
    private function handleEntityUpdate($conn, array $data): void
    {
        if (!$this->connectionManager->isAuthenticated($conn)) {
            $this->sendError($conn, 'Authentication required');
            return;
        }

        $entityCode = $data['entity_code'] ?? null;
        $organizationId = $data['organization_id'] ?? null;

        if (!$entityCode) {
            $this->sendError($conn, 'entity_code required');
            return;
        }

        // Broadcast to all authenticated users in the same organization
        $this->connectionManager->broadcastToOrganization($organizationId, [
            'type' => 'entity_update',
            'entity_code' => $entityCode,
            'record_id' => $data['record_id'] ?? null,
            'action' => $data['action'] ?? 'update',
            'timestamp' => time()
        ]);

        $this->log("Entity update broadcast: $entityCode");
    }

    /**
     * Handle notification message
     */
    private function handleNotification($conn, array $data): void
    {
        if (!$this->connectionManager->isAuthenticated($conn)) {
            $this->sendError($conn, 'Authentication required');
            return;
        }

        $message = $data['message'] ?? '';
        $level = $data['level'] ?? 'info';
        $targetUserId = $data['target_user_id'] ?? null;

        if ($targetUserId) {
            // Send to specific user
            $this->connectionManager->sendToUser($targetUserId, [
                'type' => 'notification',
                'message' => $message,
                'level' => $level,
                'timestamp' => time()
            ]);
        } else {
            // Broadcast to all
            $this->connectionManager->broadcast([
                'type' => 'notification',
                'message' => $message,
                'level' => $level,
                'timestamp' => time()
            ]);
        }

        $this->log("Notification sent: $message");
    }

    /**
     * Handle presence update
     */
    private function handlePresence($conn, array $data): void
    {
        if (!$this->connectionManager->isAuthenticated($conn)) {
            $this->sendError($conn, 'Authentication required');
            return;
        }

        $status = $data['status'] ?? 'online';
        $userInfo = $this->connectionManager->getUserInfo($conn);

        // Broadcast presence to all users
        $this->connectionManager->broadcast([
            'type' => 'presence',
            'user_id' => $userInfo['user_id'],
            'username' => $userInfo['username'],
            'status' => $status,
            'timestamp' => time()
        ]);

        $this->log("Presence update: {$userInfo['username']} is $status");
    }

    /**
     * Handle ping message (keep-alive)
     */
    private function handlePing($conn): void
    {
        $this->send($conn, ['type' => 'pong', 'timestamp' => time()]);
    }

    /**
     * Validate authentication token
     *
     * @param string $token
     * @return array|null User info if valid, null otherwise
     */
    private function validateToken(string $token): ?array
    {
        // This is a simple implementation
        // In production, you would validate against your session storage
        // or use JWT tokens

        // For now, extract session token from cookie-like format
        if (empty($token)) {
            return null;
        }

        // TODO: Implement proper token validation
        // This should check against your session database/storage
        // For now, we'll accept any non-empty token for development

        return [
            'user_id' => 'user_' . substr($token, 0, 8),
            'username' => 'User',
            'organization_id' => null
        ];
    }

    /**
     * Send message to a connection
     */
    private function send($conn, array $data): void
    {
        $conn->send(json_encode($data));
    }

    /**
     * Send error message to a connection
     */
    private function sendError($conn, string $error): void
    {
        $this->send($conn, [
            'type' => 'error',
            'error' => $error,
            'timestamp' => time()
        ]);
    }

    /**
     * Log message to console
     */
    private function log(string $message): void
    {
        echo "[" . date('Y-m-d H:i:s') . "] " . $message . "\n";
    }
}
