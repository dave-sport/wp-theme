<?php
/**
 * Setup Menu Locations
 * 
 * Run via wp-cli:
 * php ~/wp-cli.phar --path=/path/to/public_html eval-file setup-menus.php
 * 
 * Sets "Navigation" menu to primary location
 * Sets "Footer" menu to footer_menu location
 */

// Get all menus
$menus = wp_get_nav_menus();

$navigation_menu_id = null;
$footer_menu_id = null;

// Find Navigation and Footer menus
foreach ($menus as $menu) {
    if (strtolower($menu->name) === 'navigation') {
        $navigation_menu_id = $menu->term_id;
    }
    if (strtolower($menu->name) === 'footer') {
        $footer_menu_id = $menu->term_id;
    }
}

// Get current locations
$locations = get_theme_mod('nav_menu_locations', []);

// Assign Navigation to primary
if ($navigation_menu_id) {
    $locations['primary'] = $navigation_menu_id;
    echo "✅ Assigned 'Navigation' menu (ID: {$navigation_menu_id}) to primary location\n";
} else {
    echo "⚠️  'Navigation' menu not found\n";
}

// Assign Footer to footer_menu
if ($footer_menu_id) {
    $locations['footer_menu'] = $footer_menu_id;
    echo "✅ Assigned 'Footer' menu (ID: {$footer_menu_id}) to footer_menu location\n";
} else {
    echo "⚠️  'Footer' menu not found\n";
}

// Save locations
set_theme_mod('nav_menu_locations', $locations);

echo "\n📋 Current menu locations:\n";
foreach ($locations as $location => $menu_id) {
    $menu = wp_get_nav_menu_object($menu_id);
    $menu_name = $menu ? $menu->name : 'None';
    echo "  {$location}: {$menu_name} (ID: {$menu_id})\n";
}

echo "\n✨ Done!\n";
