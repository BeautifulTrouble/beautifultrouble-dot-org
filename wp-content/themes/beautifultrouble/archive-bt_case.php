<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage WP-Bootstrap
 * @since WP-Bootstrap 0.6
 */

get_header();
     $posts = query_posts($query_string . 
'&orderby=title&order=asc&posts_per_page=10');
if (have_posts() ) ;?>
<div class="row">
	<div class="container">
		<?php if (function_exists('bootstrapwp_breadcrumbs')) bootstrapwp_breadcrumbs(); ?>
	</div><!--/.container -->
</div><!--/.row -->
<div class="container">
	<header class="jumbotron subhead" id="overview">
                <h1>
<?php  
$obj = get_post_type_object('bt_case');
print $obj->labels->name;
?>
        </h1>
        <h2>
        <?php print $obj->description ?>
        </h2>
</header>

<div class="row content">
	<div class="span8">
		<?php while ( have_posts() ) : the_post(); ?>
		<div <?php post_class(); ?>>
			<a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><h3><?php the_title();?></h3></a>
			<p class="meta"><?php echo bootstrapwp_posted_on();?></p>
			<div class="row">
				        <div class="span2"><?php // Checking for a post thumbnail
				        if ( has_post_thumbnail() ) ?>
				        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
				        	<?php the_post_thumbnail();?></a>
				        </div><!-- /.span2 -->
				        <div class="span6">
				        	<?php the_excerpt();?>
				        </div><!-- /.span6 -->
				    </div><!-- /.row -->
				    <hr />
				</div><!-- /.post_class -->
			<?php endwhile; ?>

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
		</div><!-- /.span8 -->
                <div id="marginalia" class="fluid-sidebar sidebar span4" role="complementary">
                &nbsp;
                </div>
            </div><!-- /.row .content -->
<?php get_footer(); ?>
