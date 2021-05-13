<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://aconline.com.br
 * @since             1.0.0
 * @package           Library_Functions_Mega
 *
 * @wordpress-plugin
 * Plugin Name:       Library Functions Mega
 * Plugin URI:        https://aconline.com.br
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Alan Silva
 * Author URI:        https://aconline.com.br
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       library-functions-mega
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'LIBRARY_FUNCTIONS_MEGA_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-library-functions-mega-activator.php
 */
function activate_library_functions_mega() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-library-functions-mega-activator.php';
	Library_Functions_Mega_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-library-functions-mega-deactivator.php
 */
function deactivate_library_functions_mega() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-library-functions-mega-deactivator.php';
	Library_Functions_Mega_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_library_functions_mega' );
register_deactivation_hook( __FILE__, 'deactivate_library_functions_mega' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-library-functions-mega.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-library-functions-loader-api.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_library_functions_mega() {

	$apiFunctions = new Library_Functions_Api_Loader();
	$apiFunctions->loadFunctionsApi();
	
	$plugin = new Library_Functions_Mega();
	$plugin->run();

}
run_library_functions_mega();

// novo comentario agora mesmo vamos la
