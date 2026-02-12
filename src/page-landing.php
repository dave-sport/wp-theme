<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo esc_html(moodco_config('name', get_bloginfo('name'))); ?></title>
  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/landingPageStyle.css?v=1wddsdsw" />
  <link rel="stylesheet" href="<?php bloginfo('template_directory') ?>/assets/css/style.css">
  <!-- Bootstrap -->
  <link rel="stylesheet" href="<?php bloginfo('template_directory') ?>/assets/css/bootstrap.min.css">
  <!-- Fontawesome Icon -->
  <link rel="stylesheet" href="http://staging-davedotsport-com.stackstaging.com//wp-content/themes/davesport/assets/css/fontawesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <!-- Google Fonts: League Spartan (headings) + Poppins (body) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --ui-heading: 'League Spartan', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      --ui-body: 'Poppins', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
    }

    html,
    body {
      font-family: 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, sans-serif !important;
      font-size: 18px !important;
      line-height: 1.65 !important;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }


.th-social a{
	width:var(--icon-size, 30px) !important;
	height:var(--icon-size, 30px) !important;
	line-height:var(--icon-size, 30px) !important;
	margin-right:1px !important;
}

    /* ===============================
     HEADINGS
  =============================== */

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    .hero-title,
    .section-title,
    .form-title,
    .btn-submit,
    button,
    .step-card h3,
    .countdown-num,
    .countdown-dark .num {
      font-family: 'League Spartan', system-ui, sans-serif !important;
      font-weight: 700 !important;
      letter-spacing: -0.03em !important;
    }

    strong,
    b {
      font-family: 'League Spartan', system-ui, sans-serif !important;
      font-weight: 600 !important;
    }

    /* ===============================
     BODY TEXT
  =============================== */

    p,
    span,
    li,
    a,
    label,
    input,
    textarea,
    select {
      font-family: 'Poppins', system-ui, sans-serif !important;
      font-size: .85em !important;
    }

    /* ===============================
     HERO
  =============================== */

    .hero-title {
      font-size: 3.2rem !important;
      line-height: 1.05 !important;
    }

    .hero-desc {
      font-size: 1.3rem !important;
      font-weight: 500 !important;
    }
form input:not([type=submit]){
      border: 2px solid #ffc10355;

}
form input[type=submit]{
  background: #ffc404;
  font-weight: 600;
  margin-top: 10px;
}
    /* ===============================
     FORM (DESKTOP)
  =============================== */

    .form-title {
      font-size: 1.9rem !important;
    }

    .form-desc {
      font-size: 1rem !important;
    }

    .form-card input,
    .form-card label {
      font-size: .85rem !important;
    }

    /* ===============================
     BUTTONS
  =============================== */

    button,
    .btn-submit {
      font-size: 1.2rem !important;
      padding: 16px 26px !important;
    }

    /* ===============================
     MOBILE
  =============================== */

    @media (max-width: 768px) {

      html,
      body {
        font-size: 19px !important;
      }

      .hero-title {
        font-size: 3rem !important;
      }

      .hero-desc {
        font-size: 1.35rem !important;
      }

      p,
      span,
      li,
      a,
      label {
        font-size: .8rem !important;
      }

      /* form slightly smaller */
      .form-card input,
      .form-card textarea,
      .form-card select {
        font-size: 14px !important;
      }

      .form-card label {
        font-size: 14px !important;
      }

      body .form-desc {
        font-size:14px !important;
      }
    }

    @media (max-width: 480px) {

      html,
      body {
        font-size: 20px !important;
      }

      .hero-title {
        font-size: 3.3rem !important;
      }

      .hero-desc {
        font-size: 1.4rem !important;
      }

      /* form more compact */
      .form-card input,
      .form-card textarea,
      .form-card select {
        font-size: 14px !important;
      }

      .form-card label {
        font-size: 14px !important;
      }
      .hero-right{
        padding-top: 0px;
      }
    }
  </style>
  <?php wp_head(); ?>
</head>
<?php /* Template Name: Landing Page */ ?>
<body>


  <!-- Hero Section -->
  <section class="hero" id="hero">
    <div
      class="hero-bg"
      style="background-image: url('/wp-content/uploads/2026/01/stadium-hero.jpg')"></div>

    <div class="hero-content">
      <div class="hero-left">
