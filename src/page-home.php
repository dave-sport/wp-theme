<?php
/**
 * Template Name: Home
 * 
 * Config-driven homepage. Sections defined in sites/{site_key}.json
 */

get_header();

// Hero slider
get_template_part('template-parts/hero-slider');

// Category sections from config
$sections = moodco_config('homepage.sections', []);

foreach ($sections as $section) {
    get_template_part('template-parts/homepage-section', null, [
        'parent_slug' => $section['parent_category_slug'] ?? '',
        'sub_count'   => $section['subcategory_count'] ?? 3,
    ]);
}

get_footer();
