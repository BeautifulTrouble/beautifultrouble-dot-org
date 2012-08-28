<?php
/*
Plugin Name: Simple 301 Redirects
Plugin URI: http://www.scottnelle.com/simple-301-redirects-plugin-for-wordpress/
Description: Create a list of URLs that you would like to 301 redirect to another page or site
Version: 1.03
Author: Scott Nellé
Author URI: http://www.scottnelle.com/
*/

/*  Copyright 2009  Scott Nellé  (email : theguy@scottnelle.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!class_exists("Simple301redirects")) {
	class Simple301Redirects {
		/*
			generate the link to the options page under settings
		*/
		function create_menu()
		{
		  add_options_page('301 Redirects', '301 Redirects', 10, '301options', array($this,'options_page'));
		}
		
		/*
			generate the options page in the wordpress admin
		*/
		function options_page()
		{
		?>
		<div class="wrap">
		<h2>Simple 301 Redirects</h2>
		
		<form method="post" action="options-general.php?page=301options">
		
		<table>
			<tr>
				<th>Request</th>
				<th>Destination</th>
			</tr>
			<tr>
				<td><small>example: /about.htm</small></td>
				<td><small>example: <?php echo get_option('home'); ?>/about/</small></td>
			</tr>
			<?php echo $this->expand_redirects(); ?>
			<tr>
				<td><input type="text" name="301_redirects[request][]" value="" style="width:15em" />&nbsp;&raquo;&nbsp;</td>
				<td><input type="text" name="301_redirects[destination][]" value="" style="width:30em;" /></td>
			</tr>
		</table>
		
		<p class="submit">
		<input type="submit" name="submit_301" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
		</form>
		</div>
		<?php
		} // end of function options_page
		
		/*
			utility function to return the current list of redirects as form fields
		*/
		function expand_redirects(){
			$redirects = get_option('301_redirects');
			$output = '';
			if (!empty($redirects)) {
				foreach ($redirects as $request => $destination) {
					$output .= '
					
					<tr>
						<td><input type="text" name="301_redirects[request][]" value="'.$request.'" style="width:15em" />&nbsp;&raquo;&nbsp;</td>
						<td><input type="text" name="301_redirects[destination][]" value="'.$destination.'" style="width:30em;" /></td>
					</tr>
					
					';
				}
			} // end if
			return $output;
		}
		
		/*
			save the redirects from the options page to the database
		*/
		function save_redirects($data)
		{
			$redirects = array();
			
			for($i = 0; $i < sizeof($data['request']); ++$i) {
				$request = trim($data['request'][$i]);
				$destination = trim($data['destination'][$i]);
			
				if ($request == '' && $destination == '') { continue; }
				else { $redirects[$request] = $destination; }
			}

			update_option('301_redirects', $redirects);
		}
		
		/*
			Read the list of redirects and if the current page 
			is found in the list, send the visitor on her way
		*/
		function redirect()
		{
			// this is what the user asked for (strip out home portion, case insensitive)
			$userrequest = str_ireplace(get_option('home'),'',$this->getAddress());
			$userrequest = rtrim($userrequest,'/');
			
			$redirects = get_option('301_redirects');
			if (!empty($redirects)) {
				foreach ($redirects as $storedrequest => $destination) {
					// compare user request to each 301 stored in the db
					if(urldecode($userrequest) == rtrim($storedrequest,'/')) {
						header ('HTTP/1.1 301 Moved Permanently');
						header ('Location: ' . $destination);
						exit();
					}
					else { unset($redirects); }
				}
			}
		} // end funcion redirect
		
		/*
			utility function to get the full address of the current request
			credit: http://www.phpro.org/examples/Get-Full-URL.html
		*/
		function getAddress()
		{
			/*** check for https ***/
			$protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
			/*** return the full address ***/
			return $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		}
		
	} // end class Simple301Redirects
	
} // end check for existance of class

// instantiate
$redirect_plugin = new Simple301Redirects();

if (isset($redirect_plugin)) {
	// add the redirect action, high priority
	add_action('init', array($redirect_plugin,'redirect'), 1);

	// create the menu
	add_action('admin_menu', array($redirect_plugin,'create_menu'));

	// if submitted, process the data
	if (isset($_POST['submit_301'])) {
		$redirect_plugin->save_redirects($_POST['301_redirects']);
	}
}

// this is here for php4 compatibility
if(!function_exists('str_ireplace')){
  function str_ireplace($search,$replace,$subject){
    $token = chr(1);
    $haystack = strtolower($subject);
    $needle = strtolower($search);
    while (($pos=strpos($haystack,$needle))!==FALSE){
      $subject = substr_replace($subject,$token,$pos,strlen($search));
      $haystack = substr_replace($haystack,$token,$pos,strlen($search));
    }
    $subject = str_replace($token,$replace,$subject);
    return $subject;
  }
}
?>
