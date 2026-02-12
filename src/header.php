<!doctype html>
<html <?php language_attributes(); ?> class="no-js" data-theme="light">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="<?php echo esc_attr(moodco_config('colors.primary', '#a60344')); ?>">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php
$site_name = moodco_config('name', get_bloginfo('name'));
$header_title = moodco_config('header_title', $site_name . ':');
$logo_url = moodco_get_logo('header');
$news_bar_items = moodco_config('news_bar', ["This is demo breaking news","This is demo breaking news 3"]);
?>

<!-- Side Menu -->
<div class="sidemenu-wrapper sidemenu-1 d-none d-md-block">
    <div class="sidemenu-content">
        <button class="closeButton sideMenuCls"><?php echo moodco_svg_icon('close'); ?></button>
        <div class="widget">
            <div class="th-widget-about">
                <div class="about-logo">
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <img class="light-img" src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($site_name); ?>">
                    </a>
                </div>
                <p class="about-text"><?php echo esc_html(moodco_config('description', get_bloginfo('description'))); ?></p>
                <div class="th-mobile-menu desktop_side_menu">
                    <?php wp_nav_menu(['theme_location' => 'primary', 'container' => false, 'items_wrap' => '<ul>%3$s</ul>', 'fallback_cb' => false]); ?>
                </div>
                <?php moodco_render_social_links('th-social style-black'); ?>
            </div>
        </div>
        <?php if (moodco_config('features.newsletter', false)): ?>
        <div class="widget newsletter-widget">
            <h3 class="widget_title">Subscribe</h3>
            <p class="footer-text">Sign up to get update about us. Don't hesitate, your email is safe.</p>
            <?php echo do_shortcode('[sibwp_form id=2]'); ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Mobile Menu -->
<div class="th-menu-wrapper">
    <div class="th-menu-area text-center">
        <button class="th-menu-toggle"><?php echo moodco_svg_icon('close'); ?></button>
        <div class="mobile-logo">
            <a href="<?php echo esc_url(home_url('/')); ?>">
                <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($site_name); ?>">
            </a>
        </div>
        <div class="th-mobile-menu">
            <?php wp_nav_menu(['theme_location' => 'primary', 'container' => false, 'items_wrap' => '<ul>%3$s</ul>', 'fallback_cb' => false]); ?>
        </div>
    </div>
</div>

<!-- Header -->
<header class="th-header header-layout5 dark-theme">
    <div class="sticky-wrapper">
        <div class="container">
            <div class="row gx-0">
                <div class="col-lg-2 d-none d-lg-inline-block">
                    <div class="header-logo">
                        <a href="<?php echo esc_url(home_url('/')); ?>">
                            <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($site_name); ?>">
                        </a>
                    </div>
                </div>
                <div class="col-lg-10">
                    <div class="header-top">
                        <div class="row align-items-center">
                            <div class="col-xl-8">
                                <div class="news-area">
                                    <div class="title"><?php echo esc_html($header_title); ?></div>
                                    <div class="news-wrap">
                                        <div class="row slick-marquee">
                                            <?php foreach ($news_bar_items as $item): ?>
                                                <div class="col-auto">
                                                    <a class="breaking-news"><?php echo esc_html($item); ?></a>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 text-end d-none d-xl-block">
                                <div class="social-links">
                                    <span class="social-title">Follow Us :</span>
                                    <?php
                                    $social = moodco_config('social_media', []);
                                    $social = array_filter($social);
                                    foreach ($social as $platform => $url) {
                                        $icon = moodco_get_social_icon($platform);
                                        $icon = moodco_get_social_icon($platform);
                                        echo '<a href="' . esc_url($url) . '" target="_blank">';
                                        echo $icon;
                                        echo '</a>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="menu-area">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto d-none d-xl-block">
                                <div class="toggle-icon">
                                    <a href="#" class="simple-icon sideMenuToggler"><?php echo moodco_svg_icon('menu'); ?></a>
                                </div>
                            </div>
                            <div class="col-7 d-lg-none d-block">
                                <div class="header-logo">
                                    <a href="<?php echo esc_url(home_url('/')); ?>">
                                        <img class="light-img" src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($site_name); ?>">
                                    </a>
                                </div>
                            </div>
                            <div class="col-auto">
                                <nav class="main-menu d-none d-lg-inline-block">
                                    <?php wp_nav_menu(['theme_location' => 'primary', 'container' => false, 'items_wrap' => '<ul>%3$s</ul>', 'fallback_cb' => false]); ?>
                                </nav>
                            </div>
                            <div class="col-auto">
                                <div class="header-button">
                                    <?php if (moodco_config('features.contact_form', true)): ?>
                                        <a href="<?php echo esc_url(home_url('/contact-us')); ?>" class="th-btn style3">Contact Us</a>
                                    <?php endif; ?>
                                    <button type="button" class="th-menu-toggle d-block d-lg-none"><?php echo moodco_svg_icon('menu'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
