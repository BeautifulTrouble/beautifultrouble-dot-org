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
 <!-- Masthead
      ================================================== -->
      <header class="jumbotron subhead" id="overview">
        <?php the_post_thumbnail('bt-featured' ); ?>
      </header>
         
    <div class="row content">
    <div class="span8">
        <?php the_content();?>
        <?php endwhile; // end of the loop. ?>

   </div><!-- /.span8 -->
    <div id="marginalia" class="fluid-sidebar sidebar span4" role="complementary">
    &nbsp;
    </div>

    </div><!-- /.row .content -->
    <hr />
    <div class="row">
        <div class="span8">
    <?php bootstrapwp_content_nav('nav-below');?>
    <?php comments_template(); ?>
        </div>
    </div>
<?php get_footer(); ?>
