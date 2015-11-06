<?php
/* Beautiful Trouble customizations */


function beautifultrouble_css_loader() {
//wp_enqueue_style('bootstrap', get_template_directory_uri().'/css/bootstrap.css', false ,'1.0', 'all' );
wp_enqueue_style('bootstrap', get_theme_root_uri().'/beautifultrouble/css/bootstrap.css', false ,'1.0', 'all' );
wp_enqueue_style('docs', get_template_directory_uri().'/css/docs.css', false ,'1.0', 'all' );
wp_enqueue_style('prettify', get_template_directory_uri().'/css/prettify.css', false ,'1.0', 'all' );
wp_enqueue_style('responsive', get_template_directory_uri().'/css/bootstrap-responsive.css', false, '1.0', 'all' );
wp_enqueue_style('style', get_template_directory_uri().'/style.css', false ,'1.2', 'all' );
wp_enqueue_style('bt', get_theme_root_uri().'/beautifultrouble/style.css', false,'0.1', 'all' );
wp_enqueue_script('scalefix', get_theme_root_uri().'/beautifultrouble/js/scale-fix.js', false,'0.1', true );
}
add_action('wp_enqueue_scripts', 'beautifultrouble_css_loader');

function bootstrapwp_posted_on() {
	printf( __( '<span class="sep"> Contributed by </span> <span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span></span>', 'beautifultrouble' ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'bootstrap' ), get_the_author() ) ),
		esc_html( get_the_author() )
	);
}

function get_img_url($text) {
    preg_match('/src=["\'](.*?)["\']/i', $text, $matches);
    return $matches[1];
}

