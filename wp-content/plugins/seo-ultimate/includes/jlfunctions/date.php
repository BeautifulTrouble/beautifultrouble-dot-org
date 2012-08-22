<?php
/*
JLFunctions Date Class
Copyright (c)2011 John Lamansky
*/

class sudate {
	function gmt_to_unix($gmt) {
		if (preg_match('#([0-9]{1,4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})#', $gmt, $matches))
			return gmmktime($matches[4], $matches[5], $matches[6], $matches[2], $matches[3], $matches[1]);
		else
			return 0;
	}
}
?>