<?php

class EntityService {
    private $allowedEntities = ['Order', 'OrderItem', 'Person', 'PersonCredential', 'Continent', 'Language', 'Country', 'IndustryCategory', 'OrganizationLegalType'];

    public function create($entityType, $data) {
        $this->validateEntity($entityType);

        require_once __DIR__ . "/../../entities/{$entityType}.php";
        $entity = new $entityType();

        // Special handling for PersonCredential
        if ($entityType === 'PersonCredential') {
            return $this->createPersonCredential($entity, $data);
        }

        // Special handling for IndustryCategory
        if ($entityType === 'IndustryCategory') {
            return $this->createIndustryCategory($entity, $data);
        }

        $entity->fill($data);
        return $entity->save();
    }

    private function createPersonCredential($entity, $data) {
        // Fill basic data
        $entity->fill([
            'person_id' => $data['person_id'],
            'username' => $data['username']
        ]);

        // Set password
        if (isset($data['password'])) {
            $entity->setPassword($data['password']);
        }

        // Set security questions
        if (isset($data['security_question_1']) && isset($data['security_answer_1'])) {
            $entity->setSecurityQuestion(1, $data['security_question_1'], $data['security_answer_1']);
        }
        if (isset($data['security_question_2']) && isset($data['security_answer_2'])) {
            $entity->setSecurityQuestion(2, $data['security_question_2'], $data['security_answer_2']);
        }
        if (isset($data['security_question_3']) && isset($data['security_answer_3'])) {
            $entity->setSecurityQuestion(3, $data['security_question_3'], $data['security_answer_3']);
        }

        return $entity->save();
    }

    private function createIndustryCategory($entity, $data) {
        $entity->fill($data);

        // Auto-generate slug if not provided
        if (empty($data['slug'])) {
            $entity->setSlug();
        }

        $entity->updatePath();
        return $entity->save();
    }

    private function updateIndustryCategory($entity, $data) {
        $oldParentId = $entity->parent_id;

        $entity->fill($data);

        // Auto-generate slug if name changed and no slug provided
        if (isset($data['name']) && empty($data['slug'])) {
            $entity->setSlug();
        }

        // Update path if parent changed
        if ($entity->parent_id !== $oldParentId) {
            $entity->updatePath();

            // Update children paths if parent changed
            if ($entity->save()) {
                $entity->updateChildrenPaths();
            }
        } else {
            $entity->updatePath();
        }

        return $entity->save();
    }

    public function read($entityType, $id) {
        $this->validateEntity($entityType);

        require_once __DIR__ . "/../../entities/{$entityType}.php";
        return $entityType::find($id);
    }

    public function update($entityType, $id, $data) {
        $this->validateEntity($entityType);

        require_once __DIR__ . "/../../entities/{$entityType}.php";
        $entity = $entityType::find($id);
        if (!$entity) {
            throw new Exception("Entity not found");
        }

        // Special handling for IndustryCategory
        if ($entityType === 'IndustryCategory') {
            return $this->updateIndustryCategory($entity, $data);
        }

        $entity->fill($data);
        return $entity->save();
    }

    public function delete($entityType, $id) {
        $this->validateEntity($entityType);

        require_once __DIR__ . "/../../entities/{$entityType}.php";
        $entity = $entityType::find($id);
        if (!$entity) {
            throw new Exception("Entity not found");
        }

        return $entity->delete();
    }

    public function list($entityType, $filters = []) {
        $this->validateEntity($entityType);

        require_once __DIR__ . "/../../entities/{$entityType}.php";

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

        require_once __DIR__ . "/../../entities/{$entityType}.php";
        $entity = new $entityType();
        return $entity->getSchema();
    }
}