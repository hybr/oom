<?php

require_once 'config/database.php';
require_once 'entities/Order.php';
require_once 'entities/OrderItem.php';
require_once 'process/BaseProcess.php';

class DatabaseMigration {
    private $db;

    public function __construct() {
        $this->db = DatabaseConfig::getInstance();
    }

    public function migrate() {
        echo "Starting database migration...\n";

        try {
            if (!file_exists('database')) {
                mkdir('database', 0755, true);
            }

            $this->createProcessTables();
            $this->createEntityTables();

            echo "Migration completed successfully!\n";
        } catch (Exception $e) {
            echo "Migration failed: " . $e->getMessage() . "\n";
        }
    }

    private function createProcessTables() {
        echo "Creating process tables...\n";
        BaseProcess::createTables();
    }

    private function createEntityTables() {
        echo "Creating entity tables...\n";
        Order::createTable();
        OrderItem::createTable();
    }

    public function seed() {
        echo "Seeding sample data...\n";

        $sampleOrders = [
            ['customer' => 'John Doe', 'status' => 'draft', 'total' => 99.99],
            ['customer' => 'Jane Smith', 'status' => 'pending', 'total' => 149.50],
            ['customer' => 'Bob Johnson', 'status' => 'paid', 'total' => 75.25],
            ['customer' => 'Alice Brown', 'status' => 'shipped', 'total' => 200.00],
            ['customer' => 'Charlie Wilson', 'status' => 'delivered', 'total' => 125.75]
        ];

        foreach ($sampleOrders as $orderData) {
            $order = new Order();
            $order->fill($orderData);
            $order->save();

            $order->addItem('Product A', 2, 25.00);
            $order->addItem('Product B', 1, $orderData['total'] - 50.00);

            echo "Created order #{$order->id} for {$order->customer}\n";
        }
    }
}

if (php_sapi_name() == 'cli') {
    $migration = new DatabaseMigration();
    $migration->migrate();

    if (isset($argv[1]) && $argv[1] === '--seed') {
        $migration->seed();
    }
}