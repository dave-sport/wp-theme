<?php
/*
Template Name: Policy Page
*/
get_header(); 
?>



    <div class="breadcumb-wrapper">
        <div class="container">
			<ul class="breadcumb-menu">
			<li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
			<li><?php the_title(); ?></li>
			</ul>
        </div>
    </div>


   

<section class="policy-section">
	<div class="container">
		<div class="policy-content-wrp">
			<?php the_content('');?>
		</div>
	
	</div>
</section>



<?php get_footer('');?>