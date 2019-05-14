<?php
/*
 Plugin Name: Social Sharing Toolkit
 Plugin URI: http://wordpress.org/plugins/social-sharing-toolkit/
 Description: This plugin enables sharing of your content via popular social networks and can also convert Twitter names and hashtags to links. Easy & configurable.
 Version: 2.6
 Author: linksalpha
 Author URI: http://www.linksalpha.com
 */

class MR_Social_Sharing_Toolkit {
	var $count;
	var $options;
	var $types;
	var $buttons;
	var $share_buttons;
	var $follow_buttons;
	var $error;
	var $scripts;

	function MR_Social_Sharing_Toolkit() {
		$this -> count = 0;
		$this -> error = '';
		$this -> scripts = array();
		load_plugin_textdomain('mr_social_sharing_toolkit', false, dirname(plugin_basename(__FILE__)) . '/languages/');
		/* Declare button types */
		$this -> types['none'] = __('Button', 'mr_social_sharing_toolkit');
		$this -> types['none_text'] = __('Button + text', 'mr_social_sharing_toolkit');
		$this -> types['horizontal'] = __('Button + side counter', 'mr_social_sharing_toolkit');
		$this -> types['vertical'] = __('Button + top counter', 'mr_social_sharing_toolkit');
		$this -> types['icon_small'] = __('Small icon', 'mr_social_sharing_toolkit');
		$this -> types['icon_small_text'] = __('Small icon + text', 'mr_social_sharing_toolkit');
		$this -> types['icon_medium'] = __('Medium icon', 'mr_social_sharing_toolkit');
		$this -> types['icon_medium_text'] = __('Medium icon + text', 'mr_social_sharing_toolkit');
		$this -> types['icon_large'] = __('Large icon', 'mr_social_sharing_toolkit');
		$buttons = glob(dirname(__FILE__) . '/includes/buttons/button.*.php');
		if (is_array($buttons) && count($buttons) > 0) {
			foreach ($buttons as $buttonKey => $button) {
				$name = str_replace(dirname(__FILE__) . '/includes/buttons/button.', '', str_replace('.php', '', $button));
				$class_name = 'MR_Social_Sharing_Toolkit_' . ucfirst($name);
				if (class_exists($class_name)) {
					$this -> buttons[$name] = new $class_name();
					if ($this -> buttons[$name] -> hasShare()) {
						foreach ($this->buttons[$name]->getShareButtons() as $tmp) {
							$this -> share_buttons[$tmp['name']] = array('icon' => $this -> buttons[$name] -> getIcon(), 'types' => $tmp['types']);
							$this -> share_buttons[$tmp['name']]['title'] = (array_key_exists('title', $tmp)) ? $tmp['title'] : $this -> buttons[$name] -> getTitle();
							if (array_key_exists('id', $tmp)) {
								$this -> share_buttons[$tmp['name']]['id'] = $tmp['id'];
							}
							unset($tmp);
						}
					}
					if ($this -> buttons[$name] -> hasFollow()) {
						foreach ($this->buttons[$name]->getFollowButtons() as $tmp) {
							$this -> follow_buttons[$tmp['name']] = array('icon' => $this -> buttons[$name] -> getIcon(), 'types' => $tmp['types']);
							$this -> follow_buttons[$tmp['name']]['title'] = (array_key_exists('title', $tmp)) ? $tmp['title'] : $this -> buttons[$name] -> getTitle();
							if (array_key_exists('id', $tmp)) {
								$this -> follow_buttons[$tmp['name']]['id'] = $tmp['id'];
							}
							unset($tmp);
						}
					}
				}
			}
		}
		unset($buttons);
		$this -> share_buttons['ln_break_1'] = array('icon' => 'divider', 'title' => __('Divider', 'mr_social_sharing_toolkit') . ' 1', 'types' => array(''));
		$this -> share_buttons['ln_break_2'] = array('icon' => 'divider', 'title' => __('Divider', 'mr_social_sharing_toolkit') . ' 2', 'types' => array(''));
		$this -> share_buttons['ln_break_3'] = array('icon' => 'divider', 'title' => __('Divider', 'mr_social_sharing_toolkit') . ' 3', 'types' => array(''));
		$this -> follow_buttons['ln_break_1'] = array('icon' => 'divider', 'title' => __('Divider', 'mr_social_sharing_toolkit') . ' 1', 'types' => array(''));
		$this -> follow_buttons['ln_break_2'] = array('icon' => 'divider', 'title' => __('Divider', 'mr_social_sharing_toolkit') . ' 2', 'types' => array(''));
		$this -> follow_buttons['ln_break_3'] = array('icon' => 'divider', 'title' => __('Divider', 'mr_social_sharing_toolkit') . ' 3', 'types' => array(''));
		/* Set defaults and load user options */
		$this -> get_options();
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
		$attachment_image_size_options = array('default_size' => 'medium');
		$pinterest_options = array('default_image' => '', 'fixed_image' => 0);
		$bitly_options = array('enable' => 0, 'username' => '', 'key' => '', 'cache' => array());
		$opengraph_options = array('enable' => 0, 'default_image' => '', 'fixed_image' => 0);
		$this -> options = array('mr_social_sharing_buttons' => $buttons,
								'mr_social_sharing_shortcode_buttons' => $shortcodes,
								'mr_social_sharing_widget_buttons' => $widgets,
								'mr_social_sharing_follow_buttons' => $followers,
								'mr_social_sharing_display' => 'span',
								'mr_social_sharing_shortcode_display' => 'span',
								'mr_social_sharing_widget_display' => 'span',
								'mr_social_sharing_follow_display' => 'span',
								'mr_social_sharing_align' => '',
								'mr_social_sharing_shortcode_align' => '',
								'mr_social_sharing_widget_align' => '',
								'mr_social_sharing_follow_align' => '',
								'mr_social_sharing_position' => 'none',
								'mr_social_sharing_types' => array('post', 'page'),
								'mr_social_sharing_enable_shortcode' => 1,
								'mr_social_sharing_include_excerpts' => 1,
								'mr_social_sharing_button_order' => $button_order,
								'mr_social_sharing_shortcode_button_order' => $shortcode_order,
								'mr_social_sharing_widget_button_order' => $widget_order,
								'mr_social_sharing_follow_button_order' => $follow_order,
								'mr_social_sharing_linkify_content' => 0,
								'mr_social_sharing_linkify_comments' => 0,
								'mr_social_sharing_linkify_new' => 1,
								'mr_social_sharing_follow_new' => 1,
								'mr_social_sharing_twitter_handles' => 0,
								'mr_social_sharing_twitter_hashtags' => 0,
								'mr_social_sharing_js_footer' => 1,
								'mr_social_sharing_no_follow' => 0,
								'mr_social_sharing_attachment_image_size' => $attachment_image_size_options,
								'mr_social_sharing_pinterest' => $pinterest_options,
								'mr_social_sharing_bitly' => $bitly_options,
								'mr_social_sharing_opengraph' => $opengraph_options,
								'mr_social_sharing_custom_css' => '');
		foreach ($this->options as $key => $val) {
			$this -> options[$key] = get_option($key, $val);
		}
		if (!is_array($this -> options['mr_social_sharing_types'])) {
			$types = array();
			switch ($this->options['mr_social_sharing_types']) {
				case 'both' :
					$types[] = 'page';
					$types[] = 'post';
					break;
				case 'pages' :
					$types[] = 'page';
					break;
				case 'posts' :
					$types[] = 'post';
					break;
			}
			$this -> options['mr_social_sharing_types'] = $types;
		}
		if ($this -> options['mr_social_sharing_position'] == 'shortcode') {
			$this -> options['mr_social_sharing_position'] = 'none';
			$this -> options['mr_social_sharing_enable_shortcode'] = 1;
		}
		foreach ($this->share_buttons as $key => $val) {
			if (!array_key_exists($key, $this -> options['mr_social_sharing_buttons'])) {
				$this -> options['mr_social_sharing_buttons'][$key] = array('enable' => 0, 'type' => $val['types'][0]);
			}
			if (!array_key_exists($key, $this -> options['mr_social_sharing_shortcode_buttons'])) {
				$this -> options['mr_social_sharing_shortcode_buttons'][$key] = array('enable' => 0, 'type' => $val['types'][0]);
			}
			if (!array_key_exists($key, $this -> options['mr_social_sharing_widget_buttons'])) {
				$this -> options['mr_social_sharing_widget_buttons'][$key] = array('enable' => 0, 'type' => $val['types'][0]);
			}
			if (!in_array($key, $this -> options['mr_social_sharing_button_order'])) {
				$this -> options['mr_social_sharing_button_order'][] = $key;
			}
			if (!in_array($key, $this -> options['mr_social_sharing_shortcode_button_order'])) {
				$this -> options['mr_social_sharing_shortcode_button_order'][] = $key;
			}
			if (!in_array($key, $this -> options['mr_social_sharing_widget_button_order'])) {
				$this -> options['mr_social_sharing_widget_button_order'][] = $key;
			}
		}
		foreach ($this->follow_buttons as $key => $val) {
			if (!array_key_exists($key, $this -> options['mr_social_sharing_follow_buttons'])) {
				$this -> options['mr_social_sharing_follow_buttons'][$key] = array('enable' => 0, 'type' => $val['types'][0], 'id' => '');
			}
			if (!in_array($key, $this -> options['mr_social_sharing_follow_button_order'])) {
				$this -> options['mr_social_sharing_follow_button_order'][] = $key;
			}
		}
		return $this -> options;
	}

