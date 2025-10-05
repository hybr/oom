<?php

namespace App;

/**
 * Page Generator Utility
 * Generates CRUD pages for entities
 */
class PageGenerator
{
    /**
     * Generate all CRUD pages for an entity
     */
    public static function generatePages(string $entityClass, string $entityFolder, array $config = []): void
    {
        $config = array_merge([
            'displayName' => ucfirst(str_replace('_', ' ', $entityFolder)),
            'pluralName' => $entityFolder,
            'icon' => 'bi-file-earmark',
            'fields' => [],
            'searchFields' => [],
        ], $config);

        $basePath = __DIR__ . '/../public/pages/entities/' . $entityFolder;

        if (!is_dir($basePath)) {
            mkdir($basePath, 0755, true);
        }

        // Generate list page
        self::generateListPage($basePath, $entityClass, $config);

        // Generate detail page
        self::generateDetailPage($basePath, $entityClass, $config);

        // Generate create page
        self::generateCreatePage($basePath, $entityClass, $config);

        // Generate edit page
        self::generateEditPage($basePath, $entityClass, $config);

        // Generate action handlers
        self::generateStore($basePath, $entityClass);
        self::generateUpdate($basePath, $entityClass);
        self::generateDelete($basePath, $entityClass);

        echo "Generated pages for: {$config['displayName']}\n";
    }

    private static function generateListPage($basePath, $entityClass, $config): void
    {
        $content = "<?php\n/**\n * {$config['displayName']} List Page\n */\n\n";
        $content .= "use {$entityClass};\n\n";
        $content .= "\$pageTitle = '{$config['displayName']}';\n\n";
        $content .= "// Pagination\n\$page = isset(\$_GET['page']) ? (int)\$_GET['page'] : 1;\n";
        $content .= "\$perPage = isset(\$_GET['per_page']) ? (int)\$_GET['per_page'] : 25;\n";
        $content .= "\$offset = (\$page - 1) * \$perPage;\n\n";
        $content .= "// Get entities\n";
        $content .= "\$entities = {$entityClass}::all(\$perPage, \$offset);\n";
        $content .= "\$total = {$entityClass}::count();\n";
        $content .= "\$totalPages = ceil(\$total / \$perPage);\n\n";
        $content .= "include __DIR__ . '/../../../../includes/header.php';\n";
        $content .= "?>\n\n<div class=\"container-fluid mt-4\">\n";
        $content .= "    <div class=\"d-flex justify-content-between align-items-center mb-4\">\n";
        $content .= "        <h1><i class=\"{$config['icon']}\"></i> {$config['displayName']}</h1>\n";
        $content .= "        <a href=\"/{$config['pluralName']}/create\" class=\"btn btn-primary\"><i class=\"bi bi-plus-circle\"></i> Add New</a>\n";
        $content .= "    </div>\n";
        $content .= "    <div class=\"card\"><div class=\"card-body\">\n";
        $content .= "        <div class=\"table-responsive\"><table class=\"table table-hover\">\n";
        $content .= "            <thead><tr><th>ID</th><th>Details</th><th>Actions</th></tr></thead>\n";
        $content .= "            <tbody>\n";
        $content .= "                <?php foreach (\$entities as \$entity): ?>\n";
        $content .= "                <tr><td><?= \$entity->id ?></td><td><!-- Add fields --></td>\n";
        $content .= "                <td><a href=\"/{$config['pluralName']}/<?= \$entity->id ?>\" class=\"btn btn-sm btn-outline-primary\">View</a></td></tr>\n";
        $content .= "                <?php endforeach; ?>\n";
        $content .= "            </tbody>\n";
        $content .= "        </table></div>\n";
        $content .= "        <?php \$currentPage = \$page; \$baseUrl = '/{$config['pluralName']}'; include __DIR__ . '/../../../../views/components/pagination.php'; ?>\n";
        $content .= "    </div></div>\n</div>\n<?php include __DIR__ . '/../../../../includes/footer.php'; ?>";

        file_put_contents($basePath . '/list.php', $content);
    }

    private static function generateDetailPage($basePath, $entityClass, $config): void
    {
        $content = "<?php\nuse {$entityClass};\n\$id = \$_GET['id'] ?? null;\nif (!\$id) { redirect('/{$config['pluralName']}'); exit; }\n";
        $content .= "\$entity = {$entityClass}::find(\$id);\nif (!\$entity) { \$_SESSION['error'] = 'Not found'; redirect('/{$config['pluralName']}'); exit; }\n";
        $content .= "\$pageTitle = \$entity->id;\ninclude __DIR__ . '/../../../../includes/header.php';\n";
        $content .= "?>\n<div class=\"container-fluid mt-4\"><h1>{$config['displayName']} Details</h1>\n";
        $content .= "<div class=\"card\"><div class=\"card-body\"><p>ID: <?= \$entity->id ?></p></div></div>\n";
        $content .= "</div>\n<?php include __DIR__ . '/../../../../includes/footer.php'; ?>";

        file_put_contents($basePath . '/detail.php', $content);
    }

