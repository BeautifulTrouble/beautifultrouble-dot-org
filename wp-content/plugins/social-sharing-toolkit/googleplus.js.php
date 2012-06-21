<?php
header("Content-type: text/javascript");
if (isset($_GET['lang'])) {
	echo 'window.___gcfg = {lang: \''.$_GET['lang'].'\'};';
}
?>