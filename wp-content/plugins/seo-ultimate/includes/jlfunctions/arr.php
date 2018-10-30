<?php
/*
JLFunctions Array Class
Copyright (c)2009-2012 John Lamansky
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
	static function aprintf($keyformat, $valueformat, $array) {
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
	 * Removes a value from the array if found.
	 * 
	 * @param array $array Passed by reference.
	 * @param mixed $value The value to remove.
	 */
	static function remove_value(&$array, $value) {
		$index = array_search($value, $array);
		if ($index !== false)
			unset($array[$index]);
	}
	
	/**
	 * Turns a string into an array, with one line per array element.
	 * 
	 * @param string $lines
	 * 
	 * @return array
	 */
	static function explode_lines($lines) {
		$lines = explode("\n", $lines);
		$lines = array_map('trim', $lines); //Remove any \r's
		return $lines;
	}
	
	/**
	 * Sorts an array of arrays by using a given array key to locate string values in the second-level arrays and sorting the first-level array accordingly.
	 * Alphabetical sorting is used.
	 * 
	 * @param array $arr Passed by reference
	 * @param string $valuekey The array key used to access the string values by which the arrays in $arr are to be sorted
	 */
	static function vksort(&$arr, $valuekey) {
		$valuekey = sustr::preg_filter('A-Za-z0-9 ', $valuekey);
		uasort($arr, create_function('$a,$b', 'return strcasecmp($a["'.$valuekey.'"], $b["'.$valuekey.'"]);'));
	}
	
	/**
	 * Sorts an array of arrays by using a given array key to locate string values in the second-level arrays and sorting the first-level array accordingly.
	 * Reverse length sorting is used. (Longest strings are first, shortest strings last.)
	 * 
	 * @param array $arr Passed by reference
	 * @param string $valuekey The array key used to access the string values by which the arrays in $arr are to be sorted
	 */
	static function vklrsort(&$arr, $valuekey) {
		$valuekey = sustr::preg_filter('A-Za-z0-9 ', $valuekey);
		uasort($arr, create_function('$a,$b', 'return strlen($b["'.$valuekey.'"]) - strlen($a["'.$valuekey.'"]);'));
	}
	
	/**
	 * Flattens a multidimensional array (or an array of objects) into a single-dimensional array by discarding all but one element of the higher-level array(s).
	 * Works like WordPress's wp_list_pluck(), except this function is recursive and supports inserting a default value if a subarray doesn't have the specified key.
	 * 
	 * Usage example:
	 * ---
	 * $post_types = get_post_types(array('public' => true), 'objects');
	 * $singular_names = suarr::flatten_values($post_types, array('labels', 'singular_name'));
	 * ---
	 * 
	 * @param array $arr The multidimensional array (or array of objects) to flatten
	 * @param array|string|int $value_keys Each array/object in $arr will be replaced with its element/property named $value_keys. If $value_keys is an array, this will be done recursively.
	 * @param bool $use_default_if_empty If a given array/object in $arr does not have an element/property named $value_keys, should a default value be inserted?
	 * @param mixed $default
	 * 
	 * @return array The flattened array
	 */
	static function flatten_values($arr, $value_keys, $use_default_if_empty=false, $default='') {
		foreach ((array)$value_keys as $key)
			$arr = suarr::_flatten_values($arr, $key, $use_default_if_empty, $default);
		return $arr;
	}
	
	static function _flatten_values($arr, $value_key = 0, $use_default_if_empty=false, $default='') {
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
	
	/**
	 * Renames keys in an array. Supports recursion.
	 * 
	 * @param array $array The array whose keys should be renamed
	 * @param array $key_changes An array of (old_key => new_key)
	 * @param bool $recursive Whether or not to do the same for subarrays of $array
	 * @param bool $return_replaced_only If true, then elements whose keys were *not* renamed will be discarded
	 * 
	 * @return array
	 */
	static function key_replace($array, $key_changes, $recursive = true, $return_replaced_only = false) {
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
	
	/**
	 * Runs a find/replace operation on values in an array.
	 * 
	 * @param array $array The array whose values should be replaced
	 * @param array $value_changes An array of (old_value => new_value)
	 * @param bool $recursive Whether or not to do the same for subarrays of $array
	 * @param bool $return_replaced_only If true, then elements *not* replaced will be discarded
	 * 
	 * @return array
	 */
	static function value_replace($array, $value_changes, $recursive = true, $return_replaced_only = false) {
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
	
	/**
	 * Goes through an array of arrays/objects, plucks two elements/properties from each array/object, and constructs a new array, with one element/property as the key, and the other as the value.
	 * 
	 * @param array $arr The array to run this process on.
	 * @param array|string|int $keyloc The location (either a string/integer key, or an array of nested keys) of the elements' values to be used as keys in the new array
	 * @param array|string|int $valloc The location (either a string/integer key, or an array of nested keys) of the elements' values to be used as values in the new array
	 * @param bool $use_default_if_empty Whether or not to use a default value in the event that nothing is located at $keyloc or $valloc for a given array/object in $arr
	 * @param mixed $default
	 * 
	 * @return array
	 */
	static function simplify($arr, $keyloc, $valloc, $use_default_if_empty=false, $default='') {
		$keys = suarr::flatten_values($arr, $keyloc, $use_default_if_empty, $default);
		$values = suarr::flatten_values($arr, $valloc, $use_default_if_empty, $default);
		return array_combine($keys, $values);
	}
	
	//Function based on http://php.net/manual/en/function.array-unique.php#82508
	static function in_array_i($str, $a) {
		foreach($a as $v){
			if (strcasecmp($str, $v)==0)
				return true;
		}
		return false;
	}

	//Function based on http://php.net/manual/en/function.array-unique.php#82508
	static function array_unique_i($a) {
		$n = array();
		foreach($a as $k=>$v) {
			if (!suarr::in_array_i($v, $n))
				$n[$k] = $v;
		}
		return $n;
	}
}

?>