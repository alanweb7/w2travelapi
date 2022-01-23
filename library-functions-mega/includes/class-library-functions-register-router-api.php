<?php

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Library_Functions_Mega
 * @subpackage Library_Functions_Mega/includes
 * @author     Alan Silva <alanweb7@gmail.com>
 */
class Library_Functions_Register_Router
{

    /**
     * The array of actions registered with WordPress.
     *
     * @since    1.0.0
     * @access   protected
     * @var      array    $actions    The actions registered with WordPress to fire when the plugin loads.
     */
    protected $actions;

    /**
     * The array of filters registered with WordPress.
     *
     * @since    1.0.0
     * @access   protected
     * @var      array    $filters    The filters registered with WordPress to fire when the plugin loads.
     */
    protected $filters;

    /**
     * Initialize the collections used to maintain the actions and filters.
     *
     * @since    1.0.0
     */
    public function __construct()
    {

        $this->actions = array();
        $this->filters = array();

    }

    public static function loadFunctionsRegisterRouter()
    {

        /**
         * rotas abertas
         */

        //The Following registers an api route with multiple parameters.
        add_action('rest_api_init', 'add_custom_users_api');

        function add_custom_users_api()
        {
            register_rest_route('mmw/v1', '/users/market=(?P<market>[a-zA-Z0-9-]+)/lat=(?P<lat>[a-z0-9 .\-]+)/long=(?P<long>[a-z0-9 .\-]+)', array(
                'methods' => 'GET',
                'callback' => 'get_custom_users_data',
            ));

            $name_espace = "myteste/v1";
            register_rest_route("{$name_espace}", '/teste', array(
                'methods' => 'POST, GET',
                'callback' => 'printText',
            ));

            $name_espace = "local/v1";
            register_rest_route("{$name_espace}", '/paises/(?P<country>\d+)/', array(
                'methods' => 'POST, GET',
                'callback' => 'wpshout_special_update_function',
            ));
            
            register_rest_route("{$name_espace}", '/paises/(?P<country>\d+)/(?P<state>\d+)', array(
                'methods' => 'POST, GET',
                'callback' => 'wpshout_special_update_function',
            ));
            
            register_rest_route("{$name_espace}", '/paises/', array(
                'methods' => 'POST, GET',
                'callback' => 'wpshout_special_update_function',
            ));
            
            $name_espace = "files/v1";
            
            register_rest_route("{$name_espace}", '/upload/(?P<id>\d+)', array(
                'methods' => 'POST, GET',
                'callback' => 'w2travel_upload_files', 
            ));
            
            register_rest_route("{$name_espace}", '/uploads/(?P<id>\d+)', array(
                'methods' => 'GET',
                'callback' => 'w2travel_get_uploads_files'
            ));
            
            register_rest_route("{$name_espace}", '/uploads/', array(
                'methods' => 'DELETE',
                'callback' => 'w2travel_delete_files',
            ));
            
            $name_espace = "info/v1";
            register_rest_route("{$name_espace}", '/metas/(?P<id>\d+)', array(
                'methods' => 'GET',
                'callback' => 'w2travel_get_meta_post',
            ));

            //SISTEMA DE LIKES
            $name_espace = "like/v1";
            register_rest_route("{$name_espace}", '/viagens/(?P<id>\d+)', array(
                'methods' => 'GET, DELETE',
                'callback' => 'w2travel_get_like_us', 
            )); 
            
            register_rest_route("{$name_espace}", '/viagens/(?P<id>\d+)', array(
                'methods' => 'POST',
                'callback' => 'w2travel_like_us', 
            ));
            
            
            $name_espace = "managepost/v1";
            register_rest_route("{$name_espace}", '/posts/(?P<id>\d+)', array(
                'methods' => 'POST',
                'callback' => 'w2travel_manage_post', 
            ));
            
           
            // sistem do perfil 
            $name_espace = "account/v1";
            register_rest_route("{$name_espace}", '/profile/', array(
                'methods' => 'GET',
                'callback' => 'w2travel_get_account_profile', 
            ));

            register_rest_route("{$name_espace}", '/profile/', array(
                'methods' => 'POST',
                'callback' => 'w2travel_post_account_profile',
            ));
            
            register_rest_route("{$name_espace}", '/remove/', array(
                'methods' => 'DELETE',
                'callback' => 'w2travel_delete_post_by_id', 
            ));

            register_rest_route("{$name_espace}", '/profile2/', array(
                'methods' => 'POST',
                'callback' => 'w2travel_get_account_profile', 
            ));
            
            register_rest_route("{$name_espace}", '/profile/', array(
                'methods' => 'DELETE',
                'callback' => 'w2travel_delete_account_profile',
            ));

            register_rest_route("{$name_espace}", '/profile/set-password/', array(
                'methods' => 'POST',
                'callback' => 'w2travel_post_account_profile_set_password',
            ));

            $name_espace = "app/v1";
            register_rest_route("{$name_espace}", '/settings/', array(
                'methods' => 'GET',
                'callback' => 'w2travel_initialSettings',
            ));   
            
            register_rest_route("{$name_espace}", '/teste/', array(
                'methods' => 'GET',
                'callback' => 'w2travel_teste_001',
            ));
            
            
            register_rest_route("{$name_espace}", '/search/', array(
                'methods' => 'GET',
                'callback' => 'w2travel_app_search',
            ));

            register_rest_route("{$name_espace}", '/business/', array(
                'methods' => 'GET',
                'callback' => 'w2travel_app_get_business',
            ));
            
            /** perfis de empresas */
            
            $name_espace = "company/v1";
            register_rest_route("{$name_espace}", '/profile/', array(
                'methods' => 'POST',
                'callback' => 'w2travel_post_company_profile',
            ));
        }


        // rotas abertas

        add_filter( 'jwt_auth_whitelist', function ( $endpoints ) {
            return array(
                '/wp-json/custom/v1/webhook/*',
                '/wp-json/custom/v1/otp/*',
                '/wp-json/custom/v1/account/check',
                '/wp-json/custom/v1/register',
                '/wp-json/local/v1/paises/*',
                '/wp-json/info/v1/metas/*',
                '/wp-json/app/v1/settings/*',
                '/wp-json/bdpwr/v1/reset-password',
                '/wp-json/bdpwr/v1/set-password',
                '/wp-json/myteste/v1/teste', 
                '/wp-json/app/v1/search/*',
                '/wp-json/app/v1/business/*',
                '/wp-json/relevanssi/v1/search/*'
            );
        } );

        // add_filter('jwt_auth_default_whitelist', function ($default_whitelist) {
        //     // Modify the $default_whitelist here.
        //     $rest_api_slug = "/wp-json";
        //     $default_whitelist = array(
        //         // WooCommerce namespace.
        //         $rest_api_slug . '/wc/',
        //         $rest_api_slug . '/wc-auth/',
        //         $rest_api_slug . '/wc-analytics/',

        //         // WordPress namespace.
        //         $rest_api_slug . '/wp/v2/',
        //         $rest_api_slug . '/myplugin/v1/update/'
        //     );

        //     return $default_whitelist;
        // });


    }
}
