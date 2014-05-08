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
    <meta name="description" content="Beautiful Trouble is a book, web toolbox and international network of artist-activist trainers whose mission is to make grassroots movements more creative and more effective.">

    <link rel="profile" href="http://gmpg.org/xfn/11" />


    <?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

  <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="<?php bloginfo( 'template_url' );?>/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php bloginfo( 'template_url' );?>/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php bloginfo( 'template_url' );?>/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php bloginfo( 'template_url' );?>/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="<?php bloginfo( 'template_url' );?>/ico/apple-touch-icon-57-precomposed.png">

  <!--[if lt IE 9]>
<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>  data-spy="scroll" data-target=".subnav" data-offset="50" onload="prettyPrint()">
    <div class="navbar navbar-fixed-top">
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
