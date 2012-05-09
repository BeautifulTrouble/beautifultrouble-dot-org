<?php

/*
Plugin Name: CSV User Import
Plugin URI: http://luibh.ie/
Description: Allows the importation of users via an uploaded CSV file.
Author: Andy Dunn
Version: 1.0.3
Author URI: http://blog.luibh.ie/
*/

// always find line endings
ini_set('auto_detect_line_endings', true);

// add admin menu
add_action('admin_menu', 'csvuserimport_menu');

function csvuserimport_menu() {	
	add_submenu_page( 'users.php', 'CSV User Import', 'Import', 'manage_options', 'csv-user-import', 'csvuserimport_page1');	
}

// show import form
function csvuserimport_page1() {

	global $wpdb;

  	if (!current_user_can('manage_options')) {
    	wp_die( __('You do not have sufficient permissions to access this page.') );
  	}

	// if the form is submitted
	if ($_POST['mode'] == "submit") {
		
		$arr_rows = file($_FILES['csv_file']['tmp_name']);

		// loop around
		if (is_array($arr_rows)) {
			foreach ($arr_rows as $row) {

                                // More effective way to split a CSV string into an array of values
                                $arr_values   = str_getcsv( $row, $delimiter=',', $enclosure='"', $escape='\\' );

                                // firstname, lastname, username, password, email, description, website, aim, jabber
                                // yim, facebook, twitter, google
				$firstname 		= $arr_values[0];
				$lastname 		= $arr_values[1];
				$username 		= trim($arr_values[2]);
				$password 		= trim($arr_values[3]);
				$user_email 	= trim($arr_values[4]);				
                                // Fix suggested by MikaelLarsson 
                                // http://wordpress.org/support/topic/plugin-csv-user-import-small-fix-suggestion
                                if (!$user_email) { $user_email = $username."@donotreply.com"; }
                                $user_nicename	        = sanitize_title($username);
                                $description            = $arr_values[5];  // Let's add some user meta information, like a bio
                                $website                = $arr_values[6];  // Website
                                $aim                    = $arr_values[7];  // AIM
                                $jabber                 = $arr_values[8];  // Jabber (WTF uses it?)
                                $yim                    = $arr_values[9];  // Yahoo IM
                                $user_fb                = $arr_values[10]; // Facebook
                                $user_tw                = $arr_values[11]; // Twitter
                                $google_profile         = $arr_values[12]; // Google Plus


				// add the new user
				$arr_user = array( 	'user_login' => $username,
									'user_nicename' => $user_nicename,
									'user_email' => $user_email,
									'user_registered' => date( 'Y-m-d H:i:s' ),
									'user_status' => "0",
                                                                        'user_url'    => $website,
                                // TODO This should be an option on the import screen
	                       'display_name' => $firstname . ' ' . $lastname											
							 		);
				$wpdb->insert( $wpdb->users, $arr_user );				
				$user_id = $wpdb->insert_id;		
				wp_set_password($password, $user_id);

				// add default meta values
				$arr_meta_values = array(
									'nickname' => $username,
									'rich_editing' => "true",
									'comment_shortcuts' => "false",
									'admin_color' => "fresh",
                                                                        // TODO Would be sensible to put the capability options 
                                                                        // into a hash table and accept a key from the import screen
                                                                        // Contributor: a:1:{s:11:"contributor";s:1:"1";}
                                                                        // Subscriber: a:1:{s:10:"subscriber";b:1;}
                                                                        // Author:  a:1:{s:6:"author";s:1:"1";}
                                                                        // Editor: a:1:{s:6:"editor";s:1:"1";}
                                                                        // Admin: a:1:{s:13:"administrator";s:1:"1";}
									$wpdb->prefix . 'capabilities' => 'a:1:{s:11:"contributor";s:1:"1";}',
									'first_name' => $firstname,
									'last_name' => $lastname,
									'default_password_nag' => "1",
                                                                        'description' => $description,
                                                                        'aim'         => $aim,
                                                                        'jabber'      => $jabber,
                                                                        'yim'         => $yim,
                                                                        'user_fb'     => $user_fb,
                                                                        'user_tw'     => $user_tw,
                                                                        'google_profile' => $google_profile
									);

				foreach ($arr_meta_values as $key => $value) {
					$arr_meta = array(	'user_id' => $user_id,
										'meta_key' => $key,
										'meta_value' => $value
								 	);
					$wpdb->insert( $wpdb->usermeta, $arr_meta );
				}

			}	// end of 'for each around arr_rows'

			$html_update = "<div class='updated'>All users appear to be have been imported successfully.</div>";
			
		} // end of 'if arr_rows is array'
		else {
			$html_update = "<div class='updated' style='color: red'>It seems the file was not uploaded correctly.</div>";			
		}
	} 	// end of 'if mode is submit'

?>
<div class="wrap">	
	<?php echo $html_update; ?>	
	<div id="icon-users" class="icon32"><br /></div>
	<h2>CSV User Import</h2>
	<p>Please select the CSV file you want to import below.</p>
	
	<form action="users.php?page=csv-user-import" method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="submit">
		<input type="file" name="csv_file" />		
		<input type="submit" value="Import" />
	</form>
	
	<p>The CSV file should be in the following format:</p>
	
	<table>
		<tr>
			<td>firstname,</td>
			<td>lastname,</td>			
			<td>username,</td>
			<td>password (plain text),</td>
			<td>bio/descripton,</td>
			<td>website,</td>
			<td>AIM,</td>
			<td>jabber,</td>
			<td>YIM,</td>
			<td>Facebook,</td>
			<td>Twitter,</td>
			<td>Google</td>
		</tr>
	</table>
        <p>You can find an example <a href="https://gist.github.com/2640242">here</a>.</p>
	
	<p style="color: red">Please make sure you back up your database before proceeding!</p>	
</div>
<?php
}	// end of 'function csvuserimport_page1()'
?>
