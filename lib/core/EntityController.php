<?php
/**
 * Generic Entity Controller
 *
 * Handles CRUD operations for all entities based on metadata
 */

namespace V4L\Core;

class EntityController
{
    private string $entityCode;
    private ?array $entity;
    private string $tableName;

    public function __construct(string $entityCode)
    {
        $this->entityCode = $entityCode;
        $this->entity = MetadataLoader::getEntity($entityCode);

        if (!$this->entity) {
            Response::notFound("Entity '$entityCode' not found");
        }

        $this->tableName = $this->entity['table_name'];
    }

    /**
     * List all records with pagination and filtering
     */
    public function list(Request $request): void
    {
        // Pagination
        $page = max(1, (int)$request->query('page', 1));
        $pageSize = min(MAX_PAGE_SIZE, max(1, (int)$request->query('page_size', DEFAULT_PAGE_SIZE)));
        $offset = ($page - 1) * $pageSize;

        // Search
        $search = $request->query('search', '');

        // Filters
        $filters = $request->query('filters', []);

        // Build query
        $whereClauses = [];
        $params = [];

        // Add search condition
        if ($search) {
            $searchClauses = [];
            foreach ($this->entity['attributes'] as $attr) {
                if (in_array($attr['data_type'], ['TEXT', 'VARCHAR', 'STRING'])) {
                    $searchClauses[] = "{$attr['code']} LIKE :search";
                }
            }
            if ($searchClauses) {
                $whereClauses[] = '(' . implode(' OR ', $searchClauses) . ')';
                $params[':search'] = "%$search%";
            }
        }

        // Add filter conditions
        foreach ($filters as $field => $value) {
            $whereClauses[] = "$field = :filter_$field";
            $params[":filter_$field"] = $value;
        }

        $whereClause = $whereClauses ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM {$this->tableName} $whereClause";
        $totalResult = Database::fetchOne($countSql, $params);
        $total = $totalResult['total'];

        // Get records
        $sql = "SELECT * FROM {$this->tableName} $whereClause ORDER BY created_at DESC LIMIT $pageSize OFFSET $offset";
        $records = Database::fetchAll($sql, $params);

        Response::success([
            'records' => $records,
            'pagination' => [
                'page' => $page,
                'page_size' => $pageSize,
                'total' => $total,
                'total_pages' => ceil($total / $pageSize)
            ]
        ]);
    }

    /**
     * Get single record by ID
     */
    public function get(string $id): void
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE id = :id";
        $record = Database::fetchOne($sql, [':id' => $id]);

        if (!$record) {
            Response::notFound('Record not found');
        }

        // Load relationships
        $record['_relationships'] = $this->loadRelationships($id);

        Response::success($record);
    }

    /**
     * Create new record
     */
    public function create(Request $request): void
    {
        $data = $request->all();

        // Validate data
        $errors = MetadataLoader::validateData($this->entityCode, $data);
        if ($errors) {
            Response::validationError($errors);
        }

        // Add system fields
        $data['id'] = Database::generateUuid();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        // Add created_by if user is logged in
        if (Auth::isLoggedIn()) {
            $data['created_by'] = Auth::getUserId();
        }

        try {
            $id = Database::insert($this->tableName, $data);

            Response::success(
                ['id' => $id],
                'Record created successfully',
                201
            );
        } catch (\Exception $e) {
            Response::serverError('Failed to create record: ' . $e->getMessage());
        }
    }

    /**
     * Update existing record
     */
    public function update(string $id, Request $request): void
    {
        // Check if record exists
        $existing = Database::fetchOne(
            "SELECT * FROM {$this->tableName} WHERE id = :id",
            [':id' => $id]
        );

        if (!$existing) {
            Response::notFound('Record not found');
        }

        $data = $request->all();

        // Validate data
        $errors = MetadataLoader::validateData($this->entityCode, $data);
        if ($errors) {
            Response::validationError($errors);
        }

        // Update timestamp
        $data['updated_at'] = date('Y-m-d H:i:s');

        // Remove system fields that shouldn't be updated
        unset($data['id'], $data['created_at'], $data['created_by']);

        try {
            $affected = Database::update($this->tableName, $data, 'id = :id', [':id' => $id]);

            Response::success(
                ['affected_rows' => $affected],
                'Record updated successfully'
            );
        } catch (\Exception $e) {
            Response::serverError('Failed to update record: ' . $e->getMessage());
        }
    }

    /**
     * Delete record
     */
    public function delete(string $id): void
    {
        // Check if record exists
        $existing = Database::fetchOne(
            "SELECT * FROM {$this->tableName} WHERE id = :id",
            [':id' => $id]
        );

        if (!$existing) {
            Response::notFound('Record not found');
        }

        try {
            $affected = Database::delete($this->tableName, 'id = :id', [':id' => $id]);

            Response::success(
                ['affected_rows' => $affected],
                'Record deleted successfully'
            );
        } catch (\Exception $e) {
            Response::serverError('Failed to delete record: ' . $e->getMessage());
        }
    }

    /**
     * Load relationships for a record
     */
    private function loadRelationships(string $recordId): array
    {
        $relationships = [];

        foreach ($this->entity['relationships'] as $rel) {
            $relKey = $rel['relationship_code'];

            if ($rel['source_entity_id'] === $this->entity['id']) {
                // This entity is the source
                $targetTable = MetadataLoader::getTableName($rel['target_entity_code']);
                if ($targetTable) {
                    $sql = "SELECT * FROM $targetTable WHERE {$rel['source_attribute_code']} = :id";
                    $relationships[$relKey] = Database::fetchAll($sql, [':id' => $recordId]);
                }
            } else {
                // This entity is the target
                $sourceTable = MetadataLoader::getTableName($rel['source_entity_code']);
                if ($sourceTable) {
                    $sql = "SELECT * FROM $sourceTable WHERE {$rel['target_attribute_code']} = :id";
                    $relationships[$relKey] = Database::fetchAll($sql, [':id' => $recordId]);
                }
            }
        }

        return $relationships;
    }

    /**
     * Get entity metadata
     */
    public function metadata(): void
    {
        Response::success([
            'entity' => $this->entity,
            'form_fields' => MetadataLoader::getFormFields($this->entityCode),
            'display_columns' => MetadataLoader::getDisplayColumns($this->entityCode),
            'label_attribute' => MetadataLoader::getLabelAttribute($this->entityCode)
        ]);
    }
}
