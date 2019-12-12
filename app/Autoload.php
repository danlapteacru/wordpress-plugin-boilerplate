<?php

namespace bluewebteam\App;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SPL autoload registration for plugin to prevent using file includes
 */
class Autoloader
{

	/**
	 * Class contructor check PHP version and register SPL autoload callback function
	 */
	public function __construct()
	{
		if ( version_compare( PHP_VERSION, "7.1.0", "<" ) )
		{
			add_action( 'admin_notices', function(): void {
				$message = __( 'Sorry this plugin use some <strong>PHP VERSION 7.1</strong> functionality. If you want to use this plugin please update your server <strong>PHP VERSION 7.1</strong> or higher.', 'sample-text-domain' );

				printf( '<div class="notice notice-error"><p>%2$s</p></div>', esc_html( $message ) );
			});

			return;
		}

		spl_autoload_register([
            $this,
            'loader'
        ]);
	}

	/**
	 * Search for the class by namespace path and include it if found.
	 *
	 * @param string $class_name
	 */
	public function loader( string $class_name ): void
	{
		$class_path = str_replace('\\', '/', $class_name);

		// check if this class is related to the plugin namespace. exit if not
		if ( strpos($class_path, 'bluewebteam') !== 0 ) {
			return;
		}

		$path = preg_replace('/^bluewebteam\//', BWT_DIR . '/', $class_path) . '.php';

		if ( is_file($path) ) {
			require_once( $path );
		}
	}

}

new Autoloader();
