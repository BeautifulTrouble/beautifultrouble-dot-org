<?php
/*
Plugin Name: Social Sharing Toolkit
Plugin URI: http://www.active-bits.nl/support/social-sharing-toolkit/
Description: This plugin enables sharing of your content via popular social networks and can also convert Twitter names and hashtags to links. Easy & configurable.
Version: 2.0.8
Author: Marijn Rongen
Author URI: http://www.active-bits.nl
*/

class MR_Social_Sharing_Toolkit {
	var $count;
	var $options;
	var $types;
	var $share_buttons;
	var $follow_buttons;
	
	function MR_Social_Sharing_Toolkit() {
		$this->count = 0;
		load_plugin_textdomain( 'mr_social_sharing_toolkit', false, dirname(plugin_basename(__FILE__)).'/languages/');
		/* Declare button types */
		$this->types['none'] = __('Button','mr_social_sharing_toolkit');
		$this->types['none_text'] = __('Button + text','mr_social_sharing_toolkit');
		$this->types['horizontal'] = __('Button + side counter','mr_social_sharing_toolkit');
		$this->types['vertical'] = __('Button + top counter','mr_social_sharing_toolkit');
		$this->types['icon_small'] = __('Small icon','mr_social_sharing_toolkit');
		$this->types['icon_small_text'] = __('Small icon + text','mr_social_sharing_toolkit');
		$this->types['icon_medium'] = __('Medium icon','mr_social_sharing_toolkit');
		$this->types['icon_medium_text'] = __('Medium icon + text','mr_social_sharing_toolkit');
		$this->types['icon_large'] = __('Large icon','mr_social_sharing_toolkit');
		/* Declare bookmark buttons with options */
		$this->share_buttons['fb_like'] = array('icon' => 'facebook', 'title' => 'Facebook Like', 'types' => array('none', 'none_text', 'horizontal', 'vertical'));
		$this->share_buttons['fb_send'] = array('icon' => 'facebook', 'title' => 'Facebook Send', 'types' => array('none'));
		$this->share_buttons['fb_share'] = array('icon' => 'facebook', 'title' => 'Facebook Share', 'types' => array('none', 'horizontal', 'vertical', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->share_buttons['tw_tweet'] = array('icon' => 'twitter', 'title' => 'Twitter', 'id' => '@', 'types' => array('none', 'horizontal', 'vertical', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->share_buttons['bf_buffer'] = array('icon' => 'buffer', 'title' => 'Buffer', 'id' => '@', 'types' => array('none', 'horizontal', 'vertical', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->share_buttons['gl_plus'] = array('icon' => 'googleplus', 'title' => 'Google+', 'types' => array('none', 'horizontal', 'vertical'));
		$this->share_buttons['pn_pinterest'] = array('icon' => 'pinterest', 'title' => 'Pinterest Pin It', 'types' => array('none', 'horizontal', 'vertical', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->share_buttons['li_share'] = array('icon' => 'linkedin', 'title' => 'LinkedIn', 'types' => array('none', 'horizontal', 'vertical', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->share_buttons['xi_xing'] = array('icon' => 'xing', 'title' => 'Xing', 'types' => array('none', 'horizontal', 'vertical', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->share_buttons['tu_tumblr'] = array('icon' => 'tumblr', 'title' => 'Tumblr', 'types' => array('none', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->share_buttons['su_stumble'] = array('icon' => 'stumbleupon', 'title' => 'StumbleUpon', 'types' => array('none', 'horizontal', 'vertical', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->share_buttons['dl_delicious'] = array('icon' => 'delicious', 'title' => 'Delicious', 'types' => array('none', 'horizontal', 'vertical', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->share_buttons['dg_digg'] = array('icon' => 'digg', 'title' => 'Digg', 'types' => array('none', 'horizontal', 'vertical', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->share_buttons['rd_reddit'] = array('icon' => 'reddit', 'title' => 'Reddit', 'types' => array('none', 'horizontal', 'vertical', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->share_buttons['ms_myspace'] = array('icon' => 'myspace', 'title' => 'Myspace', 'types' => array('none', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->share_buttons['hv_respect'] = array('icon' => 'hyves', 'title' => 'Hyves Respect', 'types' => array('horizontal'));
		$this->share_buttons['ml_send'] = array('icon' => 'email', 'title' => __('Send email','mr_social_sharing_toolkit'), 'types' => array('none', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->share_buttons['ln_break_1'] = array('icon' => 'divider', 'title' => __('Divider','mr_social_sharing_toolkit').' 1', 'types' => array(''));
		$this->share_buttons['ln_break_2'] = array('icon' => 'divider', 'title' => __('Divider','mr_social_sharing_toolkit').' 2', 'types' => array(''));
		$this->share_buttons['ln_break_3'] = array('icon' => 'divider', 'title' => __('Divider','mr_social_sharing_toolkit').' 3', 'types' => array(''));
		/* Declare follow buttons with options */
		$this->follow_buttons['follow_facebook'] = array('icon' => 'facebook', 'title' => 'Facebook', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons['follow_twitter'] = array('icon' => 'twitter', 'title' => 'Twitter', 'id' => '@', 'types' => array('none', 'horizontal', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons['follow_plus'] = array('icon' => 'googleplus', 'title' => 'Google+', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons['follow_pinterest'] = array('icon' => 'pinterest', 'title' => 'Pinterest', 'id' => 'id:', 'types' => array('none', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons['follow_linked'] = array('icon' => 'linkedin', 'title' => 'LinkedIn Person', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons['follow_linked_co'] = array('icon' => 'linkedin', 'title' => 'LinkedIn Company', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons['follow_linked_group'] = array('icon' => 'linkedin', 'title' => 'LinkedIn Group', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons['follow_xing'] = array('icon' => 'xing', 'title' => 'Xing', 'id' => 'id:', 'types' => array('none', 'icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons['follow_tumblr'] = array('icon' => 'tumblr', 'title' => 'Tumblr', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons['follow_myspace'] = array('icon' => 'myspace', 'title' => 'Myspace', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons['follow_hyves'] = array('icon' => 'hyves', 'title' => 'Hyves', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons['follow_youtube'] = array('icon' => 'youtube', 'title' => 'YouTube', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons['follow_flickr'] = array('icon' => 'flickr', 'title' => 'Flickr', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons['follow_500px'] = array('icon' => '500px', 'title' => '500px', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons['follow_picasa'] = array('icon' => 'picasa', 'title' => 'Picasa', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons['follow_deviant'] = array('icon' => 'deviantart', 'title' => 'deviantArt', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons['follow_lastfm'] = array('icon' => 'lastfm', 'title' => 'Last.fm', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons['follow_spotify'] = array('icon' => 'spotify', 'title' => 'Spotify', 'id' => 'id:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons['follow_rss'] = array('icon' => 'rss', 'title' => __('RSS Feed','mr_social_sharing_toolkit'), 'id' => 'url:', 'types' => array('icon_small', 'icon_small_text', 'icon_medium', 'icon_medium_text', 'icon_large'));
		$this->follow_buttons['ln_break_1'] = array('icon' => 'divider', 'title' => __('Divider','mr_social_sharing_toolkit').' 1', 'types' => array(''));
		$this->follow_buttons['ln_break_2'] = array('icon' => 'divider', 'title' => __('Divider','mr_social_sharing_toolkit').' 2', 'types' => array(''));
		$this->follow_buttons['ln_break_3'] = array('icon' => 'divider', 'title' => __('Divider','mr_social_sharing_toolkit').' 3', 'types' => array(''));
		/* Set defaults and load user options */
		$this->get_options();
	}

	function get_options() {
		foreach ($this->share_buttons as $key => $val) {
			$buttons[$key] = array('enable' => 0, 'type' => $val['types'][0]);
			$shortcodes[$key] = array('enable' => 0, 'type' => $val['types'][0]);
			$widgets[$key] = array('enable' => 0, 'type' => $val['types'][0]);
			$button_order[] = $key;
			$shortcode_order[] = $key;
			$widget_order[] = $key;
		}
		foreach ($this->follow_buttons as $key => $val) {
			$followers[$key] = array('enable' => 0, 'type' => $val['types'][0], 'id' => '');
			$follow_order[] = $key;
		}
		$pinterest_options = array('default_image' => '', 'fixed_image' => 0);
		$bitly_options = array('enable' => 0, 'username' => '', 'key' => '', 'cache' => array());
		$opengraph_options = array('enable' => 0, 'default_image' => '', 'fixed_image' => 0);
		$this->options = array('mr_social_sharing_buttons' => $buttons, 'mr_social_sharing_shortcode_buttons' => $shortcodes, 'mr_social_sharing_widget_buttons' => $widgets, 'mr_social_sharing_follow_buttons' => $followers, 'mr_social_sharing_display' => 'span', 'mr_social_sharing_shortcode_display' => 'span', 'mr_social_sharing_widget_display' => 'span', 'mr_social_sharing_follow_display' => 'span', 'mr_social_sharing_align' => '', 'mr_social_sharing_shortcode_align' => '', 'mr_social_sharing_widget_align' => '', 'mr_social_sharing_follow_align' => '', 'mr_social_sharing_position' => 'none', 'mr_social_sharing_types' => array('post', 'page'), 'mr_social_sharing_enable_shortcode' => 1, 'mr_social_sharing_include_excerpts' => 1, 'mr_social_sharing_button_order' => $button_order, 'mr_social_sharing_shortcode_button_order' => $shortcode_order, 'mr_social_sharing_widget_button_order' => $widget_order, 'mr_social_sharing_follow_button_order' => $follow_order, 'mr_social_sharing_linkify_content' => 0, 'mr_social_sharing_linkify_comments' => 0, 'mr_social_sharing_linkify_new' => 1, 'mr_social_sharing_twitter_handles' => 0, 'mr_social_sharing_twitter_hashtags' => 0, 'mr_social_sharing_js_footer' => 1, 'mr_social_sharing_no_follow' => 0, 'mr_social_sharing_pinterest' => $pinterest_options, 'mr_social_sharing_bitly' => $bitly_options, 'mr_social_sharing_opengraph' => $opengraph_options);
		foreach ($this->options as $key => $val) {
			$this->options[$key] = get_option( $key, $val );
		}
		if (!is_array($this->options['mr_social_sharing_types'])) {
			$types = array();
			switch ($this->options['mr_social_sharing_types']) {
				case 'both':				
					$types[] = 'page';		
					$types[] = 'post';
					break;
				case 'pages':
					$types[] = 'page';
					break;
				case 'posts':
					$types[] = 'post';
					break;	
			}
			$this->options['mr_social_sharing_types'] = $types;
		}
		if ($this->options['mr_social_sharing_position'] == 'shortcode') {
			$this->options['mr_social_sharing_position'] = 'none';
			$this->options['mr_social_sharing_enable_shortcode'] = 1;	
		}
		foreach ($this->share_buttons as $key => $val) {
			if (!array_key_exists($key, $this->options['mr_social_sharing_buttons'])) {
				$this->options['mr_social_sharing_buttons'][$key] = array('enable' => 0, 'type' => $val['types'][0]);
			}
			if (!array_key_exists($key, $this->options['mr_social_sharing_shortcode_buttons'])) {
				$this->options['mr_social_sharing_shortcode_buttons'][$key] = array('enable' => 0, 'type' => $val['types'][0]);
			}
			if (!array_key_exists($key, $this->options['mr_social_sharing_widget_buttons'])) {
				$this->options['mr_social_sharing_widget_buttons'][$key] = array('enable' => 0, 'type' => $val['types'][0]);
			}
			if (!in_array($key, $this->options['mr_social_sharing_button_order'])) {
				$this->options['mr_social_sharing_button_order'][] = $key;
			}
			if (!in_array($key, $this->options['mr_social_sharing_shortcode_button_order'])) {
				$this->options['mr_social_sharing_shortcode_button_order'][] = $key;
			}
			if (!in_array($key, $this->options['mr_social_sharing_widget_button_order'])) {
				$this->options['mr_social_sharing_widget_button_order'][] = $key;
			}
		}
		foreach ($this->follow_buttons as $key => $val) {
			if (!array_key_exists($key, $this->options['mr_social_sharing_follow_buttons'])) {
				$this->options['mr_social_sharing_follow_buttons'][$key] = array('enable' => 0, 'type' => $val['types'][0], 'id' => '');
			}
			if (!in_array($key, $this->options['mr_social_sharing_follow_button_order'])) {
				$this->options['mr_social_sharing_follow_button_order'][] = $key;
			}
		}
		return $this->options;	
	}
	
	/* Admin functions */
	
	function save_options($new_options) {
		foreach ($this->options as $key => $val) {
			if (array_key_exists($key, $new_options)) {
				update_option( $key, $new_options[$key] );
				$this->options[$key] = $new_options[$key] ;
			} else {
				update_option( $key, 0 );
				$this->options[$key] = 0;	
			}
		}
	}
	
	function plugin_menu() {
		add_options_page('Social Sharing', 'Social Sharing Toolkit', 'manage_options', 'mr_social_sharing', array($this, 'plugin_admin_page'));
		add_filter('plugin_row_meta', array('MR_Social_Sharing_Toolkit', 'plugin_links'),10,2);
		wp_enqueue_style('mr_social_sharing-admin', plugins_url('/admin.css', __FILE__));
		wp_enqueue_script('mr_social_sharing-admin', plugins_url('/admin.js', __FILE__));
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('jquery-ui-sortable');
	}
	
	function plugin_links($links, $file) {
	    if ($file == plugin_basename(__FILE__)) {
	        $links[] = '<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=P8ZVNC57E58FE&lc=NL&item_name=WordPress%20plugins%20by%20Marijn%20Rongen&item_number=Social%20Sharing%20Toolkit&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted">'.__('Donate','mr_social_sharing_toolkit').'</a>';
	    }
	    return $links;
	}  
	
	function plugin_admin_page() {
		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
    	if( isset($_POST['mr_social_sharing_save_options']) && $_POST['mr_social_sharing_save_options'] == 'Y' ) {
       		$this->save_options($_POST);
      		echo '
       		<div class="updated"><p><strong>'.__('Settings saved.','mr_social_sharing_toolkit').'</strong></p></div>';	
    	}
		echo '
			<div class="wrap">
				<form method="post" action="">
					<input type="hidden" name="mr_social_sharing_save_options" value="Y"/>
					<h2>Social Sharing Toolkit</h2>
					
					<div id="mr_social_sharing_tabs">
						<ul class="tabs">	
							<li><a href="#tab_0">'.__('General settings','mr_social_sharing_toolkit').'</a><li>
							<li><a href="#tab_1">'.__('Content','mr_social_sharing_toolkit').'</a><li>
							<li><a href="#tab_2">'.__('Shortcode','mr_social_sharing_toolkit').'</a><li>
							<li><a href="#tab_3">'.__('Share Widget','mr_social_sharing_toolkit').'</a><li>
							<li><a href="#tab_4">'.__('Follow Widget','mr_social_sharing_toolkit').'</a><li>
							<li><a href="#tab_5">'.__('Advanced settings','mr_social_sharing_toolkit').'</a><li>							
						</ul>
						<div id="tab_0" class="mr_social_sharing_networks">
							<h3>'.__('General settings','mr_social_sharing_toolkit').':</h3>
							<label for="mr_social_sharing_js_footer" class="check"><input type="checkbox" name="mr_social_sharing_js_footer" id="mr_social_sharing_js_footer"';
		if ($this->options['mr_social_sharing_js_footer'] == 1) { echo ' checked="checked"';}
		echo ' value="1" /> '.__('Load JavaScript in footer','mr_social_sharing_toolkit').'</label>
							
							<p>
								<span class="description"> '.__('Improves performance but may not work on some themes','mr_social_sharing_toolkit').'</span>
							</p>
							<p>
								'.__('Choose where the buttons must be displayed and if the buttons should be displayed on posts, pages or both.','mr_social_sharing_toolkit').'
							</p>
							<label for="mr_social_sharing_position">'.__('Button location','mr_social_sharing_toolkit').'</label>
							<select name="mr_social_sharing_position" id="mr_social_sharing_position">
								<option value="none"';
		if ($this->options['mr_social_sharing_position'] == 'none') { echo ' selected="selected"';}
		echo '>'.__('Do not display','mr_social_sharing_toolkit').'</option>
								<option value="top"';
		if ($this->options['mr_social_sharing_position'] == 'top') { echo ' selected="selected"';}
		echo '>'.__('Display before content','mr_social_sharing_toolkit').'</option>
								<option value="bottom"';
		if ($this->options['mr_social_sharing_position'] == 'bottom') { echo ' selected="selected"';}
		echo '>'.__('Display after content','mr_social_sharing_toolkit').'</option>
								<option value="both"';
		if ($this->options['mr_social_sharing_position'] == 'both') { echo ' selected="selected"';}
		echo '>'.__('Display before and after content','mr_social_sharing_toolkit').'</option>
							</select><br/><br/>
							<label for="mr_social_sharing_types">'.__('Place buttons on','mr_social_sharing_toolkit').'</label><br/>';
							$types = get_post_types();
							if (is_array($types) && count($types) > 0) {
								foreach ($types as $type) {
									if (!in_array($type, array('attachment','revision','nav_menu_item'))) {
										echo '<label for="share_type_'.$type.'"><input type="checkbox" id="share_type_'.$type.'" name="mr_social_sharing_types[]" value="'.$type.'"';
										if (is_array($this->options['mr_social_sharing_types']) && in_array($type, $this->options['mr_social_sharing_types'])) {
											echo ' checked="checked"';	
										}	
										echo '/>'.ucfirst(__($type)).'</label><br/>';
									}
								}	
							}
							echo '
							<br/>
							<label for="mr_social_sharing_enable_shortcode" class="check"><input type="checkbox" name="mr_social_sharing_enable_shortcode" id="mr_social_sharing_enable_shortcode"';
		if ($this->options['mr_social_sharing_enable_shortcode'] == 1) {	echo ' checked="checked"';} 
		echo ' value="1" /> '.__('Enable shortcode').'</label><br/>
							<p><span class="description"> '.__('Use the shortcode [social_share/] where you want the buttons to appear', 'mr_social_sharing_toolkit').'</span></p>
							<label for="mr_social_sharing_include_excerpts" class="check"><input type="checkbox" name="mr_social_sharing_include_excerpts" id="mr_social_sharing_include_excerpts"';
		if ($this->options['mr_social_sharing_include_excerpts'] == 1) { echo ' checked="checked"';}
		echo ' value="1" /> '.__('Include buttons in excerpts','mr_social_sharing_toolkit').'</label>
							<p><span class="description">'.__('Uncheck this box if you are having issues displaying the buttons with excerpts (some themes have custom excerpt functions which do not play nice with the plugin).','mr_social_sharing_toolkit').'</p>
							<label for="mr_social_sharing_no_follow" class="check"><input type="checkbox" name="mr_social_sharing_no_follow" id="mr_social_sharing_no_follow"';
		if ($this->options['mr_social_sharing_no_follow'] == 1) { echo ' checked="checked"';}
		echo ' value="1" /> '.__('Use rel="nofollow" on links to social networks','mr_social_sharing_toolkit').'</label>
						</div>
					<div id="tab_1" class="mr_social_sharing_networks">
						<h3>'.__('Content','mr_social_sharing_toolkit').'</h3>
						<p>
							'.__('Check the boxes to display the button on your website. For each button you can select a separate style from the dropdown box. You can change the order of the buttons by dragging them to the desired location in the list. For the tweet button you can also fill in your Twitter username which will then be appended to the tweet (like via @WordPress).','mr_social_sharing_toolkit').'
						</p>';
		$this->showListAdmin($this->share_buttons);
		echo '				
					</div>
					<div id="tab_2" class="mr_social_sharing_networks">
						<h3>'.__('Shortcode','mr_social_sharing_toolkit').'</h3>
						<p>
							'.__('Check the boxes to display the button on your website. For each button you can select a separate style from the dropdown box. You can change the order of the buttons by dragging them to the desired location in the list. For the tweet button you can also fill in your Twitter username which will then be appended to the tweet (like via @WordPress).','mr_social_sharing_toolkit').'
						</p>';
		$this->showListAdmin($this->share_buttons, 'shortcode_');
		echo '				
					</div>
					<div id="tab_3" class="mr_social_sharing_networks">
						<h3>'.__('Share Widget','mr_social_sharing_toolkit').'</h3>
						<p>
							'.__('Check the boxes to display the button on Social Sharing Toolkit Share widget. For each button you can select a separate style from the dropdown box. You can change the order of the buttons by dragging them to the desired location in the list. For the tweet button you can also fill in your Twitter username which will then be appended to the tweet (like via @WordPress).','mr_social_sharing_toolkit').'
							<br/><br/>
							'.__('For each widget you can enter a fixed url and title for the buttons, to do this','mr_social_sharing_toolkit').' <a href="widgets.php">'.__('go to the widget configuration page','mr_social_sharing_toolkit').'</a>.
						</p>';		
		$this->showListAdmin($this->share_buttons, 'widget_');
		echo '
					</div>
					<div id="tab_4" class="mr_social_sharing_networks">
						<h3>'.__('Follow Widget','mr_social_sharing_toolkit').'</h3>
						<p>
							'.__('Check the boxes to display the button on Social Sharing Toolkit Follow widget. For each button you can select a separate style from the dropdown box. You can change the order of the buttons by dragging them to the desired location in the list.','mr_social_sharing_toolkit').'
							<br/><br/>
							'.__('For each button you only have to enter your id or username of the network as it appears in the url of your profile page. You will need to enter the complete url for the RSS Feed (including the http:// part) if you wish to display this button.','mr_social_sharing_toolkit').'
							<br/>
							<br/>
							'.__('To add the widget to your website','mr_social_sharing_toolkit').' <a href="widgets.php">'.__('go to the widget configuration page','mr_social_sharing_toolkit').'</a>.
						</p>';
		$this->showListAdmin($this->follow_buttons, 'follow_');
		echo '
					</div>
					<div id="tab_5" class="mr_social_sharing_networks">
						<h3>'.__('Advanced settings','mr_social_sharing_toolkit').'</h3>
						<h4>'.__('Automatic Twitter links','mr_social_sharing_toolkit').'</h4>
						<p>'.__('Select what you want to convert:','mr_social_sharing_toolkit').'</p>
						<label for="mr_social_sharing_twitter_handles" class="check"><input type="checkbox" name="mr_social_sharing_twitter_handles" id="mr_social_sharing_twitter_handles"';
		if ($this->options['mr_social_sharing_twitter_handles'] == 1) { echo ' checked="checked"';}
		echo ' value="1" /> '.__("Convert Twitter usernames", 'mr_social_sharing_toolkit').'</label><br/>
						<label for="mr_social_sharing_twitter_hashtags" class="check"><input type="checkbox" name="mr_social_sharing_twitter_hashtags" id="mr_social_sharing_twitter_hashtags"';
		if ($this->options['mr_social_sharing_twitter_hashtags'] == 1) { echo ' checked="checked"';}
		echo ' value="1" /> '.__("Convert hashtags", 'mr_social_sharing_toolkit').'</label>
						<p>'.__('And where it should be converted:','mr_social_sharing_toolkit').'</p>
						<label for="mr_social_sharing_linkify_content" class="check"><input type="checkbox" name="mr_social_sharing_linkify_content" id="mr_social_sharing_linkify_content"';
		if ($this->options['mr_social_sharing_linkify_content'] == 1) { echo ' checked="checked"';}
		echo ' value="1" /> '.__("Convert in posts and pages", 'mr_social_sharing_toolkit').'</label><br/>
						<label for="mr_social_sharing_linkify_comments" class="check"><input type="checkbox" name="mr_social_sharing_linkify_comments" id="mr_social_sharing_linkify_comments"';
		if ($this->options['mr_social_sharing_linkify_comments'] == 1) { echo ' checked="checked"';}
		echo ' value="1" /> '.__("Convert in comments", 'mr_social_sharing_toolkit').'</label><br/>
						<label for="mr_social_sharing_linkify_new" class="check"><input type="checkbox" name="mr_social_sharing_linkify_new" id="mr_social_sharing_linkify_new"';
		if ($this->options['mr_social_sharing_linkify_new'] == 1) { echo ' checked="checked"';}
		echo ' value="1" /> '.__("Open links in new window or tab", 'mr_social_sharing_toolkit').'</label>
						<h4>'.__('Bitly','mr_social_sharing_toolkit').'</h4>
						<p>'.__('Use Bitly url shortening for the tweet button','mr_social_sharing_toolkit').'</p>
						<label for="mr_social_sharing_bitly_enable" class="check"><input type="checkbox" name="mr_social_sharing_bitly[enable]" id="mr_social_sharing_bitly_enable"';
		if ($this->options['mr_social_sharing_bitly']['enable'] == 1) { echo ' checked="checked"';}
		echo ' value="1" /> '.__("Enable Bitly URL shortening", 'mr_social_sharing_toolkit').'</label></br/>
						<label for="mr_social_sharing_bitly_username">'.__('Your bitly Username','mr_social_sharing_toolkit').'</label>
						<input type="text" name="mr_social_sharing_bitly[username]" id="mr_social_sharing_bitly_username" value="'.$this->options['mr_social_sharing_bitly']['username'].'"/></br/>
						<label for="mr_social_sharing_bitly_key">'.__('Your bitly API Key','mr_social_sharing_toolkit').'</label>
						<input type="text" name="mr_social_sharing_bitly[key]" id="mr_social_sharing_bitly_key" value="'.$this->options['mr_social_sharing_bitly']['key'].'"/>
						<h4>'.__('Pinterest','mr_social_sharing_toolkit').'</h4>
						<label for="mr_social_sharing_pinterest_default_image">'.__('Default image URL','mr_social_sharing_toolkit').'</label>
						<input type="text" name="mr_social_sharing_pinterest[default_image]" id="mr_social_sharing_pinterest_default_image" value="'.$this->options['mr_social_sharing_pinterest']['default_image'].'"/></br/>
						<p><span class="description">'.__('You can specify a link to an image you would like to use for Pinterest pins when no image is available','mr_social_sharing_toolkit').'</span></p>
						<label for="mr_social_sharing_pinterest_fixed_image" class="check"><input type="checkbox" name="mr_social_sharing_pinterest[fixed_image]" id="mr_social_sharing_pinterest_fixed_image"';
		if ($this->options['mr_social_sharing_pinterest']['fixed_image'] == 1) { echo ' checked="checked"';}
		echo ' value="1" /> '.__("Always use the default image", 'mr_social_sharing_toolkit').'</label><br/>
						<p><span class="description">'.__("Check this box to always display the default image with your Pins", 'mr_social_sharing_toolkit').'</span></p>
						<h4>'.__('OpenGraph','mr_social_sharing_toolkit').'</h4>
						<p>'.__('Include Open Graph tags','mr_social_sharing_toolkit').'</p>
						<label for="mr_social_sharing_opengraph_enable" class="check"><input type="checkbox" name="mr_social_sharing_opengraph[enable]" id="mr_social_sharing_opengraph_enable"';
		if ($this->options['mr_social_sharing_opengraph']['enable'] == 1) { echo ' checked="checked"';}
		echo ' value="1" /> '.__("Enable Open Graph", 'mr_social_sharing_toolkit').'</label><br/>
						<label for="mr_social_sharing_opengraph_default_image">'.__('Default image URL','mr_social_sharing_toolkit').'</label>
						<input type="text" name="mr_social_sharing_opengraph[default_image]" id="mr_social_sharing_opengraph_default_image" value="'.$this->options['mr_social_sharing_opengraph']['default_image'].'"/></br/>
						<p><span class="description">'.__('You can specify a link to an image you would like to include in your likes and shares','mr_social_sharing_toolkit').'</span></p>
						<label for="mr_social_sharing_opengraph_fixed_image" class="check"><input type="checkbox" name="mr_social_sharing_opengraph[fixed_image]" id="mr_social_sharing_opengraph_fixed_image"';
		if ($this->options['mr_social_sharing_opengraph']['fixed_image'] == 1) { echo ' checked="checked"';}
		echo ' value="1" /> '.__("Always use the default image", 'mr_social_sharing_toolkit').'</label><br/>
						<p><span class="description">'.__("Check this box to always display the default image with your shared content", 'mr_social_sharing_toolkit').'</span></p>						
					</div>
				</div>
					<p class="submit">
						<input type="submit" name="Submit" class="button-primary" value="'.esc_attr__('Save Changes').'" />
					</p>
					<div class="mr_social_sharing_networks"> 
						<h3>'.__('Thank you for using the Social Sharing Toolkit!','mr_social_sharing_toolkit').'</h3>
						<p>
							'.__('For questions or requests about this plugin please use the','mr_social_sharing_toolkit').' <a href="http://www.active-bits.nl/support/social-sharing-toolkit/" target="_blank">'.__('official plugin page','mr_social_sharing_toolkit').'</a>. 
							'.__('If you like the plugin I would appreciate it if you provide a rating of the','mr_social_sharing_toolkit').' <a href="http://wordpress.org/extend/plugins/social-sharing-toolkit/" target="_blank">'.__('plugin on WordPress.org','mr_social_sharing_toolkit').'</a>. '.__('If you really like the plugin you can also','mr_social_sharing_toolkit').' <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=P8ZVNC57E58FE&lc=NL&item_name=WordPress%20plugins%20by%20Marijn%20Rongen&item_number=Social%20Sharing%20Toolkit&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted" target="_blank">'.__('donate here','mr_social_sharing_toolkit').'</a>.
						</p>
					</div>
					<div class="mr_social_sharing_networks banners">
						'.$this->getBanner().'
					</div>
				</form>
			</div>';
	}
	
	function showListAdmin($buttons, $button_type = '') {
		echo '
		<ul id="mr_social_sharing_'.$button_type.'networks">';
		foreach ($this->options['mr_social_sharing_'.$button_type.'button_order'] as $button) {
			echo '
							<li>
								<img src="'.plugins_url('/images/icons_small/'.$buttons[$button]['icon'].'.png', __FILE__).'" title="'.$buttons[$button]['title'].'" alt="'.$buttons[$button]['title'].'"/>
								<label for="mr_social_sharing_'.$button_type.$button.'"><input type="checkbox" name="mr_social_sharing_'.$button_type.'buttons['.$button.'][enable]" id="mr_social_sharing_'.$button_type.$button.'"';
			if ($this->options['mr_social_sharing_'.$button_type.'buttons'][$button]['enable'] == 1) { echo ' checked="checked"';}
			echo ' value="1" />'.$buttons[$button]['title'].'</label>
								<img class="right" src="'.plugins_url('/images/move.png', __FILE__).'" title="'.__('Change button order','mr_social_sharing_toolkit').'" alt="'.__('Change button order','mr_social_sharing_toolkit').'"/>';
			if (is_array($buttons[$button]['types']) && $buttons[$button]['types'][0] != '') {
				if (count($buttons[$button]['types']) == 1) {
					echo '	
								<input type="hidden" name="mr_social_sharing_'.$button_type.'buttons['.$button.'][type]" value="'.$buttons[$button]['types'][0].'"/>';
				} else {
					echo '
								<select name="mr_social_sharing_'.$button_type.'buttons['.$button.'][type]" id="mr_social_sharing_'.$button_type.$button.'_type">';
					foreach ($buttons[$button]['types'] as $type) {
						echo '<option value="'.$type.'"';
						if ($this->options['mr_social_sharing_'.$button_type.'buttons'][$button]['type'] == $type) { echo ' selected="selected"';}
						echo '>'.$this->types[$type].'</option>';
					}
					echo '</select>';
				}
			} else {
				echo '	
								<input type="hidden" name="mr_social_sharing_'.$button_type.'buttons['.$button.'][type]" value=""/>';
			}
			if (array_key_exists('id', $buttons[$button])) {
				if (is_array($buttons[$button]['id']) && array_key_exists('label', $buttons[$button]['id']) && array_key_exists('options', $buttons[$button]['id']) && is_array($buttons[$button]['id']['options'])) {
					echo '
								<select name="mr_social_sharing_'.$button_type.'buttons['.$button.'][id]" id="mr_social_sharing_'.$button_type.$button.'_id">';
					foreach ($buttons[$button]['id']['options'] as $option) {
						if (array_key_exists('label', $option) && array_key_exists('value', $option)) {
							echo '<option value="'.$option['value'].'"';
							if ($option['value'] == $this->options['mr_social_sharing_'.$button_type.'buttons'][$button]['id']) {	
								echo ' selected="selected"';
							}
							echo '>'.$option['label'].'</option>';
						}
					}
					echo '</select>';
					//			<label for="mr_social_sharing_'.$button_type.$button.'_id" class="text">'.$buttons[$button]['id']['label'].'</label>';	
				} else {
					echo '
								<input type="text" class="text" name="mr_social_sharing_'.$button_type.'buttons['.$button.'][id]" id="mr_social_sharing_'.$button_type.$button.'_id" value="'.$this->options['mr_social_sharing_'.$button_type.'buttons'][$button]['id'].'"/>
								<label for="mr_social_sharing_'.$button_type.$button.'_id" class="text">'.$buttons[$button]['id'].'</label>';
				}
			}
			echo '
								<input type="hidden" name="mr_social_sharing_'.$button_type.'button_order[]" value="'.$button.'"/>
							</li>';
		}					
		echo '
						</ul>
						<p>
							'.__('Choose button orientation horizontal to display the buttons side by side, vertical will place them below each other. You can also select an alignment to better suit your theme.','mr_social_sharing_toolkit').'
						</p>
						<label for="mr_social_sharing_'.$button_type.'display">'.__('Button orientation','mr_social_sharing_toolkit').'</label>
						<select name="mr_social_sharing_'.$button_type.'display" id="mr_social_sharing_'.$button_type.'display">
							<option value="span"';
		if ($this->options['mr_social_sharing_'.$button_type.'display'] == 'span') { echo ' selected="selected"';}
		echo '>'.__('Horizontal','mr_social_sharing_toolkit').'</option>
							<option value="div"';
		if ($this->options['mr_social_sharing_'.$button_type.'display'] == 'div') { echo ' selected="selected"';}
		echo '>'.__('Vertical','mr_social_sharing_toolkit').'</option>
						</select><br/>
						<label for="mr_social_sharing_'.$button_type.'align">'.__('Button alignment','mr_social_sharing_toolkit').'</label>
						<select name="mr_social_sharing_'.$button_type.'align" id="mr_social_sharing_'.$button_type.'align">
							<option value=""';
		if ($this->options['mr_social_sharing_'.$button_type.'align'] == '') { echo ' selected="selected"';}
		echo '>'.__('Align to bottom','mr_social_sharing_toolkit').'</option>
							<option value="_top"';
		if ($this->options['mr_social_sharing_'.$button_type.'align'] == '_top') { echo ' selected="selected"';}
		echo '>'.__('Align to top','mr_social_sharing_toolkit').'</option>
						</select>';	
	}
	
	/* Affiliates */
	
	function getBanner() {
		$banners = array();
		// ElegantThemes:
		$banners[] = '<a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=14757_0_1_7" target="_blank"><img border="0" src="http://www.elegantthemes.com/affiliates/banners/468x60.gif" width="468" height="60"></a>';
		$banners[] = '<a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=14757_0_1_7" target="_blank"><img border="0" src="http://www.elegantthemes.com/affiliates/banners/468x60.gif" width="468" height="60"></a>';
		$banners[] = '<a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=14757_0_1_7" target="_blank"><img border="0" src="http://www.elegantthemes.com/affiliates/banners/468x60.gif" width="468" height="60"></a>';
		$banners[] = '<a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=14757_0_1_7" target="_blank"><img border="0" src="http://www.elegantthemes.com/affiliates/banners/468x60.gif" width="468" height="60"></a>';
		// WooThemes:
		$banners[] = '<a href="http://www.woothemes.com/woomember/go?r=188860&i=l104" target="_blank"><img src="http://www.woothemes.com/ads/wc_468x60_grey.png" /></a>';
		$banners[] = '<a href="http://www.woothemes.com/woomember/go?r=188860&i=l102" target="_blank"><img src="http://www.woothemes.com/ads/wc_468x60_3_grey.png" /></a>';
		$banners[] = '<a href="http://www.woothemes.com/woomember/go?r=188860&i=l44" target="_blank"><img src="http://woothemes.com/ads/468x60c.jpg" /></a>';
		$banners[] = '<a href="http://www.woothemes.com/woomember/go?r=188860&i=l43" target="_blank"><img src="http://woothemes.com/ads/468x60b.jpg" /></a>';
		// Mojo Themes:
		$banners[] = '<a href="http://www.mojo-themes.com/categories/wordpress/?r=mrongen" target="_blank"><img src="'.plugins_url('/banners/mojo_1.jpg', __FILE__).'" /></a>';
		$banners[] = '<a href="http://www.mojo-themes.com/?r=mrongen" target="_blank"><img src="'.plugins_url('/banners/mojo_1.jpg', __FILE__).'" /></a>';
		$banners[] = '<a href="http://www.mojo-themes.com/categories/wordpress/?r=mrongen" target="_blank"><img src="'.plugins_url('/banners/mojo_2.jpg', __FILE__).'" /></a>';
		$banners[] = '<a href="http://www.mojo-themes.com/?r=mrongen" target="_blank"><img src="'.plugins_url('/banners/mojo_2.jpg', __FILE__).'" /></a>';
		// ThemeForest:
		$banners[] = '<a href="http://themeforest.net/category/wordpress?ref=MRongen" target="_blank"><img src="'.plugins_url('/banners/themeforest.gif', __FILE__).'" /></a>';
		$banners[] = '<a href="http://activeden.net/?ref=MRongen" target="_blank"><img src="'.plugins_url('/banners/activeden.gif', __FILE__).'" /></a>';
		$banners[] = '<a href="http://audiojungle.net/?ref=MRongen" target="_blank"><img src="'.plugins_url('/banners/audiojungle.gif', __FILE__).'" /></a>';
		$banners[] = '<a href="http://videohive.net/?ref=MRongen" target="_blank"><img src="'.plugins_url('/banners/videohive.gif', __FILE__).'" /></a>';
		$banners[] = '<a href="http://graphicriver.net/?ref=MRongen" target="_blank"><img src="'.plugins_url('/banners/graphicriver.gif', __FILE__).'" /></a>';
		$banners[] = '<a href="http://3docean.net/?ref=MRongen" target="_blank"><img src="'.plugins_url('/banners/3docean.gif', __FILE__).'" /></a>';
		$banners[] = '<a href="http://codecanyon.net/?ref=MRongen" target="_blank"><img src="'.plugins_url('/banners/codecanyon.gif', __FILE__).'" /></a>';
		$banners[] = '<a href="http://marketplace.tutsplus.com/?ref=MRongen" target="_blank"><img src="'.plugins_url('/banners/tutsplus.gif', __FILE__).'" /></a>';
		$banners[] = '<a href="http://photodune.net/?ref=MRongen" target="_blank"><img src="'.plugins_url('/banners/photodune.gif', __FILE__).'" /></a>';	
		shuffle($banners);
		return $banners[0];
	}
	
	/* Output functions */
	
	function print_opengraph() {
		echo '<!-- Open Graph tags provided by Social Sharing Toolkit v2.0.8 -->
		<meta property="og:locale" content="'.str_replace('-', '_', get_bloginfo('language')).'"/>';
		if (is_single() || is_page()) {
			$excerpt = get_the_excerpt();
			echo '
		<meta property="og:title" content="'.trim(wp_title('', 0)).'"/>';
			if ($excerpt != '') {
				echo '
		<meta property="og:description" content="'.$excerpt.'"/>';
			} else {
				echo '
		<meta property="og:description" content="'.get_bloginfo('description').'"/>';	
			}
			echo '
		<meta property="og:url" content="'.get_permalink().'"/>
		<meta property="og:type" content="article"/>';
		} else {
			echo '
		<meta property="og:title" content="'.get_bloginfo('name').'"/>
		<meta property="og:description" content="'.get_bloginfo('description').'"/>
		<meta property="og:url" content="'.get_home_url().'"/>
		<meta property="og:type" content="website"/>';
		}		
		echo '
		<meta property="og:site_name" content="'.get_bloginfo('name').'"/>';
		if ($this->options['mr_social_sharing_opengraph']['fixed_image']) {
			if ($this->options['mr_social_sharing_opengraph']['default_image'] != '') {
				echo '			
			<meta property="og:image" content="'.$this->options['mr_social_sharing_opengraph']['default_image'].'"/>';
			}
		} else {
			$media = '';
			if (current_theme_supports('post-thumbnails')) {
				if ($media = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), array(300,300))) {
	  				if (is_array($media)) {
	  					$media = $media[0];
	  				} else {
	  					$media = '';	
	  				}
				}
			}		
			if ($media == '') {
				$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_the_content(), $matches);
	  			$img = $matches[1][0];
				if($img != '') {
	    			$media = $img;
	  			}	
			}
			if ($media != '') {
				echo '			
			<meta property="og:image" content="'.$media.'"/>';
			} elseif ($this->options['mr_social_sharing_opengraph']['default_image'] != '') {
				echo '			
			<meta property="og:image" content="'.$this->options['mr_social_sharing_opengraph']['default_image'].'"/>';
			}
		}
	}
	
	
	function prepare_styles() {
		wp_enqueue_style('mr_social_sharing', plugins_url('/style.css', __FILE__));
	}
	
	function prepare_scripts() {
		$lang = __('en_US','mr_social_sharing_toolkit');
		if ($this->options['mr_social_sharing_no_follow'] == 1) {
			if ($this->options['mr_social_sharing_js_footer'] == 1) {
				wp_enqueue_script('mr_social_sharing', plugins_url('/script_no_follow.js', __FILE__), array('jquery'), false, true);
			} else {
				wp_enqueue_script('mr_social_sharing', plugins_url('/script_no_follow.js', __FILE__), array('jquery'));
			}
		} else {
			if ($this->options['mr_social_sharing_js_footer'] == 1) {
				wp_enqueue_script('mr_social_sharing', plugins_url('/script.js', __FILE__), array('jquery'), false, true);
			} else {
				wp_enqueue_script('mr_social_sharing', plugins_url('/script.js', __FILE__), array('jquery'));
			}
		}		
// ###			
		if (($this->options['mr_social_sharing_position'] != 'none' && $this->options['mr_social_sharing_buttons']['fb_send']['enable'] == 1) || $this->options['mr_social_sharing_widget_buttons']['fb_send']['enable'] == 1 || ($this->options['mr_social_sharing_enable_shortcode'] == 1 && $this->options['mr_social_sharing_shortcode_buttons']['fb_send']['enable'] == 1)) {
			if ($this->options['mr_social_sharing_js_footer'] == 1) {
				wp_enqueue_script('FacebookSend', 'http://connect.facebook.net/'.$lang.'/all.js#xfbml=1', array(), false, true);
			} else {
				wp_enqueue_script('FacebookSend', 'http://connect.facebook.net/'.$lang.'/all.js#xfbml=1');
			}
		}
		if (($this->options['mr_social_sharing_position'] != 'none' && $this->options['mr_social_sharing_buttons']['fb_share']['enable'] == 1) || $this->options['mr_social_sharing_widget_buttons']['fb_share']['enable'] == 1 || ($this->options['mr_social_sharing_enable_shortcode'] == 1 && $this->options['mr_social_sharing_shortcode_buttons']['fb_share']['enable'] == 1)) {
			if (in_array($this->options['mr_social_sharing_buttons']['fb_share']['type'], array('horizontal','vertical','none')) || in_array($this->options['mr_social_sharing_widget_buttons']['fb_share']['type'], array('horizontal','vertical','none'))) {
				if ($this->options['mr_social_sharing_js_footer'] == 1) {
					wp_enqueue_script('FacebookShare', 'http://static.ak.fbcdn.net/connect.php/js/FB.Share', array(), false, true);
				} else {
					wp_enqueue_script('FacebookShare', 'http://static.ak.fbcdn.net/connect.php/js/FB.Share');
				}
			}
		}		
// ###			
		$google_script = false;
		$digg_script = false;
		$linked_script = false;		
		$tweet_script = false;
		$buffer_script = false;		
		$stumble_script = false;
		$delicious_script = false;
		$pinit_script = false;
		$xing_script = false;
		
		if ($this->options['mr_social_sharing_position'] != 'none' && $this->options['mr_social_sharing_buttons']['gl_plus']['enable'] == 1) {
			$google_script = true;
		}
		if ($this->options['mr_social_sharing_widget_buttons']['gl_plus']['enable'] == 1) {
			$google_script = true;
		}
		if ($this->options['mr_social_sharing_enable_shortcode'] == 1 && $this->options['mr_social_sharing_shortcode_buttons']['gl_plus']['enable'] == 1) {
			$google_script = true;
		}		
		if ($this->options['mr_social_sharing_position'] != 'none' && $this->options['mr_social_sharing_buttons']['dg_digg']['enable'] == 1 && in_array($this->options['mr_social_sharing_buttons']['dg_digg']['type'], array('horizontal','vertical'))) {
			$digg_script = true;
		}		
		if ($this->options['mr_social_sharing_widget_buttons']['dg_digg']['enable'] == 1 && in_array($this->options['mr_social_sharing_widget_buttons']['dg_digg']['type'], array('horizontal','vertical'))) {
			$digg_script = true;
		}		
		if ($this->options['mr_social_sharing_enable_shortcode'] == 1 && $this->options['mr_social_sharing_shortcode_buttons']['dg_digg']['enable'] == 1 && in_array($this->options['mr_social_sharing_shortcode_buttons']['dg_digg']['type'], array('horizontal','vertical'))) {
			$digg_script = true;
		}
		if ($this->options['mr_social_sharing_position'] != 'none' && $this->options['mr_social_sharing_buttons']['li_share']['enable'] == 1 && in_array($this->options['mr_social_sharing_buttons']['li_share']['type'], array('horizontal','vertical','none'))) {
			$linked_script = true;
		}		
		if ($this->options['mr_social_sharing_widget_buttons']['li_share']['enable'] == 1 && in_array($this->options['mr_social_sharing_widget_buttons']['li_share']['type'], array('horizontal','vertical','none'))) {
			$linked_script = true;
		}
		if ($this->options['mr_social_sharing_enable_shortcode'] == 1 && $this->options['mr_social_sharing_shortcode_buttons']['li_share']['enable'] == 1 && in_array($this->options['mr_social_sharing_shortcode_buttons']['li_share']['type'], array('horizontal','vertical','none'))) {
			$linked_script = true;
		}		
		if ($this->options['mr_social_sharing_position'] != 'none' && $this->options['mr_social_sharing_buttons']['tw_tweet']['enable'] == 1 && in_array($this->options['mr_social_sharing_buttons']['tw_tweet']['type'], array('horizontal','vertical'))) {
			$tweet_script = true;
		}		
		if ($this->options['mr_social_sharing_widget_buttons']['tw_tweet']['enable'] == 1 && in_array($this->options['mr_social_sharing_widget_buttons']['tw_tweet']['type'], array('horizontal','vertical'))) {
			$tweet_script = true;
		}
		if ($this->options['mr_social_sharing_enable_shortcode'] == 1 && $this->options['mr_social_sharing_shortcode_buttons']['tw_tweet']['enable'] == 1 && in_array($this->options['mr_social_sharing_shortcode_buttons']['tw_tweet']['type'], array('horizontal','vertical'))) {
			$tweet_script = true;
		}				
		if ($this->options['mr_social_sharing_position'] != 'none' && $this->options['mr_social_sharing_buttons']['bf_buffer']['enable'] == 1 && in_array($this->options['mr_social_sharing_buttons']['bf_buffer']['type'], array('none','horizontal','vertical'))) {
			$buffer_script = true;
		}		
		if ($this->options['mr_social_sharing_widget_buttons']['bf_buffer']['enable'] == 1 && in_array($this->options['mr_social_sharing_widget_buttons']['bf_buffer']['type'], array('none','horizontal','vertical'))) {
			$buffer_script = true;
		}		
		if ($this->options['mr_social_sharing_enable_shortcode'] == 1 && $this->options['mr_social_sharing_shortcode_buttons']['bf_buffer']['enable'] == 1 && in_array($this->options['mr_social_sharing_shortcode_buttons']['bf_buffer']['type'], array('none','horizontal','vertical'))) {
			$buffer_script = true;
		}		
		if ($this->options['mr_social_sharing_position'] != 'none' && $this->options['mr_social_sharing_buttons']['su_stumble']['enable'] == 1 && in_array($this->options['mr_social_sharing_buttons']['su_stumble']['type'], array('horizontal', 'vertical'))) {
			$stumble_script = true;
		}
		if ($this->options['mr_social_sharing_widget_buttons']['su_stumble']['enable'] == 1 && in_array($this->options['mr_social_sharing_widget_buttons']['su_stumble']['type'], array('horizontal', 'vertical'))) {
			$stumble_script = true;
		}		
		if ($this->options['mr_social_sharing_enable_shortcode'] == 1 && $this->options['mr_social_sharing_shortcode_buttons']['su_stumble']['enable'] == 1 && in_array($this->options['mr_social_sharing_shortcode_buttons']['su_stumble']['type'], array('horizontal', 'vertical'))) {
			$stumble_script = true;
		}		
		if ($this->options['mr_social_sharing_position'] != 'none' && $this->options['mr_social_sharing_buttons']['dl_delicious']['enable'] == 1 && in_array($this->options['mr_social_sharing_buttons']['dl_delicious']['type'], array('horizontal', 'vertical'))) {
			$delicious_script = true;
		}		
		if ($this->options['mr_social_sharing_widget_buttons']['dl_delicious']['enable'] == 1 && in_array($this->options['mr_social_sharing_widget_buttons']['dl_delicious']['type'], array('horizontal', 'vertical'))) {
			$delicious_script = true;
		}		
		if ($this->options['mr_social_sharing_enable_shortcode'] == 1 && $this->options['mr_social_sharing_shortcode_buttons']['dl_delicious']['enable'] == 1 && in_array($this->options['mr_social_sharing_shortcode_buttons']['dl_delicious']['type'], array('horizontal', 'vertical'))) {
			$delicious_script = true;
		}		
		if ($this->options['mr_social_sharing_position'] != 'none' && $this->options['mr_social_sharing_buttons']['pn_pinterest']['enable'] == 1 && in_array($this->options['mr_social_sharing_buttons']['pn_pinterest']['type'], array('none', 'horizontal', 'vertical'))) {
			$pinit_script = true;
		}		
		if ($this->options['mr_social_sharing_widget_buttons']['pn_pinterest']['enable'] == 1 && in_array($this->options['mr_social_sharing_widget_buttons']['pn_pinterest']['type'], array('none', 'horizontal', 'vertical'))) {
		 	$pinit_script = true;
		}		
		if ($this->options['mr_social_sharing_enable_shortcode'] == 1 && $this->options['mr_social_sharing_shortcode_buttons']['pn_pinterest']['enable'] == 1 && in_array($this->options['mr_social_sharing_shortcode_buttons']['pn_pinterest']['type'], array('none', 'horizontal', 'vertical'))) {
			$pinit_script = true;
		}
		if ($this->options['mr_social_sharing_position'] != 'none' && $this->options['mr_social_sharing_buttons']['xi_xing']['enable'] == 1 && in_array($this->options['mr_social_sharing_buttons']['xi_xing']['type'], array('none','horizontal','vertical'))) {
			$xing_script = true;
		}		
		if ($this->options['mr_social_sharing_widget_buttons']['xi_xing']['enable'] == 1 && in_array($this->options['mr_social_sharing_widget_buttons']['xi_xing']['type'], array('none','horizontal','vertical'))) {
			$xing_script = true;
		}
		if ($this->options['mr_social_sharing_enable_shortcode'] == 1 && $this->options['mr_social_sharing_shortcode_buttons']['xi_xing']['enable'] == 1 && in_array($this->options['mr_social_sharing_shortcode_buttons']['xi_xing']['type'], array('none','horizontal','vertical'))) {
			$xing_script = true;
		}
		if ($this->options['mr_social_sharing_follow_buttons']['xi_xing']['enable'] == 1 && in_array($this->options['mr_social_sharing_follow_buttons']['xi_xing']['type'], array('none','horizontal'))) {
			$xing_script = true;
		}
		if (is_active_sidebar(is_active_widget( false, false, 'mr-social-sharing-toolkit-follow-widget', true))) {
			if ($this->options['mr_social_sharing_follow_buttons']['follow_twitter']['enable'] == 1 && in_array($this->options['mr_social_sharing_follow_buttons']['follow_twitter']['type'], array('none','horizontal'))) {
				$tweet_script = true;
			}
		}
			
		if ($digg_script) {
			if ($this->options['mr_social_sharing_js_footer'] == 1) {
				wp_enqueue_script('Digg', plugins_url('/digg.js', __FILE__), array(), false, true);
			} else {
				wp_enqueue_script('Digg', plugins_url('/digg.js', __FILE__));
			}	
		}
		if ($linked_script) {
			if ($this->options['mr_social_sharing_js_footer'] == 1) {
				wp_enqueue_script('LinkedIn', 'http://platform.linkedin.com/in.js', array(), false, true);
			} else {
				wp_enqueue_script('LinkedIn', 'http://platform.linkedin.com/in.js');
			}
		}
		if ($google_script) {
			if ($this->options['mr_social_sharing_js_footer'] == 1) {
				wp_enqueue_script('GooglePlusLang', plugins_url('/googleplus.js.php?lang='.$lang, __FILE__), array(), false, true);
				wp_enqueue_script('GooglePlus', 'http://apis.google.com/js/plusone.js', array(), false, true);
			} else {
				wp_enqueue_script('GooglePlusLang', plugins_url('/googleplus.js.php?lang='.$lang, __FILE__));
				wp_enqueue_script('GooglePlus', 'http://apis.google.com/js/plusone.js');
			}	
		}		
		if ($tweet_script) {
			if ($this->options['mr_social_sharing_js_footer'] == 1) {
				wp_enqueue_script('Twitter', 'http://platform.twitter.com/widgets.js', array(), false, true);
			} else {
				wp_enqueue_script('Twitter', 'http://platform.twitter.com/widgets.js');
			}
		}
		if ($buffer_script) {
			if ($this->options['mr_social_sharing_js_footer'] == 1) {
				wp_enqueue_script('Buffer', 'http://static.bufferapp.com/js/button.js', array(), false, true);
			} else {
				wp_enqueue_script('Buffer', 'http://static.bufferapp.com/js/button.js');
			}
		}		
		if ($stumble_script) {
			if ($this->options['mr_social_sharing_js_footer'] == 1) {
				wp_enqueue_script('StumbleUpon', plugins_url('/stumbleupon.js', __FILE__), array(), false, true);
			} else {
				wp_enqueue_script('StumbleUpon', plugins_url('/stumbleupon.js', __FILE__));
			}
		}
		if ($delicious_script) {
			if ($this->options['mr_social_sharing_js_footer'] == 1) {
				wp_enqueue_script('Delicious', plugins_url('/delicious.js', __FILE__), array(), false, true);
			} else {
				wp_enqueue_script('Delicious', plugins_url('/delicious.js', __FILE__));
			}
		}
		if ($pinit_script) {
 			if ($this->options['mr_social_sharing_js_footer'] == 1) {
 				wp_enqueue_script('Pinterest', 'http://assets.pinterest.com/js/pinit.js', array(), false, true);
			} else {
				wp_enqueue_script('Pinterest', 'http://assets.pinterest.com/js/pinit.js');
			}
		}
		if ($xing_script) {
			if ($this->options['mr_social_sharing_js_footer'] == 1) {
				wp_enqueue_script('Xing', 'https://www.xing-share.com/js/external/share.js', array(), false, true);
			} else {
				wp_enqueue_script('Xing', 'https://www.xing-share.com/js/external/share.js');
			}	
		}
	}
	
	function create_bookmarks($url = '', $title = '', $type = '', $media = '') {
		$url = trim($url);
		$title = trim($title);
		$title = html_entity_decode($title, ENT_QUOTES, 'UTF-8');
		if ($url == '') {
			$url = 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];	
		}
		$bookmarks = '
				<div class="mr_social_sharing_wrapper">
				<!-- Social Sharing Toolkit v2.0.8 | http://www.active-bits.nl/support/social-sharing-toolkit/ -->';
		foreach ($this->options['mr_social_sharing_'.$type.'button_order'] as $button) {
			if ($this->options['mr_social_sharing_'.$type.'buttons'][$button]['enable'] == 1) {
				$id = array_key_exists('id', $this->options['mr_social_sharing_'.$type.'buttons'][$button]) ? $this->options['mr_social_sharing_'.$type.'buttons'][$button]['id'] : '';
				$bookmarks .= $this->get_bookmark_button($button, $url, $title, $this->options['mr_social_sharing_'.$type.'buttons'][$button]['type'], $this->options['mr_social_sharing_'.$type.'display'], $this->options['mr_social_sharing_'.$type.'align'], $id, $media);
			}
		}		
		$bookmarks .= '</div>';
		$bookmarks = str_replace('<div class="mr_social_sharing_wrapper"></div>', '', $bookmarks);
		$this->count++;
		return $bookmarks;	
	}
	
	function create_followers() {
		$followers = '
				<div class="mr_social_sharing_wrapper">
				<!-- Social Sharing Toolkit v2.0.8 | http://www.active-bits.nl/support/social-sharing-toolkit/ -->';
		foreach ($this->options['mr_social_sharing_follow_button_order'] as $button) {
			if ($this->options['mr_social_sharing_follow_buttons'][$button]['enable'] == 1) {
				$id = array_key_exists('id', $this->options['mr_social_sharing_follow_buttons'][$button]) ? $this->options['mr_social_sharing_follow_buttons'][$button]['id'] : '';
				$followers .= $this->get_follow_button($button, $this->options['mr_social_sharing_follow_buttons'][$button]['type'], $this->options['mr_social_sharing_follow_display'], $this->options['mr_social_sharing_follow_align'], $id);
			}
		}		
		$followers .= '</div>';
		$followers = str_replace('<div class="mr_social_sharing_wrapper"></div>', '', $followers);
		return $followers;
	}
	
	function get_bookmark_button($button, $url, $title, $type, $display = 'span', $align = '', $id = '', $media = '') {
		if ($button == 'ln_break_1' || $button == 'ln_break_2' || $button == 'ln_break_3') {
			$retval = '</div><div class="mr_social_sharing_wrapper">';
		} else {
			if ($button == 'pn_pinterest') {
				if ($media == '') {
					return '';
				} else {
					$id = $media;	
				}
			}
			$button = 'get_'.$button;
			$retval = '<'.$display.' class="mr_social_sharing'.$align.'">'.$this->$button($url, $title, $type, $id).'</'.$display.'>';
		}
		return $retval;
	}
	
	function get_follow_button($button, $type, $display = 'span', $align = '', $id = '') {
		if ($button == 'ln_break_1' || $button == 'ln_break_2' || $button == 'ln_break_3') {
			$retval = '</div><div class="mr_social_sharing_wrapper">';
		} else {
			$button = 'get_'.$button;
			$retval = '<'.$display.' class="mr_social_sharing'.$align.'">'.$this->$button($type, $id).'</'.$display.'>';
		}
		return $retval;
	}
	
	function get_fb_like($url, $title, $type, $id) {
		$retval = '<iframe src="https://www.facebook.com/plugins/like.php?locale='.__('en_US','mr_social_sharing_toolkit').'&amp;href='.urlencode($url).'&amp;layout=';
		switch ($type) {
			case 'horizontal':
				$retval .= 'button_count';
				if ($id == 'recommend') {
					$width = __('fb_horizontal_recommend_width','mr_social_sharing_toolkit');
					$width = ($width == 'fb_horizontal_recommend_width') ? '120' : $width;
				} else {
					$width = __('fb_horizontal_width','mr_social_sharing_toolkit');
					$width = ($width == 'fb_horizontal_width') ? '90' : $width;
				}
				$height = '21';
				break;
			case 'vertical':
				$retval .= 'box_count';
				if ($id == 'recommend') {
					$width = __('fb_vertical_recommend_width','mr_social_sharing_toolkit');
					$width = ($width == 'fb_vertical_recommend_width') ? '92' : $width;
				} else {
					$width = __('fb_vertical_width','mr_social_sharing_toolkit');
					$width = ($width == 'fb_vertical_width') ? '55' : $width;
				}
				$height = '62';
				break;
			case 'none_text':
				$retval .= 'standard';
				if ($id == 'recommend') {
					$width = 'auto';
				} else {
					$width = 'auto';
				}
				$height = '25';
				break;
			default:
				$retval .= 'standard';
				if ($id == 'recommend') {
					$width = __('fb_standard_recommend_width','mr_social_sharing_toolkit');
					$width = ($width == 'fb_standard_standard_recommend_width') ? '91' : $width;
				} else {
					$width = __('fb_standard_width','mr_social_sharing_toolkit');
					$width = ($width == 'fb_standard_width') ? '51' : $width;
				}
				$height = '24';
				break;
		}
		$retval .= '&amp;show_faces=false&amp;width='.$width.'&amp;height='.$height.'" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:'.$width.'px; height:'.$height.'px;" allowTransparency="true"></iframe>';
		return $retval;
	}
				
	function get_fb_share($url, $title, $type, $id) {	
		switch ($type) {
			case 'vertical':
				$retval = '<a name="fb_share" type="box_count" share_url="'.$url.'" href="http://www.facebook.com/sharer.php">Share</a>';
				break;
			case 'horizontal':
				$retval = '<a name="fb_share" type="button_count" share_url="'.$url.'" href="http://www.facebook.com/sharer.php">Share</a>';
				break;
			case 'none':
				$retval = '<a name="fb_share" type="button" share_url="'.$url.'" href="http://www.facebook.com/sharer.php">Share</a>';
				break;
			default:
				$url = 'https://www.facebook.com/sharer/sharer.php?u='.urlencode($url).'&t='.urlencode($title);
				$title = __('Share on','mr_social_sharing_toolkit').' Facebook';
				$text = __('Share on','mr_social_sharing_toolkit').' Facebook';
				$icon = 'facebook';
				$retval = $this->get_icon($type, $url, $title, $text, $icon, true);
				break;
		}
		return $retval;
	}
	
	function get_fb_send($url, $title, $type, $id) {
		$retval = '<div id="fb-root"></div><fb:send href="'.$url.'" font=""></fb:send>';
		return $retval;			
	}
	
	function get_tw_tweet($url, $title, $type, $id) {
		$count_url = '';
		if ($this->options['mr_social_sharing_bitly']['enable'] == 1 && $this->options['mr_social_sharing_bitly']['username'] != '' && $this->options['mr_social_sharing_bitly']['key'] != '') {
			if (is_array($this->options['mr_social_sharing_bitly']['cache']) && array_key_exists($url, $this->options['mr_social_sharing_bitly']['cache'])) {
				$count_url = $url;
				$url = $this->options['mr_social_sharing_bitly']['cache'][$url];
			} else {
				$ch = curl_init('https://api-ssl.bitly.com/v3/shorten?login='.$this->options['mr_social_sharing_bitly']['username'].'&apiKey='.$this->options['mr_social_sharing_bitly']['key'].'&longUrl='.urlencode($url).'&format=txt');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				if ($short_url = curl_exec($ch)) {
					$count_url = $url;
					$url = trim($short_url);
					$this->options['mr_social_sharing_bitly']['cache'][$count_url] = $url;
					update_option( 'mr_social_sharing_bitly', $this->options['mr_social_sharing_bitly']);
				}
			}
		}
		switch ($type) {
			case 'horizontal':
				$retval = '<a href="https://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-url="'.$url.'"';
				if ($id != '') {
					$retval .= ' data-via="'.$id.'"';
				}
				if ($count_url != '') {
					$retval .= ' data-counturl="'.$count_url.'"';	
				}
				$retval .= ' data-text="'.$title.'">Tweet</a>';
				break;
			case 'vertical':
				$retval = '<a href="https://twitter.com/share" class="twitter-share-button" data-count="vertical" data-url="'.$url.'"';
				if ($id != '') {
					$retval .= ' data-via="'.$id.'"';
				}
				if ($count_url != '') {
					$retval .= ' data-counturl="'.$count_url.'"';	
				}
				$retval .= ' data-text="'.$title.'">Tweet</a>';
				break;
			default:
				$url = 'https://twitter.com/share?url='.urlencode($url).'&amp;text='.urlencode($title);
				if ($id != '') {
					$url .= '&amp;via='.$id;
				}
				if ($count_url != '') {
					$url .= '&amp;counturl='.urlencode($count_url);	
				}
				$title = __('Share on','mr_social_sharing_toolkit').' Twitter';
				$text = __('Share on','mr_social_sharing_toolkit').' Twitter';
				$icon = 'twitter';
				$retval = $this->get_icon($type, $url, $title, $text, $icon, true);
				break;
		}		
		return $retval;
	}
	
	function get_bf_buffer($url, $title, $type, $id) {		
		switch ($type) {
			case 'horizontal':
				$retval = '<a href="http://bufferapp.com/add" class="buffer-add-button" data-url="'.$url.'" data-count="horizontal"';
				if ($id != '') {
					$retval .= ' data-via="'.$id.'"';
				}
				$retval .= ' data-text="'.$title.'">Buffer</a>';
				break;
			case 'vertical':
				$retval = '<a href="http://bufferapp.com/add" class="buffer-add-button" data-url="'.$url.'" data-count="vertical"';
				if ($id != '') {
					$retval .= ' data-via="'.$id.'"';
				}
				$retval .= ' data-text="'.$title.'">Buffer</a>';
				break;
			case 'none':
				$retval = '<a href="http://bufferapp.com/add" class="buffer-add-button" data-url="'.$url.'" data-count="none"';
				if ($id != '') {
					$retval .= ' data-via="'.$id.'"';
				}
				$retval .= ' data-text="'.$title.'">Buffer</a>';
				break;
			default:
				$url = 'http://bufferapp.com/add?url='.urlencode($url).'&text='.urlencode($title);
				if ($id != '') {
					$url .= '&via='.urlencode($id);
				}
				$title = __('Add to','mr_social_sharing_toolkit').' Buffer';
				$text = __('Add to','mr_social_sharing_toolkit').' Buffer';
				$icon = 'buffer';
				$retval = $this->get_icon($type, $url, $title, $text, $icon, true);
				break;
		}		
		return $retval;
	}
	
	function get_pn_pinterest($url, $title, $type, $media) {		
		$pin_url = 'http://pinterest.com/pin/create/button/?url='.urlencode($url).'&media='.urlencode($media).'&description='.urlencode($title);
		switch ($type) {
			case 'horizontal':
				$retval = '<a href="'.$pin_url.'" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>';
				break;
			case 'vertical':
				$retval = '<a href="'.$pin_url.'" class="pin-it-button" count-layout="vertical"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>';
				break;
			case 'none':
				$retval = '<a href="'.$pin_url.'" class="pin-it-button" count-layout="none"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>';
				break;
			default:
				$title = __('Pin it on','mr_social_sharing_toolkit').' Pinterest';
				$text = __('Pin it on','mr_social_sharing_toolkit').' Pinterest';
				$icon = 'pinterest';
				$retval = $this->get_icon($type, $pin_url, $title, $text, $icon, true);
				break;
		}		
		return $retval;
	}
	
	function get_gl_plus($url, $title, $type, $id) {
		$retval = '<g:plusone';
		switch ($type) {
			case 'horizontal':
				$retval .= ' size="medium"';
				break;
			case 'vertical':
				$retval .= ' size="tall"';
				break;
			default:
				$retval .= ' size="medium" count="false"';
				break;
		}
		$retval .= ' href="'.$url.'"></g:plusone>';
		return $retval;
	}
	
	function get_li_share($url, $title, $type, $id) {
		switch ($type) {
			case 'horizontal':
				$retval = '<script type="IN/Share" data-url="'.$url.'" data-counter="right"></script>';
				break;
			case 'vertical':
				$retval = '<script type="IN/Share" data-url="'.$url.'" data-counter="top"></script>';
				break;
			case 'none':
				$retval = '<script type="IN/Share" data-url="'.$url.'"></script>';
				break;
			default:
				$url = 'http://www.linkedin.com/shareArticle?mini=true&amp;url='.urlencode($url).'&amp;title='.urlencode($title);
				$title = __('Share on','mr_social_sharing_toolkit').' LinkedIn';
				$text = __('Share on','mr_social_sharing_toolkit').' LinkedIn';
				$icon = 'linkedin';
				$retval = $this->get_icon($type, $url, $title, $text, $icon, true);
				break;
		}
		return $retval;
	}
	
	function get_xi_xing($url, $title, $type, $id) {
		switch ($type) {
			case 'horizontal':
				$retval = '<script type="XING/Share" data-counter="right" data-lang="en" data-url="'.$url.'"></script>';
				break;
			case 'vertical':
				$retval = '<script type="XING/Share" data-counter="top" data-lang="en" data-url="'.$url.'"></script>';
				break;
			case 'none':
				$retval = '<script type="XING/Share" data-counter="no_count" data-lang="en" data-url="'.$url.'" data-button-shape="rectangle"></script>';
				break;
			default:
				$url = 'https://www.xing.com/app/startpage?op=home;func_share=1;tab=link;url='.$url;
				$title = __('Share on','mr_social_sharing_toolkit').' Xing';
				$text = __('Share on','mr_social_sharing_toolkit').' Xing';
				$icon = 'xing';
				$retval = $this->get_icon($type, $url, $title, $text, $icon, true);
				break;
		}		
		return $retval;
	}
	
	function get_tu_tumblr($url, $title, $type, $id) {
		$url = 'http://www.tumblr.com/share/link?url='.urlencode($url).'&amp;name='.urlencode($title);
		$title = __('Share on','mr_social_sharing_toolkit').' Tumblr';
		$text = __('Share on','mr_social_sharing_toolkit').' Tumblr';
		$icon = 'tumblr';
		return $this->get_icon($type, $url, $title, $text, $icon, true);
	}
	
	function get_su_stumble($url, $title, $type, $id) {
		switch ($type) {
			case 'horizontal':
				$retval = '<span class="stumble_horizontal"><su:badge layout="1" location="'.$url.'"></su:badge></span>';
				break;
			case 'vertical':			
				$retval = '<span class="stumble_vertical"><su:badge layout="5" location="'.$url.'"></su:badge></span>';
				break;
			default:
				$url = 'http://www.stumbleupon.com/submit?url='.urlencode($url);
				$title = __('Submit to','mr_social_sharing_toolkit').' StumbleUpon';
				$text = __('Submit to','mr_social_sharing_toolkit').' StumbleUpon';
				$icon = 'stumbleupon';
				$retval = $this->get_icon($type, $url, $title, $text, $icon, true);
				break;
		}
		return $retval;
	}
	
	function get_dl_delicious($url, $title, $type, $id) {		
		switch ($type) {
			case 'horizontal':
				$hash = md5($url);
				$retval = '<div class="delicious_horizontal"><span class="delicious_hash">'.$hash.'</span><a class="mr_social_sharing_popup_link" href="http://del.icio.us/post?v=4&amp;noui&amp;jump=close&amp;url='.urlencode($url).'&amp;title='.urlencode($title).'" target="_blank"></a></div>'; 
				break;
			case 'vertical':
				$hash = md5($url);
				$retval = '<div class="delicious_vertical"><span class="delicious_hash">'.$hash.'</span><a class="mr_social_sharing_popup_link" href="http://del.icio.us/post?v=4&amp;noui&amp;jump=close&amp;url='.urlencode($url).'&amp;title='.urlencode($title).'" target="_blank"></a></div>'; 
				break;
			default:
				$url = 'http://del.icio.us/post?url='.urlencode($url).'&amp;title='.urlencode($title);
				$title = __('Save on','mr_social_sharing_toolkit').' Delicious';
				$text = __('Save on','mr_social_sharing_toolkit').' Delicious';
				$icon = 'delicious';
				$retval = $this->get_icon($type, $url, $title, $text, $icon, true);
				break;
		}
		return $retval;			
	}
	
	function get_dg_digg($url, $title, $type, $id) {
		switch ($type) {
			case 'horizontal':
				$retval = '<a class="DiggThisButton DiggCompact" href="http://digg.com/submit?url='.urlencode($url).'&amp;title='.urlencode($title).'"></a>';
				break;
			case 'vertical':
				$retval = '<a class="DiggThisButton DiggMedium" href="http://digg.com/submit?url='.urlencode($url).'&amp;title='.urlencode($title).'"></a>';
				break;
			default:
				$url = 'http://digg.com/submit?url='.urlencode($url).'&amp;title='.urlencode($title);
				$title = __('Digg This','mr_social_sharing_toolkit');
				$text = __('Digg This','mr_social_sharing_toolkit');
				$icon = 'digg';
				$retval = $this->get_icon($type, $url, $title, $text, $icon, true);
				break;
		}			
		return $retval;
	}
	
	function get_rd_reddit($url, $title, $type, $id) {
		switch ($type) {
			case 'horizontal':
				$retval = '<script type="text/javascript">
							  reddit_url = "'.$url.'";
							  reddit_title = "'.$title.'";
							</script>
							<script type="text/javascript" src="http://www.reddit.com/static/button/button1.js"></script>';
				break;
			case 'vertical':
				$retval = '<script type="text/javascript">
							  reddit_url = "'.$url.'";
							  reddit_title = "'.$title.'";
							</script><script type="text/javascript" src="http://www.reddit.com/static/button/button2.js"></script>';
				break;
			default:
				$url = 'http://www.reddit.com/submit?url='.urlencode($url);
				$title = __('Submit to','mr_social_sharing_toolkit').' reddit';
				$text = __('Submit to','mr_social_sharing_toolkit').' reddit';
				$icon = 'reddit';
				$retval = $this->get_icon($type, $url, $title, $text, $icon, true);
				break;
		}
		return $retval;
	}
	
	function get_ms_myspace($url, $title, $type, $id) {
		$url = 'http://www.myspace.com/Modules/PostTo/Pages/?t='.urlencode($title).'&amp;u='.urlencode($url);
		$title = __('Share on','mr_social_sharing_toolkit').' Myspace';
		$text = __('Share on','mr_social_sharing_toolkit').' Myspace';
		$icon = 'myspace';
		return $this->get_icon($type, $url, $title, $text, $icon, true);
	}
	
	function get_hv_respect($url, $title, $type, $id) {
		$retval = '<iframe src="http://www.hyves.nl/respect/button?url='.urlencode($url).'&amp;title='.urlencode($title).'" style="border: medium none; overflow:hidden; width:150px; height:21px;" scrolling="no" frameborder="0" allowTransparency="true" ></iframe>';
		return $retval;
	}
	
	function get_ml_send($url, $title, $type, $id) {	
		$url = 'mailto:?subject='.$title.'&amp;body='.$url;
		$title = __('Share via email','mr_social_sharing_toolkit');
		$text = __('Share via email','mr_social_sharing_toolkit');
		$icon = 'email';
		return $this->get_icon($type, $url, $title, $text, $icon);		
	}
	
	function get_follow_facebook($type, $id) {
		$url = 'http://www.facebook.com/'.$id;
		$title = __('Friend me on','mr_social_sharing_toolkit').' Facebook';
		$text = 'Facebook';
		$icon = 'facebook';
		return $this->get_icon($type, $url, $title, $text, $icon);
	}
	
	function get_follow_twitter($type, $id) {
		switch ($type) {
			case 'none':
				$retval = '<a href="http://twitter.com/'.$id.'" class="twitter-follow-button" data-show-count="false">Follow @'.$id.'</a>';
				break;
			case 'horizontal':
				$retval = '<a href="http://twitter.com/'.$id.'" class="twitter-follow-button">Follow @'.$id.'</a>';
				break;
			default:
				$url = 'http://twitter.com/'.$id;
				$title = __('Follow me on','mr_social_sharing_toolkit').' Twitter';
				$text = 'Twitter';
				$icon = 'twitter';
				$retval = $this->get_icon($type, $url, $title, $text, $icon);
				break;	
		}
		return $retval;
	}
	
	function get_follow_plus($type, $id) {
		$url = 'http://plus.google.com/'.$id;
		$title = __('Add me to your circles','mr_social_sharing_toolkit');
		$text = 'Google+';
		$icon = 'googleplus';
		return $this->get_icon($type, $url, $title, $text, $icon);
	}
	
	function get_follow_pinterest($type, $id) {
		$url = 'http://pinterest.com/'.$id.'/';
		$title = __('Follow Me on Pinterest','mr_social_sharing_toolkit');
		$text = 'Pinterest';
		$icon = 'pinterest';
		return $this->get_icon($type, $url, $title, $text, $icon);
	}
	
	function get_follow_linked($type, $id) {
		$url = 'http://www.linkedin.com/in/'.$id;
		$title = __('Join my network on','mr_social_sharing_toolkit').' LinkedIn';
		$text = 'LinkedIn';
		$icon = 'linkedin';
		return $this->get_icon($type, $url, $title, $text, $icon);
	}
	
	function get_follow_linked_co($type, $id) {
		$url = 'http://www.linkedin.com/company/'.$id;
		$title = __('Follow my company on','mr_social_sharing_toolkit').' LinkedIn';
		$text = 'LinkedIn';
		$icon = 'linkedin';
		return $this->get_icon($type, $url, $title, $text, $icon);
	}
	
	function get_follow_linked_group($type, $id) {
		$url = 'http://www.linkedin.com/groups?gid='.$id;
		$title = __('Join my group on','mr_social_sharing_toolkit').' LinkedIn';
		$text = 'LinkedIn';
		$icon = 'linkedin';
		return $this->get_icon($type, $url, $title, $text, $icon);
	}	
	
	function get_follow_xing($type, $id) {
		$url = 'http://www.xing.com/profile/'.$id;
		$title = __('Join my network on','mr_social_sharing_toolkit').' Xing';
		$text = 'Xing';
		$icon = 'xing';
		return $this->get_icon($type, $url, $title, $text, $icon);
	}
	
	function get_follow_tumblr($type, $id) {
		$url = 'http://'.$id.'.tumblr.com';
		$title = __('Follow me on','mr_social_sharing_toolkit').' Tumblr';
		$text = 'Tumblr';
		$icon = 'tumblr';
		return $this->get_icon($type, $url, $title, $text, $icon);
	}
	
	function get_follow_myspace($type, $id) {
		$url = 'http://www.myspace.com/'.$id;
		$title = __('Friend me on','mr_social_sharing_toolkit').' Myspace';
		$text = 'Myspace';
		$icon = 'myspace';
		return $this->get_icon($type, $url, $title, $text, $icon);
	}
	
	function get_follow_hyves($type, $id) {
		$url = 'http://'.$id.'.hyves.nl';
		$title = __('Friend me on','mr_social_sharing_toolkit').' Hyves';
		$text = 'Hyves';
		$icon = 'hyves';
		return $this->get_icon($type, $url, $title, $text, $icon);
	}
	
	function get_follow_youtube($type, $id) {
		$url = 'http://www.youtube.com/user/'.$id;
		$title = __('Watch me on','mr_social_sharing_toolkit').' YouTube';
		$text = 'YouTube';
		$icon = 'youtube';
		return $this->get_icon($type, $url, $title, $text, $icon);
	}
	
	function get_follow_flickr($type, $id) {
		$url = 'http://www.flickr.com/photos/'.$id;
		$title = __('My photostream on','mr_social_sharing_toolkit').' Flickr';
		$text = 'Flickr';
		$icon = 'flickr';
		return $this->get_icon($type, $url, $title, $text, $icon);
	}
	
	function get_follow_500px($type, $id) {
		$url = 'http://500px.com/'.$id;
		$title = __('My portfolio on','mr_social_sharing_toolkit').' 500px';
		$text = '500px';
		$icon = '500px';
		return $this->get_icon($type, $url, $title, $text, $icon);
	}
	
	function get_follow_picasa($type, $id) {
		$url = 'http://picasaweb.google.com/'.$id;
		$title = __('My Picasa Web Albums','mr_social_sharing_toolkit');
		$text = 'Picasa';
		$icon = 'picasa';
		return $this->get_icon($type, $url, $title, $text, $icon);
	}
	
	function get_follow_deviant($type, $id) {
		$url = 'http://'.$id.'.deviantart.com/';
		$title = __('My deviantArt','mr_social_sharing_toolkit');
		$text = 'deviantArt';
		$icon = 'deviantart';
		return $this->get_icon($type, $url, $title, $text, $icon);
	}
	
	function get_follow_lastfm($type, $id) {
		$url = 'http://www.last.fm/user/'.$id;
		$title = __('My profile on','mr_social_sharing_toolkit').' Last.fm';
		$text = 'Last.fm';
		$icon = 'lastfm';
		return $this->get_icon($type, $url, $title, $text, $icon);
	}
	
	function get_follow_spotify($type, $id) {
		$url = 'http://open.spotify.com/user/'.$id;
		$title = __('My profile on','mr_social_sharing_toolkit').' Spotify';
		$text = 'Spotify';
		$icon = 'spotify';
		return $this->get_icon($type, $url, $title, $text, $icon);
	}
	
	function get_follow_rss($type, $id) {
		$url = $id;
		$title = __('RSS Feed','mr_social_sharing_toolkit');
		$text = __('RSS Feed','mr_social_sharing_toolkit');
		$icon = 'rss';
		return $this->get_icon($type, $url, $title, $text, $icon);
	}
	
	function get_icon($type, $url, $title, $text, $icon, $popup = false) {
		if ($icon != 'email') {
			$url .= '" target="_blank';
		}
		if ($popup) {
			$url .= '" class="mr_social_sharing_popup_link';	
		}
		switch ($type) {
			case 'none':
				$retval = '<a href="'.$url.'"><img src="'.plugins_url('/images/buttons/'.$icon.'.png', __FILE__).'" alt="'.$title.'" title="'.$title.'"/></a>';
				break;
			case 'icon_small':
				$retval = '<a href="'.$url.'"><img src="'.plugins_url('/images/icons_small/'.$icon.'.png', __FILE__).'" alt="'.$title.'" title="'.$title.'"/></a>';
				break;
			case 'icon_small_text':
				$retval = '<a href="'.$url.'"><img src="'.plugins_url('/images/icons_small/'.$icon.'.png', __FILE__).'" alt="'.$title.'" title="'.$title.'"/><span class="mr_small_icon">'.$text.'</span></a>';
				break;
			case 'icon_medium':
				$retval = '<a href="'.$url.'"><img src="'.plugins_url('/images/icons_medium/'.$icon.'.png', __FILE__).'" alt="'.$title.'" title="'.$title.'"/></a>';
				break;
			case 'icon_medium_text':
				$retval = '<a href="'.$url.'"><img src="'.plugins_url('/images/icons_medium/'.$icon.'.png', __FILE__).'" alt="'.$title.'" title="'.$title.'"/><span class="mr_medium_icon">'.$text.'</span></a>';
				break;
			case 'icon_large':
				$retval = '<a href="'.$url.'"><img src="'.plugins_url('/images/icons_large/'.$icon.'.png', __FILE__).'" alt="'.$title.'" title="'.$title.'"/></a>';
				break;
			default:
				$retval = '<a href="'.$url.'"><img src="'.plugins_url('/images/icons_small/'.$icon.'.png', __FILE__).'" alt="'.$title.'" title="'.$title.'"/></a>';
				break;
		}		
		return $retval;	
	}
	
	function should_print_opengraph() {
		if ($this->options['mr_social_sharing_opengraph']['enable'] == 1) {
			return true;
		}
	}
	
	function should_share_content() {
		if ($this->options['mr_social_sharing_position'] != 'none' && !is_feed()) {
			return true;
		}
		return false;	
	}
	
	function should_share_excerpt() {
		if ($this->options['mr_social_sharing_position'] != 'none' && $this->options['mr_social_sharing_include_excerpts'] == 1 && !is_feed()) {
			return true;
		}
		return false;	
	}
	
	function share($content) {
		$media = '';
		if ($this->options['mr_social_sharing_pinterest']['default_image'] != '' && $this->options['mr_social_sharing_pinterest']['fixed_image'] == 1) {
			$media = $this->options['mr_social_sharing_pinterest']['default_image'];
		} else {
			if (current_theme_supports('post-thumbnails')) {
				if ($media = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()))) {
		  			if (is_array($media)) {
		  				$media = $media[0];
		  			} else {
		  				$media = '';	
		  			}
				}
			} 
			if ($media == '') {
				$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
	  			$img = $matches[1][0];
				if($img != '') {
	    			$media = $img;
	  			}	
			}
			if ($media == '' && $this->options['mr_social_sharing_pinterest']['default_image'] != '') {
				$media = $this->options['mr_social_sharing_pinterest']['default_image'];
			}
		}
		$type = get_post_type();
		if (in_array($type, $this->options['mr_social_sharing_types']) && ((is_single() || $this->options['mr_social_sharing_include_excerpts'] == 1) || $type == 'page')) {
			if ($this->options['mr_social_sharing_position'] == 'top') {
				$bookmarks = $this->create_bookmarks(get_permalink(), the_title('','',false), '', $media);
				$content = $bookmarks.$content;	
			}
			if ($this->options['mr_social_sharing_position'] == 'bottom') {
				$bookmarks = $this->create_bookmarks(get_permalink(), the_title('','',false), '', $media);
				$content .= $bookmarks;
			}
			if ($this->options['mr_social_sharing_position'] == 'both') {
				$bookmarks = $this->create_bookmarks(get_permalink(), the_title('','',false), '', $media);
				$content = $bookmarks.$content.$bookmarks;
			}
		}
		return $content;
	}
	
	function share_excerpt($content) {
		$media = '';
		if ($this->options['mr_social_sharing_pinterest']['default_image'] != '' && $this->options['mr_social_sharing_pinterest']['fixed_image'] == 1) {
			$media = $this->options['mr_social_sharing_pinterest']['default_image'];
		} else {
			if (current_theme_supports('post-thumbnails')) {
				if ($media = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()))) {
		  			if (is_array($media)) {
		  				$media = $media[0];
		  			} else {
		  				$media = '';	
		  			}
				}
			}		
			if ($media == '') {
				$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
	  			$img = $matches[1][0];
				if($img != '') {
	    			$media = $img;
	  			}	
			}
			if ($media == '' && $this->options['mr_social_sharing_pinterest']['default_image'] != '') {
				$media = $this->options['mr_social_sharing_pinterest']['default_image'];
			}
		}
		$type = get_post_type();
		if (in_array($type, $this->options['mr_social_sharing_types'])) {
			if ($this->options['mr_social_sharing_position'] == 'top') {
				$bookmarks = $this->create_bookmarks(get_permalink(), the_title('','',false), '', $media);
				$content = $bookmarks.$content;	
			}
			if ($this->options['mr_social_sharing_position'] == 'bottom') {
				$bookmarks = $this->create_bookmarks(get_permalink(), the_title('','',false), '', $media);
				$content .= $bookmarks;
			}
			if ($this->options['mr_social_sharing_position'] == 'both') {
				$bookmarks = $this->create_bookmarks(get_permalink(), the_title('','',false), '', $media);
				$content = $bookmarks.$content.$bookmarks;
			}
		}
		return $content;
	}
	
	function should_use_shortcode() {
		if ($this->options['mr_social_sharing_enable_shortcode'] == 1 && !is_feed()) {
			return true;
		}
		return false;	
	}
	
	function share_shortcode() {
		$media = '';
		if ($this->options['mr_social_sharing_pinterest']['default_image'] != '' && $this->options['mr_social_sharing_pinterest']['fixed_image'] == 1) {
			$media = $this->options['mr_social_sharing_pinterest']['default_image'];
		} else {
			if (current_theme_supports('post-thumbnails')) {
				if ($media = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()))) {
		  			if (is_array($media)) {
		  				$media = $media[0];
		  			} else {
		  				$media = '';	
		  			}
				}
			}		
			if ($media == '') {
				$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_the_content(), $matches);
	  			$img = $matches[1][0];
				if($img != '') {
	    			$media = $img;
	  			}	
			}
			if ($media == '' && $this->options['mr_social_sharing_pinterest']['default_image'] != '') {
				$media = $this->options['mr_social_sharing_pinterest']['default_image'];
			}
		}
		$type = get_post_type();
		$bookmarks = '';
		if ($this->options['mr_social_sharing_enable_shortcode'] == 1 && ((is_single() || $type == 'page') || $this->options['mr_social_sharing_include_excerpts'] == 1)) {
			$bookmarks = $this->create_bookmarks(get_permalink(), the_title('','',false), 'shortcode_', $media);
		}
		return $bookmarks;
	}
	
	function should_linkify_content() {
		if ($this->options['mr_social_sharing_linkify_content'] == 1) {
			return true;
		}
		return false;
	}
	
	function should_linkify_comments() {
		if ($this->options['mr_social_sharing_linkify_comments'] == 1) {
			return true;
		}
		return false;
	}
	
	function linkify($content) {
		if ($this->options['mr_social_sharing_linkify_new'] == 1) {
			if ($this->options['mr_social_sharing_twitter_handles'] == 1) {
				$content = preg_replace("/(^|\s)+(@([a-zA-Z0-9_-]{1,15}))(\.*[^|\n|\r|\t|\s|\<|\&]*)/i", "$1<a href=\"http://twitter.com/$3\" target=\"_BLANK\">$2</a>$4", $content);
			}
			if ($this->options['mr_social_sharing_twitter_hashtags'] == 1) {
				$content = preg_replace("/(^|\s)+((?:(?<!&))#([a-zA-Z0-9]+^[-|;]))([^|\n|\r|\t|\s|\.|\<|\&]*)/i", "$1<a href=\"http://twitter.com/search/$3\" target=\"_BLANK\">$2</a>$4", $content);
			}
		} else {
			if ($this->options['mr_social_sharing_twitter_handles'] == 1) {
				$content = preg_replace("/(^|\s)+(@([a-zA-Z0-9_-]{1,15}))(\.*[^|\n|\r|\t|\s|\<|\&]*)/i", "$1<a href=\"http://twitter.com/$3\">$2</a>$4", $content);
			}
			if ($this->options['mr_social_sharing_twitter_hashtags'] == 1) {
				$content = preg_replace("/(^|\s)+((?:(?<!&))#([a-zA-Z0-9]+^[-|;]))([^|\n|\r|\t|\s|\.|\<|\&]*)/i", "$1<a href=\"http://twitter.com/search/$3\">$2</a>$4", $content);
			}
		}
		return $content;
	}
}
class MR_Social_Sharing_Toolkit_Widget extends WP_Widget {
	function MR_Social_Sharing_Toolkit_Widget() {
		$widget_ops = array( 'classname' => 'MR_Social_Sharing_Toolkit_Widget', 'description' => '' );
		$control_ops = array( 'id_base' => 'mr-social-sharing-toolkit-widget' );
		$this->WP_Widget( 'mr-social-sharing-toolkit-widget', 'Social Sharing Toolkit '.__('Share Widget','mr_social_sharing_toolkit'), $widget_ops, $control_ops );
	}

	function widget ( $args, $instance) {
		extract( $args );
		$MR_Social_Sharing_Toolkit = new MR_Social_Sharing_Toolkit();
		$widget_title = empty($instance['widget_title']) ? '' : $instance['widget_title'];
		$url = empty($instance['fixed_url']) ? '' : $instance['fixed_url'];
		$title = empty($instance['fixed_title']) ? wp_title('', false) : $instance['fixed_title'];
		$media = '';
		if ($this->options['mr_social_sharing_pinterest']['default_image'] != '' && $this->options['mr_social_sharing_pinterest']['fixed_image'] == 1) {
			$media = $this->options['mr_social_sharing_pinterest']['default_image'];
		} else {
			if (current_theme_supports('post-thumbnails')) {
				if ($media = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()))) {
		  			if (is_array($media)) {
		  				$media = $media[0];
		  			} else {
		  				$media = '';	
		  			}
				}
			}		
			if ($media == '') {
				$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_the_content(), $matches);
	  			$img = $matches[1][0];
				if($img != '') {
	    			$media = $img;
	  			}	
			}
			if ($media == '' && $this->options['mr_social_sharing_pinterest']['default_image'] != '') {
				$media = $this->options['mr_social_sharing_pinterest']['default_image'];
			}
		}
		$bookmarks = $MR_Social_Sharing_Toolkit->create_bookmarks($url, $title, 'widget_', $media);	
		echo $before_widget;
		if ($widget_title != '') {
			echo $before_title . $widget_title . $after_title;
		}
		echo $bookmarks;
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['widget_title'] = $new_instance['widget_title'];
		$instance['fixed_title'] = $new_instance['fixed_title'];
		$instance['fixed_url'] = $new_instance['fixed_url'];
		return $instance;
	}
	
	function form($instance) {
		$instance = wp_parse_args((array) $instance, array( 'widget_title' => '', 'fixed_title' => '', 'fixed_url' => ''));
		echo '			
		<p>
			<label for="'.$this->get_field_id( 'widget_title' ).'">'.__('Title').':</label>
			<input class="widefat" id="'.$this->get_field_id( 'widget_title' ).'" name="'.$this->get_field_name( 'widget_title' ).'" value="'.$instance['widget_title'].'" />
		</p>	
		<p>
			<label for="'.$this->get_field_id( 'fixed_title' ).'">'.__('Fixed title','mr_social_sharing_toolkit').':</label>
			<input class="widefat" id="'.$this->get_field_id( 'fixed_title' ).'" name="'.$this->get_field_name( 'fixed_title' ).'" value="'.$instance['fixed_title'].'" />
		</p>
		<p>
			<label for="'.$this->get_field_id( 'fixed_url' ).'">'.__('Fixed url','mr_social_sharing_toolkit').':</label>
			<input class="widefat" id="'.$this->get_field_id( 'fixed_url' ).'" name="'.$this->get_field_name( 'fixed_url' ).'" value="'.$instance['fixed_url'].'" />
		</p>
		<p>
			'.__('Further configuration is done via the','mr_social_sharing_toolkit').' <a href="options-general.php?page=mr_social_sharing#tab_3">'.__('plugin admin screen','mr_social_sharing_toolkit').'</a>.
		</p>';
	}
}
class MR_Social_Sharing_Toolkit_Follow_Widget extends WP_Widget {
	function MR_Social_Sharing_Toolkit_Follow_Widget() {
		$widget_ops = array( 'classname' => 'MR_Social_Sharing_Toolkit_Follow_Widget', 'description' => '' );
		$control_ops = array( 'id_base' => 'mr-social-sharing-toolkit-follow-widget' );
		$this->WP_Widget( 'mr-social-sharing-toolkit-follow-widget', 'Social Sharing Toolkit '.__('Follow Widget','mr_social_sharing_toolkit'), $widget_ops, $control_ops );
	}

	function widget ( $args, $instance) {
		extract( $args );
		$MR_Social_Sharing_Toolkit = new MR_Social_Sharing_Toolkit();
		$widget_title = empty($instance['widget_title']) ? '' : $instance['widget_title'];
		$followers = $MR_Social_Sharing_Toolkit->create_followers();	
		echo $before_widget;
		if ($widget_title != '') {
			echo $before_title . $widget_title . $after_title;
		}
		echo $followers;
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['widget_title'] = $new_instance['widget_title'];
		return $instance;
	}
	
	function form($instance) {
		$instance = wp_parse_args((array) $instance, array( 'widget_title' => ''));
		echo '			
		<p>
			<label for="'.$this->get_field_id( 'widget_title' ).'">'.__('Title').':</label>
			<input class="widefat" id="'.$this->get_field_id( 'widget_title' ).'" name="'.$this->get_field_name( 'widget_title' ).'" value="'.$instance['widget_title'].'" />
		</p>
		<p>
			'.__('Further configuration is done via the','mr_social_sharing_toolkit').' <a href="options-general.php?page=mr_social_sharing#tab_4">'.__('plugin admin screen','mr_social_sharing_toolkit').'</a>.
		</p>';
	}	
}
$MR_Social_Sharing_Toolkit = new MR_Social_Sharing_Toolkit();
add_action('wp_print_styles', array($MR_Social_Sharing_Toolkit, 'prepare_styles'));
add_action('wp_print_scripts', array($MR_Social_Sharing_Toolkit, 'prepare_scripts'));
if ($MR_Social_Sharing_Toolkit->should_print_opengraph()) {	
	add_action('wp_head', array($MR_Social_Sharing_Toolkit, 'print_opengraph'), 1);
}
if ($MR_Social_Sharing_Toolkit->should_linkify_content()) {
	add_filter('the_content', array($MR_Social_Sharing_Toolkit, 'linkify'));
}
if ($MR_Social_Sharing_Toolkit->should_linkify_comments()) {
	add_filter('comment_text', array($MR_Social_Sharing_Toolkit, 'linkify'));
}
if ($MR_Social_Sharing_Toolkit->should_share_excerpt()) {
	add_filter('the_excerpt', array($MR_Social_Sharing_Toolkit, 'share_excerpt'));
}
if ($MR_Social_Sharing_Toolkit->should_share_content()) {
	add_filter('the_content', array($MR_Social_Sharing_Toolkit, 'share'));
}
if ($MR_Social_Sharing_Toolkit->should_use_shortcode()) {
	add_shortcode('social_share', array($MR_Social_Sharing_Toolkit, 'share_shortcode'));
}
/* Register widgets */
add_action('widgets_init', create_function('', 'return register_widget("MR_Social_Sharing_Toolkit_Widget");'));
add_action('widgets_init', create_function('', 'return register_widget("MR_Social_Sharing_Toolkit_Follow_Widget");'));
/* Register plugin admin page */
add_action('admin_menu', array($MR_Social_Sharing_Toolkit, 'plugin_menu'));