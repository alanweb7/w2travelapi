<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       https://aconline.com.br
 * @since      1.0.0
 *
 * @package    Library_Functions_Mega
 * @subpackage Library_Functions_Mega/includes
 */

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
class Library_Functions_Api_Loader {

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
	public function __construct() {

		$this->actions = array();
		$this->filters = array();

	}
	


public static function loadFunctionsApi(){
    
     /**
 * rotas abertas
 */ 

add_filter( 'jwt_auth_default_whitelist', function ( $default_whitelist ) {
    // Modify the $default_whitelist here.
        $default_whitelist = array(
    // WooCommerce namespace.
    $rest_api_slug . '/wc/',
    $rest_api_slug . '/wc-auth/',
    $rest_api_slug . '/wc-analytics/',

    // WordPress namespace.
    $rest_api_slug . '/wp/v2/',
);

    
    return $default_whitelist;
} );
    
    // mostrar os campos personalizados na api
    
//     register_meta('post', 'cidade', [
//     'object_subtype' => 'viagens', // Limit to a post type.
//     'type'           => 'string',
//     'description'    => 'Cidade',
//     'single'         => true,
//     'show_in_rest'   => true,
// ]);
    


/**
 * funcoes de controle dos campos da api
 **/

function slug_get_post_meta_cb( $object, $field_name, $request ) {
    return get_post_meta( $object[ 'id' ], $field_name );
}

function slug_update_post_meta_cb( $value, $object, $field_name ) {
    return update_post_meta( $object[ 'id' ], $field_name, $value );
}

function get_post_meta_for_api( $object ) {
    //get the id of the post object array
    $post_id = $object['id'];

    $meta = get_post_meta( $post_id );

    if ( isset( $meta['subtitle' ] ) && isset( $meta['subtitle' ][0] ) ) {
        //return the post meta
        return $meta['subtitle' ][0];
    }

    // meta not found
    return false;
}






function update_post_meta_for_exp( $value, $object, $field) {

    //  return new WP_Error( 'broke', __( "I've fallen and can't get up", "my_textdomain" ), array('dados' => $metaFields) );
    
    $unique = true;
    // $ret = add_post_meta($object->ID, $field, $value, $unique);
    $ret = update_post_meta($object->ID, $field, $value);
    return true;
    
    // old function
    // $escaped_json = (array) $object;
    // update_post_meta( 43, 'informations_general', [$value, $object->ID, $field]);
    // $havemetafield  = get_post_meta($object['ID'], 'viagens', false);
    // if ($havemetafield) {
    //     $ret = update_post_meta($object['ID'], 'subtitle', $meta_value );
    // } else {
    //     $ret = add_post_meta( $object['ID'], 'subtitle', $meta_value ,true );
    // }
    // return true;
}

add_action( 'rest_api_init', function() {
    
    
//     $meta = [
//     'key' => 'cidade',
//     'description' => 'This is the name of my post type',
//     'type' => 'string'
// ];
 
// register_rest_field('viagens', $meta['key'], [
//     'get_callback' => function ( $data ) {
//         return get_post_meta( $data['id'], '', '' );
//     },
//     'update_callback' => function ($value, $object, $fieldName){
//         return update_post_meta($object->ID, $fieldName, $value);
//     }
// ]);
    

/**criando array de campos metafields **/

    $dadosApi = $_REQUEST;
$metaFields = [];

foreach($_REQUEST as $field => $value){
    
    $prefix = explode("_", $field);
    
    if($prefix[0] ==  "mf"){
        
       array_push($metaFields, $field);
        
    }
    
    
    
}

foreach($metaFields as $field){
    
    register_rest_field( 'viagens', $field,
    array(
    //   'get_callback'    => 'get_post_meta_for_api', //'slug_get_post_meta_cb'
      'update_callback' => 'update_post_meta_for_exp',
      'schema'          => null,
    )
 );
 
 
}
 


});



	    /** retorna os metas dos das postagens
	     * parameters: post_type, nome_da_chave_que_vai_aparecer **/
	    
	    	register_rest_field( 'viagens', 'meta', array(
    'get_callback' => function ( $data ) {
        return get_post_meta( $data['id']);
    }, ));



    /**
 * Callback function to authorize each api requests
 * 
 * @see \WP_REST_Request
 * 
 * @param                  $response
 * @param                  $handler
 * @param \WP_REST_Request $request
 *
 * @return mixed|\WP_Error
 */
 
// function wpdocs_authorize_api_requests( $response, $handler, WP_REST_Request $request ) {
//     // allowed routes
//     $routes = array(
//         '/wp/v2/posts',
//         '/wp/v2/viagens/(?P<id>[\d]+)',
//         '/wp/v2/pages',
//     );
 
//     // check for certain role and allowed route
//     if ( !in_array( 'administrator', wp_get_current_user()->roles  )) {
//         return new WP_Error( 'forbidden', 'Access forbidden.', array( 'status' => 403, 'data' => [wp_get_current_user()->roles], 'rotas' => $request->get_route(),
//         'rotas_permitidas' => $routes) );
//     }
 
//     return $response;
 
// }
// // authorize each requests
// add_filter( 'rest_request_before_callbacks', 'wpdocs_authorize_api_requests', 10, 3 );
    
    
    
    
}




}
