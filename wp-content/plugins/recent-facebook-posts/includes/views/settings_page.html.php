<div id="rfbp-options" class="wrap">

	<div class="rfbp-column" style="width:69%;">

		<h2>Recent Facebook Posts</h2>
		
		<?php if($api->has_error()) { ?>
		<div id="setting-error-settings_updated" class="settings-error error">
			<p>
				<strong>Error:</strong> <?php echo $api->get_error_message(); ?>
			</p>
		</div>
		<?php }

		if(isset($notice)) { ?>
		<div id="setting-error-settings_updated" class="updated settings-error"> 
			<p>
				<?php echo $notice; ?>
			</p>
		</div>
		<?php 
	}
	?>


	<form method="post" action="options.php">
		<?php settings_fields( 'rfb_settings_group' ); ?>

		<h3><?php _e('Facebook Settings', 'recent-facebook-posts'); ?></h3>
		<table class="form-table">
			<tr valign="top" <?php if(empty($opts['app_id'])) echo 'class="rfbp-row-error"'; ?>>
				<th scope="row"><label for="rfb_app_id"><?php _e('Facebook App ID/API Key', 'recent-facebook-posts'); ?></label></th>
				<td>
					<input type="text" class="widefat" placeholder="Eg: 123456789012345" id="rfb_app_id" name="rfb_settings[app_id]" value="<?php echo esc_attr($opts['app_id']); ?>" required />
					<small class="help"><a href="https://developers.facebook.com/apps">get from developers.facebook.com/apps</a></small>
				</td>
			</tr>

			<tr valign="top" <?php if(empty($opts['app_secret'])) echo 'class="rfbp-row-error"'; ?>>
				<th scope="row"><label for="rfb_app_secret"><?php _e('Facebook App Secret', 'recent-facebook-posts'); ?></label></th>
				<td>
					<input type="text" class="widefat" placeholder="Eg: 16vgrz4hk45wvh29k2puk45wvk2h29pu"  id="rfb_app_secret" name="rfb_settings[app_secret]" value="<?php echo esc_attr($opts['app_secret']); ?>" required />
					<small class="help"><a href="https://developers.facebook.com/apps">get from developers.facebook.com/apps</a></small>
				</td>
			</tr>

			<tr valign="top" <?php if(empty($opts['fb_id'])) echo 'class="rfbp-row-error"'; ?>>
				<th scope="row"><label for="rfb_fb_id"><?php _e('Facebook Page ID or Slug', 'recent-facebook-posts'); ?></label></th>
				<td>
					<input type="text" class="widefat" placeholder="Eg: DannyvanKootenCOM" id="rfb_fb_id" name="rfb_settings[fb_id]" value="<?php echo esc_attr($opts['fb_id']); ?>" required />
					<small><a target="_blank" href="http://findmyfacebookid.com/"><?php _e('Use this tool to find the numeric ID of the Facebook page you want to fetch posts from', 'recent-facebook-posts'); ?></a></small>
				</td>
			</tr>
		</table>

		<h3><?php _e('Appearance', 'recent-facebook-posts'); ?></h3>
		<table class="form-table">
			<tbody>
			<tr valign="top">
				<th scope="row"><label for="rfb_page_link_text"><?php _e('Link text', 'recent-facebook-posts'); ?></label></th>
				<td><input type="text" class="widefat" placeholder="Find us on Facebook" id="rfb_page_link_text" name="rfb_settings[page_link_text]" value="<?php echo esc_attr($opts['page_link_text']); ?>" /></td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="rfb_link_new_window"><?php _e('Open links in new window?', 'recent-facebook-posts'); ?></label></th>
				<td><input type="checkbox" id="rfb_link_new_window" name="rfb_settings[link_new_window]" value="1" <?php checked($opts['link_new_window'], 1); ?> /></td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="rfb_load_css"><?php _e('Load some default CSS?', 'recent-facebook-posts'); ?></label></th>
				<td><input type="checkbox" id="rfb_load_css" name="rfb_settings[load_css]" value="1" <?php checked($opts['load_css'], 1); ?> /></td>
			</tr>
			</tbody>
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="rfb_img_size"><?php _e('Image size', 'recent-facebook-posts'); ?></label></th>
					<td>
						<select class="widefat" id="rfb_img_size" name="rfb_settings[img_size]">
							<option value="dont_show" <?php if($opts['img_size'] == 'dont_show') { echo 'selected'; } ?>><?php _e("Don't show images", 'recent-facebook-posts'); ?></option>
							<option value="thumbnail" <?php if($opts['img_size'] == 'thumbnail') { echo 'selected'; } ?>><?php _e('Thumbnail', 'recent-facebook-posts'); ?></option>
							<option value="normal" <?php if($opts['img_size'] == 'normal') { echo 'selected'; } ?>><?php _e('Normal', 'recent-facebook-posts'); ?></option>
						</select>
					</td>
				</tr>
			</tbody>
			<tbody id="rfb_img_options" <?php if($opts['img_size'] == 'dont_show') echo 'style="display:none;"'; ?>>
				<tr valign="top">
					<th><?php _e('Image dimensions', 'recent-facebook-posts'); ?><br /><small>(in pixels)</small></th>
					<td>
						<label style="float:left; margin-right:20px; ">
							<?php _e('Max Width', 'recent-facebook-posts'); ?><br />
							<input type="number" min="0" max="1600" size="3" id="rfb_img_width" name="rfb_settings[img_width]" value="<?php echo esc_attr($opts['img_width']); ?>" /> 
						</label>
						<label style="float:left; margin-right:20px;">
							<?php _e('Max Height', 'recent-facebook-posts'); ?><br />
							<input type="number" min="0" max="1600" size="3" id="rfb_img_height" name="rfb_settings[img_height]" value="<?php echo esc_attr($opts['img_height']); ?>" />
						</label>
						<br />
						<small class="help"><?php _e('Leave empty for default sizing', 'recent-facebook-posts'); ?></small>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>

	</form>

	<?php if($ready) { ?>
	<h3>Facebook Posts Cache</h3>
	<p><?php __('Because fetching posts from Facebook is slow the posts are cached for <strong>30 minutes</strong>. You can manually renew the cache or test your configuration using the button below.', 'recent-facebook-posts'); ?></p>
	<p>
		<form action="<?php echo admin_url('options-general.php?page=rfbp'); ?>" method="post">
			<input type="hidden" name="renew_cache" value="1" />
			<input type="submit" class="button-primary" value="<?php _e('Renew Facebook Posts', 'recent-facebook-posts'); ?>" />
		</form>
	</p>
	<?php } ?>
