<?php get_header(); ?>
<section class="job-section">
    <div class="container">
        <h2 class="section-title text-center">Current Open Positions</h2>

        <div class="row justify-content-between">
<?php
$args = [
    'post_type'      => 'jobpost',
    'posts_per_page' => 9,
    'post_status'    => 'publish',
];

$jobs = new WP_Query($args);

if ($jobs->have_posts()) :
    while ($jobs->have_posts()) : $jobs->the_post();

        // Get taxonomy terms
        $job_locations = get_the_terms(get_the_ID(), 'jobpost_location');
        $job_types     = get_the_terms(get_the_ID(), 'jobpost_job_type');
        $job_categories = get_the_terms(get_the_ID(), 'jobpost_category');

        // Prepare comma separated names
        $location_names = $job_locations ? wp_list_pluck($job_locations, 'name') : [];
        $type_names     = $job_types ? wp_list_pluck($job_types, 'name') : [];
        $category_names = $job_categories ? wp_list_pluck($job_categories, 'name') : [];
?>
    <div class="col-12">
        <div class="job-card">

            <h3 class="job-title"><?php the_title(); ?></h3>

            <div class="job-meta">
                <?php if (!empty($type_names)) : ?>
                    <span class="job-type"><?php echo esc_html(implode(', ', $type_names)); ?></span>
                <?php endif; ?>

                <?php if (!empty($location_names)) : ?>
                    <span class="job-location"><?php echo esc_html(implode(', ', $location_names)); ?></span>
                <?php endif; ?>

                <?php if (!empty($category_names)) : ?>
                    <span class="job-category"><?php echo esc_html(implode(', ', $category_names)); ?></span>
                <?php endif; ?>
            </div>

            <p class="job-desc">
                <?php
                    $short_desc = has_excerpt() ? get_the_excerpt() : get_the_content();
                    echo esc_html(wp_trim_words($short_desc, 50));
                ?>
            </p>

            <a href="<?php the_permalink(); ?>" class="th-btn style3">Apply Now!</a>
        </div>
    </div>
<?php
    endwhile;
    wp_reset_postdata();
else :
    echo '<p class="text-center">No open positions available.</p>';
endif;
?>
</div>

    </div>
</section>

<?php get_footer(); ?>