<?php

require_once __DIR__ . '/BaseProcess.php';
require_once __DIR__ . '/../entities/Order.php';

class OrderProcess extends BaseProcess {
    protected $processName = 'order';

    public function __construct($entityId = null) {
        parent::__construct($entityId);

        $this->allowedTransitions = [
            'draft' => [
                'pending' => [
                    'roles' => ['customer', 'admin'],
                    'condition' => function($entityId) {
                        $order = Order::find($entityId);
                        return $order && $order->total > 0;
                    }
                ]
            ],
            'pending' => [
                'paid' => [
                    'roles' => ['admin', 'payment_processor']
                ],
                'cancelled' => [
                    'roles' => ['customer', 'admin']
                ]
            ],
            'paid' => [
                'shipped' => [
                    'roles' => ['admin', 'warehouse']
                ],
                'refunded' => [
                    'roles' => ['admin']
                ]
            ],
            'shipped' => [
                'delivered' => [
                    'roles' => ['delivery', 'customer']
                ],
                'returned' => [
                    'roles' => ['customer', 'admin']
                ]
            ],
            'delivered' => [
                'closed' => [
                    'roles' => ['admin']
                ]
            ]
        ];

        $this->stateCallbacks = [
            'pending' => [$this, 'onPending'],
            'paid' => [$this, 'onPaid'],
            'shipped' => [$this, 'onShipped'],
            'delivered' => [$this, 'onDelivered'],
            'cancelled' => [$this, 'onCancelled'],
            'refunded' => [$this, 'onRefunded']
        ];
    }

    protected function getInitialState() {
        return 'draft';
    }

    protected function updateEntityState($state) {
        $order = Order::find($this->entityId);
        if ($order) {
            $order->updateStatus($state);
        }
    }

    public function onPending($entityId, $fromState, $toState) {
        $this->sendNotification($entityId, "Order #{$entityId} is now pending payment");
    }

    public function onPaid($entityId, $fromState, $toState) {
        $this->sendNotification($entityId, "Payment received for order #{$entityId}");
    }

    public function onShipped($entityId, $fromState, $toState) {
        $this->sendNotification($entityId, "Order #{$entityId} has been shipped");
    }

    public function onDelivered($entityId, $fromState, $toState) {
        $this->sendNotification($entityId, "Order #{$entityId} has been delivered");
    }

    public function onCancelled($entityId, $fromState, $toState) {
        $this->sendNotification($entityId, "Order #{$entityId} has been cancelled");
    }

    public function onRefunded($entityId, $fromState, $toState) {
        $this->sendNotification($entityId, "Order #{$entityId} has been refunded");
    }

    private function sendNotification($entityId, $message) {
        require_once 'services/notifications/NotificationService.php';
        $notificationService = new NotificationService();
        $notificationService->broadcast([
            'type' => 'order_status_change',
            'entity_id' => $entityId,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    public static function getStateFlow() {
        return [
            'draft' => ['pending'],
            'pending' => ['paid', 'cancelled'],
            'paid' => ['shipped', 'refunded'],
            'shipped' => ['delivered', 'returned'],
            'delivered' => ['closed'],
            'cancelled' => [],
            'refunded' => [],
            'returned' => [],
            'closed' => []
        ];
    }
}