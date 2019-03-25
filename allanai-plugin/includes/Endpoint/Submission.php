<?php
/**
 * WP-Reactivate
 *
 *
 * @package  	Allan AI Plugin
 * @author    Allan AI
 * @license   GPL-3.0
 * @link      https://allanai.io
 * @copyright 2019 Allan AI
 */

namespace AllanAI\WPR\Endpoint;
use AllanAI\WPR;

/**
 * @subpackage REST_Controller
 */
class Submission {
    /**
	 * Instance of this class.
	 *
	 * @since    0.8.1
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     0.8.1
	 */
	private function __construct() {
        $plugin = WPR\Plugin::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
	}

    /**
     * Set up WordPress hooks and filters
     *
     * @return void
     */
    public function do_hooks() {
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }

	/**
	 * Return an instance of this class.
	 *
	 * @since     0.8.1
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
			self::$instance->do_hooks();
		}

		return self::$instance;
	}

  public function register_routes() {
    $version = '1';
    $namespace = $this->plugin_slug . '/v' . $version;
    $endpoint = '/submission/'; // set our new endpoint

    register_rest_route( $namespace, $endpoint, array(
       array(
         'methods'   => \WP_REST_Server::CREATABLE,
         'callback'  => array( $this, 'process_submission' ), // set to our new callback function
         'args'      => array( // the expected parameters for this route
           'name' => array(
               'required' => true, // means that this parameter must be passed (whatever its value) in order for the request to succeed
               'type' => 'string',
               'description' => 'The user\'s name',
               'validate_callback' => function( $param, $request, $key ) { return ! empty( $param ); } // prevent submission of empty field
           ),
           'email' => array(
               'required' => true,
               'type' => 'string',
               'description' => 'The user\'s email address',
               'format' => 'email' // we set the format in order to take advantage of built-in email field validation
           ),
           'message' => array(
               'required' => true,
               'type' => 'string',
               'description' => 'The user\'s message',
               'validate_callback' => function( $param, $request, $key ) { return ! empty( $param ); }
           ),
         ),
       )
     )
   );
 }

 /**
  * Get Example
  *
  * @param WP_REST_Request $request Full data about the request.
  * @return WP_Error|WP_REST_Request
  */


  public function process_submission( $request ) {

    $name = $request->get_param( 'name' );
    $email = $request->get_param( 'email' );
    $message = $request->get_param( 'message' );

    return new \WP_REST_Response( array(
            'success'   => true,
            'value'     => $message . ' - ' . $name . ' (' . $email . ')'
        ), 200 );

  }
}
