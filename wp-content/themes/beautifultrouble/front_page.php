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
    <p class="well"><i class="icon-star-empty"></i> Beautiful Trouble is a book and web toolbox that puts the accumulated wisdom of decades of creative protest into the hands of the next generation of change-makers. 
     <a class="visible-phone" href="/all-modules/">Start browsing the Web Toolbox &raquo;</a>
    </p>
    <div class="row">
        <div id="myCarousel" class="carousel slide span8 hidden-phone">
        <!-- Carousel items -->
            <div class="carousel-inner">
            <! -- default module slides -->
                <div class="item active">
                    <a href="http://explore.beautifultrouble.org" "Visualizing Trouble: take our new beta visualization for a spin!"><img src="/wp-content/themes/beautifultrouble/img/visualize4.png"></a>
                </div>
                <div class="item">
                    <a href="/trainings"><img src="/wp-content/themes/beautifultrouble/img/slide-training.jpg"></a>
                </div>
            <?php $types = array( 'tactic', 'principle', 'theory', 'case', 'practitioner' ); ?>
            <?php foreach ($types as $type) { 
            //$active = $type == $types[0] ? 'active' : '';
            ?>
                <div class="item <?php echo $active ?>">
                    <a href="/<? echo $type ?>"><img src="/wp-content/themes/beautifultrouble/img/bt_slides_<?php echo $type ?>.png" /></a>
                </div>
            <?php } ?>
<?php if( $fields['slideshow'] ) {
    // Let's loop through any repeating elements and create seperate
    // arrays for each type of repeating element, i.e., Insights, Epigraphs
    $slides = $fields['slideshow'];
    foreach( $slides as $slide ) {
        if ( get_the_post_thumbnail($slide->ID, 'bt-featured') ) {
    // Can't use this now, as the defaults need the active class
    //$active = $slide == $slides[0] ? 'active' : '';
                ?>
                <div class="item <?php // echo $active ?>">
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
            </div>
        <!-- Carousel nav -->
        </div>

        <div class="span4">
            <?php get_sidebar('promo'); ?>
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
        <div class="upcoming_events" style="display:none">
            <?php $args = array('nopaging' => 'true', 'orderby' => 'meta_value', 'meta_key' => 'date', 'order' => 'ASC', 'post_type' => array('bt_event') );
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
          <?php
            if ( function_exists('dynamic_sidebar') ) 
                dynamic_sidebar("home-two");
          ?>
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
        </div>
        <div class="span4">
          <?php
          if ( function_exists('dynamic_sidebar')) dynamic_sidebar("home-five");
          ?>
        </div>
        <div class="span4">
          <?php
          if ( function_exists('dynamic_sidebar')) dynamic_sidebar("home-six");
          ?>
        </div>
    </div>
</div>
<?php get_footer();?>
