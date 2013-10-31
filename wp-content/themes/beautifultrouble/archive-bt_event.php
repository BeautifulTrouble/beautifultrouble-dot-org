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
'&orderby=meta_value&meta_key=date&order=asc&nopaging=true');
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
$obj = get_post_type_object('bt_event');
echo $obj->labels->name;
?>
        </h1>

</header>

<div class="row content">
	<div class="span8">

<p>We are currently adapting the book into specific training modules and hands-on action design tools. Many members of the Beautiful Trouble network are professional trainers. Contact us at helpout at beautifultrouble dot org to request a training in Beautiful Trouble 101, Nonviolent Direct Action, or Advanced Creative Action. We can come to your campus, organization or gathering.</p>
<p>And here&#8217;s a <a href="https://docs.google.com/document/d/1jbrGrYTI3qiMTyRFvftmNpTTSzUMfGuZw4iGiH9Q4Dg/edit">draft curriculum</a> for a week-long training we did in New York in July. More to come, soon.</p>
<p>No events listed below? <a href="http://beautifultrouble.dev/get-involved">Help us organize one!</a></p>

<hr class="soften" />

                <h2>Upcoming events</h2>
		<?php while ( have_posts() ) : the_post(); ?>
                <?php 
                $fields = get_fields();
                $date  = $fields['date'];
                $date_obj   = DateTime::createFromFormat('Y/m/d', $date );
                $time       = time();
                $unix_date  = $date_obj->format('U');
                if( $unix_date >= $time ) { ?>
		<div <?php post_class(); ?>>
			<a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><h3><?php the_title();?></h3></a>
			<div class="row">
				        <div class="span2"><?php // Checking for a post thumbnail
				        if ( has_post_thumbnail() ) ?>
				        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
				        	<?php the_post_thumbnail();?></a>
				        </div><!-- /.span2 -->
                                        <div class="span6">
                                                <div class="date"><strong>Date:</strong> <?php echo $date_obj->format('M d, Y'); ?></div>
                                                <div class="time"><strong>Time:</strong> <?php echo $fields['time']; ?></div>
                                                <div class="location"><strong>Location:</strong> <?php echo $fields['location'] ?></div> 
				        	<?php the_excerpt();?>
				        </div><!-- /.span6 -->
				    </div><!-- /.row -->
				    <hr />
				</div><!-- /.post_class -->
            <?php } ?>
	    <?php endwhile; ?>
            <hr />
            <h2>Past events</h2>
		<?php while ( have_posts() ) : the_post(); ?>
                <?php 
                $fields = get_fields();
                $date  = $fields['date'];
                $date_obj   = DateTime::createFromFormat('Y/m/d', $date );
                $time       = time();
                $unix_date  = $date_obj->format('U');
                if( $unix_date < $time ) { ?>
		<div <?php post_class(); ?>>
			<a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><h3><?php the_title();?></h3></a>
			<div class="row">
				        <div class="span2"><?php // Checking for a post thumbnail
				        if ( has_post_thumbnail() ) ?>
				        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
				        	<?php the_post_thumbnail();?></a>
				        </div><!-- /.span2 -->
                                        <div class="span6">
                                                <div class="date"><strong>Date:</strong> <?php echo $date_obj->format('M d, Y'); ?></div>
                                                <div class="time"><strong>Time:</strong> <?php echo $fields['time']; ?></div>
                                                <div class="location"><strong>Location:</strong> <?php echo $fields['location'] ?></div> 
				        	<?php the_excerpt();?>
				        </div><!-- /.span6 -->
				    </div><!-- /.row -->
				    <hr />
				</div><!-- /.post_class -->
            <?php } ?>
	    <?php endwhile; ?>

		</div><!-- /.span8 -->
                <div id="marginalia" class="fluid-sidebar sidebar span4" role="complementary">
                    <?php
                        if ( function_exists('dynamic_sidebar')) dynamic_sidebar("sidebar-event");
                    ?>
                    <?php get_sidebar('promo'); ?>
                </div>
            </div><!-- /.row .content -->
<?php get_footer(); ?>
