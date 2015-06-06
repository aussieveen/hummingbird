<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           myanimelist
 *
 * @wordpress-plugin
 * Plugin Name:       My Anime List
 * Plugin URI:        http://example.com/myanimelist-uri/
 * Description:       Allows you to display content from My Anime List using their REST api. 
 * Version:           1.0.0
 * Author:            Simon McWhinnie
 * Author URI:        http://www.veen-online.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       myanimelist
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-myanimelist-activator.php
 */
function activate_myanimelist() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-myanimelist-activator.php';
	myanimelist_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-myanimelist-deactivator.php
 */
function deactivate_myanimelist() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-myanimelist-deactivator.php';
	myanimelist_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_myanimelist' );
register_deactivation_hook( __FILE__, 'deactivate_myanimelist' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-myanimelist.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_myanimelist() {

	$plugin = new myanimelist();
	$plugin->run();

}
run_myanimelist();
