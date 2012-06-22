<?php
/**
 * Default Footer
 *
 * @package WP-Bootstrap
 * @subpackage Default_Theme
 * @since WP-Bootstrap 0.1
 *
 * Last Revised: February 4, 2012
 */
?>


      <footer>

<hr "soften" />
<div class="row">
    <div align="center">
<?php $args = array( 'nopaging' => 'true', 'post_type' => 'bt_partner');
        global $post;
        $partners = get_posts( $args );
        if ( $partners ) {
            echo '<h3 class="partners">Partners that this project would not be possible without</h3>';
            foreach( $partners as $partner ) {
        ?>
        <?php echo get_the_post_thumbnail( $partner->ID ); ?>             
<?php }
        }
?>
    </div>
</div>
<hr class="soften" />
      <p class="pull-right"><a href="#">Back to top</a></p>
        <p>&copy; <?php bloginfo('name'); ?> <?php the_time('Y') ?></p>
          <?php
    if ( function_exists('dynamic_sidebar')) dynamic_sidebar("footer-content");
?>


      </footer>

    </div> <!-- /container -->
<?php wp_footer(); ?> 
<script type="text/javascript">
// Adding the class "dropdown" to li elements with submenus  //	
jQuery(document).ready(function(){
jQuery("ul.sub-menu").parent().addClass("dropdown");
jQuery("ul#main-menu li.dropdown a").addClass("dropdown-toggle");
jQuery("ul.sub-menu li a").removeClass("dropdown-toggle"); 
jQuery('.navbar-fixed-top .dropdown-toggle').append('<b class="caret"></b>');
  });
</script>
<script type="text/javascript">
jQuery(document).ready(function(){
 // Don't FORGET: replace all $ with jQuery to prevent conflicts //
jQuery('a.dropdown-toggle')
.attr('data-toggle', 'dropdown');
  });
</script>
  </body>
</html>
