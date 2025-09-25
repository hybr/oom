<?php

require_once 'entities/BaseEntity.php';

class Order extends BaseEntity {
    protected $table = 'orders';
    protected $fillable = ['customer', 'status', 'total', 'created_at', 'updated_at'];

    public function __construct() {
        parent::__construct();
        $this->attributes['status'] = 'draft';
        $this->attributes['created_at'] = date('Y-m-d H:i:s');
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');
    }

    public function updateStatus($status) {
        $this->status = $status;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function addItem($item, $quantity, $price) {
        $orderItem = new OrderItem();
        $orderItem->fill([
            'order_id' => $this->id,
            'item' => $item,
            'quantity' => $quantity,
            'price' => $price
        ]);
        return $orderItem->save();
    }

    public function getItems() {
        if (isset($this->attributes['id'])) {
            return OrderItem::where('order_id', '=', $this->attributes['id']);
        }
        return [];
    }

    public function calculateTotal() {
        $items = $this->getItems();
        $total = 0;
        foreach ($items as $item) {
            $total += $item->quantity * $item->price;
        }
        $this->total = $total;
        return $total;
    }

    protected function getSchema() {
        return "
            CREATE TABLE IF NOT EXISTS orders (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                customer TEXT NOT NULL,
                status TEXT DEFAULT 'draft',
                total DECIMAL(10,2) DEFAULT 0.00,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ";
    }
}