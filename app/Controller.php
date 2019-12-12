<?php

namespace bluewebteam\App;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 	Main controller
 */
class Controller
{

    /**
     * Controller Constructor
     */
    public function __construct() {

    }

    /**
     * Locate template.
     *
     * Locate the called template.
     * Search Order:
     * 1. /themes/theme/your-theme-name/$template_name
     * 2. /themes/theme/$template_name
     * 3. /plugins/your-plugin-name/resources/views/$template_name.
     *
     * @param 	string 	$template_name			Template to load.
     * @param 	string 	$string $template_path	Path to templates.
     * @param 	string	$default_path			Default path to template files.
     * @return 	string 							Path to the template file.
     */
    protected function locate_template( string $template_name, string $template_path = BWT_SLUG, string $default_path = BWT_VIEWS ): string {

        // Search template file in theme folder.
        $template = locate_template([
            $template_path . '/' . $template_name,
            $template_name
        ]);

        // Get plugins template file.
        if ( ! $template ) :
            $template = $default_path . $template_name;
        endif;

        return apply_filters( 'bwt_locate_template', $template, $template_name, $template_path, $default_path );
    }

	/**
	 * Function for render views
	 * @param null|string $template   file name to be rendered
	 * @param array $params     array of variables to be passed to the view file
	 * @return null|string
	 */
	protected function _render( ?string $template, array $params = [] ): ?string
	{
        if ( is_array( $params ) && isset( $params ) ) :
            extract( $params );
        endif;

        $template_file = $this->locate_template( $template ) . ".php";

        if ( file_exists( $template_file ) ) {
            include $template_file;

            wp_die();
        }

        return null;
	}

	/**
	 * Function for render views inside AJAX request
	 * Echo rendered content directly in output buffer
	 *
	 * @param null|string $template   file name to be rendered
	 * @param string $format    json|html   control which header content type should be sent
	 * @param array $params     array of variables to be passed to the view file
	 */
	protected function _renderAjax( ?string $template = null, string $format = 'html', array $params = [] ): void
	{
		if ( $format == 'json' ) {
			wp_send_json($params);
		} else {
			header("Content-Type: text/html; charset=" . get_bloginfo('charset'));
			ob_start();
			$this->_render($template, $params);
            echo ob_get_clean();
		}

        wp_die();
	}

}