    private static function generateCreatePage($basePath, $entityClass, $config): void
    {
        $content = "<?php\n\$pageTitle = 'Add {$config['displayName']}';\ninclude __DIR__ . '/../../../../includes/header.php';\n";
        $content .= "?>\n<div class=\"container-fluid mt-4\"><h1>Add {$config['displayName']}</h1>\n";
        $content .= "<form method=\"POST\" action=\"/{$config['pluralName']}/store\">\n";
        $content .= "<?= csrf_field() ?>\n<!-- Add form fields -->\n";
        $content .= "<button type=\"submit\" class=\"btn btn-primary\">Save</button>\n";
        $content .= "</form></div>\n<?php include __DIR__ . '/../../../../includes/footer.php'; ?>";

        file_put_contents($basePath . '/create.php', $content);
    }

    private static function generateEditPage($basePath, $entityClass, $config): void
    {
        $content = "<?php\nuse {$entityClass};\n\$id = \$_GET['id'] ?? null;\nif (!\$id) { redirect('/{$config['pluralName']}'); exit; }\n";
        $content .= "\$entity = {$entityClass}::find(\$id);\nif (!\$entity) { redirect('/{$config['pluralName']}'); exit; }\n";
        $content .= "\$pageTitle = 'Edit {$config['displayName']}';\ninclude __DIR__ . '/../../../../includes/header.php';\n";
        $content .= "?>\n<div class=\"container-fluid mt-4\"><h1>Edit {$config['displayName']}</h1>\n";
        $content .= "<form method=\"POST\" action=\"/{$config['pluralName']}/<?= \$entity->id ?>/update\">\n";
        $content .= "<?= csrf_field() ?>\n<!-- Add form fields -->\n";
        $content .= "<button type=\"submit\" class=\"btn btn-primary\">Update</button>\n";
        $content .= "</form></div>\n<?php include __DIR__ . '/../../../../includes/footer.php'; ?>";

        file_put_contents($basePath . '/edit.php', $content);
    }

    private static function generateStore($basePath, $entityClass): void
    {
        $content = "<?php\nuse {$entityClass};\nif (\$_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf()) { redirect('/'); exit; }\n";
        $content .= "\$entity = new {$entityClass}();\n\$entity->fill(\$_POST);\n";
        $content .= "if (\$entity->save()) { \$_SESSION['success'] = 'Saved!'; redirect('/' . basename(dirname(__FILE__)) . '/' . \$entity->id); } else { \$_SESSION['_errors'] = \$entity->getErrors(); redirect('/' . basename(dirname(__FILE__)) . '/create'); }";

        file_put_contents($basePath . '/store.php', $content);
    }

    private static function generateUpdate($basePath, $entityClass): void
    {
        $content = "<?php\nuse {$entityClass};\n\$id = \$_POST['id'] ?? \$_GET['id'] ?? null;\n";
        $content .= "if (\$_SERVER['REQUEST_METHOD'] !== 'POST' || !\$id || !verify_csrf()) { redirect('/'); exit; }\n";
        $content .= "\$entity = {$entityClass}::find(\$id);\nif (!\$entity) { redirect('/'); exit; }\n";
        $content .= "\$entity->fill(\$_POST);\nif (\$entity->save()) { \$_SESSION['success'] = 'Updated!'; redirect('/' . basename(dirname(__FILE__)) . '/' . \$entity->id); } else { \$_SESSION['_errors'] = \$entity->getErrors(); redirect('/' . basename(dirname(__FILE__)) . '/' . \$id . '/edit'); }";

        file_put_contents($basePath . '/update.php', $content);
    }

    private static function generateDelete($basePath, $entityClass): void
    {
        $content = "<?php\nuse {$entityClass};\n\$id = \$_POST['id'] ?? \$_GET['id'] ?? null;\n";
        $content .= "if (\$_SERVER['REQUEST_METHOD'] !== 'POST' || !\$id || !verify_csrf()) { redirect('/'); exit; }\n";
        $content .= "\$entity = {$entityClass}::find(\$id);\nif (\$entity && \$entity->delete()) { \$_SESSION['success'] = 'Deleted!'; } else { \$_SESSION['error'] = 'Failed to delete'; }\n";
        $content .= "redirect('/' . basename(dirname(__FILE__)));";

        file_put_contents($basePath . '/delete.php', $content);
    }
}