	/* Admin functions */

	// Simple input sanitizing to prevent script injections:
	function sanitize_value($val) {
		$val = str_replace('"', '', html_entity_decode($val, ENT_QUOTES));
		$val = strtr($val, "&;<>'", ";;;;;");
		return str_replace(";", "", $val);
	}

	function save_options($new_options) {
		foreach ($this->options as $key => $val) {
			if (array_key_exists($key, $new_options)) {
				if (is_array($new_options[$key])) {
					foreach ($new_options[$key] as $sub_key => $val) {
						if (is_array($new_options[$key][$sub_key]) && array_key_exists('id', $new_options[$key][$sub_key])) {
							$new_options[$key][$sub_key]['id'] = $this -> sanitize_value($new_options[$key][$sub_key]['id']);
						}
						if (is_array($new_options[$key][$sub_key]) && array_key_exists('text', $new_options[$key][$sub_key])) {
							$new_options[$key][$sub_key]['text'] = $this -> sanitize_value($new_options[$key][$sub_key]['text']);
						}
					}
				}
				update_option($key, $new_options[$key]);
				$this -> options[$key] = $new_options[$key];
			} else {
				update_option($key, 0);
				$this -> options[$key] = 0;
			}
		}
		// Write to custom CSS file:
		if ($this -> options['mr_social_sharing_custom_css'] != '') {
			$upload = wp_upload_dir();
			try {
				if (!@file_put_contents($upload['basedir'] . '/social_sharing_custom.css', $this -> options['mr_social_sharing_custom_css'])) {
					throw new Exception(__('Could not write to CSS file, to fix this create a file called &quot;social_sharing_custom.css&quot; in ', 'mr_social_sharing_toolkit') . $upload['basedir'] . __('. Make sure the file has read and write permissions.', 'mr_social_sharing_toolkit'));
				}
			} catch (Exception $e) {
				$this -> error = $e -> getMessage();
			}
		}
	}

	function plugin_menu() {
		add_options_page('Social Sharing', 'Social Sharing Toolkit', 'manage_options', 'mr_social_sharing', array($this, 'plugin_admin_page'));
		add_filter('plugin_row_meta', array('MR_Social_Sharing_Toolkit', 'plugin_links'), 10, 2);
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_style('mr_social_sharing-admin', plugins_url('/admin_2.1.2.css', __FILE__));
		wp_enqueue_script('mr_social_sharing-admin', plugins_url('/admin_2.1.2.js', __FILE__));
	}

	function plugin_links($links, $file) {
		if ($file == plugin_basename(__FILE__)) {
			$links[] = '<a href="/wp-admin/options-general.php?page=mr_social_sharing">' . __('Settings') . '</a>';
		}
		return $links;
	}

	function plugin_admin_page() {
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}
		if (isset($_POST['mr_social_sharing_save_options']) && $_POST['mr_social_sharing_save_options'] == 'Y') {
			$this -> save_options($_POST);
			echo '
       		<div class="updated"><p><strong>' . __('Settings saved.', 'mr_social_sharing_toolkit') . '</strong></p></div>';
			if ($this -> error != '') {
				echo '
       		<div class="error"><p><strong>' . $this -> error . '</strong></p></div>';
			}
		}
		echo '
			<div class="wrap">
				<form method="post" action="">
					<input type="hidden" name="mr_social_sharing_save_options" value="Y"/>
					<h2>Social Sharing Toolkit</h2>

					<div id="mr_social_sharing_tabs">
						<ul class="tabs">
							<li><a href="#tab_0">' . __('General settings', 'mr_social_sharing_toolkit') . '</a><li>
							<li><a href="#tab_1">' . __('Content', 'mr_social_sharing_toolkit') . '</a><li>
							<li><a href="#tab_2">' . __('Shortcode', 'mr_social_sharing_toolkit') . '</a><li>
							<li><a href="#tab_3">' . __('Share Widget', 'mr_social_sharing_toolkit') . '</a><li>
							<li><a href="#tab_4">' . __('Follow Widget', 'mr_social_sharing_toolkit') . '</a><li>
							<li><a href="#tab_5">' . __('Advanced settings', 'mr_social_sharing_toolkit') . '</a><li>
							<li><a href="#tab_6">' . __('Custom CSS', 'mr_social_sharing_toolkit') . '</a><li>
						</ul>
						<div id="tab_0" class="mr_social_sharing_networks">
							<h3>' . __('General settings', 'mr_social_sharing_toolkit') . ':</h3>
							<label for="mr_social_sharing_js_footer" class="check"><input type="checkbox" name="mr_social_sharing_js_footer" id="mr_social_sharing_js_footer"';
		if ($this -> options['mr_social_sharing_js_footer'] == 1) { echo ' checked="checked"';
		}
		echo ' value="1" /> ' . __('Load JavaScript in footer', 'mr_social_sharing_toolkit') . '</label>