<!--         <h1 class="hero-title">
          Win a Signed David Beckham Football Shirt
        </h1>
        <p class="hero-desc">
          Enter now for your chance to own a piece of football history
        </p> -->

        <div class="form-card">
          <h1 class="form-title">Win a Signed David Beckham Football Shirt!</h1>
          <p class="form-desc">
            Enter now for your chance to own a piece of football history
          </p>
          <?php echo do_shortcode('[sibwp_form id=3]'); ?>
        </div>
      </div>

      <div class="hero-right">
        <img
          src="/wp-content/uploads/2026/01/shirt-showcase.png"
          alt="Signed David Beckham England Football Shirt"
          class="hero-shirt" />
      </div>
    </div>
  </section>

  <!-- How It Works Section -->
  <section class="how-it-works" id="how-it-works">
    <div class="section-max">
      <h2 class="section-title">
        Get Your Hands on a Piece of Football History!
      </h2>

      <!-- Top Countdown -->
      <!-- <div class="countdown-header">
        <p class="countdown-label">Hurry, Entries Close In:</p>
        <div class="countdown-boxes">
          <div class="countdown-box">
            <span class="countdown-num" id="days1">07</span>
            <span class="countdown-label-small">Days</span>
          </div>
          <div class="countdown-box">
            <span class="countdown-num" id="hours1">14</span>
            <span class="countdown-label-small">Hours</span>
          </div>
          <div class="countdown-box">
            <span class="countdown-num" id="minutes1">32</span>
            <span class="countdown-label-small">Minutes</span>
          </div>
          <div class="countdown-box">
            <span class="countdown-num" id="seconds1">09</span>
            <span class="countdown-label-small">Seconds</span>
          </div>
        </div>
      </div> -->

      <!-- Grid Layout -->
      <div class="grid-layout">
        <!-- Left: Product Box -->
        <div class="product-showcase">
          <div class="product-overlay-text">
            <p>
              Score an authentic England 2001-2003 home football shirt signed
              by the legendary David Beckham himself.
            </p>
            <p class="product-italic">This exclusive</p>
          </div>
          <img
            src="/wp-content/uploads/2026/01/shirt-showcase.png"
            alt="Signed David Beckham Football Shirt" />
        </div>

        <!-- Right: Steps Grid + Bottom Countdown -->
        <div class="right-section">
          <div class="steps-grid">
            <!-- Top Row: Steps 1 & 2 -->
            <div class="step-item step-top-1">
              <div class="step-circle">1.</div>
              <div class="step-card">
                <h3>Enter</h3>
                <p>Submit your details using the form above.</p>
              </div>
            </div>

            <div class="step-item step-top-2">
              <div class="step-circle">2.</div>
              <div class="step-card">
                <h3>We'll Draw a Winner</h3>
                <p>We'll randomly select one lucky winner.</p>
              </div>
            </div>

            <!-- Bottom Row: Step 3 (Full Width) -->
            <div class="step-item step-bottom">
              <div class="step-circle">3.</div>
              <div class="step-card">
                <h3>Shirt Delivered</h3>
                <p>We'll ship the signed shirt right to your door.</p>
              </div>
            </div>
          </div>

          <!-- Bottom Yellow Countdown with Glitter -->
          <div class="countdown-yellow" id="glitterContainer">
            <h3>Hurry, Entries Close In:</h3>
            <div class="countdown-dark-boxes">
              <div class="countdown-dark">
                <span class="num" id="days2">07</span>
                <span class="label">DAYS</span>
              </div>
              <div class="countdown-dark">
                <span class="num" id="hours2">14</span>
                <span class="label">HOURS</span>
              </div>
              <div class="countdown-dark">
                <span class="num" id="minutes2">32</span>
                <span class="label">MINUTES</span>
              </div>
              <div class="countdown-dark">
                <span class="num" id="seconds2">09</span>
                <span class="label">SECONDS</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <footer class="footer-wrapper footer-layout1"
    data-bg-src="<?php bloginfo('template_directory') ?>/assets/img/bg/footer_bg_1.png">
    <div class="widget-area">
      <div class="container">
        <div class="row justify-content-between ">
          <div class="col-md-12 col-xl-4">
            <div class="widget footer-widget">
              <div class="th-widget-about">
                <div class="about-logo">
                  <a href="<?php echo site_url() ?>"><img width="300"
                      src="<?php the_field('footer_logo_', 'option') ?>" alt="Tnews"></a>
                </div>
                <p class="about-text"><?php the_field('footer_short_description', 'option') ?></p>
                <div class="th-social style-black">
                  <?php
                  // check if the repeater field has rows of data
                  if (have_rows('social_media', 'options')):

                    // loop through the rows of data
                    while (have_rows('social_media', 'options')): the_row(); ?>
                      <a href="<?php the_sub_field('social_media_link'); ?>" target="_blank">
                        <?php the_sub_field('social_media_icon'); ?>
                      </a>
                  <?php endwhile;

                  else:
                    echo 'No social media links found.';
                  endif;
                  ?>
                </div>
              </div>
            </div>
          </div>
          <!-- <div class="col-md-4 col-xl-2 col-12">
                    <div class="widget widget_nav_menu footer-widget">
                        <h3 class="widget_title" style="color:#FFF;"><?php the_field('footer_frist_heading', 'option') ?>
                        </h3>
                        <div class="menu-all-pages-container">
                            <ul class="menu">
                                <?php
                                wp_nav_menu([
                                  'theme_location' => 'footer_menu',
                                  'container'      => 'nav',
                                  'menu_class'     => 'footer-menu',
                                ]);
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-xl-auto col-12">
                    <div class="widget widget_nav_menu footer-widget">
                        <h3 class="widget_title" style="color:#FFF;">
                            <?php the_field('footer_second_heading', 'option') ?></h3>
                        <div class="menu-all-pages-container">
                            <ul class="menu">
                                <?php
                                wp_nav_menu([
                                  'theme_location' => 'category',
                                  'container'      => 'nav',
                                  'menu_class'     => 'footer-menu',
                                ]);
                                ?>
                            </ul>
                        </div>
                    </div>
                </div> -->
          <div class="col-md-4 col-xl-auto d-lg-none d-md-block col-12">
            <div class="widget widget_nav_menu footer-widget ">
              <h3 class="widget_title" style="color:#FFF;">Our Policy </h3>
              <div class="menu-all-pages-container">
                <ul class="menu">
                  <?php
                  wp_nav_menu([
                    'theme_location' => 'copyright_menu',
                    'container'      => 'nav',
                    'menu_class'     => 'footer-menu',
                  ]);
                  ?>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-xl-3 col-12 address-09">

            <div class="widget footer-widget">
              <h3 class="widget_title" style="color:#FFF;">
                <?php the_field('footer_thrid_heading', 'option') ?></h3>
              <div class="main-add">
                <div class="clum">
                  <h3 style="
                                color: var(--body-color);
                                font-size: 16px;
                                margin-bottom: 0;
                                margin-top: 10px;
                            ">Company Number:</h3>
                  <h6 style="color: var(--body-color);
                                font-size: 14px;
                                font-weight: 400;
                                margin: 0;">16964433</h6>
                </div>

                <div class="clum">
                  <h3 style="
                                color: var(--body-color);
                                font-size: 16px;
                                margin-bottom: 0;
                                margin-top: 10px;
                            ">Registered Office:</h3>
                  <h6 style="color: var(--body-color);
                                font-size: 14px;
                                font-weight: 400;
                                margin: 0;">75 Shelton St, London, WC2H 9JQ</h6>
                </div>

                <div class="clum">
                  <h3 style="
                                    color: var(--body-color);
                                    font-size: 16px;
                                    margin-bottom: 0;
                                    margin-top: 10px;
                                ">Company Name:</h3>
                  <h6 style="color: var(--body-color);
                                    font-size: 14px;
                                    font-weight: 400;
                                    margin: 0;">SumLou Limited</h6>
                </div>



              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
    <div class="copyright-wrap">
      <div class="container">
        <div class=" d-lg-block d-none">
          <div class="row jusity-content-between align-items-center ">
            <div class="col-lg-12 col-12 br-1 ">
              <div class="footer-links">
                <ul>
                  <?php
                  wp_nav_menu([
                    'theme_location' => 'copyright_menu',
                    'menu_class'     => 'footer-links-menu',
                  ]);
                  ?>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="row jusity-content-between align-items-center br-1">
          <div class="col-lg-12">
            <p class="copyright-text text-center"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M14.83 14.83a4 4 0 110-5.66"/></svg> <?php bloginfo('name'); ?> - Rights Reserved
              2026</p>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!--********************************
			Code End  Here
	******************************** -->

  <!-- Scroll To Top -->
  <div class="scroll-top">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
      <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"
        style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;">
      </path>
    </svg>
  </div>


  <!-- Font Awesome CDN (REQUIRED) -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

  <!-- INLINE WhatsApp Floating Button -->
  <a
    href="https://whatsapp.com/channel/0029Vb7LECHFy72DMdZwxr41"
    target="_blank"
    rel="noopener noreferrer"
    aria-label="Chat on WhatsApp"
    style="
    position:fixed;
    right:20px;
    bottom:90px;
    width:64px;
    height:64px;
    background:#25D366;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    z-index:2147483647;
    box-shadow:0 12px 30px rgba(0,0,0,0.35);
    animation:waPulse 1.4s infinite;
  ">
    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="#ffffff"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
  </a>

  <script>
    (function() {
      if (document.getElementById('waFloatStyle')) return;

      var s = document.createElement('style');
      s.id = 'waFloatStyle';
      s.innerHTML = `
    @keyframes waPulse {
      0% { box-shadow: 0 0 0 0 rgba(37,211,102,0.7); }
      70% { box-shadow: 0 0 0 25px rgba(37,211,102,0); }
      100% { box-shadow: 0 0 0 0 rgba(37,211,102,0); }
    }

    @media (max-width:768px) {
      a[aria-label="Chat on WhatsApp"] {
        width:54px;
        height:54px;
        bottom:75px;
        right:18px;
      }
      a[aria-label="Chat on WhatsApp"] i {
        font-size:26px;
      }
    }
  `;
      document.head.appendChild(s);
    })();
  </script>
  <!--==============================
    All Js File
