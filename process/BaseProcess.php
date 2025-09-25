<?php

require_once 'config/database.php';

abstract class BaseProcess {
    protected $db;
    protected $processName;
    protected $entityId;
    protected $currentState;
    protected $allowedTransitions = [];
    protected $stateCallbacks = [];

    public function __construct($entityId = null) {
        $this->db = DatabaseConfig::getInstance();
        $this->entityId = $entityId;
        if ($entityId) {
            $this->loadCurrentState();
        }
    }

    public function getCurrentState() {
        return $this->currentState;
    }

    public function canTransition($toState, $role = null) {
        if (!isset($this->allowedTransitions[$this->currentState])) {
            return false;
        }

        $transitions = $this->allowedTransitions[$this->currentState];

        if (isset($transitions[$toState])) {
            $rules = $transitions[$toState];

            if (isset($rules['roles']) && $role && !in_array($role, $rules['roles'])) {
                return false;
            }

            if (isset($rules['condition']) && is_callable($rules['condition'])) {
                return $rules['condition']($this->entityId);
            }

            return true;
        }

        return false;
    }

    public function transition($toState, $role = null, $note = '') {
        if (!$this->canTransition($toState, $role)) {
            throw new Exception("Transition from {$this->currentState} to {$toState} is not allowed");
        }

        $fromState = $this->currentState;

        $this->db->execute("
            INSERT INTO process_history (
                process_name, entity_id, from_state, to_state,
                changed_by_role, note, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ", [
            $this->processName, $this->entityId, $fromState, $toState,
            $role, $note, date('Y-m-d H:i:s')
        ]);

        $this->updateEntityState($toState);
        $this->currentState = $toState;

        $this->executeStateCallback($toState, $fromState);

        return true;
    }

    public function rollback($steps = 1) {
        $history = $this->db->fetchAll("
            SELECT * FROM process_history
            WHERE process_name = ? AND entity_id = ?
            ORDER BY created_at DESC
            LIMIT ?
        ", [$this->processName, $this->entityId, $steps + 1]);

        if (count($history) <= $steps) {
            throw new Exception("Cannot rollback {$steps} steps - insufficient history");
        }

        $targetState = $history[$steps]['from_state'];

        $this->db->execute("
            INSERT INTO process_history (
                process_name, entity_id, from_state, to_state,
                changed_by_role, note, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ", [
            $this->processName, $this->entityId, $this->currentState, $targetState,
            'system', "Rollback {$steps} steps", date('Y-m-d H:i:s')
        ]);

        $this->updateEntityState($targetState);
        $this->currentState = $targetState;

        return true;
    }

    public function getHistory() {
        return $this->db->fetchAll("
            SELECT * FROM process_history
            WHERE process_name = ? AND entity_id = ?
            ORDER BY created_at DESC
        ", [$this->processName, $this->entityId]);
    }

    protected function loadCurrentState() {
        $result = $this->db->fetch("
            SELECT to_state FROM process_history
            WHERE process_name = ? AND entity_id = ?
            ORDER BY created_at DESC
            LIMIT 1
        ", [$this->processName, $this->entityId]);

        if ($result) {
            $this->currentState = $result['to_state'];
        } else {
            $this->currentState = $this->getInitialState();
            $this->recordInitialState();
        }
    }

    protected function recordInitialState() {
        $this->db->execute("
            INSERT INTO process_history (
                process_name, entity_id, from_state, to_state,
                changed_by_role, note, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ", [
            $this->processName, $this->entityId, null, $this->currentState,
            'system', 'Initial state', date('Y-m-d H:i:s')
        ]);
    }

    protected function executeStateCallback($state, $fromState) {
        if (isset($this->stateCallbacks[$state])) {
            $callback = $this->stateCallbacks[$state];
            if (is_callable($callback)) {
                $callback($this->entityId, $fromState, $state);
            }
        }
    }

    public static function createTables() {
        $db = DatabaseConfig::getInstance();

        $db->execute("
            CREATE TABLE IF NOT EXISTS process_history (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                process_name TEXT NOT NULL,
                entity_id INTEGER NOT NULL,
                from_state TEXT,
                to_state TEXT NOT NULL,
                changed_by_role TEXT,
                note TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    abstract protected function getInitialState();
    abstract protected function updateEntityState($state);
}