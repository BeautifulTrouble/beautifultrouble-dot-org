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
<?php $fields = get_fields(); ?>
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
            <?php the_post_thumbnail('bt-featured' ); ?>
            <?php // TODO move this into functions.php with an override to bootstrapwp_posted_on ?>
            <?php 
            $quotes = $fields['quotes'];
                foreach( $quotes as $quote ) {
                    echo '<span class="quote">', $quote['quote'], '</span>';
                }
            ?>
            <?php 
                $attribution = $fields['attribution'];
                $link        = $fields['link']; 
                if ( $link && $attribution ) {
                    echo '&mdash; <a href="', $link, '">', $attribution, '</a>';
                } else
                {
                    echo '&mdash; ', $attribution;
                }
                ?>
            <?php endwhile; // end of the loop. ?>
        </div><!-- /.span8 -->
        <div id="marginalia" class="fluid-sidebar sidebar span4" role="complementary">
        &nbsp;
       </div>
    </div><!-- /.row .content -->
    <hr />
    <div class="row">
        <div class="span8">
    <?php bootstrapwp_content_nav('nav-below');?>
    <?php comments_template(); ?>
        </div>
    </div> <!-- /.row -->
<?php get_footer(); ?>
