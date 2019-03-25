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
 *
 * @wordpress-plugin
 * Plugin Name:       Allan AI Plugin
 * Plugin URI:        https://allanai.io
 * Description:       Allan AI chat window plugin
 * Version:           1.0.0
 * Author:            Allan AI
 * Author URI:        https://allanai.io
 * Text Domain:       allanai-plugin
 * License:           GPL-3.0
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:       /languages
 */


namespace AllanAI\WPR;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'ALLANAI_PLUGIN_VERSION', '1.0.0' );


/**
 * Autoloader
 *
 * @param string $class The fully-qualified class name.
 * @return void
 *
 *  * @since 1.0.0
 */
spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    $prefix = __NAMESPACE__;

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/includes/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

/**
 * Initialize Plugin
 *
 * @since 1.0.0
 */
function init() {
	$wpr = Plugin::get_instance();
	$wpr_shortcode = Shortcode::get_instance();
	$wpr_admin = Admin::get_instance();
	$wpr_rest_admin = Endpoint\Admin::get_instance();
	$wpr_rest_submission = Endpoint\Submission::get_instance();
}
add_action( 'plugins_loaded', 'AllanAI\\WPR\\init' );



/**
 * Register the widget
 *
 * @since 1.0.0
 */
function widget_init() {
	return register_widget( new Widget );
}
add_action( 'widgets_init', 'AllanAI\\WPR\\widget_init' );

/**
 * Register activation and deactivation hooks
 */
register_activation_hook( __FILE__, array( 'AllanAI\\WPR\\Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'AllanAI\\WPR\\Plugin', 'deactivate' ) );
