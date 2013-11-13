<?php

function rfbp_make_clickable($text, $target)
{
	$clickable_text = make_clickable($text);

	if(!empty($target)) {
		return str_replace('<a href="', '<a target="'.$target.'" href="', $clickable_text);
	}

	return $clickable_text;
}

function rfbp_time_ago($timestamp) 
{
	$diff = time() - (int) $timestamp;

		if ($diff == 0) 
			return __('just now', "recent-facebook-posts");

		$intervals = array
		(
			1                   => array('year',    31556926),
			$diff < 31556926    => array('month',   2628000),
			$diff < 2629744     => array('week',    604800),
			$diff < 604800      => array('day',     86400),
			$diff < 86400       => array('hour',    3600),
			$diff < 3600        => array('minute',  60),
			$diff < 60          => array('second',  1)
			);

		$value = floor($diff / $intervals[1][1]);

		$time_unit = $intervals[1][0];

		switch($time_unit) {
			case 'year':
				return sprintf(_n('1 year ago', '%d years ago', $value, "recent-facebook-posts"), $value); 
			break;

			case 'month':
				return sprintf(_n('1 month ago', '%d months ago', $value, "recent-facebook-posts"), $value); 
			break;

			case 'week':
				return sprintf(_n('1 week ago', '%d weeks ago', $value, "recent-facebook-posts"), $value); 
			break;

			case 'day':
				return sprintf(_n('1 day ago', '%d days ago', $value, "recent-facebook-posts"), $value); 
			break;

			case 'hour':
				return sprintf(_n('1 hour ago', '%d hours ago', $value, "recent-facebook-posts"), $value); 
			break;

			case 'minute':
				return sprintf(_n('1 minute ago', '%d minutes ago', $value, "recent-facebook-posts"), $value); 
			break;

			case 'second':
				return sprintf(_n('1 second ago', '%d seconds ago', $value, "recent-facebook-posts"), $value); 
			break;

			default:
				return sprintf(__('Some time ago', "recent-facebook-posts")); 
			break;
		}
		
		
}