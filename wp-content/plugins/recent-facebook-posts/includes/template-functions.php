<?php

function recent_facebook_posts($args = array())
{
	echo RFBP::instance()->output($args);
}