function create_post_type() {
	register_post_type( 'bt_tactic',
		array(
			'labels' => array(
				'name' => __( 'Tactics' ),
                                'singular_name' => __( 'Tactic' ),
                                'add_new' => _x('Add new', 'Tactic'),
                                'add_new_item'  => 'Add new Tactic',
                                'edit_item'  => 'Edit Tactic',
                                'new_item'  => 'New Tactic',
                                'view_item'  => 'View Tactic',
                                'search_items'  => 'Search Tactics',
                                'not_found'  => 'No Tactics found',
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'tactic'),
                        'description' => 'Specific forms of creative action, such as a flash mob or an occupation.',
                        'menu_position' => 5,
                        'menu_icon' => '/wp-content/themes/beautifultrouble/img/bt_menu_tactic.png',
                        'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions' ),
		)
	);
	register_post_type( 'bt_principle',
		array(
			'labels' => array(
				'name' => __( 'Principles' ),
                                'singular_name' => __( 'Principle' ),
                                'add_new' => _x('Add new', 'Principle'),
                                'add_new_item'  => 'Add new Principle',
                                'edit_item'  => 'Edit Principle',
                                'new_item'  => 'New Principle',
                                'view_item'  => 'View Principle',
                                'search_items'  => 'Search Principles',
                                'not_found'  => 'No Principles found',
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'principle'),
                        'description' => 'Hard-won insights that can inform creative action design.',
                        'menu_position' => 5,
                        'menu_icon' => '/wp-content/themes/beautifultrouble/img/bt_menu_principle.png',
                        'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions' ),
		)
	);
	register_post_type( 'bt_theory',
		array(
			'labels' => array(
				'name' => __( 'Theories' ),
                                'singular_name' => __( 'Theory' ),
                                'add_new' => _x('Add new', 'Theory'),
                                'add_new_item'  => 'Add new Theory',
                                'edit_item'  => 'Edit Theory',
                                'new_item'  => 'New Theory',
                                'view_item'  => 'View Theory',
                                'search_items'  => 'Search Theories',
                                'not_found'  => 'No Theories found',
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'theory'),
                        'description' => 'Big-picture ideas that help us understand how the world works and how we might go about changing it.',
                        'menu_position' => 5,
                        'menu_icon' => '/wp-content/themes/beautifultrouble/img/bt_menu_theory.png',
                        'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions' ),
		)
	);
	register_post_type( 'bt_case',
		array(
			'labels' => array(
				'name' => __( 'Case Studies' ),
                                'singular_name' => __( 'Case Study' ),
                                'add_new' => _x('Add new', 'Case Study'),
                                'add_new_item'  => 'Add new Case Study',
                                'edit_item'  => 'Edit Case Study',
                                'new_item'  => 'New Case Study',
                                'view_item'  => 'View Case Study',
                                'search_items'  => 'Search Case Studies',
                                'not_found'  => 'No Case Studies found',
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'case'),
                        'description' => 'Capsule stories of successful creative actions.',
                        'menu_position' => 5,
                        'menu_icon' => '/wp-content/themes/beautifultrouble/img/bt_menu_case.png',
                        'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions' ),
		)
	);
	register_post_type( 'bt_practitioner',
		array(
			'labels' => array(
				'name' => __( 'Practitioners' ),
                                'singular_name' => __( 'Practitioner' ),
                                'add_new' => _x('Add new', 'Practitioner'),
                                'add_new_item'  => 'Add new Practitioner',
                                'edit_item'  => 'Edit Practitioner',
                                'new_item'  => 'New Practitioner',
                                'view_item'  => 'View Practitioner',
                                'search_items'  => 'Search Practitioners',
                                'not_found'  => 'No Practitioners found',
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'practitioner'),
                        'description' => 'Brief write-ups of some of the people and groups that inspire us to be better changemakers.',
                        'menu_position' => 5,
                        'menu_icon' => '/wp-content/themes/beautifultrouble/img/bt_menu_practitioner.png',
                        'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions' ),
		)
	);
	register_post_type( 'bt_partner',
		array(
			'labels' => array(
				'name' => __( 'Partners' ),
                                'singular_name' => __( 'Partner' ),
                                'add_new' => _x('Add new', 'Partner'),
                                'add_new_item'  => 'Add new Partner',
                                'edit_item'  => 'Edit Partner',
                                'new_item'  => 'New Partner',
                                'view_item'  => 'View Partner',
                                'search_items'  => 'Search Partners',
                                'not_found'  => 'No Partners found',
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'partner'),
                        'description' => 'Brief write-ups of some of the people and groups that helped make this project possible.',
                        'menu_position' => 5,
                        'menu_icon' => '/wp-content/themes/beautifultrouble/img/bt_menu_partner.png',
                        'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions' ),
		)
	);
        register_post_type( 'bt_trainer',
		array(
			'labels' => array(
				'name' => __( 'Trainers' ),
                                'singular_name' => __( 'Trainer' ),
                                'add_new' => _x('Add new', 'Trainer'),
                                'add_new_item'  => 'Add new Trainer',
                                'edit_item'  => 'Edit Trainer',
                                'new_item'  => 'New Trainer',
                                'view_item'  => 'View Trainer',
                                'search_items'  => 'Search Trainers',
                                'not_found'  => 'No Trainers found',
			),
			'public' => true,
			'has_archive' => false,
			'rewrite' => array('slug' => 'trainer'),
                        'description' => 'Trainers in the Beautiful Trouble network',
                        'menu_position' => 5,
                        'menu_icon' => '/wp-content/themes/beautifultrouble/img/bt_menu_partner.png',
                        'supports' => array( 'title', 'editor', 'thumbnail', 'revisions' ),
		)
	);
	register_post_type( 'bt_event',
		array(
			'labels' => array(
				'name' => __( 'Events' ),
                                'singular_name' => __( 'Event' ),
                                'add_new' => _x('Add new', 'Event'),
                                'add_new_item'  => 'Add new Event',
                                'edit_item'  => 'Edit Event',
                                'new_item'  => 'New Event',
                                'view_item'  => 'View Event',
                                'search_items'  => 'Search Events',
                                'not_found'  => 'No Events found',
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'event'),
                        'description' => 'Upcoming Beautiful Trouble events near you.',
                        'menu_position' => 5,
                        'menu_icon' => '/wp-content/themes/beautifultrouble/img/bt_menu_event.png',
                        'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions' ),
		)
	);
	register_post_type( 'bt_review',
		array(
			'labels' => array(
				'name' => __( 'Reviews' ),
                                'singular_name' => __( 'Review' ),
                                'add_new' => _x('Add new', 'Review'),
                                'add_new_item'  => 'Add new Review',
                                'edit_item'  => 'Edit Review',
                                'new_item'  => 'New Review',
                                'view_item'  => 'View Review',
                                'search_items'  => 'Search Reviews',
                                'not_found'  => 'No Reviews found',
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'review'),
                        'description' => 'Reviews of the Beautiful Trouble project',
                        'menu_position' => 5,
                        'menu_icon' => '/wp-content/themes/beautifultrouble/img/bt_menu_review.png',
                        'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions' ),
		)
	);
}

add_action( 'init', 'create_post_type' );

// add custom function to filter hook 'addquicktag_post_types'
add_filter( 'addquicktag_post_types', 'my_addquicktag_post_types' );
/**
 * Return array $post_types with custom post types
 * 
 * @param   $post_type Array
 * @return  $post_type Array
 */
