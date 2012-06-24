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
            echo '<h3 class="partners">Partner Organizations</h3>';
            echo '<div id="partner-logos">';
            foreach( $partners as $partner ) {
        ?>
        <?php echo get_the_post_thumbnail( $partner->ID ); ?>             
<?php }
        echo '</div>';
        }
?>
    <hr class="soften" />
    <h3>Supporters</h3>
        <p>We are hugely grateful for the essential financial support provided by the Lambent Foundation Fund of the Tides Foundation, as well as the Canadian Labour Congress, Communications, Energy and Paperworks Union of Canada and the <a href="http://www.kickstarter.com/projects/151304769/beautiful-trouble/backers">246 individuals who donated via Kickstarter</a>. A special thanks to our Kickstarter 'co-publishers,' Jeff Reifman, Yvonne Tasker-Rothenberg, Chris Simpson and Larry Sakin, and to <a href="http://rabble.ca">rabble.ca</a> for their sponsorship of our Canadian book tour.</p>
        <div id="supporters-logos">
            <img src="/wp-content/themes/beautifultrouble/img/lambent-logo.png" alt="Lambent Foundation Fund of the Tides Foundation" /><img src="/wp-content/themes/beautifultrouble/img/logo-CLC.png" alt="Canadian Labour Congress"  /><img src="/wp-content/themes/beautifultrouble/img/cep-logo.jpg" alt="Communications, Energy and Paperworks Union of Canada" /><img src="/wp-content/themes/beautifultrouble/img/rabble-logo.png" alt="rabble.ca -- news for the rest of us" />
        </div>
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