							<p>
								<span class="description"> ' . __('Improves performance but may not work on some themes', 'mr_social_sharing_toolkit') . '</span>
							</p>
							<p>
								' . __('Choose where the buttons must be displayed and if the buttons should be displayed on posts, pages or both.', 'mr_social_sharing_toolkit') . '
							</p>
							<label for="mr_social_sharing_position">' . __('Button location', 'mr_social_sharing_toolkit') . '</label>
							<select name="mr_social_sharing_position" id="mr_social_sharing_position">
								<option value="none"';
		if ($this -> options['mr_social_sharing_position'] == 'none') { echo ' selected="selected"';
		}
		echo '>' . __('Do not display', 'mr_social_sharing_toolkit') . '</option>
								<option value="top"';
		if ($this -> options['mr_social_sharing_position'] == 'top') { echo ' selected="selected"';
		}
		echo '>' . __('Display before content', 'mr_social_sharing_toolkit') . '</option>
								<option value="bottom"';
		if ($this -> options['mr_social_sharing_position'] == 'bottom') { echo ' selected="selected"';
		}
		echo '>' . __('Display after content', 'mr_social_sharing_toolkit') . '</option>
								<option value="both"';
		if ($this -> options['mr_social_sharing_position'] == 'both') { echo ' selected="selected"';
		}
		echo '>' . __('Display before and after content', 'mr_social_sharing_toolkit') . '</option>
							</select><br/><br/>
							<label for="mr_social_sharing_types">' . __('Place buttons on', 'mr_social_sharing_toolkit') . '</label><br/>';
		$types = get_post_types();
		if (is_array($types) && count($types) > 0) {
			foreach ($types as $type) {
				if (!in_array($type, array('attachment', 'revision', 'nav_menu_item'))) {
					echo '<label for="share_type_' . $type . '"><input type="checkbox" id="share_type_' . $type . '" name="mr_social_sharing_types[]" value="' . $type . '"';
					if (is_array($this -> options['mr_social_sharing_types']) && in_array($type, $this -> options['mr_social_sharing_types'])) {
						echo ' checked="checked"';
					}
					echo '/>&nbsp;' . ucfirst(__($type)) . '</label><br/>';
				}
			}
		}
		echo '
							<br/>
							<input type="hidden" name="mr_social_sharing_enable_shortcode" value="1" />
							<p><span class="description"> ' . __('Use the shortcode [social_share/] where you want the buttons to appear', 'mr_social_sharing_toolkit') . '</span></p>
							<label for="mr_social_sharing_include_excerpts" class="check"><input type="checkbox" name="mr_social_sharing_include_excerpts" id="mr_social_sharing_include_excerpts"';
		if ($this -> options['mr_social_sharing_include_excerpts'] == 1) { echo ' checked="checked"';
		}
		echo ' value="1" /> ' . __('Include buttons in excerpts', 'mr_social_sharing_toolkit') . '</label>
							<p><span class="description">' . __('Uncheck this box if you are having issues displaying the buttons with excerpts (some themes have custom excerpt functions which do not play nice with the plugin).', 'mr_social_sharing_toolkit') . '</p>
							<label for="mr_social_sharing_no_follow" class="check"><input type="checkbox" name="mr_social_sharing_no_follow" id="mr_social_sharing_no_follow"';
		if ($this -> options['mr_social_sharing_no_follow'] == 1) { echo ' checked="checked"';
		}
		echo ' value="1" /> ' . __('Use rel="nofollow" on links to social networks', 'mr_social_sharing_toolkit') . '</label>
						</div>
					<div id="tab_1" class="mr_social_sharing_networks">
						<h3>' . __('Content', 'mr_social_sharing_toolkit') . '</h3>
						<p>
							' . __('Check the boxes to display the button on your website. For each button you can select a separate style from the dropdown box. You can change the order of the buttons by dragging them to the desired location in the list. For the tweet button you can also fill in your Twitter username which will then be appended to the tweet (like via @WordPress).', 'mr_social_sharing_toolkit') . '
						</p>';
		$this -> showListAdmin($this -> share_buttons);
		echo '
					</div>
					<div id="tab_2" class="mr_social_sharing_networks">
						<h3>' . __('Shortcode', 'mr_social_sharing_toolkit') . '</h3>
						<p>
							' . __('Check the boxes to display the button on your website. For each button you can select a separate style from the dropdown box. You can change the order of the buttons by dragging them to the desired location in the list. For the tweet button you can also fill in your Twitter username which will then be appended to the tweet (like via @WordPress).', 'mr_social_sharing_toolkit') . '
						</p>';
		$this -> showListAdmin($this -> share_buttons, 'shortcode_');
		echo '
					</div>
					<div id="tab_3" class="mr_social_sharing_networks">
						<h3>' . __('Share Widget', 'mr_social_sharing_toolkit') . '</h3>
						<p>
							' . __('Check the boxes to display the button on Social Sharing Toolkit Share widget. For each button you can select a separate style from the dropdown box. You can change the order of the buttons by dragging them to the desired location in the list. For the tweet button you can also fill in your Twitter username which will then be appended to the tweet (like via @WordPress).', 'mr_social_sharing_toolkit') . '
							<br/><br/>
							' . __('For each widget you can enter a fixed url and title for the buttons, to do this', 'mr_social_sharing_toolkit') . ' <a href="widgets.php">' . __('go to the widget configuration page', 'mr_social_sharing_toolkit') . '</a>.
						</p>';
		$this -> showListAdmin($this -> share_buttons, 'widget_');
		echo '
					</div>
					<div id="tab_4" class="mr_social_sharing_networks">
						<h3>' . __('Follow Widget', 'mr_social_sharing_toolkit') . '</h3>
						<p>
							' . __('Check the boxes to display the button on Social Sharing Toolkit Follow widget. For each button you can select a separate style from the dropdown box. You can change the order of the buttons by dragging them to the desired location in the list.', 'mr_social_sharing_toolkit') . '
							<br/><br/>
							' . __('For each button you only have to enter your id or username of the network as it appears in the url of your profile page. You will need to enter the complete url for the RSS Feed (including the http:// part) if you wish to display this button.', 'mr_social_sharing_toolkit') . '
							<br/>
							<br/>
							' . __('To add the widget to your website', 'mr_social_sharing_toolkit') . ' <a href="widgets.php">' . __('go to the widget configuration page', 'mr_social_sharing_toolkit') . '</a>.
						</p>';
		$this -> showListAdmin($this -> follow_buttons, 'follow_');
		echo '
						<label for="mr_social_sharing_follow_new" class="check"><input type="checkbox" name="mr_social_sharing_follow_new" id="mr_social_sharing_follow_new"';
		if ($this -> options['mr_social_sharing_follow_new'] == 1) { echo ' checked="checked"';
		}
		echo ' value="1" /> ' . __("Open links in new window or tab", 'mr_social_sharing_toolkit') . '</label>
					</div>
					<div id="tab_5" class="mr_social_sharing_networks">
						<h3>' . __('Advanced settings', 'mr_social_sharing_toolkit') . '</h3>
						<h4>' . __('Automatic Twitter links', 'mr_social_sharing_toolkit') . '</h4>
						<p>' . __('Select what you want to convert:', 'mr_social_sharing_toolkit') . '</p>
						<label for="mr_social_sharing_twitter_handles" class="check"><input type="checkbox" name="mr_social_sharing_twitter_handles" id="mr_social_sharing_twitter_handles"';
		if ($this -> options['mr_social_sharing_twitter_handles'] == 1) { echo ' checked="checked"';
		}
		echo ' value="1" /> ' . __("Convert Twitter usernames", 'mr_social_sharing_toolkit') . '</label><br/>
						<label for="mr_social_sharing_twitter_hashtags" class="check"><input type="checkbox" name="mr_social_sharing_twitter_hashtags" id="mr_social_sharing_twitter_hashtags"';
		if ($this -> options['mr_social_sharing_twitter_hashtags'] == 1) { echo ' checked="checked"';
		}
		echo ' value="1" /> ' . __("Convert hashtags", 'mr_social_sharing_toolkit') . '</label>
						<p>' . __('And where it should be converted:', 'mr_social_sharing_toolkit') . '</p>
						<label for="mr_social_sharing_linkify_content" class="check"><input type="checkbox" name="mr_social_sharing_linkify_content" id="mr_social_sharing_linkify_content"';
		if ($this -> options['mr_social_sharing_linkify_content'] == 1) { echo ' checked="checked"';
		}
		echo ' value="1" /> ' . __("Convert in posts and pages", 'mr_social_sharing_toolkit') . '</label><br/>
						<label for="mr_social_sharing_linkify_comments" class="check"><input type="checkbox" name="mr_social_sharing_linkify_comments" id="mr_social_sharing_linkify_comments"';
		if ($this -> options['mr_social_sharing_linkify_comments'] == 1) { echo ' checked="checked"';
		}
		echo ' value="1" /> ' . __("Convert in comments", 'mr_social_sharing_toolkit') . '</label><br/>
						<label for="mr_social_sharing_linkify_new" class="check"><input type="checkbox" name="mr_social_sharing_linkify_new" id="mr_social_sharing_linkify_new"';
		if ($this -> options['mr_social_sharing_linkify_new'] == 1) { echo ' checked="checked"';
		}
		echo ' value="1" /> ' . __("Open links in new window or tab", 'mr_social_sharing_toolkit') . '</label>';

		// Image Size
		echo 			'<h4>' . __('Image Size', 'mr_social_sharing_toolkit') . '</h4>
						<p>' . __('Select the image size to be used while sharing', 'mr_social_sharing_toolkit') . '</p>
						<label for="mr_social_sharing_attachment_image_size_enable"><select name="mr_social_sharing_attachment_image_size" id="mr_social_sharing_attachment_image_size_enable">';
		echo 				   '<option value="thumbnail"';
		if ($this -> options['mr_social_sharing_attachment_image_size'] == 'thumbnail') { echo ' selected="selected"';}
		echo '>' . __('Thumbnail', 'mr_social_sharing_toolkit') . '</option>
								<option value="medium"';
		if ($this -> options['mr_social_sharing_attachment_image_size'] == 'medium') { echo ' selected="selected"';}
		echo '>' . __('Medium', 'mr_social_sharing_toolkit') . '</option>
								<option value="large"';
		if ($this -> options['mr_social_sharing_attachment_image_size'] == 'large') { echo ' selected="selected"';}
		echo '>' . __('Large', 'mr_social_sharing_toolkit') . '</option>
								<option value="full"';
		if ($this -> options['mr_social_sharing_attachment_image_size'] == 'full') { echo ' selected="selected"';}
		echo '>' . __('Full', 'mr_social_sharing_toolkit') . '</option>
							</select></label><br/><br/>';

		// Bitly
		echo 			'<h4>' . __('Bitly', 'mr_social_sharing_toolkit') . '</h4>
						<p>' . __('Use Bitly url shortening for the tweet button', 'mr_social_sharing_toolkit') . '</p>
						<label for="mr_social_sharing_bitly_enable" class="check"><input type="checkbox" name="mr_social_sharing_bitly[enable]" id="mr_social_sharing_bitly_enable"';
		if (isset($this -> options['mr_social_sharing_bitly']['enable']) && $this -> options['mr_social_sharing_bitly']['enable'] == 1) { echo ' checked="checked"';
		}
		echo ' value="1" /> ' . __("Enable Bitly URL shortening", 'mr_social_sharing_toolkit') . '</label></br/>
						<label for="mr_social_sharing_bitly_username">' . __('Your bitly Username', 'mr_social_sharing_toolkit') . '</label>
						<input type="text" name="mr_social_sharing_bitly[username]" id="mr_social_sharing_bitly_username" value="' . $this -> options['mr_social_sharing_bitly']['username'] . '"/></br/>
						<label for="mr_social_sharing_bitly_key">' . __('Your bitly API Key', 'mr_social_sharing_toolkit') . '</label>
						<input type="password" name="mr_social_sharing_bitly[key]" id="mr_social_sharing_bitly_key" value="' . $this -> options['mr_social_sharing_bitly']['key'] . '"/>
						<h4>' . __('Pinterest', 'mr_social_sharing_toolkit') . '</h4>
						<label for="mr_social_sharing_pinterest_default_image">' . __('Default image URL', 'mr_social_sharing_toolkit') . '</label>
						<input type="text" name="mr_social_sharing_pinterest[default_image]" id="mr_social_sharing_pinterest_default_image" value="' . $this -> options['mr_social_sharing_pinterest']['default_image'] . '"/></br/>
						<p><span class="description">' . __('You can specify a link to an image you would like to use for Pinterest pins when no image is available', 'mr_social_sharing_toolkit') . '</span></p>
						<label for="mr_social_sharing_pinterest_fixed_image" class="check"><input type="checkbox" name="mr_social_sharing_pinterest[fixed_image]" id="mr_social_sharing_pinterest_fixed_image"';
		if (isset($this -> options['mr_social_sharing_pinterest']['fixed_image']) && $this -> options['mr_social_sharing_pinterest']['fixed_image'] == 1) { echo ' checked="checked"';
		}
		echo ' value="1" /> ' . __("Always use the default image", 'mr_social_sharing_toolkit') . '</label><br/>
						<p><span class="description">' . __("Check this box to always display the default image with your Pins", 'mr_social_sharing_toolkit') . '</span></p>
						<h4>' . __('OpenGraph', 'mr_social_sharing_toolkit') . '</h4>
						<p>' . __('Include Open Graph tags', 'mr_social_sharing_toolkit') . '</p>
						<label for="mr_social_sharing_opengraph_enable" class="check"><input type="checkbox" name="mr_social_sharing_opengraph[enable]" id="mr_social_sharing_opengraph_enable"';
		if (isset($this -> options['mr_social_sharing_opengraph']['enable']) && $this -> options['mr_social_sharing_opengraph']['enable'] == 1) { echo ' checked="checked"';
		}
		echo ' value="1" /> ' . __("Enable Open Graph", 'mr_social_sharing_toolkit') . '</label><br/>
						<label for="mr_social_sharing_opengraph_default_image">' . __('Default image URL', 'mr_social_sharing_toolkit') . '</label>
						<input type="text" name="mr_social_sharing_opengraph[default_image]" id="mr_social_sharing_opengraph_default_image" value="' . $this -> options['mr_social_sharing_opengraph']['default_image'] . '"/></br/>
						<p><span class="description">' . __('You can specify a link to an image you would like to include in your likes and shares', 'mr_social_sharing_toolkit') . '</span></p>
						<label for="mr_social_sharing_opengraph_fixed_image" class="check"><input type="checkbox" name="mr_social_sharing_opengraph[fixed_image]" id="mr_social_sharing_opengraph_fixed_image"';
		if (isset($this -> options['mr_social_sharing_opengraph']['fixed_image']) && $this -> options['mr_social_sharing_opengraph']['fixed_image'] == 1) { echo ' checked="checked"';
		}
		echo ' value="1" /> ' . __("Always use the default image", 'mr_social_sharing_toolkit') . '</label><br/>
						<p><span class="description">' . __("Check this box to always display the default image with your shared content", 'mr_social_sharing_toolkit') . '</span></p>
					</div>
					<div id="tab_6" class="mr_social_sharing_networks">
						<h3>' . __('Custom CSS', 'mr_social_sharing_toolkit') . '</h3>
						<p>' . __('Enter your custom styles or CSS fixes here', 'mr_social_sharing_toolkit') . '</p>
						<textarea name="mr_social_sharing_custom_css" id="mr_social_sharing_custom_css">' . $this -> options['mr_social_sharing_custom_css'] . '</textarea>
					</div>
				</div>
					<p class="submit">
						<input type="submit" name="Submit" class="button-primary" value="' . esc_attr__('Save Changes') . '" />
					</p>
				</form>
			</div>';
	}

