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

      
 <!-- Masthead
      ================================================== -->
      <header class="jumbotron subhead" style="text-align:center;" id="overview">
        <h1><?php the_title();?></h1>
      </header>
			
				<div class="content">
				  <?php the_content();?>
				<?php endwhile; // end of the loop. ?>
		
				</div><!-- .row content -->
		

