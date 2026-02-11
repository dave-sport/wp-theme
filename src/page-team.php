<?php get_header(); ?>

<!--==============================
Breadcumb
==============================-->
<div class="breadcumb-wrapper">
    <div class="container">
        <ul class="breadcumb-menu">
            <li><a href="<?php echo site_url(); ?>">Home</a></li>
            <li><?php the_title(); ?></li>
        </ul>
    </div>
</div>

<!--==============================
Team Area  
==============================-->
<section class="space">
    <div class="container">
		<h2 class="team_page_head">
			<?php the_field('team_page_heading'); ?>
		</h2>
		<div class="row gy-30">

			<?php
			$args = array(
				'post_type'      => 'teams',
				'posts_per_page' => -1
			);

			$teams = new WP_Query($args);

			if ($teams->have_posts()) :
			while ($teams->have_posts()) : $teams->the_post();

			// Default Image
			$default_image = '<?php echo get_template_directory_uri(); ?>/assets/img/logo.png';

			// Featured Image
			$image = has_post_thumbnail()
				? get_the_post_thumbnail_url(get_the_ID(), 'medium')
				: $default_image;
			?>

			<div class="col-sm-6 col-lg-4 col-xl-3">
				<div class="team-card">

					<!-- Image -->
					<div class="box-img">
						<img src="<?php echo esc_url($image); ?>" alt="<?php the_title_attribute(); ?>">
					</div>

					<!-- Title -->
					<div>
						<h3 class="box-title">
						<?php the_title(); ?>
					</h3>

					<!-- Designation -->
					<div class="box-text">
						<?php the_content(); ?>
					</div>
					</div>
					

					<!-- Social Icons -->
					<div class="th-social">
						<?php
						if (have_rows('add_social_accounts')) :
						while (have_rows('add_social_accounts')) : the_row();

						$icon = get_sub_field('select_social_account_icon');
						$url  = get_sub_field('social_account_url');

						if ($icon && $url) :
						?>
						<a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener">
							<?php echo $icon; ?>
						</a>
						<?php
						endif;
						endwhile;
						endif;
						?>
					</div>

				</div>
			</div>

			<?php
			endwhile;
			wp_reset_postdata();
			endif;
			?>

		</div>


    </div>
</section>

<?php get_footer(); ?>
