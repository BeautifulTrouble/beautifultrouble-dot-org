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
        <h1><?php _e( 'This is Embarrassing', 'bootstrapwp' ); ?></h1>
        <p class="lead"><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'bootstrapwp' ); ?></p>
      </header>
	  
        <div class="row content">
<div class="span8">
					
<p>Not finding a module you're looking for? You can search for it here[linked] or browse the table of contents[link to whatever the website equivalent of the TOC will be], but it's also possible that the module you're looking for does not (yet) exist.</p>

<p>Why? Because Beautiful Trouble is, by design, an evolving project. Not all the tactics, principles, theories and case studies we know are important have been written up yet. We are continuing to expand the content, and welcome your help submitting new modules or helping to complete unfinished ones. To learn more, visit [volunteer/contact/submission-guidelines page].</p>	

<div class="well">
					<?php get_search_form(); ?>

</div><!--/.well -->
					</div><!--/.span8 -->
</div> <!-- row -->

<?php get_footer(); ?>
