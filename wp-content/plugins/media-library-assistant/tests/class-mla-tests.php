<?php
/**
 * Provides basic run-time tests to ensure the plugin can run in the current WordPress envrionment
 *
 * @package Media Library Assistant
 * @since 0.1
 */

/**
 * Class MLA (Media Library Assistant) Test provides basic run-time tests
 * to ensure the plugin can run in the current WordPress envrionment.
 *
 * @package Media Library Assistant
 * @since 0.1
 */
class MLATest {
	/**
	 * True if WordPress version is 3.5 or newer
	 *
	 * @since 0.60
	 *
	 * @var	boolean
	 */
	public static $wordpress_3point5_plus = null;
	
	/**
	 * Initialization function, similar to __construct()
	 *
	 * @since 0.60
	 *
	 * @return	void
	 */
	public static function initialize() {
		MLATest::$wordpress_3point5_plus = version_compare( get_bloginfo( 'version' ), '3.5', '>=' );
	}

	/**
	 * Test that your PHP version is at least that of the $min_php_version
	 *
	 * @since 0.1
	 *
	 * @param	string	representing the minimum required version of PHP, e.g. '5.3.2'
	 *
	 * @return	string	'' if pass else error message
	 */
	public static function min_php_version( $min_version )
	{
		$current_version = phpversion();
		if ( version_compare( $current_version, $min_version, '<' ) ) {
			return sprintf( '<li>The plugin requires PHP %1$s or newer; you have %2$s.<br />Contact your system administrator about updating your version of PHP.</li>', /*$1%s*/ $min_version, /*$2%s*/ $current_version );
		}

		return '';
	}
	
	/**
	 * Test that your WordPress version is at least that of the $min_version
	 *
	 * @since 0.1
	 *
	 * @param string	representing the minimum required version of WordPress, e.g. '3.3.0'
	 *
	 * @return	string	'' if pass else error message
	 */
	public static function min_WordPress_version( $min_version )
	{
		$current_version = get_bloginfo( 'version' );
		if ( version_compare( $current_version, $min_version, '<' ) ) {
			return sprintf( '<li>The plugin requires WordPress %1$s or newer; you have %2$s.<br />Contact your system administrator about updating your version of WordPress.</li>', /*$1%s*/ $min_version, /*$2%s*/ $current_version );
		}

		return '';
	}
	
} // class MLATest
?>