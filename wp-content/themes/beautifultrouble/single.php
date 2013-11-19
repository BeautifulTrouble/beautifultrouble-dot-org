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

get_header(); ?>
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
        <h1><?php the_title();?></h1>
      </header>
         
        <div class="row content">
<div class="span8">
   <p class="meta"><?php echo bootstrapwp_posted_on();?> on <span class="entry-date"><?php echo get_the_date(); ?></span></p>
   
    
            <?php the_content();?>
            <?php the_tags( '<p>Tags: ', ', ', '</p>'); ?>
<?php endwhile; // end of the loop. ?>
<hr />
 <?php comments_template(); ?>

 <?php bootstrapwp_content_nav('nav-below');?>

          </div><!-- /.span8 -->
          <div class="span4">
              <?php get_sidebar('promo'); ?>
          </div><!-- /.span4 -->

</div> <!--row -->


<?php get_footer(); ?>
