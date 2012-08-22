<?php
/*
JLFunctions Number Class
Copyright (c)2011 John Lamansky
*/

class sunum {

	function lowest() {
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