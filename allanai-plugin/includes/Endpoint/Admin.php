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
class Admin {
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

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {
        $version = '1';
        $namespace = $this->plugin_slug . '/v' . $version;
        $endpoint = '/admin/';

        register_rest_route( $namespace, $endpoint, array(
            array(
                'methods'               => \WP_REST_Server::READABLE,
                'callback'              => array( $this, 'get_admin_values' ),
                'args'                  => array(),
            ),
        ) );

        register_rest_route( $namespace, $endpoint, array(
            array(
                'methods'               => \WP_REST_Server::CREATABLE,
                'callback'              => array( $this, 'update_admin_values' ),
                'permission_callback'   => array( $this, 'admin_permissions_check' ),
                'args'                  => array( // the expected parameters for this route
									'email'=> array(
										'requred' => true,
										'type' => 'string',
										'description' => 'The user\'s email address',
										'format' => 'email',
									),
									'agentID'=> array(
										'required' => false,
										'type' => 'string',
										'description' => 'The user\'s chatbot agent ID'
									)
								),
            ),
        ) );

        register_rest_route( $namespace, $endpoint, array(
            array(
                'methods'               => \WP_REST_Server::EDITABLE,
                'callback'              => array( $this, 'update_admin_values' ),
                'permission_callback'   => array( $this, 'admin_permissions_check' ),
                'args'                  => array( // the expected parameters for this route
									'email'=> array(
										'requred' => true,
										'type' => 'string',
										'description' => 'The user\'s email address',
										'format' => 'email',
									),
									'agentID'=> array(
										'required' => false,
										'type' => 'string',
										'description' => 'The user\'s chatbot agent ID'
									)
								),
            ),
        ) );

        register_rest_route( $namespace, $endpoint, array(
            array(
                'methods'               => \WP_REST_Server::DELETABLE,
                'callback'              => array( $this, 'delete_email' ),
                'permission_callback'   => array( $this, 'admin_permissions_check' ),
                'args'                  => array(),
            ),
        ) );

    }

    /**
     * Get Example
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public function get_admin_values( $request ) {
        $email = get_option( 'allanai_plugin_email' );
				$agentID = get_option('allanai_plugin_agentID');

        // Don't return false if there is no option
        if ( !$email ) {
          $email ='';
        }
				if (!$agentID) {
        	$agentID='';
        }

        return new \WP_REST_Response( array(
	        'success' => true,
					'value' => array('email'=>$email, 'agentID'=>$agentID)
        ), 200 );
    }

    /**
     * Create OR Update Example
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public function update_admin_values( $request ) {
				$agentID_update = update_option('allanai_plugin_agentID', $request->get_param('agentID'));
        $email_update = update_option( 'allanai_plugin_email', $request->get_param( 'email' ));

				if($agentID_update || $email_update){
					$updated = True;
				} else {
					$updated = False;
				};

        return new \WP_REST_Response( array(
            'success'   => $updated,
            'value'     => array('email'=> $request->get_param( 'email' ), 'agentID'=> $request->get_param('agentID'))
        ), 200 );
    }

    /**
     * Delete Example
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public function delete_email( $request ) {
        $email_deleted = delete_option( 'allanai_plugin_email' );
				$agentID_deleted = delete_option('allanai_plugin_agentID');

				if($agentID_deleted && $email_deleted){
					$deleted = True;
				} else {
					$deleted = False;
				};

        return new \WP_REST_Response( array(
            'success'   => $deleted,
            'value'     => array('email'=> '', 'agentID'=> '')
        ), 200 );
    }

    /**
     * Check if a given request has access to update a setting
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function admin_permissions_check( $request ) {
        return current_user_can( 'manage_options' );
    }
}
