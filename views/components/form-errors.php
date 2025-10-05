<?php
/**
 * Form Errors Component
 *
 * @param array $errors
 * @param string|null $field - specific field or null for all errors
 */

$fieldErrors = [];

if (isset($field)) {
    $fieldErrors = errors($field) ?? [];
    if (!is_array($fieldErrors)) {
        $fieldErrors = [$fieldErrors];
    }
} else {
    $allErrors = errors();
    if ($allErrors) {
        foreach ($allErrors as $fieldName => $fieldErrorList) {
            if (is_array($fieldErrorList)) {
                $fieldErrors = array_merge($fieldErrors, $fieldErrorList);
            } else {
                $fieldErrors[] = $fieldErrorList;
            }
        }
    }
}
?>

<?php if (!empty($fieldErrors)): ?>
    <?php if (isset($field)): ?>
        <div class="invalid-feedback d-block">
            <?php foreach ($fieldErrors as $error): ?>
                <div><?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-danger" role="alert">
            <h6 class="alert-heading">Please correct the following errors:</h6>
            <ul class="mb-0">
                <?php foreach ($fieldErrors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
<?php endif; ?>
