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
									<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>By <?php echo esc_html(get_the_author()); ?>
								</a>
								<span>
									<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
									<?php echo get_the_date('d M, Y'); ?>
								</span>
							</div>

							<a href="<?php the_permalink(); ?>" class="th-btn style2">
								Read More <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
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
						'prev_text'  => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>',
						'next_text'  => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>',
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
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                                    </a>
                                    <?php endif; ?>

                                    <?php if ($twitter = get_field('author_x', 'user_' . $user_id)): ?>
                                    <a href="<?php echo esc_url($twitter); ?>" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                    </a>
                                    <?php endif; ?>

                                    <?php if ($instagram = get_field('autor_instagram', 'user_' . $user_id)): ?>
                                    <a href="<?php echo esc_url($instagram); ?>" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                                    </a>
                                    <?php endif; ?>

                                    <?php if ($linkedin = get_field('autor_linkedin', 'user_' . $user_id)): ?>
                                    <a href="<?php echo esc_url($linkedin); ?>" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                    </a>
                                    <?php endif; ?>

                                    <?php if ($tiktok = get_field('autor_tiktok', 'user_' . $user_id)): ?>
                                    <a href="<?php echo esc_url($tiktok); ?>" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1v-3.5a6.37 6.37 0 00-.79-.05A6.34 6.34 0 003.15 15.2a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-.81.06l-.38-.26z"/></svg>
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