<?php
/*
JLFunctions URL Class
Copyright (c)2009-2012 John Lamansky
*/

class suurl {
	
	/**
	 * Approximately determines the URL in the visitor's address bar. (Includes query strings, but not #anchors.)
	 * 
	 * @return string The current URL.
	 */
	static function current() {
		$url = 'http';
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") $url .= "s";
		$url .= "://";
		
		if ($_SERVER["SERVER_PORT"] != "80")
			return $url.$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		else
			return $url.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	
	/**
	 * Checks whether or not two URLs are equivalent.
	 * 
	 * @param string $url1
	 * @param string $url2
	 * @return bool
	 */
	static function equal($url1, $url2) {
		
		if (($url1parts = parse_url($url1)) && isset($url1parts['host'])) {
			$url1parts['host'] = strtolower($url1parts['host']);
			$url1 = self::build($url1parts);
		}
		
		if (($url2parts = parse_url($url2)) && isset($url2parts['host'])) {
			$url2parts['host'] = strtolower($url2parts['host']);
			$url2 = self::build($url2parts);
		}
		
		return $url1 == $url2;
	}
	
	/**
	 * Turns an array of URL parts (host, scheme, path, query, fragment) into a URL.
	 * 
	 * @param array $parts
	 * @return string
	 */
	static function build($parts) {
		
		$url = '';
		
		if (!empty($parts['host'])) {
			$url = empty($parts['scheme']) ? 'http://' : $parts['scheme'] . '://';
			$url .= $parts['host'];
		}
		
		if (!empty($parts['path'])) $url .= $parts['path'];
		if (!empty($parts['query'])) $url .= '?' . $parts['query'];
		if (!empty($parts['fragment'])) $url .= '#' . $parts['fragment'];
		return $url;
	}
}

?>