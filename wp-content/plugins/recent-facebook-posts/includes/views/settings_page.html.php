<?php defined("ABSPATH") or exit; ?>
<div id="rfbp-options" class="wrap">
	<div class="rfbp-container">
		<div class="rfbp-column rfbp-primary">

			<h2>Recent Facebook Posts</h2>

			<?php if( isset( $notice ) ) { ?>
			<div id="setting-error-settings_updated" class="updated settings-error"> 
				<p>
					<?php echo trim( wp_kses_post( $notice ) ); ?>
				</p>
			</div>
			<?php } ?>


		<form method="post" action="options.php">
			<?php settings_fields( 'rfb_settings_group' ); ?>
	
			<div class="rfbp-facebook-settings rfbp-<?php echo ( rfbp_valid_config() ) ? 'valid' : 'invalid'; ?>">
				<h3><?php _e('Facebook Settings', 'recent-facebook-posts'); ?></h3>

				<?php if( ! rfbp_valid_config() ) { ?>
					<div class="rfbp-info">
						<p>
							<?php _e('This plugin needs a Facebook application to work.', 'recent-facebook-posts'); ?> 
							<?php _e('Please fill in the Facebook Settings fields after creating your application.', 'recent-facebook-posts'); ?>
						</p>
						<p>
							<?php printf(__('Not sure how to proceed? Please take a close look at the <a href="%s">installation instructions</a>.', 'recent-facebook-posts'), 'https://wordpress.org/plugins/recent-facebook-posts/installation/' ); ?>
						</p>
					</div>
				<?php } ?>

				<table class="form-table">
					<tr valign="top" <?php if( empty( $opts['app_id'] ) ) echo 'class="rfbp-row-error"'; ?>>
						<th scope="row">
							<label for="rfb_app_id"><?php _e('Facebook App ID/API Key', 'recent-facebook-posts'); ?></label>
						</th>
						<td>
							<input type="text" class="widefat" placeholder="Eg: 123456789012345" id="rfb_app_id" name="rfb_settings[app_id]" value="<?php echo esc_attr( $opts['app_id'] ); ?>" required />
							<p class="help"><?php printf( __( 'Get your App ID from %s.', 'recent-facebook-posts' ), '<a href="https://developers.facebook.com/apps">developers.facebook.com/apps</a>' ); ?></p>
						</td>
					</tr>

					<tr valign="top" <?php if( empty( $opts['app_secret'] ) ) echo 'class="rfbp-row-error"'; ?>>
						<th scope="row"><label for="rfb_app_secret"><?php _e('Facebook App Secret', 'recent-facebook-posts'); ?></label></th>
						<td>
							<input type="text" class="widefat" placeholder="Eg: 16vgrz4hk45wvh29k2puk45wvk2h29pu"  id="rfb_app_secret" name="rfb_settings[app_secret]" value="<?php echo esc_attr( $opts['app_secret'] ); ?>" required />
							<p class="help"><?php printf( __( 'Get your App Secret from %s.', 'recent-facebook-posts' ), '<a href="https://developers.facebook.com/apps">developers.facebook.com/apps</a>' ); ?></p>
						</td>
					</tr>

					<tr valign="top" <?php if( empty( $opts['fb_id'] ) ) echo 'class="rfbp-row-error"'; ?>>
						<th scope="row"><label for="rfb_fb_id"><?php _e('Facebook Page ID or Slug', 'recent-facebook-posts'); ?></label></th>
						<td>
							<input type="text" class="widefat" placeholder="Eg: DannyvanKootenCOM" id="rfb_fb_id" name="rfb_settings[fb_id]" value="<?php echo esc_attr( $opts['fb_id'] ); ?>" required />
							<p class="help"><?php printf( __( 'Use <a href="%s">this tool</a> to find the numeric ID of your Facebook page.', 'recent-facebook-posts' ), 'http://findmyfacebookid.com' ); ?></p>
						</td>
					</tr>
				</table>
			</div>

			<h3><?php _e( 'Appearance', 'recent-facebook-posts' ); ?></h3>
			<table class="form-table">
				<tbody>
				<tr valign="top">
					<th scope="row"><label for="rfb_page_link_text"><?php _e( 'Link text', 'recent-facebook-posts' ); ?></label></th>
					<td><input type="text" class="widefat" placeholder="<?php _e( 'Find us on Facebook', 'recent-facebook-posts' ); ?>" id="rfb_page_link_text" name="rfb_settings[page_link_text]" value="<?php echo esc_attr($opts['page_link_text']); ?>" /></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="rfb_link_new_window"><?php _e( 'Open links in new window?', 'recent-facebook-posts' ); ?></label></th>
					<td><input type="checkbox" id="rfb_link_new_window" name="rfb_settings[link_new_window]" value="1" <?php checked( $opts['link_new_window'], 1 ); ?> /></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="rfb_load_css"><?php _e( 'Load some default styles?', 'recent-facebook-posts' ); ?></label></th>
					<td><input type="checkbox" id="rfb_load_css" name="rfb_settings[load_css]" value="1" <?php checked( $opts['load_css'], 1 ); ?> /></td>
				</tr>
				</tbody>
				<tbody>
					<tr valign="top">
						<th scope="row"><label for="rfb_img_size"><?php _e('Image size', 'recent-facebook-posts'); ?></label></th>
						<td>
							<select class="widefat" id="rfb_img_size" name="rfb_settings[img_size]">
								<option value="dont_show" <?php selected( $opts['img_size'], 'dont_show' ); ?>><?php _e("Don't show images", 'recent-facebook-posts'); ?></option>
								<option value="thumbnail" <?php selected( $opts['img_size'], 'thumbnail' ); ?>><?php _e('Thumbnail', 'recent-facebook-posts'); ?></option>
								<option value="normal" <?php selected( $opts['img_size'], 'normal' ); ?>><?php _e('Normal', 'recent-facebook-posts'); ?></option>
							</select>
						</td>
					</tr>
				</tbody>
				<tbody id="rfb_img_options" <?php if( $opts['img_size'] === 'dont_show' ) echo 'style="display:none;"'; ?>>
					<tr valign="top">
						<th><?php _e('Image dimensions', 'recent-facebook-posts'); ?><br /><small><?php _e( '(in pixels, optional)', 'recent-facebook-posts' ); ?></small></th>
						<td>
							<label style="float:left; margin-right:20px; ">
								<?php _e('Max Width', 'recent-facebook-posts'); ?><br />
								<input type="number" min="0" max="1600" size="3" id="rfb_img_width" name="rfb_settings[img_width]" value="<?php echo esc_attr( $opts['img_width'] ); ?>" />
							</label>
							<label style="float:left; margin-right:20px;">
								<?php _e('Max Height', 'recent-facebook-posts'); ?><br />
								<input type="number" min="0" max="1600" size="3" id="rfb_img_height" name="rfb_settings[img_height]" value="<?php echo esc_attr( $opts['img_height'] ); ?>" />
							</label>
							<br />
							<small class="help"><?php _e( 'Leave empty for default sizing', 'recent-facebook-posts' ); ?></small>
						</td>
					</tr>
				</tbody>
			</table>

			<?php submit_button(); ?>

		</form>

		<?php if(rfbp_valid_config()) { ?>
			<h3 class="rfbp-title"><?php _e( 'Test Configuration', 'recent-facebook-posts' ); ?></h3>
			<p><?php _e('Test your plugin configuration using the button below.', 'recent-facebook-posts'); ?></p>
			<form action="<?php echo admin_url('options-general.php?page=rfbp'); ?>" method="post">
				<input type="hidden" name="rfbp-test-config" value="1" />
				<?php submit_button( __('Test Configuration', 'recent-facebook-posts') ); ?>
			</form>

			<h3 class="rfbp-title"><?php _e( 'Facebook Posts Cache', 'recent-facebook-posts' ); ?></h3>
			<p><?php _e('Because fetching posts from Facebook is relatively slow the posts are cached for <strong>30 minutes</strong>. You can manually clear the cache using the button below.', 'recent-facebook-posts'); ?></p>
			<form action="<?php echo admin_url( 'options-general.php?page=rfbp' ); ?>" method="post">
				<input type="hidden" name="rfbp-clear-cache" value="1" />

				<?php submit_button( __( 'Clear Cache', 'recent-facebook-posts' ) ); ?>
			</form>
		<?php } ?>
	</div>

	<!-- Start RFBP Sidebar -->
	<div class="rfbp-column rfbp-secondary">
		<div class="rfbp-box">
			<h3 class="rfbp-title"><?php _e( 'Usage', 'recent-facebook-posts' ); ?></h3>
			<p><?php _e( 'To show a list of your most recent facebook posts in your posts or pages, use the following shortcode.', 'recent-facebook-posts' ); ?></p>
			<input type="text" readonly value="[recent_facebook_posts]" class="widefat" />
			<p><?php printf( __( 'The shortcode accepts various arguments, which are listed on the <a href="%s">plugin FAQ</a>.', 'recent-facebook-posts' ), 'https://wordpress.org/plugins/recent-facebook-posts/faq/' ); ?></p>
		</div>


		<div class="rfbp-box">
			<h3 class="rfbp-title"><?php _e( 'Show your appreciation!', 'recent-facebook-posts' ); ?></h3>
			<p><?php _e( 'If you like this plugin, consider showing your consideration in one of the following ways.', 'recent-facebook-posts' ); ?></p>
			<ul class="ul-square">
				<li><a href="https://wordpress.org/support/view/plugin-reviews/recent-facebook-posts?rate=5#postform" target="_blank"><?php printf( __( 'Leave a %s review on WordPress.org', 'recent-facebook-posts' ), '&#9733;&#9733;&#9733;&#9733;&#9733;' ); ?></a></li>
				<li><a href="https://twitter.com/?status=I%20show%20recent%20facebook%20posts%20on%20my%20%23WordPress%20site%20using%20Recent%20Facebook%20Posts%20by%20%40DannyvanKooten%20-%20love%20it!%20http%3A%2F%2Fwordpress.org%2Fplugins%2Frecent-facebook-posts%2F" target="_blank"><?php _e( 'Tweet about Recent Facebook Posts', 'recent-facebook-posts' ); ?></a></li>
				<li><a href="https://wordpress.org/plugins/recent-facebook-posts/#compatibility"><?php _e( 'Vote "works" on the WordPress.org plugin page', 'recent-facebook-posts' ); ?></a></li>
			</ul>
		</div>

		<div class="rfbp-box">
			<h3 class="rfbp-title"><?php _e( 'Looking for support?', 'recent-facebook-posts' ); ?></h3>
			<p><?php printf( __( 'Please use the <a href="%s">plugin support forums</a> on WordPress.org.', 'recent-facebook-posts' ), 'https://wordpress.org/support/plugin/recent-facebook-posts' ); ?></p>
			<p><?php printf( __( 'Take a close look at the <a href="%s">installation instructions</a> for help configuring the plugin and registering your own Facebook application, which is required to get this plugin to work.', 'recent-facebook-posts' ), 'https://wordpress.org/plugins/recent-facebook-posts/installation/' ); ?></p>
		</div>

		<div class="rfbp-box">
			<h3 class="rfbp-title"><?php _e( 'Other plugins', 'recent-facebook-posts' ); ?></h3>
			<ul class="ul-square">
				<li>
					<h4><a href="https://wordpress.org/plugins/mailchimp-for-wp/">MailChimp for WordPress</a></h4>
					<p>By far the best MailChimp (newsletter) sign-up plugin for WordPress, with over half a million sites happily using it.</p>
				</li>
				<li>
					<h4><a href="https://wordpress.org/plugins/boxzilla/">Boxzilla Pop-Ups</a></h4>
					<p>Need an unobtrusive conversion booster? This plugin offers an alternative to pop-ups. Highly converting yet not so annoying.</p>
				</li>
			</ul>
			</div>
		</div>
		<!-- End RFBP Sidebar -->

		<br style="clear:both; " />
	</div>
</div>

