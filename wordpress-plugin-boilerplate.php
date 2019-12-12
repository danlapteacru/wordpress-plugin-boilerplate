<?php
/*
	* Plugin Name: WordPress Plugin Boilerplate
	* Plugin URI: https://www.bluewebteam.com
	* Description: WordPress Plugin Boilerplate
	* Version: 1.0.0
	* Author: Dan Lapteacru
	* Author URI: https://www.bluewebteam.com
	*
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BWT_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'BWT_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
define( 'BWT_IMG', BWT_URL . '/dist/images/' );
define( 'BWT_DIST', BWT_URL . '/dist/' );
define( 'BWT_VIEWS', BWT_DIR . '/resources/views/' );
define( 'BWT_SLUG', 'wordpress-plugin-boilerplate' );

define( 'BWT_PLUGIN', [
	'Name' 	  => 'WordPress Plugin Boilerplate',
	'Version' => '1.0.0'
] );

define( 'BWT_VERSION',  BWT_PLUGIN['Version'] );


require_once( BWT_DIR . '/app/Autoload.php' );
require_once( BWT_DIR . '/resources/helpers.php' );

if ( file_exists( BWT_DIR . '/vendor/autoload.php' ) ) {
	require_once( BWT_DIR . '/vendor/autoload.php' );
}

use bluewebteam\App;
use bluewebteam\App\Controllers;

class BWTPluginBoilerplate extends App\Singleton
{
	/**
	 * Plugin text domain for translations
	 */
	const TEXTDOMAIN = BWT_SLUG;
	const VERSION    = BWT_VERSION;
	const SLUG       = BWT_SLUG;

	/**
	 * Textual plugin name
	 *
	 * @var string
	 */
	public static $pluginName;

	/**
	 * Variable-style plugin name
	 *
	 * @var string
	 */
	public static $pluginSlug = self::SLUG;

	/**
	 * Current plugin version
	 *
	 * @var float
	 */
	public static $version = self::VERSION;

	/**
	 * Plugin main entry point
	 *
	 * protected constructor prevents creating another plugin instance with "new" operator
	 */
	protected function __construct()
	{
		// init plugin name and version
		self::$pluginName = __('WordPress Plugin Boilerplate', self::TEXTDOMAIN);
		self::$version = self::VERSION;

		// run all init actions after theme setup to be able to add hooks from theme
		add_action( 'after_setup_theme', [
			$this,
			'init'
		] );
	}

	public function init(): void {
		// init controllers
		$this->initControllers();
	}

	/**
	 * Init all controllers to support post edit pages and admin configuration pages
	 */
	public function initControllers()
	{
		// we use wp_doing_ajax to prevent version check under ajax
		if ( ! wp_doing_ajax() ) {
			new Controllers\AdminController();
		} else {

			if ( !is_admin() )
				return;

			new Controllers\AdminController();
			new Controllers\SettingsController();
		}
	}
}

BWTPluginBoilerplate::run();