	function showListAdmin($buttons, $button_type = '') {
		echo '
		<ul id="mr_social_sharing_' . $button_type . 'networks">';
		foreach ($this->options['mr_social_sharing_'.$button_type.'button_order'] as $button) {
			if (array_key_exists($button, $buttons)) {
				echo '
							<li>
								<span class="mr_social_sharing_options"><img src="' . plugins_url('/images/icons_small/' . $buttons[$button]['icon'] . '.png', __FILE__) . '" title="' . $buttons[$button]['title'] . '" alt="' . $buttons[$button]['title'] . '"/>
								<label for="mr_social_sharing_' . $button_type . $button . '"><input type="checkbox" name="mr_social_sharing_' . $button_type . 'buttons[' . $button . '][enable]" id="mr_social_sharing_' . $button_type . $button . '"';
				if (isset($this -> options['mr_social_sharing_' . $button_type . 'buttons'][$button]['enable']) && $this -> options['mr_social_sharing_' . $button_type . 'buttons'][$button]['enable'] == 1) { echo ' checked="checked"';
				}
				if ($button == 'linksalpha') {
					echo ' checked="checked" disabled="disabled"';
				}
				echo ' value="1" />' . $buttons[$button]['title'] . '</label>
								<img class="right" src="' . plugins_url('/images/move.png', __FILE__) . '" title="' . __('Change button order', 'mr_social_sharing_toolkit') . '" alt="' . __('Change button order', 'mr_social_sharing_toolkit') . '"/>';
				if (is_array($buttons[$button]['types']) && $buttons[$button]['types'][0] != '') {
					if (count($buttons[$button]['types']) == 1) {
						echo '
								<input type="hidden" name="mr_social_sharing_' . $button_type . 'buttons[' . $button . '][type]" value="' . $buttons[$button]['types'][0] . '"/>';
					} else {
						echo '
								<select name="mr_social_sharing_' . $button_type . 'buttons[' . $button . '][type]" id="mr_social_sharing_' . $button_type . $button . '_type" class="mr_social_sharing_type_select">';
						foreach ($buttons[$button]['types'] as $type) {
							echo '<option value="' . $type . '"';
							if ($this -> options['mr_social_sharing_' . $button_type . 'buttons'][$button]['type'] == $type) { echo ' selected="selected"';
							}
							echo '>' . $this -> types[$type] . '</option>';
						}
						echo '</select>';
					}
				} else {
					echo '
								<input type="hidden" name="mr_social_sharing_' . $button_type . 'buttons[' . $button . '][type]" value=""/>';
				}
				if (array_key_exists('id', $buttons[$button])) {
					if (is_array($buttons[$button]['id']) && array_key_exists('label', $buttons[$button]['id']) && array_key_exists('options', $buttons[$button]['id']) && is_array($buttons[$button]['id']['options'])) {
						echo '
								<select name="mr_social_sharing_' . $button_type . 'buttons[' . $button . '][id]" id="mr_social_sharing_' . $button_type . $button . '_id">';
						foreach ($buttons[$button]['id']['options'] as $option) {
							if (array_key_exists('label', $option) && array_key_exists('value', $option)) {
								echo '<option value="' . $option['value'] . '"';
								if ($option['value'] == $this -> options['mr_social_sharing_' . $button_type . 'buttons'][$button]['id']) {
									echo ' selected="selected"';
								}
								echo '>' . $option['label'] . '</option>';
							}
						}
						echo '</select>';
					} else {
						echo '
								<input type="text" class="text" name="mr_social_sharing_' . $button_type . 'buttons[' . $button . '][id]" id="mr_social_sharing_' . $button_type . $button . '_id" value="' . $this -> options['mr_social_sharing_' . $button_type . 'buttons'][$button]['id'] . '"/>
								<label for="mr_social_sharing_' . $button_type . $button . '_id" class="text">' . $buttons[$button]['id'] . '</label>';
					}
				}
				echo '
								<input type="hidden" name="mr_social_sharing_' . $button_type . 'button_order[]" value="' . $button . '"/></span>
								<span class="mr_social_sharing_custom"><input type="text" class="text" name="mr_social_sharing_' . $button_type . 'buttons[' . $button . '][icon]" id="mr_social_sharing_' . $button_type . $button . '_icon" value="' . $this -> options['mr_social_sharing_' . $button_type . 'buttons'][$button]['icon'] . '"/>
								<label for="mr_social_sharing_' . $button_type . $button . '_icon" class="text">' . __('Custom icon url', 'mr_social_sharing_toolkit') . '</label>

								<input type="text" class="text" name="mr_social_sharing_' . $button_type . 'buttons[' . $button . '][text]" id="mr_social_sharing_' . $button_type . $button . '_text" value="' . stripslashes($this -> options['mr_social_sharing_' . $button_type . 'buttons'][$button]['text']) . '"/>
								<label for="mr_social_sharing_' . $button_type . $button . '_text" class="text">' . __('Custom text', 'mr_social_sharing_toolkit') . '</label></span>


							</li>';
			}
		}
		echo '
						</ul>
						<p>
							' . __('Choose button orientation horizontal to display the buttons side by side, vertical will place them below each other. You can also select an alignment to better suit your theme.', 'mr_social_sharing_toolkit') . '
						</p>
						<label for="mr_social_sharing_' . $button_type . 'display">' . __('Button orientation', 'mr_social_sharing_toolkit') . '</label>
						<select name="mr_social_sharing_' . $button_type . 'display" id="mr_social_sharing_' . $button_type . 'display">
							<option value="span"';
		if ($this -> options['mr_social_sharing_' . $button_type . 'display'] == 'span') { echo ' selected="selected"';
		}
		echo '>' . __('Horizontal', 'mr_social_sharing_toolkit') . '</option>
							<option value="div"';
		if ($this -> options['mr_social_sharing_' . $button_type . 'display'] == 'div') { echo ' selected="selected"';
		}
		echo '>' . __('Vertical', 'mr_social_sharing_toolkit') . '</option>
						</select><br/>
						<label for="mr_social_sharing_' . $button_type . 'align">' . __('Button alignment', 'mr_social_sharing_toolkit') . '</label>
						<select name="mr_social_sharing_' . $button_type . 'align" id="mr_social_sharing_' . $button_type . 'align">
							<option value=""';
		if ($this -> options['mr_social_sharing_' . $button_type . 'align'] == '') { echo ' selected="selected"';
		}
		echo '>' . __('Align to bottom', 'mr_social_sharing_toolkit') . '</option>
							<option value="_top"';
		if ($this -> options['mr_social_sharing_' . $button_type . 'align'] == '_top') { echo ' selected="selected"';
		}
		echo '>' . __('Align to top', 'mr_social_sharing_toolkit') . '</option>
						</select>';
	}

