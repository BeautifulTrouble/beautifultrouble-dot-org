<?php
/**
 * The template for displaying all pages.
 *
 * Template Name: Default Page
 * Description: Page template with a content container and right sidebar
 *
 * @package WordPress
 * @subpackage WP-Bootstrap
 * @since WP-Bootstrap 0.1
 */

get_header(); ?>
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
        <h1><?php the_title();?></h1>
      </header>
         

            <?php the_content();?>
<?php endwhile; // end of the loop. ?>
          </div><!-- /.span8 -->
          
          <div class="span4">
          <?php get_sidebar('promo'); ?>
          </div><!-- /.span4 -->

</div> <!--row -->


<?php get_footer(); ?>
