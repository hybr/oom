<?php

namespace Entities;

/**
 * EntityDefinition Entity
 * Defines entities in the system for authorization and access control
 */
class EntityDefinition extends BaseEntity
{
    protected ?string $name = null;
    protected ?string $description = null;

    public static function getTableName(): string
    {
        return 'entity_definition';
    }

    protected function getFillableAttributes(): array
    {
        return [
            'name',
            'description'
        ];
    }

    protected function getValidationRules(): array
    {
        return [
            'name' => ['required', 'min:2', 'max:200']
        ];
    }

    /**
     * Get all process authorizations for this entity
     */
    public function getAuthorizations(): array
    {
        return EntityProcessAuthorization::where('entity_id = :entity_id', ['entity_id' => $this->id]);
    }

    /**
     * Get all instance authorizations for this entity
     */
    public function getInstanceAuthorizations(): array
    {
        return EntityInstanceAuthorization::where('entity_id = :entity_id', ['entity_id' => $this->id]);
    }

    /**
     * Get authorizations by action
     */
    public function getAuthorizationsByAction(string $action): array
    {
        return EntityProcessAuthorization::where(
            'entity_id = :entity_id AND action = :action',
            ['entity_id' => $this->id, 'action' => $action]
        );
    }

    /**
     * Get active instance authorizations
     */
    public function getActiveInstanceAuthorizations(): array
    {
        return EntityInstanceAuthorization::where(
            'entity_id = :entity_id AND status = :status',
            ['entity_id' => $this->id, 'status' => 'active']
        );
    }

    /**
     * Check if a position can perform an action on this entity
     */
    public function canPositionPerform(string $action, int $positionId): bool
    {
        $authorizations = EntityProcessAuthorization::where(
            'entity_id = :entity_id AND action = :action AND popular_position_id = :position_id',
            ['entity_id' => $this->id, 'action' => $action, 'position_id' => $positionId],
            1
        );

        return !empty($authorizations);
    }

    /**
     * Check if a person can perform an action on this entity
     */
    public function canPersonPerform(string $action, int $personId): bool
    {
        // Check process authorization through positions
        $person = Person::find($personId);
        if (!$person) {
            return false;
        }

        // Get person's positions
        // This would need to be implemented based on how positions are assigned to people

        return false; // Default deny
    }

    /**
     * Add process authorization
     */
    public function addProcessAuthorization(string $action, int $positionId, ?string $remarks = null, ?int $userId = null): ?EntityProcessAuthorization
    {
        $auth = new EntityProcessAuthorization();
        $auth->entity_id = $this->id;
        $auth->action = $action;
        $auth->popular_position_id = $positionId;
        $auth->remarks = $remarks;

        return $auth->save($userId) ? $auth : null;
    }

    /**
     * Add instance authorization
     */
    public function addInstanceAuthorization(
        int $entityRecordId,
        string $action,
        ?int $assignedPositionId = null,
        ?int $assignedPersonId = null,
        ?string $validFrom = null,
        ?string $validTo = null,
        ?int $userId = null
    ): ?EntityInstanceAuthorization {
        $auth = new EntityInstanceAuthorization();
        $auth->entity_id = $this->id;
        $auth->entity_record_id = $entityRecordId;
        $auth->action = $action;
        $auth->assigned_position_id = $assignedPositionId;
        $auth->assigned_person_id = $assignedPersonId;
        $auth->valid_from = $validFrom ?? date('Y-m-d H:i:s');
        $auth->valid_to = $validTo;
        $auth->status = 'active';

        return $auth->save($userId) ? $auth : null;
    }

    /**
     * Get entity by name
     */
    public static function getByName(string $name): ?self
    {
        $entities = static::where('name = :name', ['name' => $name], 1);
        return !empty($entities) ? $entities[0] : null;
    }

    /**
     * Search entities by name or description
     */
    public static function searchEntities(string $query): array
    {
        return static::search($query, ['name', 'description']);
    }

    /**
     * Get all defined entities
     */
    public static function getAllEntities(): array
    {
        return static::all();
    }

    /**
     * Check if entity name exists
     */
    public static function nameExists(string $name): bool
    {
        return static::getByName($name) !== null;
    }

    /**
     * Get authorization summary for this entity
     */
    public function getAuthorizationSummary(): array
    {
        $processAuths = $this->getAuthorizations();
        $instanceAuths = $this->getActiveInstanceAuthorizations();

        $summary = [
            'entity_name' => $this->name,
            'description' => $this->description,
            'process_authorizations_count' => count($processAuths),
            'instance_authorizations_count' => count($instanceAuths),
            'actions' => []
        ];

        // Group by action
        foreach ($processAuths as $auth) {
            if (!isset($summary['actions'][$auth->action])) {
                $summary['actions'][$auth->action] = 0;
            }
            $summary['actions'][$auth->action]++;
        }

        return $summary;
    }
}
