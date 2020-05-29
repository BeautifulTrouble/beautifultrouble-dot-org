<?php
/**
 * Template Name: Blank Page
 * Description: A blank template with no sidebar
 *
 * @package WordPress
 * @subpackage WP-Bootstrap
 * @since WP-Bootstrap 0.1
 */

get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
   <div class="container">

      
				<div class="content">
				  <?php the_content();?>
				<?php endwhile; // end of the loop. ?>
		
				</div><!-- .row content -->
		