function my_addquicktag_post_types( $post_types ) {

    $post_types = array( 'bt_tactic', 'bt_principle', 'bt_theory', 'bt_case' );

    return $post_types;
}
add_image_size( 'bt-featured', 770, 500, false );
add_image_size( 'bt-thumb-600', 600, 300, false );
add_image_size( 'bt-thumb-300', 300, 100, true );


function custom_excerpt_length( $length ) {
	return 50;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

// Numeric Page Navi (built into the theme by default)
function page_navi($before = '', $after = '') {
	global $wpdb, $wp_query;
	$request = $wp_query->request;
	$posts_per_page = intval(get_query_var('posts_per_page'));
	$paged = intval(get_query_var('paged'));
	$numposts = $wp_query->found_posts;
	$max_page = $wp_query->max_num_pages;
	if ( $numposts <= $posts_per_page ) { return; }
	if(empty($paged) || $paged == 0) {
		$paged = 1;
	}
	$pages_to_show = 7;
	$pages_to_show_minus_1 = $pages_to_show-1;
	$half_page_start = floor($pages_to_show_minus_1/2);
	$half_page_end = ceil($pages_to_show_minus_1/2);
	$start_page = $paged - $half_page_start;
	if($start_page <= 0) {
		$start_page = 1;
	}
	$end_page = $paged + $half_page_end;
	if(($end_page - $start_page) != $pages_to_show_minus_1) {
		$end_page = $start_page + $pages_to_show_minus_1;
	}
	if($end_page > $max_page) {
		$start_page = $max_page - $pages_to_show_minus_1;
		$end_page = $max_page;
	}
	if($start_page <= 0) {
		$start_page = 1;
	}
		
	echo $before.'<div class="pagination"><ul class="clearfix">'."";
	if ($paged > 1) {
		$first_page_text = "&laquo";
		echo '<li class="prev"><a href="'.get_pagenum_link().'" title="First">'.$first_page_text.'</a></li>';
	}
		
	$prevposts = get_previous_posts_link('&larr; Previous');
	if($prevposts) { echo '<li>' . $prevposts  . '</li>'; }
	else { echo '<li class="disabled"><a href="#">&larr; Previous</a></li>'; }
	
	for($i = $start_page; $i  <= $end_page; $i++) {
		if($i == $paged) {
			echo '<li class="active"><a href="#">'.$i.'</a></li>';
		} else {
			echo '<li><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
		}
	}
	echo '<li class="">';
	next_posts_link('Next &rarr;');
	echo '</li>';
	if ($end_page < $max_page) {
		$last_page_text = "&raquo;";
		echo '<li class="next"><a href="'.get_pagenum_link($max_page).'" title="Last">'.$last_page_text.'</a></li>';
	}
	echo '</ul></div>'.$after."";
}

/**
 * Display navigation to next/previous pages when applicable
 */
function bootstrapwp_content_nav( $nav_id ) {
	global $wp_query;

	?>

	<?php if ( is_single()  ) : // navigation links for single posts ?>
        <?php $sort_by = get_post_type( $post ) == 'post' ? 'post_date' : 'post_title' ?>
<ul class="pager">
		<?php previous_post_link_plus( array('order_by' => $sort_by, 'format' => '<li class="previous">%link</li>', 'link' => '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'beautifultrouble' ) . '</span> %title' ) ); ?>
		<?php next_post_link_plus( array('order_by' => $sort_by, 'format' => '<li class="next">%link</li>', 'link' => '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'bootstrapwp' ) . '</span>') ); ?>
</ul>
	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>
					<?php if (function_exists('page_navi')) { // if expirimental feature is active ?>
						
						<?php page_navi(); // use the page navi function ?>
						
					<?php } else { // if it is disabled, display regular wp prev & next links ?>
<ul class="pager">
		<?php if ( get_next_posts_link() ) : ?>
		<li class="next"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'bootstrapwp' ) ); ?></li>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<li class="previous"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'bootstrapwp' ) ); ?></li>
		<?php endif; ?>
</ul>
					<?php } ?>			
	<?php endif; ?>

	<?php
}

function thumbnail_caption($html, $post_id, $post_thumbnail_id, $size, $attr)
// Variation of this idea http://stereointeractive.com/blog/2010/02/12/wordpress-get-post-images-and-the_post_thumbnail-caption/ 
// However, we just return the HTML if we're on an archive page, or there is no thumnail image.
    {
    if ( $post_thumbnail_id && is_single() ) {
      $attachment =& get_post($post_thumbnail_id);
      // post_title => image title
      // post_excerpt => image caption
      // post_content => image description
     
      if ($attachment->post_excerpt || $attachment->post_content) {
        $caption = $attachment->post_excerpt ? $attachment->post_excerpt : $attachment->post_content; 
        $html .= '<p class="thumbcaption">';
          $html .= '<span class="captitle">' . $caption . '</span>';
      }
     
        } 
            return $html;
    } 
add_action('post_thumbnail_html', 'thumbnail_caption', null, 5);

function beautifultrouble_widgets_init() {
  register_sidebar( array(
    'name' => 'Page Sidebar',
    'id' => 'sidebar-page',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => "</div>",
    'before_title' => '<h4 class="widget-title">',
    'after_title' => '</h4>',
  ) );

  register_sidebar( array(
    'name' => 'Posts Sidebar',
    'id' => 'sidebar-posts',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => "</div>",
    'before_title' => '<h4 class="widget-title">',
    'after_title' => '</h4>',
  ) );

  register_sidebar( array(
    'name' => 'Events Sidebar',
    'id' => 'sidebar-event',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => "</div>",
    'before_title' => '<h4 class="widget-title">',
    'after_title' => '</h4>',
  ) );

  register_sidebar(array(
    'name' => 'Home One',
    'id'   => 'home-one',
    'description'   => 'Left textbox on homepage',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>'
  ));

    register_sidebar(array(
    'name' => 'Home Two',
    'id'   => 'home-two',
    'description'   => 'Middle textbox on homepage',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>'
  ));

    register_sidebar(array(
    'name' => 'Home Three',
    'id'   => 'home-three',
    'description'   => 'Right textbox on homepage',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>'
  ));

  register_sidebar(array(
    'name' => 'Home Four',
    'id'   => 'home-four',
    'description'   => 'Left textbox on homepage',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>'
  ));

    register_sidebar(array(
    'name' => 'Home Five',
    'id'   => 'home-five',
    'description'   => 'Middle textbox on homepage',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>'
  ));

    register_sidebar(array(
    'name' => 'Home Six',
    'id'   => 'home-six',
    'description'   => 'Right textbox on homepage',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>'
));

    register_sidebar(array(
    'name' => 'Home Seven',
    'id'   => 'home-seven',
    'description'   => 'Under events listing on homepage',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>'
));

    register_sidebar(array(
    'name' => 'Footer Content',
    'id'   => 'footer-content',
    'description'   => 'Footer text or acknowledgements',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h4>',
    'after_title'   => '</h4>'
  ));
}
add_action( 'init', 'beautifultrouble_widgets_init' );

function validate_gravatar($email) {
	// Craft a potential url and test its headers
	$hash = md5(strtolower(trim($email)));
	$uri = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
	$headers = @get_headers($uri);
	if (!preg_match("|200|", $headers[0])) {
		$has_valid_avatar = FALSE;
	} else {
		$has_valid_avatar = TRUE;
	}
	return $has_valid_avatar;
}

/**
 * truncate() Simple function to shorten a string and add an ellipsis
 *
 * @param string $string Origonal string
 * @param integer $max Maximum length
 * @param string $rep Replace with... (Default = '' - No elipsis -)
 * @return string
 * @author David Duong
 **/
function truncate ($string, $max = 50, $rep = '') {
    $leave = $max - strlen ($rep);
    return substr_replace($string, $rep, $leave);
}

function count_user_modules( $userid ) {
    global $wpdb;
    $post_types = array( 'bt_tactic', 'bt_principle', 'bt_theory', 'bt_case' );
    $modules = 0;
    foreach ( $post_types as $post_type ) {
      $where = get_posts_by_author_sql($post_type, true, $userid);
      $count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts $where" );
      $modules = $modules + $count;
    }
    return $modules;
}  
//
// adding the facebook and twitter links to the user profile
function bt_add_user_fields( $contactmethods ) {
    // Add Facebook
    $contactmethods['user_fb'] = 'Facebook';
    // Add Twitter
    $contactmethods['user_tw'] = 'Twitter';
    return $contactmethods;
}
add_filter('user_contactmethods','bt_add_user_fields',10,1);


/**
 * Customize recent posts widget to add post thumbnails.
 */
class CustomRecentPosts_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_recent_entries', 'description' => __( "The most recent posts on your site") );
		parent::__construct('recent-posts', __('Recent Posts'), $widget_ops);
		$this->alt_option_name = 'widget_recent_entries';

		add_action( 'save_post', array($this, 'flush_widget_cache') );
		add_action( 'deleted_post', array($this, 'flush_widget_cache') );
		add_action( 'switch_theme', array($this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('widget_recent_posts', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();
		extract($args);

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts' );
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 10;
		if ( ! $number )
 			$number = 10;
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

		$r = new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) );
		if ($r->have_posts()) :
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . '<a style="color:inherit" href="/news">' . $title . '</a>' . $after_title; ?>
                <style>
                    
                </style>
		<ul>
		<?php while ( $r->have_posts() ) : $r->the_post(); ?>
			<li class="news-item">
				<a href="<?php the_permalink(); ?>">
                                    <?php 
                                    if ( has_post_thumbnail($r->ID)) { 
                                        echo get_the_post_thumbnail($r->ID, array(64,64));
                                    } else {
                                        echo '<img src="/wp-content/themes/beautifultrouble/img/blog-thumb-64.jpg">';
                                    }
                                    get_the_title() ? the_title() : the_ID();
                                    if ( $show_date ) { ?>
                                            <br><span class="post-date"><?php echo get_the_date(); ?></span>
                                    <?php } ?>
                                </a>
			</li>
		<?php endwhile; ?>
		</ul>
		<?php echo $after_widget; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_recent_posts', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_entries']) )
			delete_option('widget_recent_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_recent_posts', 'widget');
	}

	function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label></p>
