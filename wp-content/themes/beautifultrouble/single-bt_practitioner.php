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

get_header(); 
global $query_string;
$original_posts = $query_string;
?>
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
        <?php the_content();?>
        <?php $values = get_field('sources');
                        if($values)
                        {
                                echo '<h3 id="sources" class="sources">Sources</h3>';
                                echo '<ul>';
                         
                                foreach($values as $value)
                                {
                                    echo '<li>';
                                    echo '<a href="' . $value['link'] . '">' . $value['description'] . '</a>';  
                                    echo '</li>';
                                }
                         
                                echo '</ul>';
                        }
                        ?>						

                <?php $args = array(
                    'numberposts'     => -1,
                    'offset'          => 0,
                    'orderby'         => 'title',
                    'order'           => 'DESC',
                    'post_type'       => array('bt_tactic', 'bt_principle', 'bt_theory', 'bt_case'),
                    'post_status'     => 'any',
                    'meta_query' => array(
                            array(
                                    'key' => 'related_practitioners',
                                    'compare' => 'LIKE',
                                    'value' => $post->ID,
                            )
                    )
                );  
                $posts_array = get_posts( $args );
                if( $posts_array ) {
                    echo '<h3 id="related-modules" class="related-modules">Related Modules</h3>';
                    echo '<ul>';
                    foreach( $posts_array as $post ) {
                        echo '<li>';
                        echo '<a href="' . $post->guid . '">' . $post->post_title . '</a>';
                        echo '</li>';
                    }
                    echo '</ul>';
                }
                ?>
        <?php wp_reset_postdata(); ?>
        <br />
        <p class="alert alert-info hidden-phone">Hey there! Did you know that you can jump into our <a href="http://beautifultrouble.org/2013/03/12/visualize-beautiful-trouble/">experimental visualization interface</a> right from <a href="http://explore.beautifultrouble.org/#<?php echo $post->post_name; ?>">this point</a>? <b><a href="http://explore.beautifultrouble.org/#<?php echo $post->post_name; ?>">Give it a try and send us your feedback!</a></b></p>
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
    </div>
<?php get_footer(); ?>
