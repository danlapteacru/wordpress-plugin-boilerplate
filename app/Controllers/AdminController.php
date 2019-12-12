<?php

namespace bluewebteam\App\Controllers;

use bluewebteam\App;

class AdminController extends App\Controller
{
	/**
	 * Init all wp-actions
	 */
	public function __construct()
	{
		parent::__construct();
		add_action('admin_menu', [
			$this,
			'initRoutes'
		]);
	}

	/**
	 * Init routes for settings page
	 *
	 */
	public function initRoutes(): void
	{
		$page_title = __( BWT_PLUGIN['Name'] . ' Settings', \BWTPluginBoilerplate::TEXTDOMAIN );
		add_options_page($page_title, $page_title, 'manage_options', BWT_SLUG . '-settings', [ $this, 'actionIndex' ] );
	}

	/**
	 * Render settings page
	 */
	public function actionIndex(): ?string
	{

		return $this->_render('admin/settings');
	}

}
