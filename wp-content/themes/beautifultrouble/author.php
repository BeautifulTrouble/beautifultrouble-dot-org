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
        <div class="row content">
            <div class="span8">
                <header class="jumbotron subhead" id="overview">
                    <h1 class="page-title author"><?php printf( '<span class="vcard"><a class="url fn n" href="' . get_author_posts_url( get_the_author_meta( "ID" ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' ); ?></h1>
                        <?php
                        $avatar_size = 440;
                        $user_email = get_the_author_meta('user_email');
                        $avatar_url = get_img_url(get_avatar($user_email, $avatar_size));
                        ?>
                        <div class="row spacer">
                            <div class="span4 portrait-avatar" style="background-image:url('<?php echo $avatar_url; ?>');"></div>
                            <div class="span4"><br class="visible-phone"><?php the_author_meta('description'); ?>
                                <ul class="author-social-links">
                                <?php 
                                    $twitter    = get_the_author_meta('user_tw');
                                    $facebook   = get_the_author_meta('user_fb');
                                    $google     = get_the_author_meta('googleplus');
                                    $website    = get_the_author_meta('url');
                                    $twitter = preg_replace("#(http://twitter.com/|twitter.com/|www.twitter.com/|@)#ie", "", $twitter);
                                    if ( $twitter ) {
                                        echo '<li class="twitter"><a  href="http://twitter.com/', $twitter, '">@', $twitter, '</a></li>';
                                    }
                                    if ( $facebook ) {
                                        echo '<li class="facebook"><a href="', $facebook, '">', get_the_author(), ' on Facebook</a></li>';
                                    }
                                    if ( $google ) {
                                        echo '<li class="google"><a href="', $google, '">', get_the_author(), ' on Google Plus</a></li>';
                                    }
                                    if ( $website ) {
                                        echo '<li class="website"><a href="', $website, '">', $website, '</a></li>';
                                    }
                                ?>
                                </ul>
                            </div>
                        </div>
                </header>
                <br clear="all" />
                <hr class="soften" />
                <h2>Contributed Modules</h2>
                <?php
                /* Since we called the_post() above, we need to
                 * rewind the loop back to the beginning that way
                 * we can run the loop properly, in full.
                 */
                rewind_posts(); 
                $image_size = 170;
                while ( have_posts() ) : the_post(); 
                    $id = get_the_id();
                    $post_type = get_post_type($id);
                    $post_type_name = get_post_type_object($post_type)->labels->singular_name;
                    $image_url = get_img_url(get_the_post_thumbnail($id, array($image_size, $image_size) )); 
                    if (empty($image_url)) {
                        $image_url = '/wp-content/themes/beautifultrouble/img/icon_' . strtolower(str_replace(" ", "-", $post_type_name)) . '.png';
                    }
                    if ($post_type == 'bt_practitioner') {
                        $image_url = '/wp-content/themes/beautifultrouble/img/icon_practitioner.png';
                    }
                    ?>
                    
                    <div class="row spacer">
                        <div class="span2 big-avatar" style="background-image:url('<?php echo $image_url; ?>');"></div>
                        <div class="span6 <?php echo $post_type; ?>">
                            <h3><a href="<?php the_permalink() ?>"><?php echo $post_type_name . ': '; the_title() ?></a></h3>
                            <p class="meta">Contributed by <?php if( function_exists('coauthors_posts_links') ) coauthors_posts_links(); else the_author_posts_link(); ?></br><?php the_time('F j, Y'); ?></p>
                            <p><?php the_excerpt() ?></p>
                        </div>
                    </div>

                <?php endwhile; ?>
        </div><!-- /.span8 -->

      <div class="span4">
      <?php get_sidebar('promo'); ?>
      </div><!-- /.span4 -->
    </div><!-- /.row .content -->
<?php endif; ?>

<?php get_footer(); ?>
