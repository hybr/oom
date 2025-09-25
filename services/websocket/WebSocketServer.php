<?php

class WebSocketServer {
    private $address;
    private $port;
    private $socket;
    private $clients = [];
    private $messageQueue = [];

    public function __construct($address = '127.0.0.1', $port = 8080) {
        $this->address = $address;
        $this->port = $port;
    }

    public function start() {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 1);

        if (!socket_bind($this->socket, $this->address, $this->port)) {
            throw new Exception("Could not bind to {$this->address}:{$this->port}");
        }

        if (!socket_listen($this->socket, 5)) {
            throw new Exception("Could not listen on socket");
        }

        echo "WebSocket server started on {$this->address}:{$this->port}\n";

        while (true) {
            $this->processConnections();
            $this->broadcastQueuedMessages();
            usleep(10000); // 10ms delay
        }
    }

    private function processConnections() {
        $read = array_merge([$this->socket], $this->clients);
        $write = null;
        $except = null;

        $activity = socket_select($read, $write, $except, 0);

        if ($activity === false) {
            return;
        }

        // Handle new connections
        if (in_array($this->socket, $read)) {
            $newSocket = socket_accept($this->socket);
            if ($newSocket) {
                $this->clients[] = $newSocket;
                $this->performHandshake($newSocket);
                echo "New client connected\n";
            }

            unset($read[array_search($this->socket, $read)]);
        }

        // Handle client messages
        foreach ($read as $client) {
            $bytes = @socket_recv($client, $buffer, 2048, 0);

            if ($bytes === 0 || $bytes === false) {
                $this->disconnectClient($client);
            } else {
                $decodedMessage = $this->decode($buffer);
                if ($decodedMessage) {
                    echo "Received: {$decodedMessage}\n";
                    $this->handleMessage($client, $decodedMessage);
                }
            }
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

    private function encode($message) {
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

    private function decode($data) {
        if (empty($data)) return false;

        $bytes = unpack('C*', $data);
        $secondByte = $bytes[2];
        $masked = ($secondByte >> 7) & 1;

        if (!$masked) return false;

        $payloadLength = $secondByte & 0x7F;
        $maskStart = 3;

        if ($payloadLength === 126) {
            $payloadLength = unpack('n', substr($data, 2, 2))[1];
            $maskStart = 4;
        } elseif ($payloadLength === 127) {
            $payloadLength = unpack('J', substr($data, 2, 8))[1];
            $maskStart = 10;
        }

        $mask = substr($data, $maskStart - 1, 4);
        $payload = substr($data, $maskStart + 3);

        $decoded = '';
        for ($i = 0; $i < strlen($payload); $i++) {
            $decoded .= $payload[$i] ^ $mask[$i % 4];
        }

        return $decoded;
    }

    private function handleMessage($client, $message) {
        $data = json_decode($message, true);

        if ($data && isset($data['type'])) {
            switch ($data['type']) {
                case 'ping':
                    $this->sendToClient($client, json_encode(['type' => 'pong']));
                    break;

                case 'subscribe':
                    // Handle subscription logic
                    break;
            }
        }
    }

    private function disconnectClient($client) {
        $index = array_search($client, $this->clients);
        if ($index !== false) {
            unset($this->clients[$index]);
            socket_close($client);
            echo "Client disconnected\n";
        }
    }

    private function sendToClient($client, $message) {
        $encoded = $this->encode($message);
        @socket_write($client, $encoded, strlen($encoded));
    }

    public function broadcast($message) {
        $this->messageQueue[] = $message;
    }

    private function broadcastQueuedMessages() {
        $websocketFile = sys_get_temp_dir() . '/websocket_messages.json';

        if (file_exists($websocketFile)) {
            $content = file_get_contents($websocketFile);
            $messages = json_decode($content, true) ?: [];

            foreach ($messages as $msgData) {
                $this->broadcastToClients($msgData['message']);
            }

            // Clear processed messages
            unlink($websocketFile);
        }

        // Process internal queue
        while (!empty($this->messageQueue)) {
            $message = array_shift($this->messageQueue);
            $this->broadcastToClients($message);
        }
    }

    private function broadcastToClients($message) {
        foreach ($this->clients as $client) {
            $this->sendToClient($client, $message);
        }
        echo "Broadcasted: {$message}\n";
    }
}

// CLI Runner
if (php_sapi_name() == 'cli') {
    $server = new WebSocketServer();
    try {
        $server->start();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}