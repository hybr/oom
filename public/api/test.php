<?php
header('Content-Type: application/json');

try {
    echo json_encode([
        'success' => true,
        'message' => 'API test successful',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>