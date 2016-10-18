<?php

if( ! defined( 'RFBP_VERSION' ) ) {
	exit;
}

class RFBP_API {

	/**
	 * @var string
	 */
	private $app_id = '';

	/**
	 * @var string
	 */
	private $app_secret = '';

	/**
	 * @var
	 */
	private $error;

	/**
	 * @param        $app_id
	 * @param        $app_secret
	 * @param string $fb_id
	 */
	public function __construct( $app_id, $app_secret, $fb_id = '' )
	{
		$this->app_id = $app_id;
		$this->app_secret = $app_secret;
		$this->fb_id = $fb_id;
	}

	/**
	 * Fetch posts from the given Facebook page.
	 *
	 * @return array|bool
	 */
	public function get_posts()
	{
		$result = $this->call("{$this->fb_id}/posts", array(
			'fields' => 'id,picture,type,from,message,status_type,object_id,name,caption,description,link,created_time,comments.limit(1).summary(true),likes.limit(1).summary(true)'
		));

		if( is_object( $result ) ) {
			if( isset( $result->data ) ) {
				return $this->format_data( $result->data );
			} elseif( isset( $result->error->message ) ) {
				$this->error = __( 'Facebook error:', 'recent-facebook-posts' ) . ' <code>' . $result->error->message . '</code>';
				return false;
			}
		} 

		return false;
	}

	/**
	 * @param $data
	 *
	 * @return array
	 */
	private function format_data( $data ) {

		$posts = array();
		foreach ( $data as $p ) {

			// skip this "post" if it is not of one of the following types
			if ( ! in_array( $p->type, array( 'status', 'photo', 'video', 'link' ) ) ) {
				continue;
			}

			// skip empty status updates
			if ( $p->type === 'status' && ( ! isset( $p->message ) || empty( $p->message ) ) ) {
				continue;
			}

			// skip empty links.
			if ( $p->type === 'link' && ! isset( $p->name ) && ( ! isset( $p->message ) || empty( $p->message ) ) ) {
				continue;
			}

			// skip friend approvals
			if ( $p->type === 'status' && $p->status_type === 'approved_friend' ) {
				continue;
			}

			//split user and post ID (userID_postID)
			$idArray = explode( "_", $p->id );

			$post = array();
			$post['type'] = sanitize_text_field( $p->type );
			$post['content'] = '';
			$post['image'] = null;
			$post['name'] = '';
			$post['post_link'] = '#';

			if( isset( $p->message ) ) {
				// remove emoji's
				$post['content'] = preg_replace( '/[\x{1F600}-\x{1F64F}]|[\x{1F300}-\x{1F5FF}]|[\x{1F680}-\x{1F6FF}]|[\x{1F1E0}-\x{1F1FF}]/u', '', $p->message );

				// sanitize content
				$post['content'] = sanitize_text_field( $post['content'] );
			}

			// set post name and url
			if (isset($p->name)) $post['name'] = $p->name;
			if (isset($p->link)) $post['post_link'] = $p->link;

			// set type specific content
			switch( $p->type ) {

				case 'photo':
					// set post image, use protocol relative URL
					$post['image'] = "//graph.facebook.com/". $p->object_id . '/picture';
					break;

				case 'video':
					// set thumbnail
					$post['image'] = $p->picture;
					break;

				case 'link':
					$post['link_image'] = ( isset( $p->picture ) ) ? $p->picture : '';
					$post['link_name'] = ( isset( $p->name ) ) ? sanitize_text_field( $p->name ) : '';
					$post['link_caption'] = ( isset( $p->caption ) ) ? sanitize_text_field( $p->caption ) : '';
					$post['link_description'] = ( isset( $p->description ) ) ? sanitize_text_field( $p->description ) : '';
					$post['link_url'] = $p->link;
					break;
			}

			// calculate post like and comment counts
			if ( isset( $p->likes->summary->total_count ) ) {
				$post['like_count'] = absint( $p->likes->summary->total_count );
			} else {
				$post['like_count'] = 0;
			}

			if ( isset( $p->comments->summary->total_count ) ) {
				$post['comment_count'] = absint( $p->comments->summary->total_count );
			} else {
				$post['comment_count'] = 0;
			}

			$post['timestamp'] = strtotime( $p->created_time );
			$post['url'] = "https://www.facebook.com/". $this->fb_id . "/posts/" . $idArray[1];

			// add to posts array
			$posts[] = $post;
		}

		return $posts;
	}

	/**
	 * Fetch 1 post from Facebook to see if the configuration works
	 *
	 * @return bool
	 */
	public function ping()
	{
		$result = $this->call("{$this->fb_id}/posts", array('fields' => 'name', 'limit' => 1) );

		if( is_object( $result ) ) {

			if( isset( $result->data ) ) {
				return true;
			} elseif( isset( $result->error->message ) ) {
				$this->error = __( 'Facebook error:', 'recent-facebook-posts' ) . ' <code>' . $result->error->message . '</code>';
				return false;
			}
		}

		return false;
	}

	/**
	 * @param string $endpoint
	 * @param array $data
	 *
	 * @return array|bool|mixed
	 */
	private function call( $endpoint, array $data = array() )
	{
		// Only do something if an App ID and Secret is given
		if ( empty( $this->app_id ) || empty( $this->app_secret ) ) {
			return false;
		}

		// Format URL
		$url = "https://graph.facebook.com/{$endpoint}";

		// Add access token to data array
		$data['access_token'] = "{$this->app_id}|{$this->app_secret}";
		
		// Add culture to localize returned content
		$data['locale'] = get_locale();

		// Add all data to URL
		$url = add_query_arg( $data, $url );

		$response = wp_remote_get($url, array( 
			'timeout' => 10,
			'headers' => array( 'Accept-Encoding' => '' ),
			'sslverify' => false
			) 
		); 

		// Did the request succeed?
		if( is_wp_error( $response ) ) {
			$this->error = __( 'Connection error:', 'recent-facebook-posts' ) . ' <code>' . $response->get_error_message() . '</code>';
			return false;
		} else {			
			$body = wp_remote_retrieve_body($response);
			return json_decode( $body );
		}
	}

	/**
	 * @return bool
	 */
	public function has_error() {
		return ( ! empty( $this->error ) );
	}

	/**
	 * @return mixed
	 */
	public function get_error_message()
	{
		if( is_object( $this->error ) ) {
			return $this->error->message;
		}

		return $this->error;		
	}
}
