<?php
/*
JLFunctions Number Class
Copyright (c)2011-2012 John Lamansky
*/

class sunum {
	
	/**
	 * Returns the lowest of a set of numbers.
	 * 
	 * @param array|int $number,... The numbers may be passed as function arguments or as an array.
	 * 
	 * @return int The lowest of the numbers.
	 */
	static function lowest() {
		$numbers = func_get_args();
		$numbers = array_values($numbers);
		
		if (count($numbers)) {
			
			if (is_array($numbers[0]))
				$numbers = $numbers[0];
			
			if (array_walk($numbers, 'intval')) {
				sort($numbers, SORT_NUMERIC);
				return reset($numbers);
			}
		}
		return false;
	}
}
?>