	/* Output functions */

	function print_opengraph() {
		echo '<!-- Open Graph tags provided by Social Sharing Toolkit v2.1.2 -->
		<meta property="og:locale" content="' . str_replace('-', '_', get_bloginfo('language')) . '"/>';
		if (is_single() || is_page()) {
			$excerpt = get_the_excerpt();
			echo '
		<meta property="og:title" content="' . trim(wp_title('', 0)) . '"/>';
			if ($excerpt != '') {
				echo '
		<meta property="og:description" content="' . $excerpt . '"/>';
			} else {
				echo '
		<meta property="og:description" content="' . get_bloginfo('description') . '"/>';
			}
			echo '
		<meta property="og:url" content="' . get_permalink() . '"/>
		<meta property="og:type" content="article"/>';
		} else {
			echo '
		<meta property="og:title" content="' . get_bloginfo('name') . '"/>
		<meta property="og:description" content="' . get_bloginfo('description') . '"/>
		<meta property="og:url" content="' . get_home_url() . '"/>
		<meta property="og:type" content="website"/>';
		}
		echo '
		<meta property="og:site_name" content="' . get_bloginfo('name') . '"/>';
		if (isset($this -> options['mr_social_sharing_opengraph']['fixed_image']) && $this -> options['mr_social_sharing_opengraph']['fixed_image']) {
			if ($this -> options['mr_social_sharing_opengraph']['default_image'] != '') {
				echo '
		<meta property="og:image" content="' . $this -> options['mr_social_sharing_opengraph']['default_image'] . '"/>';
			}
		} else {
			$media = '';
			if (current_theme_supports('post-thumbnails')) {
				if(isset($this->options['mr_social_sharing_attachment_image_size'])) {
					$image_size = $this->options['mr_social_sharing_attachment_image_size'];
				} else {
					$image_size = 'thumbnail';
				}
				if ($media = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $image_size)) {
					if (is_array($media)) {
						$media = $media[0];
					}
				}
			}
			if ($media == '') {
				$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_the_content(), $matches);
				if (!empty($matches[1])) {
					$img = $matches[1][0];
					if ($img != '') {
						$media = $img;
					}
				}
			}
			if ($media != '') {
				echo '
					<meta property="og:image" content="' . $media . '"/>';
			} elseif ($this -> options['mr_social_sharing_opengraph']['default_image'] != '') {
				echo '
					<meta property="og:image" content="' . $this -> options['mr_social_sharing_opengraph']['default_image'] . '"/>';
			}
		}
	}

	function prepare_styles() {
		wp_enqueue_style('mr_social_sharing', plugins_url('/style_2.1.2.css', __FILE__));
		$upload = wp_upload_dir();
		if (file_exists($upload['basedir'] . '/social_sharing_custom.css') && $this -> options['mr_social_sharing_custom_css'] != '') {
			wp_enqueue_style('mr_social_sharing_custom', $upload['baseurl'] . '/social_sharing_custom.css');
		}
	}

	function prepare_scripts() {
		if ($this -> options['mr_social_sharing_no_follow'] == 1) {
			if ($this -> options['mr_social_sharing_js_footer'] == 1) {
				wp_enqueue_script('mr_social_sharing', plugins_url('/script_no_follow_2.1.2.js', __FILE__), array('jquery'), false, true);
			} else {
				wp_enqueue_script('mr_social_sharing', plugins_url('/script_no_follow_2.1.2.js', __FILE__), array('jquery'));
			}
		} else {
			if ($this -> options['mr_social_sharing_js_footer'] == 1) {
				wp_enqueue_script('mr_social_sharing', plugins_url('/script_2.1.2.js', __FILE__), array('jquery'), false, true);
			} else {
				wp_enqueue_script('mr_social_sharing', plugins_url('/script_2.1.2.js', __FILE__), array('jquery'));
			}
		}
		$this -> load_scripts();
		if (is_array($this -> scripts) && count($this -> scripts) > 0) {
			foreach ($this->scripts as $script) {
				wp_enqueue_script($script['name'], $script['src'], array(), false, $script['in_footer']);
			}
		}
	}

	function load_scripts() {
		$types = array('', 'shortcode_', 'widget_');
		foreach ($types as $type) {
			foreach ($this->options['mr_social_sharing_'.$type.'button_order'] as $button) {
				if (isset($this -> options['mr_social_sharing_' . $type . 'buttons'][$button]['enable']) && $this -> options['mr_social_sharing_' . $type . 'buttons'][$button]['enable'] == 1) {
					$id = (array_key_exists('id', $this -> options['mr_social_sharing_' . $type . 'buttons'][$button])) ? $this -> options['mr_social_sharing_' . $type . 'buttons'][$button]['id'] : '';
					$text = (array_key_exists('text', $this -> options['mr_social_sharing_' . $type . 'buttons'][$button])) ? stripslashes($this -> options['mr_social_sharing_' . $type . 'buttons'][$button]['text']) : '';
					$icon = (array_key_exists('icon', $this -> options['mr_social_sharing_' . $type . 'buttons'][$button])) ? $this -> options['mr_social_sharing_' . $type . 'buttons'][$button]['icon'] : '';
					foreach ($this->buttons as $tmp) {
						if (method_exists($tmp, $button)) {
							$tmp -> $button('', '', $this -> options['mr_social_sharing_' . $type . 'buttons'][$button]['type'], $id, '', '', $text, $icon);
							if (is_array($tmp -> get_enqueued_scripts())) {
								$this -> scripts = array_merge($this -> scripts, $tmp -> get_enqueued_scripts());
							}
						}
					}
				}
			}
		}
		foreach ($this->options['mr_social_sharing_follow_button_order'] as $button) {
			if (isset($this -> options['mr_social_sharing_follow_buttons'][$button]['enable']) && $this -> options['mr_social_sharing_follow_buttons'][$button]['enable'] == 1) {
				$id = (array_key_exists('id', $this -> options['mr_social_sharing_follow_buttons'][$button])) ? $this -> options['mr_social_sharing_follow_buttons'][$button]['id'] : '';
				$text = (array_key_exists('text', $this -> options['mr_social_sharing_follow_buttons'][$button])) ? stripslashes($this -> options['mr_social_sharing_follow_buttons'][$button]['text']) : '';
				$icon = (array_key_exists('icon', $this -> options['mr_social_sharing_follow_buttons'][$button])) ? $this -> options['mr_social_sharing_follow_buttons'][$button]['icon'] : '';
				foreach ($this->buttons as $tmp) {
					if (method_exists($tmp, $button)) {
						$tmp -> $button($this -> options['mr_social_sharing_follow_buttons'][$button]['type'], $id, $text, $icon);
						if (is_array($tmp -> get_enqueued_scripts())) {
							$this -> scripts = array_merge($this -> scripts, $tmp -> get_enqueued_scripts());
						}
					}
				}
			}
		}
	}

	function create_bookmarks($url = '', $title = '', $type = '', $media = '', $description = '') {
		$url = trim($url);
		$title = trim($title);
		$title = $this->prepare_text($title);
		if($description) {
			$description = $this->prepare_text($description);
		}
		if ($url == '') {
			$url = 'http://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		}
		$count_bookmarks = 0;
		$bookmarks = '
				<div class="mr_social_sharing_wrapper">
				<!-- Social Sharing Toolkit v2.2 -->';
		foreach ($this->options['mr_social_sharing_'.$type.'button_order'] as $button) {
			if (isset($this -> options['mr_social_sharing_' . $type . 'buttons'][$button]['enable']) && $this -> options['mr_social_sharing_' . $type . 'buttons'][$button]['enable'] == 1) {
				$id = (array_key_exists('id', $this -> options['mr_social_sharing_' . $type . 'buttons'][$button])) ? $this -> options['mr_social_sharing_' . $type . 'buttons'][$button]['id'] : '';
				$text = (array_key_exists('text', $this -> options['mr_social_sharing_' . $type . 'buttons'][$button])) ? stripslashes($this -> options['mr_social_sharing_' . $type . 'buttons'][$button]['text']) : '';
				$icon = (array_key_exists('icon', $this -> options['mr_social_sharing_' . $type . 'buttons'][$button])) ? $this -> options['mr_social_sharing_' . $type . 'buttons'][$button]['icon'] : '';
				$bookmarks .= $this -> get_bookmark_button($button, $url, $title, $this -> options['mr_social_sharing_' . $type . 'buttons'][$button]['type'], $this -> options['mr_social_sharing_' . $type . 'display'], $this -> options['mr_social_sharing_' . $type . 'align'], $id, $media, $description, $text, $icon);
				$count_bookmarks++;
			}
		}
		if ($count_bookmarks) {
			$button = 'linksalpha';
			$type = (array_key_exists('icon', $this -> options['mr_social_sharing_' . $type . 'buttons'][$button])) ? $this -> options['mr_social_sharing_' . $type . 'buttons'][$button]['type'] : '';
			$la_button = $this -> get_bookmark_button($button, $url, $title, $type, $this -> options['mr_social_sharing_display'], '', '', $media, $description);
			$bookmarks .= $la_button;
		}
		$bookmarks .= '</div>';
		$bookmarks = str_replace('<div class="mr_social_sharing_wrapper"></div>', '', $bookmarks);
		$this -> count++;
		return $bookmarks;
	}

	function create_followers() {
		$followers = '
				<div class="mr_social_sharing_wrapper">
				<!-- Social Sharing Toolkit v2.2 -->';
		foreach ($this->options['mr_social_sharing_follow_button_order'] as $button) {
			if (isset($this -> options['mr_social_sharing_follow_buttons'][$button]['id']) && $this -> options['mr_social_sharing_follow_buttons'][$button]['id']) {
				$id = (array_key_exists('id', $this -> options['mr_social_sharing_follow_buttons'][$button])) ? $this -> options['mr_social_sharing_follow_buttons'][$button]['id'] : '';
				$text = (array_key_exists('text', $this -> options['mr_social_sharing_follow_buttons'][$button])) ? stripslashes($this -> options['mr_social_sharing_follow_buttons'][$button]['text']) : '';
				$icon = (array_key_exists('icon', $this -> options['mr_social_sharing_follow_buttons'][$button])) ? $this -> options['mr_social_sharing_follow_buttons'][$button]['icon'] : '';
				$followers .= $this -> get_follow_button($button, $this -> options['mr_social_sharing_follow_buttons'][$button]['type'], $this -> options['mr_social_sharing_follow_display'], $this -> options['mr_social_sharing_follow_align'], $id, $text, $icon);
			}
		}
		$followers .= '</div>';
		$followers = str_replace('<div class="mr_social_sharing_wrapper"></div>', '', $followers);
		return $followers;
	}

	function get_bookmark_button($button, $url, $title, $type, $display = 'span', $align = '', $id = '', $media = '', $description = '', $text = '', $icon = '') {
		$retval = '';
		if ($button == 'ln_break_1' || $button == 'ln_break_2' || $button == 'ln_break_3') {
			$retval = '</div><div class="mr_social_sharing_wrapper">';
		} else {
			foreach ($this->buttons as $tmp) {
				if (method_exists($tmp, $button)) {
					$retval = '<' . $display . ' class="mr_social_sharing' . $align . '">' . $tmp -> $button($url, $title, $type, $id, $media, $description, $text, $icon) . '</' . $display . '>';
				}
			}
		}
		return $retval;
	}

	function get_follow_button($button, $type, $display = 'span', $align = '', $id = '', $text = '', $icon = '') {
		$retval = '';
		if ($button == 'ln_break_1' || $button == 'ln_break_2' || $button == 'ln_break_3') {
			$retval = '</div><div class="mr_social_sharing_wrapper">';
		} else {
			foreach ($this->buttons as $tmp) {
				if (method_exists($tmp, $button)) {
					$retval = '<' . $display . ' class="mr_social_sharing' . $align . '">' . $tmp -> $button($type, $id, $text, $icon) . '</' . $display . '>';
				}
			}
		}
		return $retval;
	}

	function should_print_opengraph() {
		if (isset($this -> options['mr_social_sharing_opengraph']['enable']) && $this -> options['mr_social_sharing_opengraph']['enable'] == 1) {
			return true;
		}
	}

	function should_share_content() {
		if ($this -> options['mr_social_sharing_position'] != 'none') {
			return true;
		}
		return false;
	}

	function should_share_excerpt() {
		if ($this -> options['mr_social_sharing_position'] != 'none' && $this -> options['mr_social_sharing_include_excerpts'] == 1) {
			return true;
		}
		return false;
	}

	function share($content) {
		if (is_feed()) {
			return $content;
		} else {
			$media = '';
			if ($this -> options['mr_social_sharing_pinterest']['default_image'] != '' && $this -> options['mr_social_sharing_pinterest']['fixed_image'] == 1) {
				$media = $this -> options['mr_social_sharing_pinterest']['default_image'];
			} else {
				if (current_theme_supports('post-thumbnails')) {
					if(isset($this->options['mr_social_sharing_attachment_image_size'])) {
						$image_size = $this->options['mr_social_sharing_attachment_image_size'];
					} else {
						$image_size = 'thumbnail';
					}
					if ($media = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $image_size)) {
						if (is_array($media)) {
							$media = $media[0];
						}
					}
				}
				if ($media == '') {
					$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
					if (!empty($matches[1])) {
						$img = $matches[1][0];
						if ($img != '') {
							$media = $img;
						}
					}
				}
				if ($media == '' && $this -> options['mr_social_sharing_pinterest']['default_image'] != '') {
					$media = $this -> options['mr_social_sharing_pinterest']['default_image'];
				}
			}
			$type = get_post_type();
			if (in_array($type, $this -> options['mr_social_sharing_types']) && ((is_single() || $this -> options['mr_social_sharing_include_excerpts'] == 1) || $type == 'page')) {
				if ($this -> options['mr_social_sharing_position'] == 'top') {
					$bookmarks = $this -> create_bookmarks(get_permalink(), the_title('', '', false), '', $media, get_the_content());
					$content = $bookmarks . $content;
				}
				if ($this -> options['mr_social_sharing_position'] == 'bottom') {
					$bookmarks = $this -> create_bookmarks(get_permalink(), the_title('', '', false), '', $media, get_the_content());
					$content .= $bookmarks;
				}
				if ($this -> options['mr_social_sharing_position'] == 'both') {
					$bookmarks = $this -> create_bookmarks(get_permalink(), the_title('', '', false), '', $media, get_the_content());
					$bookmarks2 = $this -> create_bookmarks(get_permalink(), the_title('', '', false), '', $media, get_the_content());
					$content = $bookmarks . $content . $bookmarks2;
				}
			}
			return $content;
		}
	}

	function share_excerpt($content) {
		if (is_feed()) {
			return $content;
		} else {
			$media = '';
			if ($this -> options['mr_social_sharing_pinterest']['default_image'] != '' && $this -> options['mr_social_sharing_pinterest']['fixed_image'] == 1) {
				$media = $this -> options['mr_social_sharing_pinterest']['default_image'];
			} else {
				if (current_theme_supports('post-thumbnails')) {
					if(isset($this->options['mr_social_sharing_attachment_image_size'])) {
						$image_size = $this->options['mr_social_sharing_attachment_image_size'];
					} else {
						$image_size = 'thumbnail';
					}
					if ($media = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $image_size)) {
						if (is_array($media)) {
							$media = $media[0];
						}
					}
				}
				if ($media == '') {
					$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
					if (!empty($matches[1])) {
						$img = $matches[1][0];
						if ($img != '') {
							$media = $img;
						}
					}
				}
				if ($media == '' && $this -> options['mr_social_sharing_pinterest']['default_image'] != '') {
					$media = $this -> options['mr_social_sharing_pinterest']['default_image'];
				}
			}
			$type = get_post_type();
			if (in_array($type, $this -> options['mr_social_sharing_types'])) {
				if ($this -> options['mr_social_sharing_position'] == 'top') {
					$bookmarks = $this -> create_bookmarks(get_permalink(), the_title('', '', false), '', $media, get_the_content());
					$content = $bookmarks . $content;
				}
				if ($this -> options['mr_social_sharing_position'] == 'bottom') {
					$bookmarks = $this -> create_bookmarks(get_permalink(), the_title('', '', false), '', $media, get_the_content());
					$content .= $bookmarks;
				}
				if ($this -> options['mr_social_sharing_position'] == 'both') {
					$bookmarks = $this -> create_bookmarks(get_permalink(), the_title('', '', false), '', $media, get_the_content());
					$content = $bookmarks . $content . $bookmarks;
				}
			}
			return $content;
		}
	}

	function share_shortcode($atts) {
		extract(shortcode_atts(array('url' => '', 'title' => ''), $atts));
		if (is_feed()) {
			return '';
		} else {
			$media = '';
			if ($this -> options['mr_social_sharing_pinterest']['default_image'] != '' && $this -> options['mr_social_sharing_pinterest']['fixed_image'] == 1) {
				$media = $this -> options['mr_social_sharing_pinterest']['default_image'];
			} else {
				if (current_theme_supports('post-thumbnails')) {
					if(isset($this->options['mr_social_sharing_attachment_image_size'])) {
						$image_size = $this->options['mr_social_sharing_attachment_image_size'];
					} else {
						$image_size = 'thumbnail';
					}
					if ($media = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $image_size)) {
						if (is_array($media)) {
							$media = $media[0];
						}
					}
				}
				if ($media == '') {
					$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_the_content(), $matches);
					if (!empty($matches[1])) {
						$img = $matches[1][0];
						if ($img != '') {
							$media = $img;
						}
					}
				}
				if ($media == '' && $this -> options['mr_social_sharing_pinterest']['default_image'] != '') {
					$media = $this -> options['mr_social_sharing_pinterest']['default_image'];
				}
			}
			$type = get_post_type();
			$bookmarks = '';
			if ((is_single() || $type == 'page') || $this -> options['mr_social_sharing_include_excerpts'] == 1) {
				$url = ($url == '') ? get_permalink() : $url;
				$title = ($title == '') ? the_title('', '', false) : $title;
				$bookmarks = $this -> create_bookmarks($url, $title, 'shortcode_', $media, get_the_content());
			}
			return $bookmarks;
		}
	}

	function should_linkify_content() {
		if ($this -> options['mr_social_sharing_linkify_content'] == 1) {
			return true;
		}
		return false;
	}

	function should_linkify_comments() {
		if ($this -> options['mr_social_sharing_linkify_comments'] == 1) {
			return true;
		}
		return false;
	}

	function linkify($content) {
		if ($this -> options['mr_social_sharing_linkify_new'] == 1) {
			if ($this -> options['mr_social_sharing_twitter_handles'] == 1) {
				$content = preg_replace("/(^|\s)+(@([a-zA-Z0-9_-]{1,15}))(\.*[^|\n|\r|\t|\s|\<|\&]*)/i", "$1<a href=\"http://twitter.com/$3\" target=\"_BLANK\">$2</a>$4", $content);
			}
			if ($this -> options['mr_social_sharing_twitter_hashtags'] == 1) {
				$content = preg_replace("/(^|\s)+((?:(?<!&))#([a-zA-Z0-9]+^[-|;]))([^|\n|\r|\t|\s|\.|\<|\&]*)/i", "$1<a href=\"http://twitter.com/search/$3\" target=\"_BLANK\">$2</a>$4", $content);
			}
		} else {
			if ($this -> options['mr_social_sharing_twitter_handles'] == 1) {
				$content = preg_replace("/(^|\s)+(@([a-zA-Z0-9_-]{1,15}))(\.*[^|\n|\r|\t|\s|\<|\&]*)/i", "$1<a href=\"http://twitter.com/$3\">$2</a>$4", $content);
			}
			if ($this -> options['mr_social_sharing_twitter_hashtags'] == 1) {
				$content = preg_replace("/(^|\s)+((?:(?<!&))#([a-zA-Z0-9]+^[-|;]))([^|\n|\r|\t|\s|\.|\<|\&]*)/i", "$1<a href=\"http://twitter.com/search/$3\">$2</a>$4", $content);
			}
		}
		return $content;
	}

	function prepare_text($text) {
		$text = stripslashes($text);
		$text = strip_tags($text);
		$text = preg_replace("/\[.*?\]/", '', $text);
		$text = preg_replace('/([\n \t\r]+)/', ' ', $text);
		$text = preg_replace('/( +)/', ' ', $text);
		$text = preg_replace('/\s\s+/', ' ', $text);
		$text = $this->prepare_string($text, 310);
		$text = $this->smart_truncate($text, 300);
		$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
		$text = trim($text);
		return $text;
	}

	function smart_truncate($string, $required_length) {
		$parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
		$parts_count = count($parts);
		$length = 0;
		$last_part = 0;
		for (; $last_part < $parts_count; ++$last_part) {
			$length += strlen($parts[$last_part]);
			if ($length > $required_length) {
				break;
			}
		}
		return implode(array_slice($parts, 0, $last_part));
	}

	function prepare_string($string, $string_length) {
		$final_string = '';
		$utf8marker = chr(128);
		$count = 0;
		while (isset($string{$count})) {
			if ($string{$count} >= $utf8marker) {
				$parsechar = substr($string, $count, 2);
				$count += 2;
			} else {
				$parsechar = $string{$count};
				$count++;
			}
			if ($count > $string_length) {
				return $final_string;
			}
			$final_string = $final_string . $parsechar;
		}
		return $final_string;
	}

}

