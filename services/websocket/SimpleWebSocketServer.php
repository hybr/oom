<?php

class SimpleWebSocketServer {
    private $address;
    private $port;
    private $running = false;

    public function __construct($address = '127.0.0.1', $port = 8080) {
        $this->address = $address;
        $this->port = $port;
    }

    public function start() {
        // Check if sockets extension is available
        if (!extension_loaded('sockets')) {
            echo "Error: PHP sockets extension is not installed.\n";
            echo "Alternative: Using file-based polling system instead.\n\n";
            $this->startFileBasedServer();
            return;
        }

        echo "Starting WebSocket server on {$this->address}:{$this->port}...\n";

        try {
            $this->startSocketServer();
        } catch (Exception $e) {
            echo "Socket server failed: " . $e->getMessage() . "\n";
            echo "Falling back to file-based system...\n\n";
            $this->startFileBasedServer();
        }
    }

    private function startSocketServer() {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (!$socket) {
            throw new Exception("Could not create socket");
        }

        socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

        if (!socket_bind($socket, $this->address, $this->port)) {
            throw new Exception("Could not bind to {$this->address}:{$this->port}");
        }

        if (!socket_listen($socket, 5)) {
            throw new Exception("Could not listen on socket");
        }

        echo "WebSocket server started successfully!\n";
        echo "Listening for connections...\n\n";

        $this->running = true;
        $clients = [];

        while ($this->running) {
            $read = array_merge([$socket], $clients);
            $write = null;
            $except = null;

            $activity = socket_select($read, $write, $except, 1);

            if ($activity === false) continue;

            // Handle new connections
            if (in_array($socket, $read)) {
                $newSocket = socket_accept($socket);
                if ($newSocket) {
                    $clients[] = $newSocket;
                    $this->performHandshake($newSocket);
                    echo "New client connected (" . count($clients) . " total)\n";
                }
                unset($read[array_search($socket, $read)]);
            }

            // Handle client messages
            foreach ($read as $client) {
                $bytes = @socket_recv($client, $buffer, 2048, 0);
                if ($bytes === 0 || $bytes === false) {
                    $this->disconnectClient($client, $clients);
                }
            }

            // Broadcast queued messages
            $this->processMessageQueue($clients);
        }

        socket_close($socket);
    }

    private function startFileBasedServer() {
        echo "Starting file-based real-time system...\n";
        echo "This will poll for messages and simulate WebSocket functionality.\n";
        echo "Press Ctrl+C to stop.\n\n";

        $this->running = true;
        $lastCheck = 0;

        while ($this->running) {
            $this->processMessageQueue([]);

            // Show activity indicator
            if (time() - $lastCheck > 5) {
                echo "Polling for messages... (" . date('H:i:s') . ")\n";
                $lastCheck = time();
            }

            sleep(1);
        }
    }

    private function performHandshake($socket) {
        $headers = socket_read($socket, 1024);
        $lines = explode("\r\n", $headers);

        $key = '';
        foreach ($lines as $line) {
            if (strpos($line, 'Sec-WebSocket-Key:') !== false) {
                $key = trim(substr($line, 18));
                break;
            }
        }

        $acceptKey = base64_encode(sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true));

        $response = "HTTP/1.1 101 Switching Protocols\r\n" .
                   "Upgrade: websocket\r\n" .
                   "Connection: Upgrade\r\n" .
                   "Sec-WebSocket-Accept: {$acceptKey}\r\n\r\n";

        socket_write($socket, $response, strlen($response));
    }

    private function disconnectClient($client, &$clients) {
        $index = array_search($client, $clients);
        if ($index !== false) {
            unset($clients[$index]);
            socket_close($client);
            echo "Client disconnected (" . count($clients) . " remaining)\n";
        }
    }

    private function processMessageQueue($clients) {
        $websocketFile = sys_get_temp_dir() . '/websocket_messages.json';

        if (file_exists($websocketFile)) {
            $content = file_get_contents($websocketFile);
            $messages = json_decode($content, true) ?: [];

            if (!empty($messages)) {
                foreach ($messages as $msgData) {
                    if (count($clients) > 0) {
                        $this->broadcastToClients($clients, $msgData['message']);
                    } else {
                        echo "Message ready: " . $msgData['message'] . "\n";
                    }
                }

                // Clear processed messages
                unlink($websocketFile);
            }
        }
    }

    private function broadcastToClients($clients, $message) {
        $encoded = $this->encodeMessage($message);
        foreach ($clients as $client) {
            @socket_write($client, $encoded, strlen($encoded));
        }
        echo "Broadcasted to " . count($clients) . " clients: {$message}\n";
    }

    private function encodeMessage($message) {
        $length = strlen($message);
        $firstByte = 0x81; // Text frame

        if ($length <= 125) {
            return chr($firstByte) . chr($length) . $message;
        } elseif ($length <= 65535) {
            return chr($firstByte) . chr(126) . pack('n', $length) . $message;
        } else {
            return chr($firstByte) . chr(127) . pack('J', $length) . $message;
        }
    }

    public function stop() {
        $this->running = false;
    }
}

// Signal handling for graceful shutdown
if (function_exists('pcntl_signal')) {
    pcntl_signal(SIGTERM, function() use ($server) {
        echo "\nShutting down server...\n";
        if (isset($server)) $server->stop();
        exit(0);
    });

    pcntl_signal(SIGINT, function() use ($server) {
        echo "\nShutting down server...\n";
        if (isset($server)) $server->stop();
        exit(0);
    });
}

// CLI Runner
if (php_sapi_name() == 'cli') {
    echo "=== Simple WebSocket Server ===\n\n";

    $server = new SimpleWebSocketServer();
    try {
        $server->start();
    } catch (Exception $e) {
        echo "Server error: " . $e->getMessage() . "\n";
        exit(1);
    }
}