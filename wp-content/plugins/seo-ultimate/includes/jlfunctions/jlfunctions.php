<?php
/*
JLFunctions Library
Copyright (c)2009-2012 John Lamansky
*/

foreach (array('arr', 'html', 'io', 'num', 'str', 'url', 'web') as $jlfuncfile) {
	include dirname(__FILE__)."/$jlfuncfile.php";
}

?>