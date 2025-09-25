<?php

class EntityService {
    private $allowedEntities = ['Order', 'OrderItem'];

    public function create($entityType, $data) {
        $this->validateEntity($entityType);

        require_once "entities/{$entityType}.php";
        $entity = new $entityType();
        $entity->fill($data);
        return $entity->save();
    }

    public function read($entityType, $id) {
        $this->validateEntity($entityType);

        require_once "entities/{$entityType}.php";
        return $entityType::find($id);
    }

    public function update($entityType, $id, $data) {
        $this->validateEntity($entityType);

        require_once "entities/{$entityType}.php";
        $entity = $entityType::find($id);
        if (!$entity) {
            throw new Exception("Entity not found");
        }

        $entity->fill($data);
        return $entity->save();
    }

    public function delete($entityType, $id) {
        $this->validateEntity($entityType);

        require_once "entities/{$entityType}.php";
        $entity = $entityType::find($id);
        if (!$entity) {
            throw new Exception("Entity not found");
        }

        return $entity->delete();
    }

    public function list($entityType, $filters = []) {
        $this->validateEntity($entityType);

        require_once "entities/{$entityType}.php";

        if (empty($filters)) {
            return $entityType::all();
        }

        $results = [];
        foreach ($filters as $field => $value) {
            $results = array_merge($results, $entityType::where($field, '=', $value));
        }

        return array_unique($results, SORT_REGULAR);
    }

    private function validateEntity($entityType) {
        if (!in_array($entityType, $this->allowedEntities)) {
            throw new Exception("Entity type '{$entityType}' is not allowed");
        }
    }

    public function getSchema($entityType) {
        $this->validateEntity($entityType);

        require_once "entities/{$entityType}.php";
        $entity = new $entityType();
        return $entity->getSchema();
    }
}