<?php
/**
 * Theme setup — menus, theme supports, widgets, image sizes.
 */

function moodco_theme_setup() {
    // Theme supports
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);

    // Navigation menus
    register_nav_menus([
        'primary'        => __('Primary Menu', 'davesport'),
        'products'       => __('Products Menu', 'davesport'),
        'quick'          => __('Quick Links', 'davesport'),
        'footer_menu'    => __('Footer Menu', 'davesport'),
        'copyright_menu' => __('Copyright Menu', 'davesport'),
    ]);

    // Custom image sizes
    add_image_size('hero-large', 800, 500, true);
    add_image_size('post-card', 400, 250, true);
    add_image_size('post-card-small', 200, 125, true);
}
add_action('after_setup_theme', 'moodco_theme_setup');

/**
 * Register widget areas.
 */
function moodco_widgets_init() {
    register_sidebar([
        'name'          => __('Main Sidebar', 'davesport'),
        'id'            => 'main-sidebar',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ]);
}
add_action('widgets_init', 'moodco_widgets_init');
