<?php
/**
 * Foreign Key Label Component
 * Creates a clickable label that links to the foreign entity's detail page
 *
 * Usage:
 * <?php
 * $fk_label = 'Person';
 * $fk_for = 'person_id';
 * $fk_entity = 'persons';
 * $fk_id = $entity->person_id ?? null; // Optional: If editing, provide the ID to link to
 * $fk_required = true; // Optional: defaults to false
 * include __DIR__ . '/../../views/components/fk-label.php';
 * ?>
 */

$label = $fk_label ?? 'Foreign Key';
$for = $fk_for ?? 'fk_id';
$entity = $fk_entity ?? null;
$id = $fk_id ?? null;
$required = $fk_required ?? false;
$icon = $fk_icon ?? 'bi-link-45deg';

?>
<label for="<?= $for ?>" class="form-label">
    <?php if ($entity): ?>
        <a href="/<?= $entity ?><?= $id ? '/' . $id : '' ?>"
           class="text-decoration-none"
           title="View <?= htmlspecialchars($label) ?> details"
           target="_blank">
            <i class="bi <?= $icon ?>"></i> <?= htmlspecialchars($label) ?>
        </a>
    <?php else: ?>
        <?= htmlspecialchars($label) ?>
    <?php endif; ?>
    <?php if ($required): ?>
        <span class="text-danger">*</span>
    <?php endif; ?>
</label>
<?php
// Clear variables to prevent pollution
unset($fk_label, $fk_for, $fk_entity, $fk_id, $fk_required, $fk_icon, $label, $for, $entity, $id, $required, $icon);
?>
