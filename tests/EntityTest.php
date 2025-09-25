<?php

require_once 'tests/TestRunner.php';
require_once 'config/database.php';
require_once 'entities/Order.php';

class EntityTest {
    public static function runTests() {
        $runner = new TestRunner();

        // Setup test database
        if (file_exists('database/test.db')) {
            unlink('database/test.db');
        }

        if (!file_exists('database')) {
            mkdir('database', 0755, true);
        }

        $runner->addTest('Database Connection', function() {
            $db = DatabaseConfig::getInstance();
            Assert::notNull($db->getConnection());
            return true;
        });

        $runner->addTest('Create Order Table', function() {
            Order::createTable();
            return true;
        });

        $runner->addTest('Create Order Entity', function() {
            $order = new Order();
            $order->customer = 'Test Customer';
            $order->total = 99.99;
            $order->save();

            Assert::notNull($order->id);
            Assert::equals('Test Customer', $order->customer);
            Assert::equals(99.99, (float)$order->total);
            return true;
        });

        $runner->addTest('Find Order Entity', function() {
            $order = new Order();
            $order->customer = 'Find Test';
            $order->total = 50.00;
            $order->save();

            $found = Order::find($order->id);
            Assert::notNull($found);
            Assert::equals('Find Test', $found->customer);
            Assert::equals(50.00, (float)$found->total);
            return true;
        });

        $runner->addTest('Update Order Entity', function() {
            $order = new Order();
            $order->customer = 'Update Test';
            $order->total = 75.00;
            $order->save();

            $order->customer = 'Updated Customer';
            $order->total = 100.00;
            $order->save();

            $updated = Order::find($order->id);
            Assert::equals('Updated Customer', $updated->customer);
            Assert::equals(100.00, (float)$updated->total);
            return true;
        });

        $runner->addTest('Delete Order Entity', function() {
            $order = new Order();
            $order->customer = 'Delete Test';
            $order->save();
            $id = $order->id;

            $order->delete();
            $deleted = Order::find($id);
            Assert::equals(null, $deleted);
            return true;
        });

        $runner->addTest('List All Orders', function() {
            // Create test orders
            for ($i = 1; $i <= 3; $i++) {
                $order = new Order();
                $order->customer = "Customer {$i}";
                $order->total = $i * 10.00;
                $order->save();
            }

            $orders = Order::all();
            Assert::true(count($orders) >= 3);
            return true;
        });

        $runner->addTest('Filter Orders', function() {
            $order = new Order();
            $order->customer = 'Filter Test';
            $order->status = 'pending';
            $order->save();

            $filtered = Order::where('status', '=', 'pending');
            Assert::true(count($filtered) >= 1);

            $found = false;
            foreach ($filtered as $o) {
                if ($o->customer === 'Filter Test') {
                    $found = true;
                    break;
                }
            }
            Assert::true($found);
            return true;
        });

        return $runner->run();
    }
}