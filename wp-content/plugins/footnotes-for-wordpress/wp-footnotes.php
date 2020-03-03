<?php
/*
Plugin Name: Footnotes for WordPress
Plugin URI: http://projects.radgeek.com/wp-footnotes.php
Description: easy-to-use fancy footnotes for WordPress posts
Version: 2016.1230
Author: Charles Johnson
Author URI: http://radgeek.com/
License: GPL
*/

/**
 * @package FootnotesForWordPress
 * @version 2016.1230
 */
define('FFWP_VERSION', '2016.1230');

class FootnotesForWordPress {
	var $accumulated;
	private $note_number_base = 0;
	private $note_number_base_at = 0;
	private $discharge_the_content;
	
	public function __construct () { // constructor
		$this->accumulated = array();
		$this->discharge_the_content = true;
		
		$url = $this->plugin_url();
		
		// Pre-register scripts and styles
		wp_register_script(
			'footnote-voodoo',
			"${url}footnote-voodoo.js",
			/*depends on=*/ array('jquery'),
			/*ver=*/ FFWP_VERSION
		);
		wp_register_style(
			'footnote-voodoo',
			"${url}footnote-voodoo.css",
			/*depends on=*/ array(),
			/*ver=*/ FFWP_VERSION
		);

		add_shortcode('ref', array(&$this, 'shortcode'));
		add_shortcode('backref', array(&$this, 'backref'));
		add_shortcode('references', array(&$this, 'discharge'));
		
		// Way downstream; needs to be after do_shortcode (priority 11), for one thing
		add_filter('the_content', array(&$this, 'the_content'), 1000, 2);
		
		add_action('init', array(&$this, 'add_scripts'));
		add_action('wp_head', array(&$this, 'add_inline_styles'));
	} /* FootnotesForWordPress constructor */

	var $plugin_path = NULL;
	public function plugin_url () {
		if (is_null($this->plugin_path)) :
			preg_match (
				'|'.WP_PLUGIN_DIR.'/(.+)$|',
				dirname(__FILE__),
				$ref
			);
			
			if (isset($ref[1])) :
				$this->plugin_path = $ref[1];
			else : // Something went wrong. Let's just guess.
				$this->plugin_path = 'footnotes-for-wordpress';
			endif;
		endif;
		return trailingslashit(WP_PLUGIN_URL.'/'.$this->plugin_path);
	}
	
	public function add_scripts () {
		wp_enqueue_script('footnote-voodoo');
		wp_enqueue_style('footnote-voodoo');
	}
	
	public function add_inline_styles () {
	?>
<style type="text/css">

	.footnote-indicator:before {
		content: url(<?php print $this->plugin_url(); ?>footnoted.png);
		width: 10px;
		height: 10px;
	}
	ol.footnotes li {
		background: #eeeeee url(<?php print $this->plugin_url(); ?>note.png) 0px 0px repeat-x;
	}
</style>
<script type="text/javascript">
	// Globals
	var tipUpUrl = 'url(<?php print $this->plugin_url(); ?>tip.png)';
	var tipDownUrl = 'url(<?php print $this->plugin_url(); ?>tip-down.png)';
</script>
	<?php
	}

