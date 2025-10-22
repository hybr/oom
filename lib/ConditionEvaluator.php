<?php
/**
 * ConditionEvaluator - Evaluates edge conditions for process transitions
 *
 * Handles:
 * - Evaluating structured conditions from PROCESS_EDGE_CONDITION
 * - Supporting multiple data sources (entity fields, flow variables, task variables)
 * - Various operators (EQ, GT, LT, IN, CONTAINS, etc.)
 * - Logical operators (AND, OR)
 * - Condition grouping
 */

class ConditionEvaluator
{
    /**
     * Evaluate a set of conditions
     *
     * @param array $conditions Array of condition records from process_edge_condition
     * @param array $entityData Data from the entity record
     * @param array $flowVariables Variables from the flow instance
     * @param array $taskData Data from completed task
     * @return bool True if all conditions are met
     */
    public static function evaluate($conditions, $entityData = [], $flowVariables = [], $taskData = [])
    {
        if (empty($conditions)) {
            return true;
        }

        // Group conditions by condition_group
        $groups = [];
        foreach ($conditions as $condition) {
            $groupId = $condition['condition_group'] ?? 0;
            if (!isset($groups[$groupId])) {
                $groups[$groupId] = [];
            }
            $groups[$groupId][] = $condition;
        }

        // Evaluate each group (groups are OR'd together)
        foreach ($groups as $group) {
            if (self::evaluateGroup($group, $entityData, $flowVariables, $taskData)) {
                return true;
            }
        }

        return count($groups) === 0; // Empty = true
    }

    /**
     * Evaluate a group of conditions (within group, conditions are AND'd or OR'd based on logic_operator)
     */
    private static function evaluateGroup($conditions, $entityData, $flowVariables, $taskData)
    {
        if (empty($conditions)) {
            return true;
        }

        $result = true;
        $currentLogic = 'AND';

        foreach ($conditions as $index => $condition) {
            $conditionMet = self::evaluateCondition($condition, $entityData, $flowVariables, $taskData);

            if ($index === 0) {
                $result = $conditionMet;
            } else {
                // Apply previous condition's logic operator
                if ($currentLogic === 'AND') {
                    $result = $result && $conditionMet;
                } else { // OR
                    $result = $result || $conditionMet;
                }
            }

            // Get logic operator for next iteration
            $currentLogic = $condition['logic_operator'] ?? 'AND';

            // Short circuit evaluation
            if (!$result && $currentLogic === 'AND') {
                return false;
            }
            if ($result && $currentLogic === 'OR') {
                return true;
            }
        }

        return $result;
    }

    /**
     * Evaluate a single condition
     */
    private static function evaluateCondition($condition, $entityData, $flowVariables, $taskData)
    {
        // Get the value from the appropriate source
        $actualValue = self::getFieldValue(
            $condition['field_source'],
            $condition['field_name'],
            $entityData,
            $flowVariables,
            $taskData
        );

        // Get comparison value
        $compareValue = self::parseValue($condition['compare_value'], $condition['value_type']);

        // Evaluate based on operator
        return self::evaluateOperator(
            $actualValue,
            $condition['operator'],
            $compareValue,
            $condition['value_type']
        );
    }

    /**
     * Get field value from appropriate source
     */
    private static function getFieldValue($fieldSource, $fieldName, $entityData, $flowVariables, $taskData)
    {
        switch ($fieldSource) {
            case 'ENTITY_FIELD':
                return $entityData[$fieldName] ?? null;

            case 'FLOW_VARIABLE':
                return $flowVariables[$fieldName] ?? null;

            case 'TASK_VARIABLE':
                return $taskData[$fieldName] ?? null;

            case 'SYSTEM':
                return self::getSystemValue($fieldName);

            default:
                return null;
        }
    }

    /**
     * Get system values (like current date, time, etc.)
     */
    private static function getSystemValue($fieldName)
    {
        switch ($fieldName) {
            case 'CURRENT_DATE':
                return date('Y-m-d');

            case 'CURRENT_TIME':
                return date('H:i:s');

            case 'CURRENT_DATETIME':
                return date('Y-m-d H:i:s');

            case 'CURRENT_YEAR':
                return (int) date('Y');

            case 'CURRENT_MONTH':
                return (int) date('m');

            case 'CURRENT_DAY':
                return (int) date('d');

            default:
                return null;
        }
    }

