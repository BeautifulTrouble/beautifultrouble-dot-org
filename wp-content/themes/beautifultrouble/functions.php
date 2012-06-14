<?php
/* Beautiful Trouble customizations */


function beautifultrouble_css_loader() {
wp_enqueue_style('bootstrap', get_template_directory_uri().'/css/bootstrap.css', false ,'1.0', 'all' );
wp_enqueue_style('docs', get_template_directory_uri().'/css/docs.css', false ,'1.0', 'all' );
wp_enqueue_style('prettify', get_template_directory_uri().'/css/prettify.css', false ,'1.0', 'all' );
wp_enqueue_style('responsive', get_template_directory_uri().'/css/bootstrap-responsive.css', false, '1.0', 'all' );
wp_enqueue_style('style', get_template_directory_uri().'/style.css', false ,'1.1', 'all' );
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
				'name' => __( 'Pricicples' ),
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
                        'description' => 'Capsule stories of successful creative actions, useful for illustrating how tactics, principles and theories can be successfully applied.',
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
                        'description' => 'Events that you want to know about.',
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

    $post_types = array( 'bt_case', 'bt_tactic', 'bt_principle', 'bt_theory' );

    return $post_types;
}
add_image_size( 'bt-featured', 770, 0, false );
add_image_size( 'bt-thumb-600', 600, 300, false );
add_image_size( 'bt-thumb-300', 300, 100, true );
