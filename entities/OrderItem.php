<?php

require_once 'entities/BaseEntity.php';

class OrderItem extends BaseEntity {
    protected $table = 'order_items';
    protected $fillable = ['order_id', 'item', 'quantity', 'price'];

    protected function getSchema() {
        return "
            CREATE TABLE IF NOT EXISTS order_items (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                order_id INTEGER NOT NULL,
                item TEXT NOT NULL,
                quantity INTEGER DEFAULT 1,
                price DECIMAL(10,2) NOT NULL,
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
            )
        ";
    }
}