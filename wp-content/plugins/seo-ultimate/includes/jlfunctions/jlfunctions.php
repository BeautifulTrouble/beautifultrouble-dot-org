<?php
/*
JLFunctions Library
Copyright (c)2009-2011 John Lamansky
*/

foreach (array('arr', 'date', 'html', 'io', 'num', 'str', 'url', 'web') as $jlfuncfile) {
	include dirname(__FILE__)."/$jlfuncfile.php";
}

?>