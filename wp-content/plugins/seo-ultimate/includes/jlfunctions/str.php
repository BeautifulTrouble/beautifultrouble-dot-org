<?php
/*
JLFunctions String Class
Copyright (c)2009-2011 John Lamansky
*/

class sustr {
	
	/**
	 * Returns whether or not a given string starts with a given substring.
	 * 
	 * @param string $str The "haystack" string.
	 * @param string $sub The "needle" string.
	 * @return bool Whether or not $str starts with $sub.
	 */
	function startswith( $str, $sub ) {
	   return ( substr( $str, 0, strlen( $sub ) ) === $sub );
	}

	/**
	 * Returns whether or not a given string ends with a given substring.
	 * 
	 * @param string $str The "haystack" string.
	 * @param string $sub The "needle" string.
	 * @return bool Whether or not $str ends with $sub.
	 */
	function endswith( $str, $sub ) {
	   return ( substr( $str, strlen( $str ) - strlen( $sub ) ) === $sub );
	}
	
	function startwith( $str, $start ) {
		if (!sustr::startswith($str, $start))
			return $start.$str;
		else
			return $str;
	}
	
	function endwith( $str, $end ) {
		if (!sustr::endswith($str, $end))
			return $str.$end;
		else
			return $str;
	}
	
	function has($str, $sub) {
		return (strpos($str, $sub) !== false);
	}
	
	function ihas($str, $sub) {
		$str = strtolower($str);
		$sub = strtolower($sub);
		return (strpos($str, $sub) !== false);
	}

	/**
	 * Truncates a string if it is longer than a given length.
	 * 
	 * @param string $str The string to possibly truncate.
	 * @param int $maxlen The desired maximum length of the string.
	 * @param str $truncate The string that should be added to the end of a truncated string.
	 */
	function truncate( $str, $maxlen, $truncate = '...', $maintain_words=false ) {
		
		if ( strlen($str) > $maxlen ) {
			$str = substr( $str, 0, $maxlen - strlen($truncate) );
			if ($maintain_words) $str = preg_replace('/ ([^ ]+)$/', '', $str);
			$str .= $truncate;
		}
			
		return $str;
	}
	
	function truncate_at( $str, $end ) {
		if ($endpos = strpos($str, $end))
			return substr($str, 0, $endpos);
		else
			return $str;
	}
	
	/**
	 * Joins strings into a natural-language list.
	 * Can be internationalized with gettext or the su_lang_implode filter.
	 * 
	 * @param array $items The strings (or objects with $var child strings) to join.
	 * @param string|false $var The name of the items' object variables whose values should be imploded into a list.
		If false, the items themselves will be used.
	 * @param bool $ucwords Whether or not to capitalize the first letter of every word in the list.
	 * @return string|array The items in a natural-language list.
	 */
	function nl_implode($items, $var=false, $ucwords=false) {
		
		if (is_array($items) ) {
			
			if (strlen($var)) {
				$_items = array();
				foreach ($items as $item) $_items[] = $item->$var;
				$items = $_items;
			}
			
			if ($ucwords) $items = array_map('ucwords', $items);
			
			switch (count($items)) {
				case 0: $list = ''; break;
				case 1: $list = $items[0]; break;
				case 2: $list = sprintf(__('%s and %s', 'seo-ultimate'), $items[0], $items[1]); break;
				default:
					$last = array_pop($items);
					$list = implode(__(', ', 'seo-ultimate'), $items);
					$list = sprintf(__('%s, and %s', 'seo-ultimate'), $list, $last);
					break;
			}
			
			return apply_filters('su_lang_implode', $list, $items);
		}

		return $items;
	}
	
	/**
	 * If the given string ends with the given suffix, the suffix is removed.
	 * 
	 * @param string $str The string of which the provided suffix should be trimmed if located.
	 * @param string $totrim The suffix that should be trimmed if found.
	 * @return string The possibly-trimmed string.
	 */
	function rtrim_str($str, $totrim) {
		if (strlen($str) > strlen($totrim) && sustr::endswith($str, $totrim))
			return substr($str, 0, -strlen($totrim));
		
		return $str;
	}
	
