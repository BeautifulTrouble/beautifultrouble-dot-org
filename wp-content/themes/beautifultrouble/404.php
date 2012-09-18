<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage WP-Bootstrap
 * @since WP-Bootstrap 0.7
 *
 * Last Revised: January 22, 2012
 */
get_header(); ?>
  <div class="row">
  <div class="container">
   <?php if (function_exists('bootstrapwp_breadcrumbs')) bootstrapwp_breadcrumbs(); ?>
   </div><!--/.container -->
   </div><!--/.row -->
   <div class="container">

      
 <!-- Masthead
      ================================================== -->
      <header class="jumbotron subhead" id="overview">
        <h1><?php _e( 'Here be dragons.', 'bootstrapwp' ); ?></h1>
        <p class="lead"><?php _e( 'You&apos;ve reached the known limits of the Beautiful Trouble universe.', 'bootstrapwp' ); ?></p>
      </header>
	  
        <div class="row content">
<div class="span8">
					
<p>So many more tactic, principle, theory and case study modules could be written than we've managed to write so far. Rather than only reference already-existing modules, we've deliberately designed the project to be expansive and outward looking, freely referencing modules that have yet to be written in the hopes they someday might.

<p>In fact, maybe you’re the one who can write up the module you’re looking for. </p>

<p> To learn more, check out our <a href="/get-involved">Get Involved page</a>.</p>	

<div class="well">
					<?php get_search_form(); ?>

</div><!--/.well -->
					</div><!--/.span8 -->
</div> <!-- row -->

<?php get_footer(); ?>
