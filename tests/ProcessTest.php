<?php

require_once 'tests/TestRunner.php';
require_once 'config/database.php';
require_once 'entities/Order.php';
require_once 'process/OrderProcess.php';
require_once 'process/BaseProcess.php';

class ProcessTest {
    public static function runTests() {
        $runner = new TestRunner();

        // Setup
        $runner->addTest('Setup Process Tables', function() {
            BaseProcess::createTables();
            Order::createTable();
            return true;
        });

        $runner->addTest('Create Order Process', function() {
            $order = new Order();
            $order->customer = 'Process Test';
            $order->total = 100.00;
            $order->save();

            $process = new OrderProcess($order->id);
            Assert::equals('draft', $process->getCurrentState());
            return true;
        });

        $runner->addTest('Valid State Transition', function() {
            $order = new Order();
            $order->customer = 'Transition Test';
            $order->total = 50.00;
            $order->save();

            $process = new OrderProcess($order->id);
            Assert::true($process->canTransition('pending', 'admin'));

            $result = $process->transition('pending', 'admin', 'Test transition');
            Assert::true($result);
            Assert::equals('pending', $process->getCurrentState());
            return true;
        });

        $runner->addTest('Invalid State Transition', function() {
            $order = new Order();
            $order->customer = 'Invalid Test';
            $order->save();

            $process = new OrderProcess($order->id);
            Assert::false($process->canTransition('shipped', 'admin'));

            Assert::throws(function() use ($process) {
                $process->transition('shipped', 'admin');
            });
            return true;
        });

        $runner->addTest('Role-based Transition Control', function() {
            $order = new Order();
            $order->customer = 'Role Test';
            $order->total = 100.00;
            $order->save();

            $process = new OrderProcess($order->id);
            $process->transition('pending', 'admin');

            Assert::true($process->canTransition('paid', 'admin'));
            Assert::false($process->canTransition('paid', 'customer'));
            return true;
        });

        $runner->addTest('Process History Tracking', function() {
            $order = new Order();
            $order->customer = 'History Test';
            $order->total = 75.00;
            $order->save();

            $process = new OrderProcess($order->id);
            $process->transition('pending', 'admin', 'First transition');
            $process->transition('paid', 'admin', 'Second transition');

            $history = $process->getHistory();
            Assert::true(count($history) >= 3); // Initial + 2 transitions
            Assert::equals('paid', $history[0]['to_state']); // Most recent first
            return true;
        });

        $runner->addTest('Process Rollback', function() {
            $order = new Order();
            $order->customer = 'Rollback Test';
            $order->total = 100.00;
            $order->save();

            $process = new OrderProcess($order->id);
            $process->transition('pending', 'admin');
            $process->transition('paid', 'admin');
            Assert::equals('paid', $process->getCurrentState());

            $process->rollback(1);
            Assert::equals('pending', $process->getCurrentState());
            return true;
        });

        $runner->addTest('Condition-based Transitions', function() {
            // Order with no total should not be able to go to pending
            $order = new Order();
            $order->customer = 'Condition Test';
            $order->total = 0.00;
            $order->save();

            $process = new OrderProcess($order->id);
            Assert::false($process->canTransition('pending', 'admin'));

            // Order with total should be able to go to pending
            $order->total = 50.00;
            $order->save();

            $process = new OrderProcess($order->id);
            Assert::true($process->canTransition('pending', 'admin'));
            return true;
        });

        return $runner->run();
    }
}