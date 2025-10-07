<?php

namespace Entities;

/**
 * EntityInstanceAuthorization Entity
 * Defines instance-specific authorizations for particular records (instance-level authorization)
 */
class EntityInstanceAuthorization extends BaseEntity
{
    protected ?int $entity_id = null;
    protected ?int $entity_record_id = null;
    protected ?string $action = null;
    protected ?int $assigned_position_id = null;
    protected ?int $assigned_person_id = null;
    protected ?string $valid_from = null;
    protected ?string $valid_to = null;
    protected ?string $status = null; // active, revoked, expired

    public static function getTableName(): string
    {
        return 'entity_instance_authorization';
    }

    protected function getFillableAttributes(): array
    {
        return [
            'entity_id',
            'entity_record_id',
            'action',
            'assigned_position_id',
            'assigned_person_id',
            'valid_from',
            'valid_to',
            'status'
        ];
    }

    protected function getValidationRules(): array
    {
        return [
            'entity_id' => ['required', 'numeric'],
            'entity_record_id' => ['required', 'numeric'],
            'action' => ['required', 'min:2', 'max:100'],
            'status' => ['required']
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
        return $this->assigned_position_id ? PopularOrganizationPosition::find($this->assigned_position_id) : null;
    }

    /**
     * Get the person that has this authorization
     */
    public function getPerson(): ?Person
    {
        return $this->assigned_person_id ? Person::find($this->assigned_person_id) : null;
    }

    /**
     * Check if authorization is currently active
     */
    public function isActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $now = new \DateTime();

        if ($this->valid_from) {
            $validFrom = new \DateTime($this->valid_from);
            if ($now < $validFrom) {
                return false;
            }
        }

        if ($this->valid_to) {
            $validTo = new \DateTime($this->valid_to);
            if ($now > $validTo) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if authorization has expired
     */
    public function hasExpired(): bool
    {
        if (!$this->valid_to) {
            return false;
        }

        $now = new \DateTime();
        $validTo = new \DateTime($this->valid_to);

        return $now > $validTo;
    }

    /**
     * Revoke the authorization
     */
    public function revoke(?int $userId = null): bool
    {
        $this->status = 'revoked';
        return $this->save($userId);
    }

    /**
     * Mark as expired
     */
    public function markExpired(?int $userId = null): bool
    {
        $this->status = 'expired';
        return $this->save($userId);
    }

    /**
     * Extend the validity period
     */
    public function extend(string $newValidTo, ?int $userId = null): bool
    {
        $this->valid_to = $newValidTo;
        return $this->save($userId);
    }

    /**
     * Get days remaining until expiry
     */
    public function getDaysRemaining(): ?int
    {
        if (!$this->valid_to) {
            return null; // No expiry
        }

        $now = new \DateTime();
        $validTo = new \DateTime($this->valid_to);

        if ($now > $validTo) {
            return 0;
        }

        return $now->diff($validTo)->days;
    }

    /**
     * Get authorizations by entity
     */
    public static function getByEntity(int $entityId): array
    {
        return static::where('entity_id = :entity_id', ['entity_id' => $entityId]);
    }

    /**
     * Get authorizations by entity record
     */
    public static function getByEntityRecord(int $entityId, int $recordId): array
    {
        return static::where(
            'entity_id = :entity_id AND entity_record_id = :record_id',
            ['entity_id' => $entityId, 'record_id' => $recordId]
        );
    }

    /**
     * Get authorizations by person
     */
    public static function getByPerson(int $personId): array
    {
        return static::where('assigned_person_id = :person_id', ['person_id' => $personId]);
    }

    /**
     * Get authorizations by position
     */
    public static function getByPosition(int $positionId): array
    {
        return static::where('assigned_position_id = :position_id', ['position_id' => $positionId]);
    }

    /**
     * Get active authorizations for a person
     */
    public static function getActiveByPerson(int $personId): array
    {
        $authorizations = static::getByPerson($personId);
        return array_filter($authorizations, fn($auth) => $auth->isActive());
    }

    /**
     * Check if person has authorization for a specific record and action
     */
    public static function personCanPerform(int $entityId, int $recordId, string $action, int $personId): bool
    {
        $authorizations = static::where(
            'entity_id = :entity_id AND entity_record_id = :record_id AND action = :action AND assigned_person_id = :person_id AND status = :status',
            [
                'entity_id' => $entityId,
                'record_id' => $recordId,
                'action' => $action,
                'person_id' => $personId,
                'status' => 'active'
            ],
            1
        );

        if (!empty($authorizations)) {
            return $authorizations[0]->isActive();
        }

        return false;
    }

    /**
     * Grant instance authorization
     */
    public static function grant(
        int $entityId,
        int $recordId,
        string $action,
        ?int $assignedPositionId = null,
        ?int $assignedPersonId = null,
        ?string $validFrom = null,
        ?string $validTo = null,
        ?int $userId = null
    ): ?self {
        $auth = new static();
        $auth->entity_id = $entityId;
        $auth->entity_record_id = $recordId;
        $auth->action = $action;
        $auth->assigned_position_id = $assignedPositionId;
        $auth->assigned_person_id = $assignedPersonId;
        $auth->valid_from = $validFrom ?? date('Y-m-d H:i:s');
        $auth->valid_to = $validTo;
        $auth->status = 'active';

        return $auth->save($userId) ? $auth : null;
    }

    /**
     * Get authorization summary
     */
    public function getSummary(): array
    {
        return [
            'entity' => $this->getEntity()?->toArray(),
            'action' => $this->action,
            'position' => $this->getPosition()?->toArray(),
            'person' => $this->getPerson()?->toArray(),
            'valid_from' => $this->valid_from,
            'valid_to' => $this->valid_to,
            'status' => $this->status,
            'is_active' => $this->isActive(),
            'days_remaining' => $this->getDaysRemaining()
        ];
    }

    /**
     * Bulk revoke authorizations for a record
     */
    public static function bulkRevokeForRecord(int $entityId, int $recordId, ?int $userId = null): int
    {
        $authorizations = static::getByEntityRecord($entityId, $recordId);
        $count = 0;

        foreach ($authorizations as $auth) {
            if ($auth->isActive() && $auth->revoke($userId)) {
                $count++;
            }
        }

        return $count;
    }
}
