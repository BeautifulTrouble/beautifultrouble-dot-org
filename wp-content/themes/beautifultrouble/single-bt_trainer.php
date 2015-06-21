<?php
/**
 * The template for displaying all posts.
 *
 * Default Post Template
 *
 * Page template with a fixed 940px container and right sidebar layout
 *
 * @package WordPress
 * @subpackage WP-Bootstrap
 * @since WP-Bootstrap 0.1
 */

get_header(); 
global $query_string;
$original_posts = $query_string;
?>
<?php while ( have_posts() ) : the_post(); ?>
    <div class="row">
        <div class="container">
    <?php if (function_exists('bootstrapwp_breadcrumbs')) bootstrapwp_breadcrumbs(); ?>
        </div><!--/.container -->
    </div><!--/.row -->
    <div class="container">
    <div class="row content">
    <div class="span8">
 <!-- Masthead
      ================================================== -->
      <header class="jumbotron subhead" id="overview">
        <h1><?php the_title(); ?></h1>
      </header>
         
        <div class="row spacer">
            <div class="span4 portrait-avatar" style="background-image:url('<?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>');"></div>
            <div class="span4"><br class="visible-phone"><?php the_content(); ?>
                <ul class="author-social-links">
                <?php 
                    $twitter    = get_field('twitter', $post->ID);
                    $facebook   = get_field('user_fb', $post->ID);
                    $google     = get_field('googleplus', $post->ID);
                    $website    = get_field('url', $post->ID);
                    $twitter = preg_replace("#(http://twitter.com/|twitter.com/|www.twitter.com/|@)#ie", "", $twitter);
                    if ( $twitter ) {
                        echo '<li class="twitter"><a  href="http://twitter.com/', $twitter, '">@', $twitter, '</a></li>';
                    }
                    if ( $facebook ) {
                        echo '<li class="facebook"><a href="', $facebook, '">', get_the_author(), ' on Facebook</a></li>';
                    }
                    if ( $google ) {
                        echo '<li class="google"><a href="', $google, '">', get_the_author(), ' on Google Plus</a></li>';
                    }
                    if ( $website ) {
                        echo '<li class="website"><a href="', $website, '">', $website, '</a></li>';
                    }
                ?>
                </ul>
            </div>
        </div>
<?php endwhile; // end of the loop. ?>

   </div><!-- /.span8 -->
  <div class="span4">
  <?php get_sidebar('promo'); ?>
  </div><!-- /.span4 -->

</div><!-- /.row .content -->
<?php get_footer(); ?>
