<?php
/**
 *
 * Default Page Header
 *
 * @package WP-Bootstrap
 * @subpackage Default_Theme
 * @since WP-Bootstrap 0.1
 *
 * Last Revised: April 11, 2012
 */ ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
   <title><?php
  /*
   * Print the <title> tag based on what is being viewed.
   */
  global $page, $paged;

  wp_title( '|', true, 'right' );

  // Add the blog name.
  bloginfo( 'name' );

  // Add the blog description for the home/front page.
  $site_description = get_bloginfo( 'description', 'display' );
  if ( $site_description && ( is_home() || is_front_page() ) )
    echo " | $site_description";

  // Add a page number if necessary:
  if ( $paged >= 2 || $page >= 2 )
    echo ' | ' . sprintf( __( 'Page %s', 'bootstrapwp' ), max( $paged, $page ) );

  ?></title>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, target-densitydpi=medium-dpi">
    <meta name="description" content="Beautiful Trouble exists to make nonviolent revolution irresistible by providing an ever-growing suite of strategic tools and trainings that inspire movements for a more just, healthy, and equitable world.">
    <meta name="google-site-verification" content="OH-dhmLtRPitaL7XOelQED0am7LBgMsUDL_f-qFFIB8" />
    <meta name="theme-color" content="#000000">


    <link rel="profile" href="http://gmpg.org/xfn/11" />


    <?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

  <!-- All fav and touch icons -->
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_stylesheet_directory_uri(); ?>/ico/apple-touch-icon-57x57.png?v=allEgyg8xQ">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo get_stylesheet_directory_uri(); ?>/ico/apple-touch-icon-60x60.png?v=allEgyg8xQ">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_stylesheet_directory_uri(); ?>/ico/apple-touch-icon-72x72.png?v=allEgyg8xQ">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_stylesheet_directory_uri(); ?>/ico/apple-touch-icon-76x76.png?v=allEgyg8xQ">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_stylesheet_directory_uri(); ?>/ico/apple-touch-icon-114x114.png?v=allEgyg8xQ">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_stylesheet_directory_uri(); ?>/ico/apple-touch-icon-120x120.png?v=allEgyg8xQ">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_stylesheet_directory_uri(); ?>/ico/apple-touch-icon-144x144.png?v=allEgyg8xQ">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_stylesheet_directory_uri(); ?>/ico/apple-touch-icon-152x152.png?v=allEgyg8xQ">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_stylesheet_directory_uri(); ?>/ico/apple-touch-icon-180x180.png?v=allEgyg8xQ">
    <link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/ico/favicon-32x32.png?v=allEgyg8xQ" sizes="32x32">
    <link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/ico/android-chrome-192x192.png?v=allEgyg8xQ" sizes="192x192">
    <link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/ico/favicon-96x96.png?v=allEgyg8xQ" sizes="96x96">
    <link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/ico/favicon-16x16.png?v=allEgyg8xQ" sizes="16x16">
    <link rel="manifest" href="<?php echo get_stylesheet_directory_uri(); ?>/ico/manifest.json?v=allEgyg8xQ">
    <link rel="mask-icon" href=<?php echo get_stylesheet_directory_uri(); ?>/ico"/safari-pinned-tab.svg?v=allEgyg8xQ" color="#000000">
    <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/ico/favicon.ico?v=allEgyg8xQ">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo get_stylesheet_directory_uri(); ?>/ico/mstile-144x144.png?v=allEgyg8xQ">
    <meta name="theme-color" content="#ffffff">
  <!--[if lt IE 9]>
<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>  data-spy="scroll" data-target=".subnav" data-offset="50" onload="prettyPrint()">
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
           <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <?php
           /** Loading WordPress Custom Menu with Fallback to wp_list_pages **/
      wp_nav_menu( array( 'menu' => 'main-menu', 'container_class' => 'nav-collapse', 'menu_class' => 'nav', 'menu_id' => 'main-menu') ); ?>
					<?php get_search_form(); ?>
        </div>
      </div>
    </div>
    <div class="new-site-notice">
        <div>
            <span>We have a new website!</span>
            Check out the latest version of our toolkit at 
            <a href="https://beautifultrouble.org/toolbox" target="_blank">beautifultrouble.org/toolbox</a>
        </div>
    </div>
    <?php if ( !is_page_template('page-blank.php') ) { ?>
    <div class="container">
    <div class="row">
        <div class="span8"><a href="/" title="Beautiful Trouble front page"><h1 class="logo">Beautiful Trouble</h1></a></div>
        <div class="span4">
            <?php $args = array( 'numberposts' => 1, 'orderby' => 'rand', 'post_type' => array ('bt_review'));
            global $post;
            $review = get_posts( $args );
            $field = get_field('quotes', $review[0]->ID);
            $key = array_rand( $field, 1 );
            $quote = $field[$key]['quote'];
            $attribution = get_field('attribution', $review[0]->ID );
            ?>
                <blockquote id="quote-top"><p><?php echo $quote; ?></p>
                <small><?php echo $attribution; ?></small>
                </blockquote>
        </div>
    </div>
    </div>
    <?php } ?>
