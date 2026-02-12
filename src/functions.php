<?php
/**
 * DaveSport Theme - functions.php
 * 
 * Clean, modular. All functionality split into inc/ files.
 * Site-specific config lives in sites/{site_key}.json
 */

// Config loader (must be first — other files depend on it)
require_once get_template_directory() . '/inc/config-loader.php';

// Theme setup (menus, supports, widgets)
require_once get_template_directory() . '/inc/setup.php';

// Asset enqueue (all CSS/JS)
require_once get_template_directory() . '/inc/enqueue.php';

// Social media helper
require_once get_template_directory() . '/inc/social-helper.php';

// SVG icons
require_once get_template_directory() . '/inc/svg-icons.php';

/**
 * Helper: Get category icon (ACF field).
 */
function moodco_get_category_icon($term_id, $state = 'default') {
    if (!function_exists('get_field')) return '';
    $field = ($state === 'hover') ? 'icon_hover' : 'icon_default';
    $icon = get_field($field, 'category_' . $term_id);
    return $icon ? $icon['url'] : '';
}

/**
 * Helper: Truncate text to a word boundary.
 */
function moodco_excerpt($text, $length = 20) {
    $text = wp_strip_all_tags($text);
    $words = explode(' ', $text);
    if (count($words) > $length) {
        return implode(' ', array_slice($words, 0, $length)) . '...';
    }
    return $text;
}

/**
 * Helper: Time ago string.
 */
function moodco_time_ago($post_id = null) {
    $time = get_the_time('U', $post_id);
    $diff = time() - $time;
    
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff / 60) . ' mins ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
    if ($diff < 604800) return floor($diff / 86400) . ' days ago';
    
    return get_the_date('M d, Y', $post_id);
}


// ACF Theme Option
include('inc/acf-fields.php');
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Theme Options',
        'menu_title' => 'Theme Options',
        'menu_slug'  => 'theme-options',
        'capability' => 'edit_posts',
        'redirect'   => false
    ));
}

// Shortcode to return site title
function reads_title_shortcode() {
    return get_bloginfo('name');
}
add_shortcode('site_title', 'reads_title_shortcode');

// Enable shortcode in post titles
add_filter('the_title', 'do_shortcode');

