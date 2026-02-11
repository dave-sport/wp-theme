<?php get_header();?>
<!--==============================
    Breadcumb
============================== -->
<div class="breadcumb-wrapper">
    <div class="container">
        <ul class="breadcumb-menu">

            <!-- Home -->
            <li>
                <a href="<?php echo esc_url( home_url('/') ); ?>">Home</a>
            </li>

            <?php
            /* =========================
             * JOB SINGLE PAGE
             * ========================= */
            if ( is_singular('jobpost') ) {

                // Job categories
                $terms = get_the_terms( get_the_ID(), 'job_category' );

                if ( ! empty($terms) && ! is_wp_error($terms) ) {
                    $term = $terms[0];
                    echo '<li><a href="' . esc_url( get_term_link($term) ) . '">' . esc_html($term->name) . '</a></li>';
                }

                echo '<li>' . esc_html( get_the_title() ) . '</li>';
            }

            /* =========================
             * JOB CATEGORY PAGE
             * ========================= */
            elseif ( is_tax('job_category') ) {

                $current_term = get_queried_object();

                if ( $current_term->parent != 0 ) {
                    $parent_term = get_term( $current_term->parent, 'job_category' );
                    echo '<li><a href="' . esc_url( get_term_link($parent_term) ) . '">' . esc_html($parent_term->name) . '</a></li>';
                }

                echo '<li>' . esc_html($current_term->name) . '</li>';
            }

            /* =========================
             * NORMAL PAGE
             * ========================= */
            elseif ( is_page() ) {
                echo '<li>' . esc_html( get_the_title() ) . '</li>';
            }
            ?>

        </ul>
    </div>
</div>

<!--==============================
    Category Section Start
============================== -->



<section class="space">
    <div class="container">
		
		<?php
		if ( have_posts() ) :
		while ( have_posts() ) : the_post();
		?>

		<h1 class="job-title"><?php the_title(); ?></h1>

		<div class="job-content">
			<?php the_content(); ?>
		</div>

		<?php
		endwhile;
		endif;
		?>

      
    </div>
</section>



<!--==============================
    Category Section End
============================== -->



<?php get_footer();?>