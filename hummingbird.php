<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/simonmcwhinnie/hummingbird
 * @since             0.0.1
 * @package           hummingbird
 *
 * @wordpress-plugin
 * Plugin Name:       Hummingbird
 * Plugin URI:        https://github.com/simonmcwhinnie/hummingbird/
 * Description:       Allows you to display content from Hummingbird.me using their REST api.
 * Version:           0.0.2
 * Author:            Simon McWhinnie
 * Author URI:        http://www.veen-online.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hummingbird
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-hummingbird-activator.php
 */
function activate_hummingbird() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hummingbird-activator.php';
	hummingbird_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-hummingbird-deactivator.php
 */
function deactivate_hummingbird() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hummingbird-deactivator.php';
	hummingbird_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_hummingbird' );
register_deactivation_hook( __FILE__, 'deactivate_hummingbird' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-hummingbird.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.0.1
 */
function run_hummingbird() {

	$plugin = new hummingbird();
	$plugin->run();

}
run_hummingbird();
