<?php

require_once __DIR__ . '/BaseEntity.php';

class PersonCredential extends BaseEntity {
    protected $table = 'person_credentials';
    protected $fillable = [
        'id',
        'person_id',
        'username',
        'password_hash',
        'security_question_1',
        'security_answer_1_hash',
        'security_question_2',
        'security_answer_2_hash',
        'security_question_3',
        'security_answer_3_hash',
        'last_login',
        'login_attempts',
        'locked_until',
        'password_reset_token',
        'password_reset_expires',
        'is_active',
        'created_at',
        'updated_at'
    ];

    public function __construct() {
        parent::__construct();
        $this->attributes['is_active'] = 1;
        $this->attributes['login_attempts'] = 0;
        $this->attributes['created_at'] = date('Y-m-d H:i:s');
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');
    }

    public function setPassword($password) {
        $this->password_hash = password_hash($password, PASSWORD_DEFAULT);
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    public function verifyPassword($password) {
        return password_verify($password, $this->password_hash);
    }

    public function setSecurityQuestion($questionNumber, $question, $answer) {
        if ($questionNumber < 1 || $questionNumber > 3) {
            throw new InvalidArgumentException('Question number must be between 1 and 3');
        }

        $questionField = "security_question_{$questionNumber}";
        $answerField = "security_answer_{$questionNumber}_hash";

        $this->$questionField = $question;
        $this->$answerField = password_hash(strtolower(trim($answer)), PASSWORD_DEFAULT);
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    public function verifySecurityAnswer($questionNumber, $answer) {
        if ($questionNumber < 1 || $questionNumber > 3) {
            return false;
        }

        $answerField = "security_answer_{$questionNumber}_hash";
        $storedHash = $this->$answerField;

        if (!$storedHash) {
            return false;
        }

        return password_verify(strtolower(trim($answer)), $storedHash);
    }

    public function login($username, $password) {
        if (!$this->isActive()) {
            return ['success' => false, 'message' => 'Account is inactive'];
        }

        if ($this->isLocked()) {
            return ['success' => false, 'message' => 'Account is temporarily locked'];
        }

        if (!$this->verifyPassword($password)) {
            $this->incrementLoginAttempts();
            return ['success' => false, 'message' => 'Invalid credentials'];
        }

        $this->resetLoginAttempts();
        $this->last_login = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
        $this->save();

        return ['success' => true, 'message' => 'Login successful'];
    }

    public function resetPassword($securityAnswers, $newPassword) {
        $correctAnswers = 0;
        for ($i = 1; $i <= 3; $i++) {
            if (isset($securityAnswers[$i]) && $this->verifySecurityAnswer($i, $securityAnswers[$i])) {
                $correctAnswers++;
            }
        }

        if ($correctAnswers < 2) {
            return ['success' => false, 'message' => 'At least 2 security questions must be answered correctly'];
        }

        $this->setPassword($newPassword);
        $this->resetLoginAttempts();
        $this->clearPasswordResetToken();
        $this->save();

        return ['success' => true, 'message' => 'Password reset successfully'];
    }

    public function generatePasswordResetToken() {
        $this->password_reset_token = bin2hex(random_bytes(32));
        $this->password_reset_expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->password_reset_token;
    }

    public function clearPasswordResetToken() {
        $this->password_reset_token = null;
        $this->password_reset_expires = null;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    public function isPasswordResetTokenValid($token) {
        return $this->password_reset_token === $token &&
               $this->password_reset_expires &&
               strtotime($this->password_reset_expires) > time();
    }

    public function incrementLoginAttempts() {
        $this->login_attempts = ($this->login_attempts ?? 0) + 1;

        if ($this->login_attempts >= 5) {
            $this->locked_until = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        }

        $this->updated_at = date('Y-m-d H:i:s');
        if (isset($this->attributes['id']) && $this->attributes['id']) {
            $this->save();
        }
        return $this;
    }

    public function resetLoginAttempts() {
        $this->login_attempts = 0;
        $this->locked_until = null;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this;
    }

    public function isLocked() {
        return $this->locked_until && strtotime($this->locked_until) > time();
    }

    public function isActive() {
        return $this->is_active == 1;
    }

    public function activate() {
        $this->is_active = 1;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function deactivate() {
        $this->is_active = 0;
        $this->updated_at = date('Y-m-d H:i:s');
        return $this->save();
    }

    public static function findByUsername($username) {
        $results = static::where('username', '=', $username);
        return !empty($results) ? $results[0] : null;
    }

    public static function findByPersonId($personId) {
        $results = static::where('person_id', '=', $personId);
        return !empty($results) ? $results[0] : null;
    }

    public static function authenticate($username, $password) {
        $credential = static::findByUsername($username);
        if (!$credential) {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }

        if (!$credential->isActive()) {
            return ['success' => false, 'message' => 'Account is inactive'];
        }

        if ($credential->isLocked()) {
            return ['success' => false, 'message' => 'Account is temporarily locked'];
        }

        if (!$credential->verifyPassword($password)) {
            $credential->incrementLoginAttempts();
            return ['success' => false, 'message' => 'Invalid credentials'];
        }

        $credential->resetLoginAttempts();
        $credential->last_login = date('Y-m-d H:i:s');
        $credential->updated_at = date('Y-m-d H:i:s');
        $credential->save();

        return ['success' => true, 'message' => 'Login successful'];
    }

    protected function getSchema() {
        return "
            CREATE TABLE IF NOT EXISTS person_credentials (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                person_id INTEGER NOT NULL,
                username TEXT UNIQUE NOT NULL,
                password_hash TEXT NOT NULL,
                security_question_1 TEXT,
                security_answer_1_hash TEXT,
                security_question_2 TEXT,
                security_answer_2_hash TEXT,
                security_question_3 TEXT,
                security_answer_3_hash TEXT,
                last_login DATETIME,
                login_attempts INTEGER DEFAULT 0,
                locked_until DATETIME,
                password_reset_token TEXT,
                password_reset_expires DATETIME,
                is_active INTEGER DEFAULT 1,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (person_id) REFERENCES persons (id) ON DELETE CASCADE
            )
        ";
    }
}