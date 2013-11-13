<?php

class RFBP_API {

	private $app_id = '';
	private $app_secret = '';
	private $error;
	
	public function __construct($app_id, $app_secret, $fb_id = '')
	{
		$this->app_id = $app_id;
		$this->app_secret = $app_secret;
		$this->fb_id = $fb_id;
	}

	public function get_posts()
	{
		$result = $this->call("{$this->fb_id}/posts", array(
			'fields' => 'id,picture,type,from,message,status_type,object_id,picture,name,caption,description,link,created_time,comments.limit(1).summary(true),likes.limit(1).summary(true)',
			'access_token' => "{$this->app_id}|{$this->app_secret}"
		));
		
		if($result) {
			if(isset($result->data)) {
				return $result->data;
			} else {
				$this->error = $result->error;
				return false;
			}
		} 

		return false;
	}

	private function call($endpoint, array $data = array())
	{
		if(empty($this->app_id) || empty($this->app_secret)) { return false; }

		$url = "https://graph.facebook.com/{$endpoint}";

		$url = add_query_arg($data, $url);

		$response = wp_remote_get($url, array( 
			'timeout' => 10,
			'headers' => array('Accept-Encoding' => ''),
			'sslverify' => false
			) 
		); 

		if(is_wp_error($response)) {
			$this->error = $response->get_error_message();
			return false;
		} else {			
			$body = wp_remote_retrieve_body($response);
			return json_decode($body);
		}
	}
	
	public function has_error() {
		return (!empty($this->error));
	}

	public function get_error_message()
	{
		if(is_object($this->error)) {
			return $this->error->message;
		}

		return $this->error;		
	}
}