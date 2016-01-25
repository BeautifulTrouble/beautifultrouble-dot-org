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
            <ul class="breadcrumb elevator"><li>
                Beautiful Trouble is a book, web toolbox and international network of artist-activist <a href="/trainings/">trainers</a> whose mission is to make grassroots movements more creative and more effective.
                <a class="visible-phone" href="/all-modules/">Start browsing the Web Toolbox &raquo;</a>
            </li></ul>
            <br>
    <div class="row">
        <div id="myCarousel" class="carousel slide span8 hidden-phone">
        <!-- Carousel items -->
            <div class="carousel-inner">
                <div class="item active">
                    <a href="https://solutions.thischangeseverything.org/"><img src="/wp-content/themes/beautifultrouble/img/BT_Banners1.jpg"></a>
                </div>
                <div class="item">
                    <a href="http://beautifulrising.org"><img src="/wp-content/themes/beautifultrouble/img/BT_Banners2.jpg"></a>
                </div>
                <div class="item">
                    <img src="/wp-content/themes/beautifultrouble/img/BT_Banners3.jpg">
                </div>
                <div class="item">
                    <img src="/wp-content/themes/beautifultrouble/img/BT_Banners4.jpg">
                </div>
                <div class="item">
                    <a href="/study-guide"><img src="/wp-content/themes/beautifultrouble/img/BT_Banners5.jpg"></a>
                </div>
                <div class="item">
                    <a href="/training"><img src="/wp-content/themes/beautifultrouble/img/slide-training.jpg"></a>
                </div>
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
