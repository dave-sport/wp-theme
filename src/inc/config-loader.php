<?php
/**
 * Config Loader
 * 
 * Reads per-site JSON config based on MOODCO_SITE_KEY constant.
 * Falls back to _template.json if no site-specific config found.
 */

function moodco_get_config() {
    static $config = null;
    if ($config !== null) return $config;

    $site_key = defined('MOODCO_SITE_KEY') ? MOODCO_SITE_KEY : '_template';
    $config_dir = get_template_directory() . '/sites';
    $config_file = $config_dir . '/' . $site_key . '.json';

    if (!file_exists($config_file)) {
        $config_file = $config_dir . '/_template.json';
    }

    if (file_exists($config_file)) {
        $config = json_decode(file_get_contents($config_file), true);
    }

    if (!$config) {
        $config = [
            'site_key' => 'default',
            'name' => get_bloginfo('name'),
            'colors' => ['primary' => '#a60344', 'primary2' => '#3D72FC'],
            'homepage' => ['hero_count' => 12, 'sections' => []],
        ];
    }

    return $config;
}

/**
 * Get a nested config value with dot notation.
 * e.g. moodco_config('colors.primary', '#a60344')
 */
function moodco_config($key, $default = null) {
    $config = moodco_get_config();
    $keys = explode('.', $key);
    $value = $config;

    foreach ($keys as $k) {
        if (!is_array($value) || !isset($value[$k])) return $default;
        $value = $value[$k];
    }

    return $value;
}

/**
 * Inject CSS custom properties from config.
 * Uses wp_add_inline_style to ensure it loads AFTER the main stylesheet.
 */
function moodco_inject_css_vars() {
    $colors = moodco_config('colors', []);
    $fonts = moodco_config('fonts', []);

    $vars = [];
    if (!empty($colors['primary']))      $vars[] = '--theme-color: ' . $colors['primary'];
    if (!empty($colors['primary2']))     $vars[] = '--theme-color2: ' . $colors['primary2'];
    if (!empty($colors['title']))        $vars[] = '--title-color: ' . $colors['title'];
    if (!empty($colors['body']))         $vars[] = '--body-color: ' . $colors['body'];
    if (!empty($colors['smoke']))        $vars[] = '--smoke-color: ' . $colors['smoke'];
    if (!empty($colors['background']))   $vars[] = '--white-color: ' . $colors['background'];
    if (!empty($colors['header_bg']))    $vars[] = '--header-bg: ' . $colors['header_bg'];
    if (!empty($colors['header_top']))   $vars[] = '--header-top-bg: ' . $colors['header_top'];
    if (!empty($colors['menu_bg']))      $vars[] = '--menu-bg: ' . $colors['menu_bg'];
    if (!empty($colors['menu_link']))    $vars[] = '--menu-link: ' . $colors['menu_link'];

    if (!empty($fonts['title']['family'])) {
        $fb = $fonts['title']['fallback'] ?? 'sans-serif';
        $vars[] = '--title-font: "' . $fonts['title']['family'] . '", ' . $fb;
    }
    if (!empty($fonts['body']['family'])) {
        $fb = $fonts['body']['fallback'] ?? 'sans-serif';
        $vars[] = '--body-font: "' . $fonts['body']['family'] . '", ' . $fb;
    }

    if (!empty($vars)) {
        $inline_css = ":root { " . implode('; ', $vars) . "; }";
        wp_add_inline_style('davesport-main', $inline_css);
    }
}
add_action('wp_enqueue_scripts', 'moodco_inject_css_vars', 20);

/**
 * Get logo URL from config.
 */
function moodco_get_logo($type = 'header') {
    $config_path = moodco_config('logos.' . $type);
    if ($config_path) {
        return get_template_directory_uri() . '/' . $config_path;
    }
    return '';
}

/**
 * Disable WordPress default site icon (we use config favicons).
 * Remove all WP favicon/site icon output.
 */
function moodco_disable_wp_site_icon() {
    // Remove site icon from wp_head
    remove_action('wp_head', 'wp_site_icon', 99);
    
    // Remove site icon from login/admin
    remove_action('login_head', 'wp_site_icon', 99);
    remove_action('admin_head', 'wp_site_icon', 99);
}
add_action('init', 'moodco_disable_wp_site_icon');

/**
 * Inject favicon tags from config.
 */
function moodco_inject_favicons() {
    $theme_uri = get_template_directory_uri();

    $favicon = moodco_config('logos.favicon');
    $favicon_16 = moodco_config('logos.favicon_16');
    $apple = moodco_config('logos.apple_touch_icon');
    $android_192 = moodco_config('logos.android_192');
    $android_512 = moodco_config('logos.android_512');

    if (!$favicon && !$apple) return; // No config favicons, let WP handle it

    if ($favicon)    echo '<link rel="icon" type="image/png" sizes="32x32" href="' . esc_url($theme_uri . '/' . $favicon) . '">' . "\n";
    if ($favicon_16) echo '<link rel="icon" type="image/png" sizes="16x16" href="' . esc_url($theme_uri . '/' . $favicon_16) . '">' . "\n";
    if ($apple)      echo '<link rel="apple-touch-icon" sizes="180x180" href="' . esc_url($theme_uri . '/' . $apple) . '">' . "\n";

    // Web app manifest inline
    if ($android_192 || $android_512) {
        $icons = [];
        if ($android_192) $icons[] = '{"src":"' . esc_url($theme_uri . '/' . $android_192) . '","sizes":"192x192","type":"image/png"}';
        if ($android_512) $icons[] = '{"src":"' . esc_url($theme_uri . '/' . $android_512) . '","sizes":"512x512","type":"image/png"}';
        $manifest = '{"name":"' . esc_js(moodco_config('name', get_bloginfo('name'))) . '","icons":[' . implode(',', $icons) . ']}';
        echo '<link rel="manifest" href="data:application/manifest+json,' . rawurlencode($manifest) . '">' . "\n";
    }
}
add_action('wp_head', 'moodco_inject_favicons', 2);

/**
 * Inject analytics from config.
 */
function moodco_inject_analytics() {
    $gtag = moodco_config('analytics.gtag_id');
    $ahrefs = moodco_config('analytics.ahrefs_key');

    if ($gtag) {
        echo '<script async src="https://www.googletagmanager.com/gtag/js?id=' . esc_attr($gtag) . '"></script>';
        echo '<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag("js",new Date());gtag("config","' . esc_js($gtag) . '");</script>';
    }

    if ($ahrefs) {
        echo '<meta name="ahrefs-site-verification" content="' . esc_attr($ahrefs) . '">';
    }
}
add_action('wp_head', 'moodco_inject_analytics', 99);
