<?php
/*
JLFunctions Array Class
Copyright (c)2009-2011 John Lamansky
*/

class suarr {
	
	/**
	 * Plugs an array's keys and/or values into sprintf-style format string(s).
	 * 
	 * @param string|false $keyformat The sprintf-style format for the key, e.g. "prefix_%s" or "%s_suffix"
	 * @param string|false $valueformat The sprintf-style format for the value.
	 * @param array $array The array whose keys/values should be formatted.
	 * @return array The array with the key/value formats applied.
	 */
	function aprintf($keyformat, $valueformat, $array) {
		$newarray = array();
		foreach ($array as $key => $value) {
			if ($keyformat) {
				if (is_int($key)) $key = $value;
				$key = str_replace('%s', $key, $keyformat);
			}
			if ($valueformat) $value = str_replace('%s', $value, $valueformat);
			$newarray[$key] = $value;
		}
		return $newarray;
	}
	
	/**
	 * Removes elements that are blank (after trimming) from the beginning of the given array.
	 */
	function ltrim($array) {
		while (count($array) && !strlen(trim($array[0])))
			array_shift($array);
		return $array;
	}
	
	/**
	 * Removes a value from the array if found.
	 */
	function remove_value(&$array, $value) {
		$index = array_search($value, $array);
		if ($index !== false)
			unset($array[$index]);
	}
	
	/**
	 * Returns whether or not any of the specified $needles are in the $haystack.
	 * 
	 * @param array $needles
	 * @param array $haystack
	 * @param bool $case Whether or not the search should be case-sensitive.
	 * 
	 * @return bool
	 */
	function any_in_array($needles, $haystack, $case = true) {
		if (!$case) {
			$needles  = array_map('strtolower', $needles);
			$haystack = array_map('strtolower', $haystack);
		}
		
		return count(array_intersect($needles, $haystack)) > 0;
	}
	
	function explode_lines($lines) {
		$lines = explode("\n", $lines);
		$lines = array_map('trim', $lines); //Remove any \r's
		return $lines;
	}
	
	//Based on recursive array search function from:
	//http://www.php.net/manual/en/function.array-search.php#91365
	function search_recursive($needle, $haystack) {
		foreach ($haystack as $key => $value) {
			if ($needle === $value || (is_array($value) && suarr::search_recursive($needle, $value) !== false))
				return $key;
		}
		return false;
	}
	
	function recursive_get($array, $key) {
		if (is_array($array)) {
			if (isset($array[$key]))
				return $array[$key];
			
			foreach ($array as $subarray) {
				if ($value = suarr::recursive_get($subarray, $key))
					return $value;
			}
		}
		
		return false;
	}
	
	function vksort(&$arr, $valuekey) {
		$valuekey = sustr::preg_filter('A-Za-z0-9 ', $valuekey);
		uasort($arr, create_function('$a,$b', 'return strcasecmp($a["'.$valuekey.'"], $b["'.$valuekey.'"]);'));
	}
	
	function vklrsort(&$arr, $valuekey) {
		$valuekey = sustr::preg_filter('A-Za-z0-9 ', $valuekey);
		uasort($arr, create_function('$a,$b', 'return strlen($b["'.$valuekey.'"]) - strlen($a["'.$valuekey.'"]);'));
	}
	
	function flatten_values($arr, $value_keys, $use_default_if_empty=false, $default='') {
		foreach ((array)$value_keys as $key)
			$arr = suarr::_flatten_values($arr, $key, $use_default_if_empty, $default);
		return $arr;
	}
	
	function _flatten_values($arr, $value_key = 0, $use_default_if_empty=false, $default='') {
		if (!is_array($arr) || !count($arr)) return array();
		$newarr = array();
		foreach ($arr as $key => $array_value) {
			$success = false;
			
			if (is_array($array_value)) {
				if (isset($array_value[$value_key])) {
					$newarr[$key] = $array_value[$value_key];
					$success = true;
				}
			} elseif (is_object($array_value)) {
				if (isset($array_value->$value_key)) {
					$newarr[$key] = $array_value->$value_key;
					$success = true;
				}
			}
			
			if (!$success && $use_default_if_empty)
				$newarr[$key] = $default;
		}
		return $newarr;
	}
	
	function key_replace($array, $key_changes, $recursive = true, $return_replaced_only = false) {
		$newarray = array();
		foreach ($array as $key => $value) {
			$changed = false;
			if ($recursive && is_array($value)) {
				$oldvalue = $value;
				$value = suarr::key_replace($value, $key_changes, true, $return_replaced_only);
				if ($oldvalue != $value) $changed = true;
			}
			
			if (isset($key_changes[$key])) {
				$key = $key_changes[$key];
				$changed = true;
			}
			
			if ($changed || !$return_replaced_only)
				$newarray[$key] = $value;
		}
		return $newarray;
	}
	
	function value_replace($array, $value_changes, $recursive = true, $return_replaced_only = false) {
		$newarray = array();
		
		foreach ((array)$array as $key => $value) {
			
			$oldvalue = $value;
			
			if ($recursive && is_array($value))
				$value = suarr::value_replace($value, $value_changes, true);
			elseif (isset($value_changes[$value]))
				$value = $value_changes[$value];
			
			if ($value != $oldvalue || !$return_replaced_only)
				$newarray[$key] = $value;
		}
		
		return $newarray;
	}
	
	function simplify($arr, $keyloc, $valloc, $use_default_if_empty=false, $default='') {
		$keys = suarr::flatten_values($arr, $keyloc, $use_default_if_empty, $default);
		$values = suarr::flatten_values($arr, $valloc, $use_default_if_empty, $default);
		return array_combine($keys, $values);
	}
	
	function has_keys($array, $keys) {
		if (is_array($array) && is_array($keys)) {
			if (count($keys))
				return count(array_diff($keys, array_keys($array))) == 0;
			
			return true;
		}
		
		return false;
	}
	
	function key_check($array, $function) {
		return (count($array) == count(array_filter(array_keys($array), $function)));
	}
	
	//Function based on http://php.net/manual/en/function.array-unique.php#82508
	function in_array_i($str, $a) {
		foreach($a as $v){
			if (strcasecmp($str, $v)==0)
				return true;
		}
		return false;
	}

	//Function based on http://php.net/manual/en/function.array-unique.php#82508
	function array_unique_i($a) {
		$n = array();
		foreach($a as $k=>$v) {
			if (!suarr::in_array_i($v, $n))
				$n[$k] = $v;
		}
		return $n;
	}
	
	function replace_empty_values_with_keys($array) {
		$newarray = array();
		foreach ($array as $key => $value) {
			if (empty($value))
				$newarray[$key] = $key;
			else
				$newarray[$key] = $value;
		}
		return $newarray;
	}
}

?>