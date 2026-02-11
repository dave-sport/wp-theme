<?php
/**
 * Social media helper functions.
 */

/**
 * Get social media SVG icon.
 */
function moodco_get_social_icon($platform) {
    return moodco_svg_icon($platform, 'social-icon');
}

/**
 * Render social media links from config.
 */
function moodco_render_social_links($wrapper_class = 'th-social style-black') {
    $social = moodco_config('social_media', []);
    $social = array_filter($social); // Remove nulls

    if (empty($social)) return;

    echo '<div class="' . esc_attr($wrapper_class) . '">';
    foreach ($social as $platform => $url) {
        $icon = moodco_get_social_icon($platform);
        echo '<a href="' . esc_url($url) . '" target="_blank">';
        echo $icon;
        echo '</a>';
    }
    echo '</div>';
}
