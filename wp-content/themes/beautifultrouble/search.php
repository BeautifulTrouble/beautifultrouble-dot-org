<?php
/**
 *
 * Search Template
 *
 *
 * @package WP-Bootstrap
 * @subpackage Default_Theme
 * @since WP-Bootstrap 0.7
 *
 * Last Revised: January 22, 2012
 */
get_header(); ?>
 <div class="container">
			 <div class="row content">
<div class="span8">
<?php if ( have_posts() ) : ?>
  
    <!-- Masthead
      ================================================== -->
      <header class="jumbotron subhead" id="overview">
        <h1><?php printf( __( 'Search Results for: %s', 'bootstrapwp' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
       
      </header>
					<?php while ( have_posts() ) : the_post(); ?>
<a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><h2> <?php the_title();?></h2></a>
<p><?php the_excerpt();?></p>
<hr />

				<?php endwhile; ?>
			<?php else : ?>
 <!-- Masthead
      ================================================== -->
      <header class="jumbotron subhead" id="overview">
        <h1><?php _e( 'No Results Found', 'bootstrapwp' ); ?></h1>
      <p class="lead"><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps you should try again with a different search term.', 'bootstrapwp' ); ?></p>
      </header>
					

<div class="well">
					<?php get_search_form(); ?>

</div><!--/.well -->
<?php endif ;?>
			

					<?php if (function_exists('page_navi')) { // if expirimental feature is active ?>
						
						<?php page_navi(); // use the page navi function ?>
						
					<?php } else { // if it is disabled, display regular wp prev & next links ?>
						<nav class="wp-prev-next">
							<ul class="clearfix">
								<li class="prev-link"><?php next_posts_link(_e('&laquo; Older Entries', "bonestheme")) ?></li>
								<li class="next-link"><?php previous_posts_link(_e('Newer Entries &raquo;', "bonestheme")) ?></li>
							</ul>
						</nav>
					<?php } ?>			
		</div><!--/.span8 -->
    </div> <!-- /.row -->
<?php get_footer(); ?>
