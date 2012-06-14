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
                <?php the_post_thumbnail('bt-featured' ); ?>
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
                <div id="in-summary" class="alert alert-info">
                    <strong>In Sum</strong>
                    <?php the_excerpt(); ?>
                </div> 
                <div id="origins">
                    <strong class="origins">Origins:</strong> <?php echo $fields['origins']; ?>
                </div>
            <?php the_content();?>
            <?php if( $fields['key_principle_at_work'] ) {
                echo '<div id="key-principle" class="alert alert-success">';
                echo '<strong id="key-principle">Key Principle at work</strong><br />';
                // Key Principle At Work
                $principles = $fields['key_principle_at_work'];
                foreach( $principles as $principle ) {
                    $related = array_pop( $principle['related_principle'] );
                    echo '<p class="principle"><b><a href="' . $related->guid . '">' . $related->post_title . '</a></b><br />';
                    echo $principle['explanation'];
                    echo '</p>';
                }
                echo '</div>';
            }
            ?>
            <?php if( $fields['potential_pitfalls'] ) {
                // Potential Pitfalls
                echo '<div class="alert">';
                echo '<strong id="potential-pitfalls">Potential Pitfalls</strong>';
                echo '<p class="pitfalls">' . $fields['potential_pitfalls'] . '</p>';
                echo '</div>';
            } ?>
            <?php the_tags( '<p>Tags: ', ', ', '</p>'); ?>
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
                $types = array( 'tactics' => 'Tactics', 'theories' => 'Theories', 'case_studies' => 'Case Studies', 'principles' => 'Principles', 'practitioners' => 'Practitioners' );
                foreach( array_keys( $types ) as $type ) {
                $relateds = get_field( "related_$type" ); 
                if( $relateds ) {
                    echo '<strong id="related-' . $type . '">Related ' . $types[ $type ] . '</strong>';
                    echo '<ul id="' . $type . '">';
                    foreach( $relateds as $related ) {
                       echo '<li><a href="'. $related->guid . '" title="' . $related->post_excerpt . '">' . $related->post_title . '</a></li>'; 
                    }
                    echo '</ul>';
                }
                }
            ?>
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
