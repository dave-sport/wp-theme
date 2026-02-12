<?php
$site_name = moodco_config('name', get_bloginfo('name'));
$company = moodco_config('company', []);
$footer_logo = moodco_get_logo('footer');
$footer_desc = function_exists('get_field') ? get_field('footer_short_description', 'option') : '';
$footer_h1 = function_exists('get_field') ? get_field('footer_frist_heading', 'option') : 'Quick Links';
$footer_h2 = function_exists('get_field') ? get_field('footer_second_heading', 'option') : 'Explore';
$footer_h3 = function_exists('get_field') ? get_field('footer_thrid_heading', 'option') : 'Contact';
$social_media = function_exists('have_rows') && have_rows('social_media', 'options');
?>

<footer class="footer-wrapper footer-layout1" data-bg-src="<?php echo get_template_directory_uri(); ?>/assets/img/bg/footer_bg_1.png">
    <div class="widget-area">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-12 col-xl-4">
                    <div class="widget footer-widget">
                        <div class="th-widget-about">
                            <div class="about-logo">
                                <a href="<?php echo esc_url(home_url('/')); ?>">
                                    <img width="300" src="<?php echo esc_url($footer_logo ?: ''); ?>" alt="<?php echo esc_attr($site_name); ?>">
                                </a>
                            </div>
                            <p class="about-text"><?php echo esc_html($footer_desc); ?></p>
                            <div class="th-social style-black">
                                <?php if ($social_media): while (have_rows('social_media', 'options')): the_row(); ?>
                                    <a href="<?php the_sub_field('social_media_link'); ?>" target="_blank"><?php the_sub_field('social_media_icon'); ?></a>
                                <?php endwhile; endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-xl-2 col-12">
                    <div class="widget widget_nav_menu footer-widget">
                        <h3 class="widget_title footer-widget-title"><?php echo esc_html($footer_h1); ?></h3>
                        <div class="menu-all-pages-container">
                            <?php wp_nav_menu(['theme_location' => 'footer_menu', 'container' => 'nav', 'menu_class' => 'footer-menu', 'fallback_cb' => false]); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-xl-auto col-12">
                    <div class="widget widget_nav_menu footer-widget">
                        <h3 class="widget_title footer-widget-title"><?php echo esc_html($footer_h2); ?></h3>
                        <div class="menu-all-pages-container">
                            <?php wp_nav_menu(['theme_location' => 'quick', 'container' => 'nav', 'menu_class' => 'footer-menu', 'fallback_cb' => false]); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-xl-auto d-lg-none d-md-block col-12">
                    <div class="widget widget_nav_menu footer-widget">
                        <h3 class="widget_title footer-widget-title">Our Policy</h3>
                        <div class="menu-all-pages-container">
                            <?php wp_nav_menu(['theme_location' => 'copyright_menu', 'container' => 'nav', 'menu_class' => 'footer-menu', 'fallback_cb' => false]); ?>
                        </div>
                    </div>
                </div>
                <?php if (!empty($company)): ?>
                <div class="col-md-4 col-xl-3 col-12 address-09">
                    <div class="widget footer-widget">
                        <h3 class="widget_title footer-widget-title"><?php echo esc_html($footer_h3); ?></h3>
                        <div class="main-add">
                            <?php if (!empty($company['number'])): ?>
                            <div class="clum">
                                <h3 class="footer-info-label">Company Number:</h3>
                                <h6 class="footer-info-value"><?php echo esc_html($company['number']); ?></h6>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($company['address'])): ?>
                            <div class="clum">
                                <h3 class="footer-info-label">Registered Office:</h3>
                                <h6 class="footer-info-value"><?php echo esc_html($company['address']); ?></h6>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($company['name'])): ?>
                            <div class="clum">
                                <h3 class="footer-info-label">Company Name:</h3>
                                <h6 class="footer-info-value"><?php echo esc_html($company['name']); ?></h6>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="copyright-wrap">
        <div class="container">
            <div class="d-lg-block d-none">
                <div class="row justify-content-between align-items-center">
                    <div class="col-lg-12 col-12 br-1">
                        <div class="footer-links">
                            <?php wp_nav_menu(['theme_location' => 'copyright_menu', 'menu_class' => 'footer-links-menu', 'fallback_cb' => false]); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix" style="height:20px;"></div>
            <div class="row justify-content-between align-items-center br-1">
                <div class="col-lg-12">
                    <p class="copyright-text text-center">
                        &copy; <?php bloginfo('name'); ?> - Rights Reserved <?php echo date('Y'); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Scroll To Top -->
<div class="scroll-top">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;"></path>
        
    </svg>
</div>

<?php wp_footer(); ?>
</body>
</html>