</div>

<div class="rfbp-sidebar clearfix">

	<div class="rfbp-box rfbp-well">
		<h3>Donate $10, $20 or $50</h3>
		<p>I spent countless hours developing this plugin, offering support, chasing bugs, etc. If you like it, consider showing me a token of your appreciation.</p>

		<div class="rfbp-donate">
			<form class="donate" action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_donations">
				<input type="hidden" name="business" value="AP87UHXWPNBBU">
				<input type="hidden" name="lc" value="US">
				<input type="hidden" name="item_name" value="Danny van Kooten">
				<input type="hidden" name="item_number" value="Recent Facebook Posts">
				<input type="hidden" name="currency_code" value="USD">
				<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHosted">
				<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.paypalobjects.com/nl_NL/i/scr/pixel.gif" width="1" height="1">
			</form>
		</div>

		<p>Alternatively, you can: </p>
		<ul class="ul-square">
			<li><a href="http://wordpress.org/support/view/plugin-reviews/recent-facebook-posts?rate=5#postform" target="_blank">Give a &#9733;&#9733;&#9733;&#9733;&#9733; rating on WordPress.org</a></li>
			<li><a href="http://dannyvankooten.com/wordpress-plugins/recent-facebook-posts/" target="_blank">Blog about it and link to the plugin page</a></li>
			<li><a href="http://twitter.com/?status=I%20show%20recent%20facebook%20posts%20on%20my%20%23WordPress%20site%20using%20Recent%20Facebook%20Posts%20by%20%40DannyvanKooten%20-%20love%20it!%20http%3A%2F%2Fwordpress.org%2Fplugins%2Frecent-facebook-posts%2F" target="_blank">Tweet about Recent Facebook Posts</a></li>
			<li><a href="http://wordpress.org/plugins/recent-facebook-posts/#compatibility">Vote "works" on the WordPress.org plugin page</a></li>
		</ul>
	</div>

	<div class="rfbp-box">
		<h3>Looking for support?</h3>
		<p>Having trouble? Please use the <a href="http://wordpress.org/support/plugin/recent-facebook-posts">support forums</a> on WordPress.org.</p>
		<p>Take a close look at the <a href="http://wordpress.org/plugins/recent-facebook-posts/installation/">installation instructions</a> for help configuring the plugin and registering your own Facebook application (required).</p>
	</div>

	<div class="rfbp-box">
		<h3>Other Useful plugins</h3>
		<ul class="ul-square">
			<li><a href="http://wordpress.org/plugins/mailchimp-for-wp/">MailChimp for WordPress</a></li>
			<li><a href="http://wordpress.org/plugins/wysiwyg-widgets/">WYSIWYG Widgets</a>
				<li><a href="http://wordpress.org/plugins/newsletter-sign-up/">Newsletter Sign-Up</a></li>
			</ul>
		</div>

		<div class="rfbp-box">
			<h3>About the developer</h3>
			<p>I am <a href="http://dannyvankooten.com/">Danny</a>, a young Dutch Computer Science student. I seriously enjoy coding, eating, sleeping, playing (sports) and happy clients.</p>
			<p>I developed a few <a href="http://dannyvankooten.com/wordpress-plugins/">other WordPress plugins</a>, have a look.</p>
			<p><em>PS: Donations are much appreciated!</em></p>
		</div>
	</div>
</div>