============================== -->

  <!-- Timer and Others -->
  <script src="<?php bloginfo('template_directory') ?>/assets/js/landingPageScript.js"></script>

  <!-- Jquery -->
  <script src="<?php bloginfo('template_directory') ?>/assets/js/vendor/jquery-3.6.0.min.js"></script>

  <!-- Slick Slider -->
  <script src="<?php bloginfo('template_directory') ?>/assets/js/slick.min.js"></script>
  <!-- Bootstrap -->
  <script src="<?php bloginfo('template_directory') ?>/assets/js/bootstrap.min.js"></script>
  <!-- Magnific Popup -->
  <script src="<?php bloginfo('template_directory') ?>/assets/js/jquery.magnific-popup.min.js"></script>
  <!-- Counter Up -->
  <script src="<?php bloginfo('template_directory') ?>/assets/js/jquery.counterup.min.js"></script>
  <!-- Range Slider -->
  <script src="<?php bloginfo('template_directory') ?>/assets/js/jquery-ui.min.js"></script>
  <!-- Isotope Filter -->
  <script src="<?php bloginfo('template_directory') ?>/assets/js/imagesloaded.pkgd.min.js"></script>
  <script src="<?php bloginfo('template_directory') ?>/assets/js/isotope.pkgd.min.js"></script>
  <!-- Vimeo Player -->
  <script src="<?php bloginfo('template_directory') ?>/assets/js/vimeo_player.js"></script>

  <!-- Main Js File -->
  <script src="<?php bloginfo('template_directory') ?>/assets/js/main.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
  <script>
    var heroSwiper = new Swiper('.mySwiperHeroBanner', {
      slidesPerView: 1,
      spaceBetween: 20,
      loop: true,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      speed: 900,
    });
  </script>



  <?php wp_footer(); ?>




</body>

</html>