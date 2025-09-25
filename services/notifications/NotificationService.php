<?php

class NotificationService {
    private $subscribers = [];

    public function broadcast($data) {
        $this->logNotification($data);
        $this->sendWebSocketMessage($data);
    }

    private function logNotification($data) {
        $db = DatabaseConfig::getInstance();
        $db->execute("
            INSERT INTO notifications (type, entity_id, message, data, created_at)
            VALUES (?, ?, ?, ?, ?)
        ", [
            $data['type'] ?? 'general',
            $data['entity_id'] ?? null,
            $data['message'] ?? '',
            json_encode($data),
            date('Y-m-d H:i:s')
        ]);
    }

    private function sendWebSocketMessage($data) {
        $message = json_encode([
            'type' => 'notification',
            'data' => $data
        ]);

        $websocketFile = sys_get_temp_dir() . '/websocket_messages.json';

        $messages = [];
        if (file_exists($websocketFile)) {
            $content = file_get_contents($websocketFile);
            $messages = json_decode($content, true) ?: [];
        }

        $messages[] = [
            'message' => $message,
            'timestamp' => time()
        ];

        $messages = array_filter($messages, function($msg) {
            return (time() - $msg['timestamp']) < 300;
        });

        file_put_contents($websocketFile, json_encode($messages));
    }

    public function getRecentNotifications($limit = 50) {
        $db = DatabaseConfig::getInstance();
        return $db->fetchAll("
            SELECT * FROM notifications
            ORDER BY created_at DESC
            LIMIT ?
        ", [$limit]);
    }

    public function getNotificationsForEntity($entityType, $entityId, $limit = 20) {
        $db = DatabaseConfig::getInstance();
        return $db->fetchAll("
            SELECT * FROM notifications
            WHERE entity_id = ?
            ORDER BY created_at DESC
            LIMIT ?
        ", [$entityId, $limit]);
    }

    public static function createTable() {
        $db = DatabaseConfig::getInstance();
        $db->execute("
            CREATE TABLE IF NOT EXISTS notifications (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                type TEXT NOT NULL,
                entity_id INTEGER,
                message TEXT,
                data TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }
}