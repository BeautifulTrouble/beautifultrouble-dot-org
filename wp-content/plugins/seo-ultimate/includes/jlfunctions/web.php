<?php
/*
JLFunctions Web Class
Copyright (c)2010 John Lamansky
*/

class suweb {
	
	static function is_search_engine_ua($user_agent) {
		$ua_keywords = array(
			  'Googlebot' //Google
			, 'Yahoo', 'Y!' //Yahoo
			, 'Baiduspider' //Baidu (Chinese)
			, 'msnbot' //Bing
			, 'Yandex' //Yandex (Russian)
			, 'Sosospider' //Soso (Chinese)
			, 'Teoma' //Ask.com
			, 'sogou spider' //Sogou (Chinese)
			, 'Scooter', 'AltaVista' //AltaVista
		);
		
		foreach ($ua_keywords as $keyword) {
			if (sustr::ihas($user_agent, $keyword)) return true;
		}
		
		return false;
	}

}

?>