<?php
	}
}
add_action( 'widgets_init', create_function( '', 'register_widget( "CustomRecentPosts_Widget" );' ) );

/**
 * Adds SpotlightedModule_Widget widget.
 */
class SpotlightedModule_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'spotlightedmodule_widget', 'Spotlighted Module',
            array( 'description' => 'Displays one spotlighted module' )
        );
    }
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        $fields = get_fields(); 
        if( $fields['spotlight_module'] ) {
            $module  = array_shift( $fields['spotlight_module'] );
        ?>
        <div class="clearfix">
            <h2>Spotlighted Module</h2>
            <?php echo get_the_post_thumbnail($module->ID, 'thumbnail', array( 'class' => "spotlight-module-img", 'alt' => $module->post_title, 'title' => $module->post_title ) ); ?>
            <a href="<?php echo get_permalink( $module->ID ); ?>"><h3><?php echo $module->post_title; ?></h3></a>
            <p><?php 
            if ( $module->post_excerpt ) { 
                echo $module->post_excerpt;
            } else {
                echo truncate( $module->post_content, 300, '...' );
                echo ' <a href="', get_permalink( $module->ID ), '">Read more</a>'; 
            } 
            ?></p>   
        </div>
        <?php 
        }    
        echo $args['after_widget'];
    }
    public function form( $instance ) { }
    public function update( $new_instance, $old_instance ) { return $new_instance; }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "SpotlightedModule_Widget" );' ) );


