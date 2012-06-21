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
                    <h4><?php echo $slide->post_title; ?></h4>
                    <p><?php echo $slide->post_excerpt; ?></p>
                    </div>
                </div>
        
<?php  }
    }
} ?> 
            </div>
        <!-- Carousel nav -->
        <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
        <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
        </div>

        <div class="span3">
        <h2>Buy the book!</h2>
                    <p>
                    Yes, that's right, you can order <i>Beautiful Trouble</i> right now! Available exclusively from our publisher <a href="http://www.orbooks.com/catalog/beautiful-trouble/">OR Books</a>.</p>
                    <p>
                    <img src="wp-content/themes/beautifultrouble/img/Beautiful_Trouble_Book_Cover.png" /><br />
                    <a class="img" href="http://orbooks.mybigcommerce.com/cart.php?action=add&product_id=264"><img src="http://orbooks.com/wp-content/themes/orbooks/media/image/add_button.gif" /></a>&nbsp;Paperback:&nbsp;$25&nbsp;<br />
                    <a class="img" href="http://orbooks.mybigcommerce.com/cart.php?action=add&product_id=265"><img src="http://orbooks.com/wp-content/themes/orbooks/media/image/add_button.gif" /></a>&nbsp;Ebook:&nbsp;$10&nbsp;<br />
                    <a class="img" href="http://orbooks.mybigcommerce.com/cart.php?action=add&product_id=266"><img src="http://orbooks.com/wp-content/themes/orbooks/media/image/add_button.gif" /></a>&nbsp;Both:&nbsp;$28&nbsp;
                    </p>
                    <p><i class="highlight">Not sure yet? <b><a href="http://docs.google.com/viewer?url=http%3A%2F%2Fbeautifultrouble.org%2Fbeautiful_trouble_module_example.pdf">Have a look inside the book</a>!</b></i></p>
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
            <?php $args = array('post_type' => array ('bt_event'));
            $my_query = null;
            $my_query = new WP_Query($args);
            if( $my_query->have_posts() ) {
              echo "<h2>Upcoming Events</h2>";
              echo "<ul>";
              while ($my_query->have_posts()) : $my_query->the_post(); ?>   		
                    <li class="event"><a href="<?php post_permalink(); ?>"><?php the_title(); ?></a></li>                
              <?php
              endwhile;
            }
            echo "</ul>";
            wp_reset_query();  // Restore global post data stomped by the_post().
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
<?php if( $fields['spotlight_module'] ) {
    $module  = array_shift( $fields['spotlight_module'] );
?>
    <h2>Spotlighted Module</h2>
    <?php echo get_the_post_thumbnail($module->ID, 'thumbnail', array( 'class' => "spotlight-module-img", 'alt' => $module->post_title, 'title' => $module->post_title ) ); ?>
    <a href="<?php echo get_permalink( $module->ID ); ?>"><h3><?php echo $module->post_title; ?></h3></a>
        <p><?php echo $module->post_excerpt; ?></p>   
<?php } ?>    
        </div>
        <div class="span4">
      <?php
      if ( function_exists('dynamic_sidebar')) dynamic_sidebar("home-five");
?>
        &nbsp;
<?php if( $fields['spotlight_partner'] ) {
    $module  = array_shift( $fields['spotlight_partner'] );
?>
    <h2>Spotlighted Partner</h2>
    <?php echo get_the_post_thumbnail($module->ID, 'bt-thumb-300', array( 'class' => "spotlight-module-img", 'alt' => $module->post_title, 'title' => $module->post_title ) ); ?>
    <a href="<?php echo get_permalink( $module->ID ); ?>"><h3><?php echo $module->post_title; ?></h3></a>
        <p><?php echo $module->post_excerpt; ?></p>   
<?php } ?>    
        </div>
        <div class="span4">
      <?php
      if ( function_exists('dynamic_sidebar')) dynamic_sidebar("home-six");
      ?>
        </div>
    </div>
</div>
<?php get_footer();?>
