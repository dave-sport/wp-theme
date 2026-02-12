<?php get_header();?>


<?php
if ( have_posts() ) :
    while ( have_posts() ) : the_post();
?>

<!--==============================
    Breadcumb
============================== -->
    <div class="breadcumb-wrapper">
    <div class="container">
        <ul class="breadcumb-menu">
            <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>

            <?php
            $categories = get_the_category();

            if (!empty($categories)) {

                // Find the child category (category with a parent)
                $child_cat = null;

                foreach ($categories as $cat) {
                    if ($cat->parent != 0) {
                        $child_cat = $cat;
                        break;
                    }
                }

                // If no child category found, use first category
                if (!$child_cat) {
                    $child_cat = $categories[0];
                }

                // Show parent category if exists
                if ($child_cat->parent != 0) {
                    $parent_cat = get_category($child_cat->parent);
                    ?>
                    <li>
                        <a href="<?php echo esc_url(get_category_link($parent_cat->term_id)); ?>">
                            <?php echo esc_html($parent_cat->name); ?>
                        </a>
                    </li>
                    <?php
                }
                ?>

                <!-- Sub Category (underlined, no link) -->
                <li class="active">
                    <?php echo esc_html($child_cat->name); ?>
                </li>

            <?php } ?>
        </ul>
    </div>
</div>





<!--==============================
        Blog Area
    ==============================-->
    <section class="th-blog-wrapper blog-details space-top space-extra-bottom">
        <div class="container">
            <div class="row">
                <div class="col-xxl-9 col-lg-8">
                    <div class="th-blog blog-single">
                        <h2 class="blog-title"><?php the_title();?></h2>
                        <div class="blog-meta">
							<a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> By <?php the_author(); ?>
							</a>
							<a href="<?php the_permalink(); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg><?php echo get_the_date(); ?>
							</a>
						</div>
                        <div class="blog-img">
                            <?php if ( has_post_thumbnail() ) : ?>
								<img 
									src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ); ?>" 
									alt="<?php echo esc_attr( get_the_title() ); ?>">
							<?php endif; ?>

                        </div>
                        <div class="blog-content-wrap">
                            <div class="blog-content">
								<div class="content">
									<?php the_content();?>
								</div>
                            </div>
                        </div>
						<div class="blog-author">
							<div class="auhtor-img">
								<?php
								$author_id = get_the_author_meta('ID');
								$author_image_id = get_field('autor_profile', 'user_' . $author_id);

								if ($author_image_id) {
									echo wp_get_attachment_image($author_image_id, 'thumbnail');
								} else {
									echo get_avatar($author_id, 150);
								}
								?>
							</div>


							<div class="media-body">
								<div class="author-top">
									<div>
										<h3 class="author-name">
											<a class="text-inherit" href="<?php echo get_author_posts_url($author_id); ?>">
												<?php the_author(); ?>
											</a>
										</h3>
									</div>
									<?php
									$user_id = get_the_author_meta('ID');
									?>

									<div class="social-links author_social">

										<?php if ($facebook = get_field('autor_facebook', 'user_' . $user_id)) : ?>
										<a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="nofollow noopener">
											<i class="fab fa-facebook-f"></i>
										</a>
										<?php endif; ?>

										<?php if ($twitter = get_field('author_x', 'user_' . $user_id)) : ?>
										<a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="nofollow noopener">
											<i class="fab fa-twitter"></i>
										</a>
										<?php endif; ?>

										<?php if ($instagram = get_field('autor_instagram', 'user_' . $user_id)) : ?>
										<a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="nofollow noopener">
											<i class="fab fa-instagram"></i>
										</a>
										<?php endif; ?>

										<?php if ($linkedin = get_field('autor_linkedin', 'user_' . $user_id)) : ?>
										<a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="nofollow noopener">
											<i class="fab fa-linkedin-in"></i>
										</a>
										<?php endif; ?>

										<?php if ($tiktok = get_field('autor_tiktok', 'user_' . $user_id)) : ?>
										<a href="<?php echo esc_url($tiktok); ?>" target="_blank" rel="nofollow noopener">
											<i class="fab fa-tiktok"></i>
										</a>
										<?php endif; ?>

									</div>


								</div>
								<p class="author-text">
									<?php the_author_meta('description', $author_id); ?>
								</p>
							</div>
						</div>
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4 sidebar-wrap">
                    <aside class="sidebar-area">
                        
						<?php
						// Detect parent category
						if (is_category()) {
							$current_cat = get_queried_object();
							$parent_id   = $current_cat->term_id;
						} elseif (is_single()) {
							$cats = get_the_category();
							$parent_id = !empty($cats) ? $cats[0]->term_id : 0;
						} else {
							$parent_id = 0;
						}
					

						$subcategories = get_categories([
							'taxonomy'   => 'category',
							'parent'     => $parent_id,
							'hide_empty' => true,
						]);

						if (!empty($subcategories)) :
						?>
						<div class="widget widget_categories">
							<h3 class="widget_title">Categories</h3>
							<ul>
								<?php foreach ($subcategories as $subcat) : ?>
								<li>
									<a href="<?php echo esc_url(get_category_link($subcat->term_id)); ?>">
										<?php echo esc_html($subcat->name); ?>
									</a>
								</li>
								<?php endforeach; ?>
							</ul>
						</div>
						<?php endif; ?>

						<div class="widget">
							<h3 class="widget_title">Latest News</h3>
							<div class="recent-post-wrap">

								<?php
								// Determine category context
								if (is_category()) {
									$cat_id = get_queried_object_id();
								} elseif (is_single()) {
									$categories = get_the_category();
									$cat_id = !empty($categories) ? $categories[0]->term_id : 0;
								} else {
									$cat_id = 0;
								}

								// Query recent posts
								$recent_posts = new WP_Query([
									'post_type'      => 'post',
									'posts_per_page' => 4,
									'cat'            => $cat_id,
									'post_status'    => 'publish',
									'ignore_sticky_posts' => true,
								]);

								if ($recent_posts->have_posts()) :
								while ($recent_posts->have_posts()) : $recent_posts->the_post();
								?>
								<div class="recent-post">
									<div class="media-img">
										<a href="<?php the_permalink(); ?>">
											<?php if (has_post_thumbnail()) : ?>
											<?php the_post_thumbnail('thumbnail'); ?>
											<?php else : ?>
											<img src="<?php echo get_template_directory_uri(); ?>/assets/img/blog/default-thumb.jpg" alt="<?php the_title_attribute(); ?>">
											<?php endif; ?>
										</a>
									</div>

									<div class="media-body">
										<h4 class="post-title">
											<a class="hover-line" href="<?php the_permalink(); ?>">
												<?php the_title(); ?>
											</a>
										</h4>

										<div class="recent-post-meta">
											<span>
												<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
												<?php echo get_the_date(); ?>
											</span>
										</div>
									</div>
								</div>
								<?php
								endwhile;
								wp_reset_postdata();
								else :
								echo '<p>No posts found.</p>';
								endif;
								?>

							</div>
						</div>

                      
                    </aside>
                </div>
            </div>
        </div>
    </section>
<?php
    endwhile;
endif;
?>
<?php get_footer();?>
