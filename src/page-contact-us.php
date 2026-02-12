<?php get_header('');?>


<!--==============================
    Breadcumb
============================== -->
 <div class="breadcumb-wrapper">
        <div class="container">
			<ul class="breadcumb-menu">
			<li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
			<li><?php the_title(); ?></li>
			</ul>
        </div>
    </div>
<!--==============================
Contact Info Area  
==============================-->
    <div class="space2">
        <div class="container">
            <div class="row">
                <div class="col-xl-5">
                    <div class="pe-xxl-4 me-xl-3 text-center text-xl-start mb-40 mb-lg-0">
                        <div class="title-area mb-32">
                            <h2 class="sec-title2"><?php the_field('contact_us_tittle'); ?></h2>
                            <p class="sec-text"><?php the_field('contact_us_content'); ?></p>
                        </div>
                        <div class="contact-feature-wrap">
                           <!-- Items-->
							  <?php
                    // check if the repeater field has rows of data
                    if (have_rows('contact_info', 'option')):
                        // loop through the rows of data
                        while (have_rows('contact_info', 'option')):
                            the_row();
                            ?>
							<?php 
                            $link = get_sub_field('contact_info_link','option');
                            if($link) :
                              $link_url = $link['url'];
                              $link_title = $link['title'];
                              $link_target = $link['target']  ? $link['target'] : '_Self' 
                        ?>
                            <div class="contact-feature">
                                <div class="box-icon">
                                    <img src="<?php the_sub_field('contact_info_image'); ?>" alt="icon">
                                </div>
                                <div class="box-content">
                                    <h3 class="box-title-22"><?php the_sub_field('contact_info_heading');?></h3>
                                    <p class="box-text" ><?php echo esc_html($link_title); ?></p>
                                </div>
                            </div>
                           <?php endif;?>
							<?php endwhile; ?>
                            <?php else: ?>===
                            // Sorry, no Content found
                            <?php
                    endif;
                    ?>
                          
                        </div>
                    </div>
                </div>
                <div class="col-xl-7">
                    <div class="quote-form-box">
                        <h4 class="form-title"><?php the_field('contact_us_form_heading'); ?></h4>
<!--                         <form action="mail.php" method="POST" class="contact-form ajax-contact">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Your Name">
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Email Address">
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="tel" class="form-control" name="number" id="number" placeholder="Phone Number">
                                </div>
                                <div class="form-group col-md-6">
                                    <select name="subject" id="subject" class="form-select">
                                        <option value="" disabled selected hidden>Select Subject</option>
                                        <option value="Writing Article">Writing Article</option>
                                        <option value="Become Author">Become Author</option>
                                        <option value="Gest Posting">Gest Posting</option>
                                        <option value="Personal Question">Personal Question</option>
                                    </select>
                                </div>
                                <div class="form-group col-12">
                                    <textarea name="message" id="message" cols="30" rows="3" class="form-control" placeholder="Your Message"></textarea>
                                </div>
                                <div class="form-btn col-12">
                                    <button class="th-btn">Submit Now<i class="fas fa-arrow-up-right ms-2"></i></button>
                                </div>
                            </div>
                            <p class="form-messages mb-0 mt-3"></p>
                        </form> -->
 						<?php echo do_shortcode('[contact-form-7 id="cb86f89" title="Contact form 1"]'); ?>
						
						
                    </div>
                </div>
            </div>
        </div>
    </div> <!--==============================
Contact Area  
==============================-->
    <div class="contact-map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1278.6249119547733!2d-0.12498583345435049!3d51.515128837481136!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4876059a259cebd9%3A0x4ac7a99056933653!2s71-75%20Ltd!5e0!3m2!1sen!2s!4v1767075083597!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>




<?php get_footer('');?>