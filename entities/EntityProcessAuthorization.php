<?php

namespace Entities;

/**
 * EntityProcessAuthorization Entity
 * Defines which positions can perform which actions on entities (process-level authorization)
 */
class EntityProcessAuthorization extends BaseEntity
{
    protected ?int $entity_id = null;
    protected ?string $action = null;
    protected ?int $popular_position_id = null;
    protected ?string $remarks = null;

    public static function getTableName(): string
    {
        return 'entity_process_authorization';
    }

    protected function getFillableAttributes(): array
    {
        return [
            'entity_id',
            'action',
            'popular_position_id',
            'remarks'
        ];
    }

    protected function getValidationRules(): array
    {
        return [
            'entity_id' => ['required', 'numeric'],
            'action' => ['required', 'min:2', 'max:100'],
            'popular_position_id' => ['required', 'numeric']
        ];
    }

    /**
     * Get the entity this authorization is for
     */
    public function getEntity(): ?EntityDefinition
    {
        return EntityDefinition::find($this->entity_id);
    }

    /**
     * Get the position that has this authorization
     */
    public function getPosition(): ?PopularOrganizationPosition
    {
        return PopularOrganizationPosition::find($this->popular_position_id);
    }

    /**
     * Check if a person in this position can perform the action
     */
    public function canPerformAction(int $personId): bool
    {
        // This would check if the person holds this position
        // Implementation depends on how positions are assigned to people
        return false; // Default deny
    }

    /**
     * Get all authorizations for an entity
     */
    public static function getByEntity(int $entityId): array
    {
        return static::where('entity_id = :entity_id', ['entity_id' => $entityId]);
    }

    /**
     * Get all authorizations for a position
     */
    public static function getByPosition(int $positionId): array
    {
        return static::where('popular_position_id = :position_id', ['position_id' => $positionId]);
    }

    /**
     * Get authorizations by entity and action
     */
    public static function getByEntityAndAction(int $entityId, string $action): array
    {
        return static::where(
            'entity_id = :entity_id AND action = :action',
            ['entity_id' => $entityId, 'action' => $action]
        );
    }

    /**
     * Check if a position can perform an action on an entity
     */
    public static function canPerform(int $entityId, string $action, int $positionId): bool
    {
        $authorizations = static::where(
            'entity_id = :entity_id AND action = :action AND popular_position_id = :position_id',
            ['entity_id' => $entityId, 'action' => $action, 'position_id' => $positionId],
            1
        );

        return !empty($authorizations);
    }

    /**
     * Check if authorization exists
     */
    public static function exists(int $entityId, string $action, int $positionId): bool
    {
        return static::canPerform($entityId, $action, $positionId);
    }

    /**
     * Grant authorization
     */
    public static function grant(int $entityId, string $action, int $positionId, ?string $remarks = null, ?int $userId = null): ?self
    {
        if (static::exists($entityId, $action, $positionId)) {
            return null; // Already exists
        }

        $auth = new static();
        $auth->entity_id = $entityId;
        $auth->action = $action;
        $auth->popular_position_id = $positionId;
        $auth->remarks = $remarks;

        return $auth->save($userId) ? $auth : null;
    }

    /**
     * Revoke authorization
     */
    public static function revoke(int $entityId, string $action, int $positionId): bool
    {
        $authorizations = static::where(
            'entity_id = :entity_id AND action = :action AND popular_position_id = :position_id',
            ['entity_id' => $entityId, 'action' => $action, 'position_id' => $positionId],
            1
        );

        if (!empty($authorizations)) {
            return $authorizations[0]->delete();
        }

        return false;
    }

    /**
     * Get all actions a position can perform
     */
    public static function getActionsForPosition(int $positionId): array
    {
        $authorizations = static::getByPosition($positionId);
        $actions = [];

        foreach ($authorizations as $auth) {
            $entity = $auth->getEntity();
            if ($entity) {
                $actions[] = [
                    'entity' => $entity->name,
                    'action' => $auth->action
                ];
            }
        }

        return $actions;
    }

    /**
     * Get all positions that can perform an action on an entity
     */
    public static function getPositionsForAction(int $entityId, string $action): array
    {
        $authorizations = static::getByEntityAndAction($entityId, $action);
        $positions = [];

        foreach ($authorizations as $auth) {
            $position = $auth->getPosition();
            if ($position) {
                $positions[] = $position;
            }
        }

        return $positions;
    }

    /**
     * Get authorization summary
     */
    public function getSummary(): array
    {
        return [
            'entity' => $this->getEntity()?->toArray(),
            'position' => $this->getPosition()?->toArray(),
            'action' => $this->action,
            'remarks' => $this->remarks
        ];
    }

    /**
     * Bulk grant authorizations
     */
    public static function bulkGrant(int $entityId, array $actions, int $positionId, ?int $userId = null): array
    {
        $granted = [];

        foreach ($actions as $action) {
            $auth = static::grant($entityId, $action, $positionId, null, $userId);
            if ($auth) {
                $granted[] = $auth;
            }
        }

        return $granted;
    }
}
