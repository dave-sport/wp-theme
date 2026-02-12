<?php get_header();?>
<!--==============================
    Breadcumb
============================== -->
<div class="breadcumb-wrapper">
    <div class="container">
        <ul class="breadcumb-menu">
            <li>
                <a href="<?php echo home_url(); ?>">Home</a>
            </li>
            <?php
            $current_cat = get_queried_object();
            if ( $current_cat->parent != 0 ) {
                $parent_cat = get_category( $current_cat->parent );
                echo '<li><a href="' . get_category_link( $parent_cat->term_id ) . '">' . esc_html( $parent_cat->name ) . '</a></li>';
            }
            ?>
            <li>
                <?php echo esc_html( single_cat_title('', false) ); ?>
            </li>
        </ul>
    </div>
</div>
<!--==============================
    Category Section Start
============================== -->

<?php if ( have_posts() ) : ?>

<section class="space">
    <div class="container">
		<div class="row gy-30">

			<?php while ( have_posts() ) : the_post(); ?>

			<div class="col-xl-3 col-lg-4 col-sm-6">
				<div class="blog-style1">

					<div class="blog-img">
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail('medium'); ?>
						</a>

						<?php
						$post_categories = get_the_category();
						$cat = null;

						// Get current category from URL (archive/category page)
						$current_cat = get_queried_object();
						if ( $current_cat && ! is_wp_error($current_cat) ) {
							// Check if post has this category
							foreach ( $post_categories as $c ) {
								if ( $c->term_id == $current_cat->term_id ) {
									$cat = $c;
									break;
								}
							}
						}

						// Fallback: first category
						if ( ! $cat && !empty($post_categories) ) {
							$cat = $post_categories[0];
						}

						if ( $cat ) :
						$icon_default = get_field('icon_default', 'category_' . $cat->term_id);
						$icon_hover   = get_field('icon_hover', 'category_' . $cat->term_id);

						if ( is_array($icon_default) && isset($icon_default['url']) ) {
							$icon_default = $icon_default['url'];
						}
						if ( is_array($icon_hover) && isset($icon_hover['url']) ) {
							$icon_hover = $icon_hover['url'];
						}
						?>
						<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="category">
							<?php if ( $icon_default ): ?>
							<img src="<?php echo esc_url($icon_default); ?>" class="category_icon_default" alt="<?php echo esc_attr($cat->name); ?>">
							<?php endif; ?>

							<?php if ( $icon_hover ): ?>
							<img src="<?php echo esc_url($icon_hover); ?>" class="category_icon_hover" alt="<?php echo esc_attr($cat->name); ?>">
							<?php endif; ?>

							<?php echo esc_html( $cat->name ); ?>
						</a>
						<?php endif; ?>
					</div>

					<h3 class="box-title-20 text_clip" >
						<a class="hover-line" href="<?php the_permalink(); ?>">
							<?php the_title(); ?>
						</a>
					</h3>

					<div class="blog-meta">
						<a href="<?php echo get_author_posts_url( get_the_author_meta('ID') ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>By <?php the_author(); ?>
						</a>
						<a href="#">
							<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> <?php echo get_the_date(); ?>
						</a>
					</div>

				</div>
			</div>

			<?php endwhile; ?>

		</div>


        <!-- Pagination -->
        <div class="th-pagination mt-40 mb-0 text-center">
            <?php
            echo paginate_links([
                'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>',
                'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>',
            ]);
            ?>
        </div>

    </div>
</section>

<?php else : ?>

<section class="space">
    <div class="container text-center">
        <h2>No Posts Found</h2>
        <p>There are currently no posts in this category.</p>
    </div>
</section>

<?php endif; ?>

<!--==============================
    Category Section End
============================== -->



<?php get_footer();?>