	function rtrim_substr($str, $totrim) {
		for ($i = strlen($totrim); $i > 0; $i--) {
			$totrimsub = substr($totrim, 0, $i);
			if (sustr::endswith($str, $totrimsub))
				return sustr::rtrim_str($str, $totrimsub);
		}
		
		return $str;
	}
	
	function ltrim_str($str, $totrim) {
		if (strlen($str) > strlen($totrim) && sustr::startswith($str, $totrim))
			return substr($str, strlen($totrim));
		
		return $str;
	}
	
	function batch_replace($search, $replace, $subjects) {
		$subjects = array_unique((array)$subjects);
		$results = array();
		foreach ($subjects as $subject) {
			$results[$subject] = str_replace($search, $replace, $subject);
		}
		return $results;
	}
	
	function unique_words($str) {
		$str = explode(' ', $str);
		$str = array_unique($str);
		$str = implode(' ', $str);
		return $str;
	}
	
	function remove_double_words($str) {
		$words = explode(' ', $str);
		foreach ($words as $word) $str = str_replace("$word $word", $word, $str);
		return $str;
	}
	
	function preg_filter($filter, $str) {
		$filter = str_replace('/', '\\/', $filter);
		return preg_replace("/[^{$filter}]/", '', $str);
	}
	
	function to_int($str) {
		return intval(sustr::preg_filter('0-9', strval($str)));
	}
	
	function preg_escape($str, $delim='%') {
		$chars = "\ ^ . $ | ( ) [ ] * + ? { } , ".$delim;
		$chars = explode(' ', $chars);
		foreach ($chars as $char)
			$str = str_replace($char, '\\'.$char, $str);
		return $str;
	}
	
	function htmlsafe_str_replace($search, $replace, $subject, $limit, &$count, $exclude_tags = false) {
		$search = sustr::preg_escape($search, '');
		return sustr::htmlsafe_preg_replace($search, $replace, $subject, $limit, $count, $exclude_tags);
	}
	
	function htmlsafe_preg_replace($search, $replace, $subject, $limit, &$count, $exclude_tags = false) {
		
		if (!$exclude_tags || !is_array($exclude_tags)) $exclude_tags = array('a', 'pre', 'code', 'kbd');
		if (count($exclude_tags) > 1)
			$exclude_tags = sustr::preg_filter('a-z0-9|', implode('|', $exclude_tags));
		else
			$exclude_tags = array_shift($exclude_tags);
		
		$search = str_replace('/', '\/', $search);
		
		//Based off of regex from
		//http://stackoverflow.com/questions/3013164/regex-to-replace-a-string-in-html-but-not-within-a-link-or-heading
		$search_regex = "/\b($search)\b(?!(?:(?!<\/?(?:$exclude_tags).*?>).)*<\/(?:$exclude_tags).*?>)(?![^<>]*>)/imsU";
		
		return preg_replace($search_regex, $replace, $subject, $limit, $count);
	}
	
	function upto($str, $sub) {
		$end = strpos($str, $sub);
		if ($end === false)
			return $str;
		else
			return substr($str, 0, $end);
	}
	
	function str2func($varval) {
		return create_function('', 'return "'.addcslashes((string)$varval, '"').'";');
	}
	
	function tclcwords($str) {
		$words = explode(' ', $str);
		$new_words = array();
		foreach ($words as $word) {
			if (strtolower($word) == $word)
				$new_words[] = ucwords($word);
			else
				$new_words[] = $word;
		}
		return implode(' ', $new_words);
	}
	
	function camel_case($string) {
		$string = strtolower($string);
		$string = preg_replace('@[^a-z0-9]@', ' ', $string);
		$words = array_filter(explode(' ', $string));
		$first = array_shift($words);
		$words = array_map('ucwords', $words);
		$words = implode('', $words);
		$string = $first . $words;
		return $string;
	}
	
	function wildcards_to_regex($wcstr) {
		$wcstr = sustr::preg_escape($wcstr, '@');
		$wcstr = str_replace('\\*', '.*', $wcstr);
		$regex = "@^$wcstr$@i";
		$regex = str_replace(array('@^.*', '.*$@i'), array('@', '@i'), $regex);
		return $regex;
	}
	
	function tolower($str) {
		if (function_exists('mb_strtolower'))
			return mb_strtolower($str);
		
		return strtolower($str);
	}
}

?>