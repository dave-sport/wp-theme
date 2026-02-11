<?php
/**
 * Enqueue all CSS and JS assets properly via WordPress.
 */

function moodco_enqueue_assets() {
    $theme_uri = get_template_directory_uri();
    $ver = wp_get_theme()->get('Version');

    // ── CSS ──
    wp_enqueue_style('bootstrap', $theme_uri . '/assets/css/bootstrap.min.css', [], '5.0');
    wp_enqueue_style('davesport-main', $theme_uri . '/assets/css/style.css', ['bootstrap'], $ver);
    wp_enqueue_style('davesport-custom', $theme_uri . '/assets/css/custom.css', ['davesport-main'], $ver);

    // Google Fonts
    $fonts = moodco_config('fonts', []);
    $families = [];
    if (!empty($fonts['title']['family'])) $families[] = str_replace(' ', '+', $fonts['title']['family']) . ':wght@400;500;600;700;800;900';
    if (!empty($fonts['body']['family']))  $families[] = str_replace(' ', '+', $fonts['body']['family']) . ':wght@300;400;500;600;700';
    if (!empty($families)) {
        wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=' . implode('&family=', $families) . '&display=swap', [], null);
    }

    // ── JS ──
    // Vanilla JS theme (no jQuery!)
    wp_enqueue_script('davesport-theme', $theme_uri . '/assets/js/theme.js', [], $ver, true);
    
    // Homepage-only scripts
    if (is_front_page()) {
        wp_enqueue_script('davesport-hero-slider', $theme_uri . '/assets/js/hero-slider.js', [], $ver, true);
        wp_enqueue_script('davesport-tabs', $theme_uri . '/assets/js/tabs.js', [], $ver, true);
    }

    // Pass config to JS
    wp_localize_script('davesport-main', 'davesportConfig', [
        'heroCount' => moodco_config('homepage.hero_count', 12),
        'siteName'  => moodco_config('name', get_bloginfo('name')),
    ]);
}
add_action('wp_enqueue_scripts', 'moodco_enqueue_assets');