/**
 * Adds SpotlightedContributor_Widget widget.
 */
class SpotlightedContributor_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'spotlightedcontributor_widget', 'Spotlighted Contributor',
            array( 'description' => 'Displays spotlighted contributor' )
        );
    }
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        ?>
        <div class="clearfix">
            <h2>Spotlighted Contributor</h2> 
            <?php if ( validate_gravatar( get_the_author_meta('user_email') ) ) { 
                echo get_avatar( get_the_author_meta('user_email') );  
            } ?>
            <h3><?php the_author_posts_link(); ?></h3>
            <?php the_author_meta( 'description' );?>
        </div>
        <?php echo $args['after_widget'];
    }
    public function form( $instance ) { }
    public function update( $new_instance, $old_instance ) { return $new_instance; }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "SpotlightedContributor_Widget" );' ) );


/**
 * Adds CustomPostCount_Widget widget.
 */
class CustomPostCount_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
	 		'custompostcount_widget', // Base ID
			'Custom Post Count Widget', // Name
			array( 'description' => __( 'A Custom Posts Count Widget', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		if ( ! empty( $title ) )
                    echo $before_title . $title . $after_title;
                    echo '<div class="span4" style="margin-left:10px;"><a href="/new"><img src="/wp-content/themes/beautifultrouble/img/new.png">&nbsp;New Modules</a></div>';
                $post_types = array( 'bt_tactic', 'bt_principle', 'bt_theory', 'bt_case', 'bt_practitioner' );
                foreach ( $post_types as $post_type ) { 
                    $obj = get_post_type_object( $post_type );
                    $post_type_name = $obj->labels->name;
                    $post_type_url  = $obj->rewrite['slug'];
                    $post_icon = '/wp-content/themes/beautifultrouble/img/icon_small_' . $obj->rewrite['slug'] . '.png';
                    $num = wp_count_posts( $post_type );
                    echo "<div class=\"span4 $post_type\" style=\"margin:5px 0 0 30px;\"><a href=\"/$post_type_url\"><img src=\"$post_icon\" />&nbsp;$num->publish $post_type_name</a></div>";
                }
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}

} // class custompostcount_Widget
// register custompostcount_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "custompostcount_widget" );' ) );

