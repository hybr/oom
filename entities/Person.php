<?php

require_once __DIR__ . '/BaseEntity.php';

class Person extends BaseEntity {
    protected $table = 'persons';
    protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'date_of_birth', 'status', 'created_at', 'updated_at'];

    public function __construct() {
        parent::__construct();
        $this->attributes['status'] = 'active';
        $this->attributes['created_at'] = date('Y-m-d H:i:s');
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');
    }

    public function getFullName() {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function updateStatus($status) {
        $this->status = $status;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function getAge() {
        if ($this->date_of_birth) {
            $birthDate = new DateTime($this->date_of_birth);
            $today = new DateTime();
            return $today->diff($birthDate)->y;
        }
        return null;
    }

    public function isActive() {
        return $this->status === 'active';
    }

    public function deactivate() {
        return $this->updateStatus('inactive');
    }

    public function activate() {
        return $this->updateStatus('active');
    }

    public static function findByEmail($email) {
        return static::where('email', '=', $email);
    }

    public static function findByPhone($phone) {
        return static::where('phone', '=', $phone);
    }

    protected function getSchema() {
        return "
            CREATE TABLE IF NOT EXISTS persons (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                first_name TEXT NOT NULL,
                last_name TEXT NOT NULL,
                email TEXT UNIQUE NOT NULL,
                phone TEXT,
                date_of_birth DATE,
                status TEXT DEFAULT 'active',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ";
    }
}