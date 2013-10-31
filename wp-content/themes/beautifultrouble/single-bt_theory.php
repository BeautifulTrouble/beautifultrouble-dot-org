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
<?php if( $fields['repeating_elements'] ) {
    // Let's loop through any repeating elements and create seperate
    // arrays for each type of repeating element, i.e., Insights, Epigraphs
    $repeater = $fields['repeating_elements'];
    $further_insights = array();
    $epigraphs        = array();
    foreach( $repeater as $item ) {
        if( $item['acf_fc_layout'] == 'further_insight' ) {
            array_push( $further_insights, $item );
        } elseif( $item['acf_fc_layout'] == 'epigraph' ) {
            array_push( $epigraphs, $item );
        }

    }
} ?> 
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
                <div class="featured-image">
                    <?php the_post_thumbnail('bt-featured' ); ?>
                </div>
                <?php // TODO move this into functions.php with an override to bootstrapwp_posted_on ?>
                <p class="meta">Contributed by <?php if( function_exists('coauthors_posts_links') ) coauthors_posts_links(); else the_author_posts_link(); ?></p>
                <?php if( $epigraphs ) {
                    // Epigraphs
                        foreach( $epigraphs as $item ) {
                            echo '<blockquote><p class="quote">';
                            echo $item['quote'];
                            echo '</p>';
                            echo '<small class="attribution">';
                            echo $item['attribution'];
                            echo '</small>';
                            echo '</blockquote>';
                        }
                }
                ?>
            <?php if ( get_the_excerpt() ) { ?>
                <div id="in-summary" class="alert alert-info">
                    <strong>In Sum</strong>
                    <?php the_excerpt(); ?>
                </div> 
            <?php } ?>
            <?php if( $fields['origins'] && strlen( $fields['origins'] ) > 1 ) {
            ?>
                <div id="origins" class="well">
                    <strong class="origins">Origins:</strong> <?php echo $fields['origins']; ?>
                </div>
            <?php
                }   
            ?>
            <?php the_content();?>

            <?php the_tags( '<p>Tags: ', ', ', '</p>'); ?>

        <?php $coauthors = get_coauthors(); ?>
        <hr class="soften" />
        <div class="author-bios">
        <?php foreach( $coauthors as $coauthor ) : ?>
            <p>
                <?php echo $coauthor->description; ?>
            </p>
        <?php endforeach; ?>
        </div>

        <br />
        <p class="alert alert-info hidden-phone">Hey there! Did you know that you can jump into our <a href="http://beautifultrouble.org/2013/03/12/visualize-beautiful-trouble/">experimental visualization interface</a> right from <a href="http://explore.beautifultrouble.org/#<?php echo $post->post_name; ?>">this point</a>? <b><a href="http://explore.beautifultrouble.org/#<?php echo $post->post_name; ?>">Give it a try and send us your feedback!</a></b></p>
<?php endwhile; // end of the loop. ?>

        </div><!-- /.span8 -->
        <div id="marginalia" class="fluid-sidebar sidebar span4" role="complementary">
            <?php if( $fields['repeating_elements'] ) {
                if( $further_insights ) {
                // Further Insights
                echo '<strong id="further-insights">Further Insights</strong>';
                echo '<ul>';
                foreach( $further_insights as $item ) {
                    if( $item['link'] ) {
                     echo '<li><a href="' . $item['link'] . '">'. $item['insight'] . '</a></li>';
                     } else { echo '<li>' . $item['insight'] . '</li>' ; }
                }
                echo '</ul>';
                }
            } ?> 
            <?php 
                $types = array( 'tactics' => 'Tactics', 'principles' => 'Principles', 'theories' => 'Theories', 'case_studies' => 'Case Studies', 'practitioners' => 'Practitioners' );
                foreach( array_keys( $types ) as $type ) {
                $relateds = get_field( "related_$type" ); 
                if( $relateds ) {
                    echo '<strong id="related-' . $type . '">Related ' . $types[ $type ] . '</strong>';
                    echo '<ul id="' . $type . '">';
                    foreach( $relateds as $related ) {
                       if ( $related->post_status == "publish" ) {
                           echo '<li><a href="'. $related->guid . '" title="' . $related->post_excerpt . '">' . $related->post_title . '</a></li>'; 
                       } else {
                           echo '<li>' . $related->post_title . '</li>'; 
                       }
                    }
                    echo '</ul>';
                }
                }
            ?>
            <hr class="soften" />
            <?php get_sidebar('promo'); ?>
        </div>
    </div><!-- /.row .content -->
    <hr />
    <div class="row">
        <div class="span8">
    <?php bootstrapwp_content_nav('nav-below');?>
    <?php comments_template(); ?>
        </div>
    </div>
<?php get_footer(); ?>
