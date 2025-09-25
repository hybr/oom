<?php

require_once 'process/OrderProcess.php';

class ProcessService {
    private $processes = [
        'order' => 'OrderProcess'
    ];

    public function getProcess($processType, $entityId) {
        if (!isset($this->processes[$processType])) {
            throw new Exception("Process type '{$processType}' not found");
        }

        $processClass = $this->processes[$processType];
        return new $processClass($entityId);
    }

    public function getCurrentState($processType, $entityId) {
        $process = $this->getProcess($processType, $entityId);
        return $process->getCurrentState();
    }

    public function canTransition($processType, $entityId, $toState, $role = null) {
        $process = $this->getProcess($processType, $entityId);
        return $process->canTransition($toState, $role);
    }

    public function transition($processType, $entityId, $toState, $role = null, $note = '') {
        $process = $this->getProcess($processType, $entityId);
        return $process->transition($toState, $role, $note);
    }

    public function rollback($processType, $entityId, $steps = 1) {
        $process = $this->getProcess($processType, $entityId);
        return $process->rollback($steps);
    }

    public function getHistory($processType, $entityId) {
        $process = $this->getProcess($processType, $entityId);
        return $process->getHistory();
    }

    public function getStateFlow($processType) {
        if (!isset($this->processes[$processType])) {
            throw new Exception("Process type '{$processType}' not found");
        }

        $processClass = $this->processes[$processType];
        if (method_exists($processClass, 'getStateFlow')) {
            return $processClass::getStateFlow();
        }

        throw new Exception("State flow not available for process '{$processType}'");
    }

    public function getAvailableTransitions($processType, $entityId, $role = null) {
        $process = $this->getProcess($processType, $entityId);
        $currentState = $process->getCurrentState();
        $stateFlow = $this->getStateFlow($processType);

        if (!isset($stateFlow[$currentState])) {
            return [];
        }

        $availableTransitions = [];
        foreach ($stateFlow[$currentState] as $toState) {
            if ($process->canTransition($toState, $role)) {
                $availableTransitions[] = $toState;
            }
        }

        return $availableTransitions;
    }
}