<?php
/**
 *
 * Template Name: Front Page Template
 *
 *
 * @package WP-Bootstrap
 * @subpackage Default_Theme
 * @since WP-Bootstrap 0.5
 *
 * Last Revised: March 4, 2012
 */
get_header(); ?>
<?php $fields = get_fields(); ?>
<div class="container">
    <p class="well"><i class="icon-star-empty"></i> Beautiful Trouble is a book and web toolbox that puts the accumulated wisdom of decades of creative protest into the hands of the next generation of change-makers.</p>
    <div class="row">
        <div id="myCarousel" class="carousel slide span8">
        <!-- Carousel items -->
            <div class="carousel-inner">
<?php if( $fields['slideshow'] ) {
    // Let's loop through any repeating elements and create seperate
    // arrays for each type of repeating element, i.e., Insights, Epigraphs
    $slides = $fields['slideshow'];
        //echo "<pre>", print_r( $slide ), "</pre>";
    foreach( $slides as $slide ) {
        if ( get_the_post_thumbnail($slide->ID, 'bt-featured') ) {
    $active = $slide == $slides[0] ? 'active' : '';
                ?>
                <div class="item <?php echo $active ?>">
                    <a href="<?php echo get_permalink( $slide->ID ); ?>"><?php echo get_the_post_thumbnail($slide->ID, 'bt-featured', array( 'alt' => $slide->post_title, 'title' => $slide->post_title ) ); ?></a>
                    <div class="carousel-caption">
                    <h4><?php $obj = get_post_type_object( get_post_type( $slide->ID) ); echo strtoupper( $obj->labels->singular_name ); ?>: <?php echo $slide->post_title; ?></h4>
                    <p><?php if ( $slide->post_excerpt ) { 
                        echo $slide->post_excerpt;
                    } else {
                        echo truncate( $slide->post_content, 248, '...' ); 
                    } ?></p>
                    </div>
                </div>
        
<?php  }
    }
} ?>    
                <div class="item">
                    <a href="/the-book/"><img src="/wp-content/themes/beautifultrouble/img/slide-book-promo.jpg" /></a>
                </div> 
            </div>
        <!-- Carousel nav -->
        <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
        <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
        </div>

        <div class="span4">
            <?php get_sidebar('book'); ?>
        </div>
    </div> <!-- row -->
<div class="marketing">
  <div class="row">
    <div class="span4">
      <?php
      if ( function_exists('dynamic_sidebar')) dynamic_sidebar("home-one");
      ?>
    </div>
    <div class="span4">
      <?php
      if ( function_exists('dynamic_sidebar')) dynamic_sidebar("home-two");
      ?>
        <div class="upcoming_events">
            <?php $args = array('orderby' => 'meta_value', 'meta_key' => 'date', 'order' => 'ASC', 'post_type' => array('bt_event') );
            $my_query = null;
            $my_query = new WP_Query($args);
            echo "<h2>Upcoming Events</h2>";
            if( $my_query->have_posts() ) {
              echo '<ul class="unstyled">';
              while ($my_query->have_posts()) : $my_query->the_post();
                $date  = get_field('date');
                $date_obj   = DateTime::createFromFormat('Y/m/d', $date );
                $time       = time();
                $unix_date  = $date_obj->format('U');
                $nice_date  = $date_obj->format('M d');
                if( $unix_date >= $time ) { ?>
                    <li class="event"><span class="date badge badge-inverse"><?php echo $nice_date ?></span> <a href="<?php echo post_permalink(); ?>"><?php the_title(); ?></a></li>                
              <?php }
              endwhile;
                echo "</ul>";
                echo 'No event in your town? <a href="/get-involved">Help us organize one!</a>';
            } else {
                echo 'No events? <a href="/get-involved">Help us organize one!</a>';
            }
            wp_reset_query();  // Restore global post data stomped by the_post().
            ?>
            </div>
    </div>
    <div class="span4">
      <?php
      if ( function_exists('dynamic_sidebar')) dynamic_sidebar("home-three");
      ?>
    </div>
  </div>
</div><!-- /.marketing -->
<div class="marketing">
    <div class="row">
        <div class="span4">
          <?php
          if ( function_exists('dynamic_sidebar')) dynamic_sidebar("home-four");
          ?>
<?php if( $fields['spotlight_module'] ) {
    $module  = array_shift( $fields['spotlight_module'] );
?>
    <h2>Spotlighted Module</h2>
    <?php echo get_the_post_thumbnail($module->ID, 'thumbnail', array( 'class' => "spotlight-module-img", 'alt' => $module->post_title, 'title' => $module->post_title ) ); ?>
    <a href="<?php echo get_permalink( $module->ID ); ?>"><h3><?php echo $module->post_title; ?></h3></a>
        <p><?php if ( $module->post_excerpt ) { 
                        echo $module->post_excerpt;
                    } else {
                        echo truncate( $module->post_content, 300, '...' );
                        echo ' <a href="', get_permalink( $module->ID ), '">Read more</a>'; 
                    } ?></p>   
<?php } ?>    
        </div>
        <div class="span4">
      <?php
      if ( function_exists('dynamic_sidebar')) dynamic_sidebar("home-five");
?>
        <h2>Spotlighted Contributor</h2> 
        <?php if ( validate_gravatar( get_the_author_meta('user_email') ) ) { 
            echo get_avatar( get_the_author_meta('user_email') );  
        } ?>
        <h3><?php the_author_posts_link(); ?></h3>
        <?php the_author_meta( 'description' ); ?> 
        </div>
        <div class="span4">
      <?php
      if ( function_exists('dynamic_sidebar')) dynamic_sidebar("home-six");
      ?>
        </div>
    </div>
</div>
<?php get_footer();?>
