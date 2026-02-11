<?php
    get_header();

    $author = get_queried_object();
    $paged  = max(1, get_query_var('paged'));

    $args = [
        'post_type'      => 'post',
        'author'         => $author->ID,
        'posts_per_page' => 10,
        'paged'          => $paged,
    ];

    $author_query = new WP_Query($args);
?>

<!-- AUTHOR SCHEMA -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Person",
    "name": "<?php echo esc_js($author->display_name); ?>",
    "url": "<?php echo esc_url(get_author_posts_url($author->ID)); ?>",
    "email": "<?php echo esc_js($author->user_email); ?>",
    "description": "<?php echo esc_js(get_the_author_meta('description', $author->ID)); ?>"
}
</script>

<section class="space space-extra-bottom">
    <div class="container">
        <div class="row">
            <!-- POSTS -->
			<div class="col-xl-8">
				<?php 
				// Ensure $paged is set
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

				if ($author_query->have_posts()): 
				while ($author_query->have_posts()): $author_query->the_post(); 
				?>

				<div class="mb-4 border-blog">
					<div class="blog-style4">
						<div class="blog-img">
							<a href="<?php the_permalink(); ?>">
								<?php if (has_post_thumbnail()) {
	the_post_thumbnail('');
} ?>
							</a>
							<?php
							$categories = get_the_category();
							if (!empty($categories)):
							// Take the first category for display
							$cat = $categories[0];

							// Get category icons
							$icon_default = get_field('icon_default', 'category_' . $cat->term_id);
							$icon_hover   = get_field('icon_hover', 'category_' . $cat->term_id);

							if (is_array($icon_default) && isset($icon_default['url'])) {
								$icon_default = $icon_default['url'];
							}
							if (is_array($icon_hover) && isset($icon_hover['url'])) {
								$icon_hover = $icon_hover['url'];
							}
							?>
							<a class="category author_cat_tag" href="<?php echo esc_url(get_category_link($cat->term_id)); ?>">
								<?php if ($icon_default): ?>
								<img src="<?php echo esc_url($icon_default); ?>" class="category_icon_default" alt="">
								<?php endif; ?>
								<?php if ($icon_hover): ?>
								<img src="<?php echo esc_url($icon_hover); ?>" class="category_icon_hover" alt="">
								<?php endif; ?>
								<?php echo esc_html($cat->name); ?>
							</a>
							<?php endif; ?>
						</div>

						<div class="blog-content">
							

							<h3 class="box-title-22">
								<a class="hover-line" href="<?php the_permalink(); ?>">
									<?php the_title(); ?>
								</a>
							</h3>

							<div class="blog-meta">
								<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
									<i class="far fa-user"></i>By <?php echo esc_html(get_the_author()); ?>
								</a>
								<span>
									<i class="fal fa-calendar-days"></i>
									<?php echo get_the_date('d M, Y'); ?>
								</span>
							</div>

							<a href="<?php the_permalink(); ?>" class="th-btn style2">
								Read More <i class="fas fa-arrow-up-right ms-2"></i>
							</a>
						</div>
					</div>
				</div>

				<?php endwhile; ?>

				<!-- PAGINATION -->
				<div class="th-pagination pt-10">
					<?php
					echo paginate_links([
						'total'      => $author_query->max_num_pages,
						'current'    => $paged,
						'prev_text'  => '<i class="fas fa-arrow-left"></i>',
						'next_text'  => '<i class="fas fa-arrow-right"></i>',
						'type'       => 'list',
					]);
					?>
				</div>

				<?php else: ?>
				<p>No posts found.</p>
				<?php endif; ?>

				<?php wp_reset_postdata(); ?>
			</div>


            <!-- AUTHOR SIDEBAR -->
            <div class="col-xl-4 sidebar-wrap">
                <div class="sidebar-area mb-0">
                    <div class="widget  ">
                        <div class="author-details">
                            

							<div class="author-img">
								<?php
								$author_id = get_the_author_meta('ID');

								$author_image_id = get_field('autor_profile', 'user_' . $author_id);

								$company_image = get_template_directory_uri() . '/assets/images/team-logo.png';

								if ($author_image_id) {
									echo wp_get_attachment_image($author_image_id, 'thumbnail');
								} else {
									echo '<img src="' . esc_url($company_image) . '" alt="<?php echo esc_attr(moodco_config('name', get_bloginfo('name'))); ?>" />';
								}
								?>
							</div>

                            <div class="author-content">
                                <h3 class="box-title-24">
                                    <?php echo esc_html($author->display_name); ?>
                                </h3>
                                <div class="info-wrap">
                                    <span class="info">Senior. Writer</span>
                                    <span class="info">
                                        <strong>Post: </strong>
                                        <?php echo count_user_posts($author->ID); ?>
                                    </span>
                                </div>
                                <?php
                                    $bio       = get_the_author_meta('description', $author->ID);
                                    $short_bio = wp_trim_words($bio, 25, '…');
                                ?>
                                <p class="author-bio"><?php echo esc_html($short_bio); ?>.</p>
                                <?php
                                    $email       = get_the_author_meta('user_email', $author->ID);
                                    $short_email = (strlen($email) > 20) ? substr($email, 0, 20) . '…' : $email;
                                ?>



                                <h4 class="box-title-18">Social Media</h4>
                                <?php
                                    $user_id = get_the_author_meta('ID'); // or use any specific user ID
                                ?>

                                <div class="th-social">

                                    <?php if ($facebook = get_field('autor_facebook', 'user_' . $user_id)): ?>
                                    <a href="<?php echo esc_url($facebook); ?>" target="_blank">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <?php endif; ?>

                                    <?php if ($twitter = get_field('author_x', 'user_' . $user_id)): ?>
                                    <a href="<?php echo esc_url($twitter); ?>" target="_blank">
                                        <i class="fa-brands fa-x-twitter"></i>
                                    </a>
                                    <?php endif; ?>

                                    <?php if ($instagram = get_field('autor_instagram', 'user_' . $user_id)): ?>
                                    <a href="<?php echo esc_url($instagram); ?>" target="_blank">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                    <?php endif; ?>

                                    <?php if ($linkedin = get_field('autor_linkedin', 'user_' . $user_id)): ?>
                                    <a href="<?php echo esc_url($linkedin); ?>" target="_blank">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                    <?php endif; ?>

                                    <?php if ($tiktok = get_field('autor_tiktok', 'user_' . $user_id)): ?>
                                    <a href="<?php echo esc_url($tiktok); ?>" target="_blank">
                                        <i class="fab fa-tiktok"></i>
                                    </a>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>