	var $bullets = array();
	public function shortcode ($atts, $content = NULL, $code = '') {
		global $post;

		// Get parameters
		$atts = shortcode_atts( array(
			"name" => NULL,
			"group" => NULL,
			"number" => NULL,
			"superscript" => NULL,
			'backlink-prefix' => 'to-',
		), $atts );

		$noteId = $atts['name'];
		if (!isset($this->bullets[$post->post_name])) :
			$this->bullets[$post->post_name] = array();
		endif;
		
		$numberOfNotes = (count($this->bullets[$post->post_name]) + 1);
		
		if (!is_null($atts['number'])) :
			if (intval($atts['number']) > 0) :
				$this->note_number_base = (intval($atts['number']) - 1);
				$this->note_number_base_at = $numberOfNotes - 1;
			endif;
		endif;
	
		$bullet = $this->note_number_base + ($numberOfNotes - $this->note_number_base_at);
		if (is_null($noteId) and !is_null($post)) :
			$noteId = $post->post_name.'-n-'.$bullet;
		endif;
		
		$bulletText = (is_null($atts['superscript']) ? $bullet : $atts['superscript']);
		$this->bullets[$post->post_name][$noteId] = array(
			"number" => $bullet,
			"text" => $bulletText,
		);
		
		// Allow any inside shortcodes to do their work.
		$content = array( do_shortcode($content) );
		$note_marker = "<strong><sup>[$bulletText]</sup></strong>";

		$prefix = "<li class=\"footnote\" id=\"$noteId\">$note_marker";
		$suffix = "<a class=\"note-return\" href=\"#{$atts['backlink-prefix']}{$noteId}\">&#x21A9;</a></li>";

		// previously set.
		if (isset($this->accumulated[$noteId])) :
			$silent = true;
			$content = array_merge(
				$this->accumulated[$noteId][1],
				$content
			);
		endif;
		
		// [prefix, content (array), suffix]
		$this->accumulated[$noteId] = array($prefix, $content, $suffix);

		return ($silent ? '' : '<sup>[<a href="#'.$noteId.'" class="footnoted" id="'.$atts['backlink-prefix'].$noteId.'">'.$bulletText.'</a>]</sup>');
	} /* FootnotesForWordPress::shortcode */

	public function backref ($atts = array(), $content = NULL, $code = '') {
		global $post;

		// Get parameters
		$atts = shortcode_atts( array(
			"name" => NULL,
			"group" => NULL,
			"number" => NULL,
			"superscript" => NULL,
			'backlink-prefix' => 'to-',
		), $atts );

		$bullet = $this->bullets[$post->post_name][$atts['name']];

		if (!is_null($atts['name'])) :
			$ret = '<sup>[<a href="#'.$atts['name'].'" class="footnoted">'.$bullet['text'].'</a>]</sup>';
		else :
			$ret = '';
		endif;

		return $ret;
	} /* FootnotesForWordPress::backref */
	
	public function discharge ($atts = array(), $content = NULL, $code = '') {
		// Get parameters
		$atts = shortcode_atts ( array(
			"class" => "footnotes",
			"group" => NULL,
		), $atts );

		$notes = '';
		if (count($this->accumulated) > 0) :
			$notes = "<ol class=\"{$atts['class']}\">\n\t"
				.implode("\n\t", array_map(array($this, 'implode_accumulated_line'), $this->accumulated))
				."</ol>\n";
			$this->accumulated = array();
		endif;

		return $notes;
	} /* FootnotesForWordPress::discharge */

	public function implode_accumulated_line ($line) {
		return $line[0] . implode("\n", $line[1]) . $line[2];
	} /* FootnotesForWordPress::implode_accumulated_line () */
	
	public function discharge_at_end_of_post ($value = NULL) {
		$ret = null;
		if (is_null($value)) :
			$ret = $this->discharge_the_content;
		else :
			$this->discharge_the_content = $value;			
		endif;
		return $ret;
	} /* FootnotesForWordPress::discharge_at_end_of_post () */
	
	public function the_content ($content) {
		/* Discharge any remaining footnotes */
		if ($this->discharge_at_end_of_post()) :
			$content .= "\n".$this->discharge();
		endif;
		
		return $content;
	} /* FootnotesForWordPress::the_content() */
} /* class FootnotesForWordPress */

function post_has_footnotes ($atts = array()) {
	global $footnotesForWordPress;
	return (count($footnotesForWordPress->accumulated) > 0);
}

function the_post_footnotes ($atts = array()) {
	global $footnotesForWordPress;
	
	print $footnotesForWordPress->discharge($atts);
} /* the_post_footnotes () */

global $footnotesForWordPress; // Singleton object.

$footnotesForWordPress = new FootnotesForWordPress;