require 'includes/class.button.php';
$buttons = glob(dirname(__FILE__) . '/includes/buttons/button.*.php');
if (is_array($buttons) && count($buttons) > 0) {
	foreach ($buttons as $button) {
		include  str_replace(dirname(__FILE__) . '/', '', $button);
	}
}
unset($buttons);

define('MR_Social_Sharing_Toolkit_Admin_URL', "options-general.php?page=mr_social_sharing");
function mr_social_sharing_actlinks( $links ) {
    $settings_link = '<a href="'.MR_Social_Sharing_Toolkit_Admin_URL.'">'.__('Settings').'</a>';
    array_unshift( $links, $settings_link );
    return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'mr_social_sharing_actlinks' );

$MR_Social_Sharing_Toolkit = new MR_Social_Sharing_Toolkit();
add_action('admin_menu', array($MR_Social_Sharing_Toolkit, 'plugin_menu'));
if ($MR_Social_Sharing_Toolkit -> should_print_opengraph()) {
	add_action('wp_head', array($MR_Social_Sharing_Toolkit, 'print_opengraph'), 1);
}
if ($MR_Social_Sharing_Toolkit -> should_linkify_content()) {
	add_filter('the_content', array($MR_Social_Sharing_Toolkit, 'linkify'));
}
if ($MR_Social_Sharing_Toolkit -> should_linkify_comments()) {
	add_filter('comment_text', array($MR_Social_Sharing_Toolkit, 'linkify'));
}
if ($MR_Social_Sharing_Toolkit -> should_share_excerpt()) {
	add_filter('the_excerpt', array($MR_Social_Sharing_Toolkit, 'share_excerpt'));
}
if ($MR_Social_Sharing_Toolkit -> should_share_content()) {
	add_filter('the_content', array($MR_Social_Sharing_Toolkit, 'share'));
}
add_shortcode('social_share', array($MR_Social_Sharing_Toolkit, 'share_shortcode'), 15);
add_action('wp_print_styles', array($MR_Social_Sharing_Toolkit, 'prepare_styles'), 50);
add_action('wp_print_scripts', array($MR_Social_Sharing_Toolkit, 'prepare_scripts'), 50);
include 'includes/share.widget.php';
include 'includes/follow.widget.php';
?>