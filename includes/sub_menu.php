<?php
// Generate sub-menu based on current page context
function generateSubMenu($current_page, $menu_structure, $menu_context) {
    // If we're on Dashboard (level 1), no sub-menu needed
    if ($menu_context['level'] === 1) {
        return '';
    }

    $html = '<div class="container-fluid mt-3 mb-4">';
    $html .= '<nav aria-label="Secondary navigation">';
    $html .= '<ol class="breadcrumb">';

    // Level 2 pages (under main sections)
    if ($menu_context['level'] === 2) {
        $section = $menu_structure[$menu_context['section']];

        // Add breadcrumb
        $html .= '<li class="breadcrumb-item">';
        $html .= '<span class="nav-icon">' . $section['icon'] . '</span> ' . $menu_context['section'];
        $html .= '</li>';

        // Show all items in this section
        $html .= '</ol>';
        $html .= '<div class="btn-toolbar" role="toolbar" aria-label="Section navigation">';
        $html .= '<div class="btn-group me-2" role="group">';

        foreach ($section['items'] as $page => $item) {
            $isActive = ($page === $current_page) ? 'btn-primary' : 'btn-outline-secondary';
            $html .= '<a href="' . $page . '.php" class="btn ' . $isActive . ' btn-sm">';
            $html .= '<span class="nav-icon">' . $item['icon'] . '</span> ' . $item['title'];
            $html .= '</a>';
        }

        $html .= '</div>';
        $html .= '</div>';
    }

    // Level 3 pages (sub-items)
    if ($menu_context['level'] === 3) {
        $section = $menu_structure[$menu_context['section']];
        $parent_item = $section['items'][$menu_context['parent']];

        // Add breadcrumb
        $html .= '<li class="breadcrumb-item">';
        $html .= '<span class="nav-icon">' . $section['icon'] . '</span> ' . $menu_context['section'];
        $html .= '</li>';
        $html .= '<li class="breadcrumb-item">';
        $html .= '<a href="' . $menu_context['parent'] . '.php" class="text-decoration-none">';
        $html .= '<span class="nav-icon">' . $parent_item['icon'] . '</span> ' . $parent_item['title'];
        $html .= '</a>';
        $html .= '</li>';
        $html .= '<li class="breadcrumb-item active" aria-current="page">';
        $current_item = $parent_item['sub_items'][$current_page];
        $html .= '<span class="nav-icon">' . $current_item['icon'] . '</span> ' . $current_item['title'];
        $html .= '</li>';

        $html .= '</ol>';

        // Show parent and sub-items
        $html .= '<div class="btn-toolbar" role="toolbar" aria-label="Sub-section navigation">';
        $html .= '<div class="btn-group me-2" role="group">';

        // Parent item
        $isParentActive = ($menu_context['parent'] === $current_page) ? 'btn-primary' : 'btn-outline-secondary';
        $html .= '<a href="' . $menu_context['parent'] . '.php" class="btn ' . $isParentActive . ' btn-sm">';
        $html .= '<span class="nav-icon">' . $parent_item['icon'] . '</span> ' . $parent_item['title'];
        $html .= '</a>';

        // Sub-items
        if (isset($parent_item['sub_items'])) {
            foreach ($parent_item['sub_items'] as $sub_page => $sub_item) {
                $isActive = ($sub_page === $current_page) ? 'btn-primary' : 'btn-outline-secondary';
                $html .= '<a href="' . $sub_page . '.php" class="btn ' . $isActive . ' btn-sm">';
                $html .= '<span class="nav-icon">' . $sub_item['icon'] . '</span> ' . $sub_item['title'];
                $html .= '</a>';
            }
        }

        $html .= '</div>';
        $html .= '</div>';
    }

    // Level 4 pages (sub-sub-items)
    if ($menu_context['level'] === 4) {
        $section = $menu_structure[$menu_context['section']];
        $parent_item = $section['items'][$menu_context['parent']];
        $grandparent_item = $parent_item['sub_items'][$menu_context['grandparent']];

        // Add breadcrumb
        $html .= '<li class="breadcrumb-item">';
        $html .= '<span class="nav-icon">' . $section['icon'] . '</span> ' . $menu_context['section'];
        $html .= '</li>';
        $html .= '<li class="breadcrumb-item">';
        $html .= '<a href="' . $menu_context['parent'] . '.php" class="text-decoration-none">';
        $html .= '<span class="nav-icon">' . $parent_item['icon'] . '</span> ' . $parent_item['title'];
        $html .= '</a>';
        $html .= '</li>';
        $html .= '<li class="breadcrumb-item">';
        $html .= '<a href="' . $menu_context['grandparent'] . '.php" class="text-decoration-none">';
        $html .= '<span class="nav-icon">' . $grandparent_item['icon'] . '</span> ' . $grandparent_item['title'];
        $html .= '</a>';
        $html .= '</li>';
        $html .= '<li class="breadcrumb-item active" aria-current="page">';
        $current_item = $grandparent_item['sub_items'][$current_page];
        $html .= '<span class="nav-icon">' . $current_item['icon'] . '</span> ' . $current_item['title'];
        $html .= '</li>';

        $html .= '</ol>';

        // Show parent, grandparent and sub-sub-items
        $html .= '<div class="btn-toolbar" role="toolbar" aria-label="Sub-sub-section navigation">';
        $html .= '<div class="btn-group me-2" role="group">';

        // Parent item
        $isParentActive = ($menu_context['parent'] === $current_page) ? 'btn-primary' : 'btn-outline-secondary';
        $html .= '<a href="' . $menu_context['parent'] . '.php" class="btn ' . $isParentActive . ' btn-sm">';
        $html .= '<span class="nav-icon">' . $parent_item['icon'] . '</span> ' . $parent_item['title'];
        $html .= '</a>';

        // Grandparent item
        $isGrandparentActive = ($menu_context['grandparent'] === $current_page) ? 'btn-primary' : 'btn-outline-secondary';
        $html .= '<a href="' . $menu_context['grandparent'] . '.php" class="btn ' . $isGrandparentActive . ' btn-sm">';
        $html .= '<span class="nav-icon">' . $grandparent_item['icon'] . '</span> ' . $grandparent_item['title'];
        $html .= '</a>';

        // Sub-sub-items
        if (isset($grandparent_item['sub_items'])) {
            foreach ($grandparent_item['sub_items'] as $sub_sub_page => $sub_sub_item) {
                $isActive = ($sub_sub_page === $current_page) ? 'btn-primary' : 'btn-outline-secondary';
                $html .= '<a href="' . $sub_sub_page . '.php" class="btn ' . $isActive . ' btn-sm">';
                $html .= '<span class="nav-icon">' . $sub_sub_item['icon'] . '</span> ' . $sub_sub_item['title'];
                $html .= '</a>';
            }
        }

        $html .= '</div>';
        $html .= '</div>';
    }

    $html .= '</nav>';
    $html .= '</div>';

    return $html;
}

// Output the sub-menu
echo generateSubMenu($current_page, $menu_structure, $menu_context);
?>