    /**
     * Parse comparison value based on type
     */
    private static function parseValue($value, $valueType)
    {
        if ($value === null) {
            return null;
        }

        switch ($valueType) {
            case 'NUMBER':
                return is_numeric($value) ? (float) $value : 0;

            case 'INTEGER':
                return (int) $value;

            case 'BOOLEAN':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);

            case 'LIST':
                return json_decode($value, true) ?? [];

            case 'DATE':
            case 'DATETIME':
            case 'STRING':
            default:
                return $value;
        }
    }

    /**
     * Evaluate operator
     */
    private static function evaluateOperator($actualValue, $operator, $compareValue, $valueType)
    {
        // Handle NULL checks first
        if ($operator === 'IS_NULL') {
            return $actualValue === null || $actualValue === '';
        }

        if ($operator === 'IS_NOT_NULL') {
            return $actualValue !== null && $actualValue !== '';
        }

        // If actual value is null and not checking for null, return false
        if ($actualValue === null) {
            return false;
        }

        // Normalize values based on type
        $actualValue = self::normalizeValue($actualValue, $valueType);
        $compareValue = self::normalizeValue($compareValue, $valueType);

        switch ($operator) {
            case 'EQ':
                return $actualValue == $compareValue;

            case 'NEQ':
                return $actualValue != $compareValue;

            case 'GT':
                return $actualValue > $compareValue;

            case 'GTE':
                return $actualValue >= $compareValue;

            case 'LT':
                return $actualValue < $compareValue;

            case 'LTE':
                return $actualValue <= $compareValue;

            case 'IN':
                if (!is_array($compareValue)) {
                    $compareValue = [$compareValue];
                }
                return in_array($actualValue, $compareValue);

            case 'NOT_IN':
                if (!is_array($compareValue)) {
                    $compareValue = [$compareValue];
                }
                return !in_array($actualValue, $compareValue);

            case 'CONTAINS':
                return stripos((string) $actualValue, (string) $compareValue) !== false;

            case 'NOT_CONTAINS':
                return stripos((string) $actualValue, (string) $compareValue) === false;

            case 'STARTS_WITH':
                return stripos((string) $actualValue, (string) $compareValue) === 0;

            case 'ENDS_WITH':
                $length = strlen($compareValue);
                return substr((string) $actualValue, -$length) === $compareValue;

            case 'REGEX':
                return preg_match($compareValue, (string) $actualValue) === 1;

            case 'BETWEEN':
                if (!is_array($compareValue) || count($compareValue) !== 2) {
                    return false;
                }
                return $actualValue >= $compareValue[0] && $actualValue <= $compareValue[1];

            default:
                error_log("Unknown operator: {$operator}");
                return false;
        }
    }

    /**
     * Normalize value for comparison
     */
    private static function normalizeValue($value, $valueType)
    {
        switch ($valueType) {
            case 'NUMBER':
                return is_numeric($value) ? (float) $value : 0;

            case 'INTEGER':
                return (int) $value;

            case 'BOOLEAN':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);

            case 'DATE':
                // Convert to timestamp for comparison
                if (is_string($value)) {
                    return strtotime($value);
                }
                return $value;

            case 'DATETIME':
                // Convert to timestamp for comparison
                if (is_string($value)) {
                    return strtotime($value);
                }
                return $value;

            case 'LIST':
                if (is_string($value)) {
                    return json_decode($value, true) ?? [];
                }
                return is_array($value) ? $value : [$value];

            case 'STRING':
            default:
                return (string) $value;
        }
    }

    /**
     * Helper: Create a simple condition array (for programmatic use)
     */
    public static function createCondition($fieldSource, $fieldName, $operator, $compareValue, $valueType = 'STRING', $logicOperator = 'AND')
    {
        return [
            'field_source' => $fieldSource,
            'field_name' => $fieldName,
            'operator' => $operator,
            'compare_value' => $compareValue,
            'value_type' => $valueType,
            'logic_operator' => $logicOperator,
            'condition_group' => 0
        ];
    }

    /**
     * Helper: Test a simple condition without database
     */
    public static function test($fieldSource, $fieldName, $operator, $compareValue, $valueType, $testData)
    {
        $condition = self::createCondition($fieldSource, $fieldName, $operator, $compareValue, $valueType);

        $entityData = $testData['entity'] ?? [];
        $flowVariables = $testData['flow'] ?? [];
        $taskData = $testData['task'] ?? [];

        return self::evaluateCondition($condition, $entityData, $flowVariables, $taskData);
    }
}
