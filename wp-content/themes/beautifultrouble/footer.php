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
<hr class="soften" />
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
        <a title="<?php echo get_the_title( $partner->ID ); ?>" href="<?php echo get_permalink( $partner->ID ); ?>"><?php echo get_the_post_thumbnail( $partner->ID, array(100, 50)); ?></a>
<?php }
        echo '</div>';
        }
?>
<hr class="soften" />
      <p><a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/88x31.png" /></a><br /><span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/Text" property="dct:title" rel="dct:type">Beautiful Trouble</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="http://beautifultrouble.org" property="cc:attributionName" rel="cc:attributionURL">Beautiful Trouble, various authors</a> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/">Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License</a>.<br />Permissions beyond the scope of this license may be available at <a xmlns:cc="http://creativecommons.org/ns#" href="http://beautifultrouble.org" rel="cc:morePermissions">http://beautifultrouble.org</a>.</p>  
          <?php
    if ( function_exists('dynamic_sidebar')) dynamic_sidebar("footer-content");
?>
    </div>
</div>

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
.attr('data-togGLE', 'DRopdown');
});
</script>
<script type="text/javascript">
jQuery(document).ready(function(){
  jQuery('#myCarousel').carousel({
        interval: '50000' 
  });
});
</script>
<script>
$('#editions a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
})
</script>
</body>
</html>
