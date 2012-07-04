<?php
/**
 * The template for displaying Author Archive pages.
 *
 */

get_header(); ?>

<?php 
global $query_string;
$posts = query_posts( $query_string . '&post_type[]=bt_theory&post_type[]=bt_tactic&post_type[]=bt_theory&post_type[]=bt_case&post_type[]=bt_principle&nopaging=true' );

if ( have_posts() ) : ?>

	<?php
	/* Queue the first post, that way we know
	 * what author we're dealing with (if that is the case).
	 *
	 * We reset this later so we can run the loop
	 * properly with a call to rewind_posts().
	 */
	the_post();
	?>
	<div class="row">
		<div class="container">
                <ul class="breadcrumb"><li><a href="/">Home</a></li> <span class="divider">/</span> <li class="active"><?php echo get_the_author() ?></li></ul>		
		</div><!--/.container -->
	</div><!--/.row -->
	<div class="container">
		<header class="jumbotron subhead" id="overview">
			<h1 class="page-title author"><?php printf( '<span class="vcard"><a class="url fn n" href="' . get_author_posts_url( get_the_author_meta( "ID" ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' ); ?></h1>
                        <p><?php the_author_meta('description'); ?></p>
		</header>
                <hr class="soften" />
                <h2>Contributed Modules</h3>
		<?php
					/* Since we called the_post() above, we need to
					 * rewind the loop back to the beginning that way
					 * we can run the loop properly, in full.
					 */
					rewind_posts();
					?>
					<div class="row content">
						<div class="span8">
							<?php /* Start the Loop */ ?>
							<?php while ( have_posts() ) : the_post(); ?>
							<div <?php post_class(); ?>>
								<a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><h3><?php the_title();?></h3></a>
								<p class="meta"><?php echo bootstrapwp_posted_on();?></p>
								<div class="row">
                                                                                <div class="span2"><?php // Checking for a post thumbnail
                                                                                if ( has_post_thumbnail() )  { ?>
                                                                                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
                                                                                        <?php the_post_thumbnail();?></a>
                                                                                <?php } else {
                                                                                    $type = get_post_type();
                                                                                    $obj = get_post_type_object( $type );
                                                                                    $type_name = strtolower( $obj->labels->singular_name );
                                                                                ?>
                                                                                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
                                                                                        <img src="/wp-content/themes/beautifultrouble/img/icon_<?php echo $type_name ?>.png" /></a>
                                                                                <?php } ?> 
                                                                                </div>
									        <div class="span6">
									        	<?php the_excerpt();?>
									        </div><!-- /.span6 -->
									    </div><!-- /.row -->
									    <hr />
									</div><!-- /.post_class -->
								<?php endwhile; ?>
							<?php endif; ?>
						</div><!-- /.span8 -->

    <div id="marginalia" class="fluid-sidebar sidebar span4" role="complementary">
    </div>

</div><!-- /.row .content -->

<?php get_footer(); ?>
