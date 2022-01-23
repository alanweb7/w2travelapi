<?php

/**
 * NOTA:
 * nao precisa decode JWT as funcoes de get user, funcionam
 * normalmente
 */

use WPMailSMTP\Vendor\GuzzleHttp\Psr7\Message;

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
class Library_Functions_Api_Loader
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

    public static function loadFunctionsApi()
    {

        /**
         * funcoes de controle dos campos da api
         **/

        function slug_get_post_meta_cb($object, $field_name, $request)
        {
            return get_post_meta($object['id'], $field_name);
        }

        function slug_update_post_meta_cb($value, $object, $field_name)
        {
            return update_post_meta($object['id'], $field_name, $value);
        }

        function get_post_meta_for_api_old($object)
        {
            //get the id of the post object array
            $post_id = $object['id'];

            $meta = get_post_meta($post_id);

            if (isset($meta['subtitle']) && isset($meta['subtitle'][0])) {
                //return the post meta
                return $meta['subtitle'][0];
            }

            // meta not found
            return false;
        }

        function get_post_meta_for_api($object)
        {
            //get the id of the post object array

            $post_id = unserialize($object['id']);

            //return the post meta
            return get_post_meta($post_id);
        }

        function update_post_meta_for_exp($value, $object, $field)
        {

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

        /** retorna os metas dos das postagens
         * parameters: post_type, nome_da_chave_que_vai_aparecer **/

        register_rest_field('viagens', 'meta', array(
            'get_callback' => function ($data) {
                $metas = get_post_meta($data['id']);
                $response = [];

                foreach ($metas as $key => $meta) {
                    $data = $meta[0];
                    if (is_serialized($data)) {
                        $data = unserialize($data);
                    }

                    $response[$key] = $data;
                }
                return $response;
            }));

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

        function wpshout_special_update_function(WP_REST_Request $request)
        {

            $country = $request->get_param('country'); //PARAMETRO UNITARIO ALTERNATIVO
            $state = $request->get_param('state'); //PARAMETRO UNITARIO ALTERNATIVO
            $citie = $request->get_param('citie'); //PARAMETRO UNITARIO ALTERNATIVO
            $id = $request->get_param('id'); //PARAMETRO UNITARIO ALTERNATIVO

            // You can get the combined, merged set of parameters:
            $parameters = $request->get_params(); //todoas od parameros

            // The individual sets of parameters are also available, if needed:
            // $parameters = $request->get_url_params(); //somnte GET
            // $parameters = $request->get_body_params(); //somente POST
            // $parameters = $request->get_query_params();
            // $parameters = $request->get_json_params();
            // $parameters = $request->get_default_params();

            // // Uploads aren't merged in, but can be accessed separately:
            // $parameters = $request->get_file_params(); //somente mobile

            // return $parameters;

            // $strJsonFileContents = file_get_contents(LIBFMEGA . "/assets/cities.json");
            // $json_data = json_decode($strJsonFileContents, true);

            // require_once __DIR__ . '/vendor/autoload.php';
            // $products = JsonMachine\JsonMachine::fromFile(LIBFMEGA . "/assets/cities.json");

            // foreach ($products as $product) {
            //     $productData = json_encode($product, true);
            //     // print($productData) . PHP_EOL;
            // }

            // return $productData;

            // $curl = curl_init();
            // $payload = json_encode( array(
            //     "country"=> "brazil"
            //     ) );
            // curl_setopt_array($curl, array(
            //   CURLOPT_URL => "https://countriesnow.space/api/v0.1/countries/cities",
            //   CURLOPT_RETURNTRANSFER => true,
            //   CURLOPT_ENCODING => "",
            //   CURLOPT_MAXREDIRS => 10,
            //   CURLOPT_TIMEOUT => 30,
            //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //   CURLOPT_CUSTOMREQUEST => "POST",
            //   CURLOPT_POSTFIELDS => $payload,
            //   CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            // ));

            // $response = curl_exec($curl);
            // $err = curl_error($curl);

            // curl_close($curl);

            // return json_decode($response, true);
            // return $strJsonFileContents;

            // 1st Method - Declaring $wpdb as global and using it to execute an SQL query statement that returns a PHP object
            $servidor = "localhost";
            $usuario = "wtraveln_locations";
            $senha = "@segurolive332";

            $dbname = "wtraveln_countries";
            $dbTable = "countries";
            $WHERE = "";
            $AND = "";
            $parent = "";

            if ($country) {
                $dbname = "wtraveln_states";
                $dbTable = "states";
                $WHERE = " WHERE country_id = {$country} ";
                $parent = $country;

            }

            if ($state) {
                $dbname = "wtraveln_cities";
                $dbTable = "cities";
                $WHERE = " WHERE state_id = {$state} ";
                $parent = $state;
            }

            //Criar a conexao
            $conn = mysqli_connect($servidor, $usuario, $senha, $dbname);

            $result_cities = "SELECT *
            FROM {$dbTable}
            {$WHERE}
            {$AND}
            LIMIT 300";
            $resultado_cities = mysqli_query($conn, $result_cities);

            $resultado = [];

            /**  nesse formato retorna todas as informacoes **/
            // while ($rows_citie = mysqli_fetch_assoc($resultado_cities)) {
            //     $resultado['id'] = $rows_citie['id'];
            // }

            // nesse formato filtra as informacoes
            while ($rows_citie = mysqli_fetch_assoc($resultado_cities)) {
                $res['id'] = $rows_citie['id'];
                $res['name'] = utf8_encode($rows_citie['name']);
                $res['parent'] = $parent;
                $resultado[] = $res;
            }
            return $resultado;

        }

        function wpdocs_create_meta_fields($response, $handler, WP_REST_Request $request)
        {

            // allowed routes

            $parameters = $request->get_params(); //todoas od parameros

            /**criando array de campos metafields se estiver nas rots abaixo**/

            $method = $request->get_method();
            if ($method === "POST") {

                $postTypes = ["viagens", "hospedagens", "carros"];

                $pathRoute = $request->get_route();
                $isCreate = false;

                foreach ($postTypes as $key => $find) {
                    if (strpos($pathRoute, $find)) {

                        $isCreate = true;

                        break;

                    }
                }

                // return new WP_Error('forbidden', 'Access forbidden.', array(
                //     'status' => 403,
                //     'data' => $response,
                //     'request' => $parameters,
                //     'pathRoute' => $request->get_route(),
                //     'method' => $request->get_method(),
                //     "isCreate" => $isCreate,

                // ));

                if ($isCreate) {

                    $metaFields = [];

                    foreach ($parameters as $field => $value) {

                        $prefix = explode("_", $field);

                        if ($prefix[0] == "mf") {

                            array_push($metaFields, $field);

                        }

                    }

                    foreach ($metaFields as $field) {

                        register_rest_field('viagens', $field,
                            array(
                                'get_callback' => 'get_post_meta_for_api', //'slug_get_post_meta_cb'
                                'update_callback' => 'update_post_meta_for_exp',
                                'schema' => null,
                            )
                        );

                    }

                }

            } //if method POST

            return $response;

        }

        // authorize each requests
        add_filter('rest_request_before_callbacks', 'wpdocs_create_meta_fields', 10, 3);

        // upload files

        // method post route /wp-json/like/v1/viagens/<id>
        function w2travel_like_us(WP_REST_Request $request)
        {

            // $key  = $request->get_header();
            // return "Teste";
            $action = $request->get_param('action') ?? 'like';

            $post_id = $request->get_param('id');
            $userid = get_current_user_id();

            $meta_key = "mf_like_us";
            $meta_value = [$userid];

            $existing_pms = get_post_meta($post_id, $meta_key, false);

            //se nao existir, adicionar o meta e curtir
            if (!$existing_pms) {

                $addMeta = add_post_meta($post_id, $meta_key, $meta_value, true);

                if ($addMeta) {

                    $success = [
                        "data" => [
                            status => 200,
                            code => 'liked',
                            message => 'Você curtiu com sucesso!',
                            count => get_like_us($post_id, 'count'),
                        ],
                    ];

                    return $success;
                }

                $error = [
                    "data" => [
                        status => 403,
                        code => 'error_in_created',
                        message => 'Não foi possível curtir',
                    ],
                ];

                return $error;
            }

            // se existir atualizar

            if ($existing_pms) {

                // return 'Acao: '.$action;

                //  se existir e o usuario atual já curtiu

                $list = $existing_pms[0];
                if (in_array($userid, $list)) {

                    $myPos = array_search($userid, $list);

                    unset($list[$myPos]);

                    $upDateMeta = update_post_meta($post_id, $meta_key, $list);

                    $error = [
                        "data" => [
                            status => 403,
                            code => 'you_desliked',
                            message => 'Você deu deslike!',
                            // ids => get_post_meta($post_id, $meta_key, false),
                            // myPos => $myPos,
                            count => get_like_us($post_id, 'count'),
                        ],
                    ];

                    return $error;
                }

                $Likes = $existing_pms[0];
                array_push($Likes, $userid);

                // return $Likes;

                $upDateMeta = update_post_meta($post_id, $meta_key, $Likes);

                if ($upDateMeta) {
                    $success = [
                        "data" => [
                            status => 200,
                            code => 'like_update',
                            message => 'Você curtiu com sucesso!',
                            count => get_like_us($post_id, 'count'),
                        ],
                    ];

                    return $success;
                }

                $error = [
                    "data" => [
                        status => 403,
                        code => 'like_error',
                        message => 'Ops! Algo deu errado ao curtir, tente de novo!',
                    ],
                ];

                return $error;
            }

        }

        /** manage posts duplicate, remove, etc... */
        function w2travel_manage_post(WP_REST_Request $request)
        {

            $parameters = $request->get_params();

            // return $parameters;

            $action = $request->get_param('action') ?? 'like';

            $post_id = $request->get_param('id');
            $old_post = get_post($post_id);

            $new_post = array(
                'post_author' => $old_post->post_author,
                'post_content' => $old_post->post_content,
                'post_title' => $old_post->post_title . " (copy)",
                'post_excerpt' => $old_post->post_excerpt,
                'post_status' => $old_post->post_status,
                'comment_status' => $old_post->comment_status,
                'ping_status' => $old_post->ping_status,
                'post_password' => $old_post->post_password,
                // 'post_name'          => $old_post->post_name,
                'to_ping' => $old_post->to_ping,
                'pinged' => $old_post->pinged,
                'post_content_filtered' => $old_post->post_content_filtered,
                'post_parent' => $old_post->post_parent,
                'menu_order' => $old_post->menu_order,
                'post_type' => $old_post->post_type,
                'post_mime_type' => $old_post->post_mime_type,
            );

            /* Add the new post */
            $new_post_id = wp_insert_post($new_post);

            $message = [
                status => 200,
                code => "success_post",
                message => "Operação realizada com sucesso",
            ];

            if ($new_post_id) {

                /* Copy post metadata from old to new post */
                $meta_data = get_post_meta($post_id);
                foreach ($meta_data as $meta_key => $meta_value) {
                    update_post_meta($new_post_id, $meta_key, maybe_unserialize($meta_value[0]));
                }

                $message['data'] = [
                    ID => $new_post_id,
                    post_title => $new_post['post_title'],
                ];
            } else {

                $message['status'] = 403;
                $message['code'] = "error";
                $message['message'] = "Não foi possível finalizar a operação";

            }

            return $message;
        }

        ///verificar se o current user curtiu e quantos curtiram a viagem
        function w2travel_get_like_us(WP_REST_Request $request)
        {
            $action = $request->get_param('action');

            $post_id = $request->get_param('id');
            $user_id = get_current_user_id();

            $meta_key = "mf_like_us";

            $method = $request->get_method();

            if ($method === "DELETE") {

                $userAuthozized = [1, 53]; /// usuarios autorizados para deletar os likes

                if (in_array($user_id, $userAuthozized)) {

                    delete_post_meta($post_id, $meta_key);
                    return "likes deleteds";

                } else {

                    $error = [
                        "data" => [
                            status => 403,
                            code => 'error_in_delete',
                            message => 'Sem permissão para Deletar',
                        ],
                    ];

                    return $error;

                }
            }

            $getLikes = get_like_us($post_id);
            $isLiked = in_array($user_id, $getLikes[0]);

            if (isset($action)) {

                if ('list_users' === $action) {

                    $listUsers = [];

                    foreach ($getLikes[0] as $userID) {
                        # code...
                        $user = w2travel_get_user_meta_by_id($userID);

                        if ($user['user_data_profile']) {

                            array_push($listUsers, $user);
                        }

                    }

                    $success = [
                        "data" => [
                            status => 200,
                            code => 'likes_response',
                            message => 'Usuários que curtiram essa viagem',
                            list_users => $listUsers,
                            youLiked => $isLiked,
                            user_id => $user_id,
                        ],
                    ];

                    return $success;
                }

            }

            // return $getLikes;

            if ($getLikes) {
                $success = [
                    "data" => [
                        status => 200,
                        code => 'likes_response',
                        message => 'Usuários que curtiram essa viagem',
                        count => count($getLikes[0]),
                        youLiked => $isLiked,
                        user_id => $user_id,
                    ],
                ];

                return $success;
            }

            $response = [
                "data" => [
                    status => 404,
                    code => 'empty_response',
                    message => 'Sem curtidas nessa viagem',
                    count => get_like_us($post_id, 'count'),
                ],
            ];

            return $response;

        }

        //funcao local interna
        function get_like_us($post_id, $action = null)
        {

            $meta_key = "mf_like_us";
            $getLikes = get_post_meta($post_id, $meta_key);
            $countLikes = count($getLikes[0]);

            if ($action === 'count') {
                return $countLikes;
            }

            if ($action === 'all') {
                return [
                    "userLikes" => $getLikes,
                    "count" => $countLikes,
                ];
            }

            //return default
            return $getLikes;

        }

        /**
         * retorna os metas do usuario
         */
        function w2travel_get_user_meta()
        {

            $user_id = get_current_user_id();

            $meta = array_map(function ($a) {

                $count = count($a);
                if ($count >= 2) {
                    $collection = [];
                    foreach ($a as $key => $value) {
                        if (is_serialized($value)) {
                            $value = unserialize($value);
                        }

                        array_push($collection, $value);
                    }
                    return $collection;
                };

                if (is_serialized($a[0])) {
                    return unserialize($a[0]);
                }

                return $a[0];

            }, get_user_meta($user_id));

            unset($meta['session_tokens']);
            return $meta;

        }

        /**
         * retorna os metas do usuario
         */
        function w2travel_get_user_meta_by_id($user_id)
        {

            // $user_id = get_current_user_id();

            $meta = array_map(function ($a) {

                $count = count($a);
                if ($count >= 2) {
                    $collection = [];
                    foreach ($a as $key => $value) {
                        if (is_serialized($value)) {
                            $value = unserialize($value);
                        }

                        array_push($collection, $value);
                    }
                    return $collection;
                };

                if (is_serialized($a[0])) {
                    return unserialize($a[0]);
                }

                return $a[0];

            }, get_user_meta($user_id));

            unset($meta['session_tokens']);
            return $meta;

        }

        function apiInfo($request)
        {

            $text = '';
            $response = [
                status => 200,

            ];
            return $response;

        }

        function infoThisRoute($request)
        {
            /**
             * Undocumented function
             *
             * @param WP_REST_Request $request
             * @return void
             */

            $queryParams = $request->get_query_params();
            $bodyParams = $request->get_body_params();
            $attrParams = $request->get_attributes(); //atributos da rota (methods, callback, etc...)
            $allParams = $request->get_params(); //pega todos os parametros

            return [
                diretrize => 'Campos requeridos para agravação de perfis: profile, meta_key, map_key',
                query => $queryParams,
                body => $bodyParams,
                attr => $attrParams,
                all => $allParams,
            ];

            /**
             * PARA FAZER UM REQUEST DE DENTRO DO SISTEMA USAR:
             *          $request = new WP_REST_Request( 'POST', '/account/v1/profile' );
             *          $response = rest_do_request( $request );
             *          $data = $response->get_data();
             *          return $data;
             */

        }

        function get_current_user_idz()
        {
            if (class_exists('Jwt_Auth_Public')) {
                $jwt = new \Jwt_Auth_Public('jwt-auth', '1.1.0');
                $token = $jwt->validate_token(false);
                if (\is_wp_error($token)) {
                    return false;
                }

                return $token->data->user->id;
            } else {
                return false;
            }
        }

        // route ./wp-json/account/v1/profile/<id>
        function w2travel_get_account_profile(WP_REST_Request $request)
        {

            $urlID = $request->get_param('id');
            $token = $request->get_param('token');

            $filter = $request->get_param('filter');
            $user_id = get_current_user_id();

            $current_user = wp_get_current_user();
            // return $current_user ; //<- add this

            // Get all user meta data for $user_id
            $meta = w2travel_get_user_meta();

            if ($filter) {

                // Filter out empty meta data
                $meta = array_filter(array_map(function ($a) {
                    return $a;
                }, $meta));

            }

            // unset($meta['session_tokens']);

            $response = [

                status => 200,
                data => [

                    status => 200,
                    code => 'success',
                    code => 'Meta dados do usuário atual.',
                    meta => $meta,

                ],

            ];

            return $response;
        }

        function checkloggedinuser()
        {
            $currentuserid_fromjwt = get_current_user_id();
            print_r($currentuserid_fromjwt);
            exit;
        }

        add_action('rest_api_init', function () {
            register_rest_route('testone', 'loggedinuser', array(
                'methods' => 'POST',
                'callback' => 'checkloggedinuser',
            ));
        });

        // route ./wp-json/account/v1/profile/?profile=carro (DELETE)
        function w2travel_delete_account_profile(WP_REST_Request $request)
        {

            // record functions
            $user_id = get_current_user_id();

            // return $user_id;
            $parameters = $request->get_params();

            $user_id = get_current_user_id();
            $user = new \WP_User($user_id);

            $profile = $parameters['profile']; //nome do perfil a procurar

            if (!$profile) {
                return [status => 403, code => "empty_profile", message => "Faltando Perfil"];
            }

            $meta_key = $parameters['meta_key'] ?? 'user_data_profile'; //grupo principal da informacao
            $map_key = $parameters['map_key'] ?? 'profile'; //chave para achar a informcao

            $removed = false;
            $deleted = false;

            // return delete_user_meta($user_id, $meta_key);

            $user->remove_role($profile);

            //encontranda  a chave que deve-se deletar
            $all_profiles = get_user_meta($user_id, $meta_key, false);
            $profile_id = array_search($profile, array_column($all_profiles, $map_key));

            if ($profile_id) {

                $deleted = delete_user_meta($user_id, $meta_key, $all_profiles[$profile_id]);

            }

            $status = !$deleted ? 403 : 200;

            return [
                status => $status,
                data => [status => $status],
                message => $deleted ? "Deleted profile: {$profile}" : 'No deleted!',
                meta => w2travel_get_user_meta(), ///meta atualizado
            ];

        }

        // route ./wp-json/account/v1/profile/ (POST)
        function w2travel_post_account_profile(WP_REST_Request $request)
        {

            $parameters = $request->get_params();

            /**
             * NO SISTEMA DE PREENCHIMENTO DE PERFIL
             * USAR A KEY "PROFILE" PARA GRAVACAO DOS DADOS DE DIFERENTES PERFIS
             * EX.: traveler
             * Campos requeridos para agravação de perfis: profile, meta_key, map_key
             */

            $filter = $request->get_param('filter'); //a chave para informacao da rota
            $action = $request->get_param('action'); //a chave para informacao da rota

            if ($filter === 'info') {
                return infoThisRoute($request);
            }

            if ($action === 'create_profile') {
                return createNewProfile($parameters);
            }

            $user_id = get_current_user_id();
            $profile = $request->get_param('profile'); //a chave principal dos dados do perfil

            if ($profile) {

                // funcao de teste

                // $map_key = $parameters['map_key'] ?? 'profile';
                // $previous_profile = get_user_meta($user_id, $profile, false);
                // $profile_id = array_search($profile, array_column($previous_profile, $map_key));

                // $data = [
                //     status => 200,
                //     profile_data => $previous_profile,
                //     profile => $profile,
                //     profile_id => $profile_id,
                // ];

                // return $data;

                //// funcao correta
                $queryParams = $request->get_params();
                return UserMetaRecord($queryParams); ///gravando os dados

            }

            if (!$profile) {
                return [
                    status => 403,
                    data => [
                        status => 403,
                        code => 'missing_parameter',
                        message => 'faltando Perfil',
                    ],
                ];
            }

            return 'others functions';
        }

        function w2travel_delete_post_by_id(WP_REST_Request $request)
        {

            $parameters = $request->get_params();

            $post_id = (int) $parameters['id'];

            $post_info = get_post($post_id);
            $author = (int) $post_info->post_author;

            $auth = $author === get_current_user_id() ? true : false;

            // return "deletar {$parameters['id']}";

            if ($auth) {
                $delete = wp_delete_post($post_id, true);

                $resp = [
                    status => 200,
                    message => "Sucesso ao excluir!",
                    ID => $post_id,
                ];
                return $resp;
            }

            return [
                status => 403,
                message => "Sem Permissão para excluir!",
                ID => $post_id,
            ];

        }

        function createNewProfile($parameters)
        {
            /** criar novo custom post, novo post, criar post **/

            $user_id = get_current_user_id();
            $post_arr = array(
                'post_title' => $parameters['name'],
                'post_status' => 'publish',
                'post_author' => $user_id,
                'post_type' => $parameters['profile'] ?? 'empresa',
                'meta_input' => array(
                    'profile' => $parameters['profile'],
                ),
            );

            $id = wp_insert_post($post_arr);
            $response = [
                "status" => 200,
                "ID" => $id,
                "post_title" => $parameters['name'],
                "request" => $parameters,
            ];
            return $response;
        }

        function recordUserAvatar($attachment)
        {

            return $attachment;
            // $user_id = get_current_user_id();
            // $user = new \WP_User($user_id);
            // $action = $parameters['action'];

            // if($action === 'avatar'){

            //     $response = [
            //         status => 200,
            //         data =>[
            //             user_id => $user_id,
            //             parameters => $parameters,
            //         ]
            //     ];
            //     return $response;
            // }

        }

        function UserMetaRecord($parameters)
        {
            // record functions
            $user_id = get_current_user_id();
            $user = new \WP_User($user_id);

            $profile = $parameters['profile']; //nome do perfil a procurar
            $meta_key = $parameters['meta_key'] ?? 'user_data_profile'; //grupo principal da informacao
            $map_key = $parameters['map_key'] ?? 'profile'; //chave para achar a informcao
            $remove = $parameters['remove'] ?? false;

            if ($profile && !$meta_key) {

                return [
                    status => 403,
                    data => [
                        code => 'empty_field',
                        message => 'faltando campos',
                        missing => 'meta_key',
                    ],

                ];
            }

            /**
             * CRIAR POSTAGEM CUSTOMIZADA
             */

            $profilePost = recordProfilePost($parameters);

            unset($parameters['meta_key']); //removendo o meta_key

            if ($parameters['create']) {

                $result = add_role(
                    $profile,
                    __('Guest Author', 'testdomain'),
                    array(
                        'read' => true, // true allows this capability
                        'edit_posts' => true,
                        'delete_posts' => false, // Use false to explicitly deny
                    )
                );

                if (null !== $result) {
                    $message = "Success: {$result->name} user role created.";

                    return [
                        status => 200,
                        message => $message,
                        post_type => $profile,
                        meta => w2travel_get_user_meta(), ///meta atualizado
                    ];
                } else {
                    $message = 'Failure: user role already exists.';
                    return [
                        status => 200,
                        message => $message,
                        meta => w2travel_get_user_meta(), ///meta atualizado
                    ];
                }

            }

            $previous_profile = get_user_meta($user_id, $meta_key, false);

            // return 'Itens encontrados: '.count( $previous_profile );
            /**
             * encontrar a posicao dos dados no array
             * $profile : perfil que se deseja atualizar/ADD
             * $map_key : chave de parametro dentro do array para encontar o perfil desejado
             */
            $profile_id = array_search($profile, array_column($previous_profile, $map_key));

            if (false === $profile_id) {
                // add if the wp_usermeta meta_key[favorite_coffee] => meta_value[ $parameters[ $coffee_id ] ] pair does not exist

                add_user_meta($user_id, $meta_key, $parameters);

                if (count($previous_profile) == 0) {

                    $parameters_default = [];
                    $parameters_default['profile'] = 'default';
                    add_user_meta($user_id, $meta_key, $parameters_default);

                }

                $user->add_role($profile);

                $message = 'Meta adicionada com sucesso!.';

            } else {
                // update if the wp_usermeta meta_key[favorite_coffee] => meta_value[ $parameters[ $coffee_id ] ] pair already exists
                update_user_meta($user_id, $meta_key, $parameters, $previous_profile[$profile_id]);
                $message = 'Meta Atualizada com sucesso!.';
            }

            $meta = w2travel_get_user_meta(); ///meta atualizado

            $response = [

                status => 200,
                data => [

                    status => 200,
                    code => 'success',
                    profile => $profilePost,
                    message => $message,
                    meta => $meta,

                ],

            ];

            return $response;
        }

        function recordProfilePost($parameters)
        {

            $user_id = get_current_user_id();
            $post_type = $parameters['profile'];
            $post_ID = $parameters['ID'];

            $address = [
                "country" => $parameters['country'],
                "state" => $parameters['state'],
                "city" => $parameters['city'],
                "district" => $parameters['district'],
            ];

            /**verifica se ja existe um profile e edita */

            $args = array(
                'posts_per_page' => 10,
                'post_type' => array($post_type),
                'post_status' => 'publish',
                'author' => $user_id,
                'offset' => 0,
                'orderby' => 'post_type',
                'order' => 'ASC',
            );

            $posts = get_posts($args);

            $profileInfo = $posts[0];
            $post_ID = $post_ID ?? $profileInfo->ID;

            if ($post_ID) {

                /** @var WP_Post $post */
                $post = get_post($post_ID);

                if (!$post->ID) {
                    return "Missing Post";
                }

                $post->post_content = "Some other content";

                $profile_data = [];
                // WordPress does this automatically when an array is passed as meta value
                // $values = maybe_serialize( $values );
                update_post_meta($post->ID, 'country', $address['country']);
                update_post_meta($post->ID, 'state', $address['state']);
                update_post_meta($post->ID, 'city', $address['city']);
                update_post_meta($post->ID, 'district', $address['district']);
                wp_update_post($post);

                $postUpdate = get_post($post_ID);
                $postUpdate->profile_meta = get_post_meta($post->ID);

                foreach ($postUpdate->profile_meta as $key => $value) {
                    if (is_serialized($value[0])) {

                        $postUpdate->profile_meta[$key] = unserialize($value[0]);

                    }
                }

                return $postUpdate;

            }

            /**create post */

            $new = array(
                'post_type' => $post_type,
                'post_title' => 'Our new post',
                'post_content' => 'This is the content of our new post.',
                'post_status' => 'publish',
            );

            $post_id = wp_insert_post($new);

            // // Product Title
            // $post_title = 'Test Product';

            // // Add Product
            // $new_post = array(
            //     'post_title' => $post_title,
            //     'post_type' => 'product',
            //     'post_staus' => 'draft',
            //     'post_content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            //     'post_excerpt' => 'Lorem Ipsum is simply dummy text'
            // );

            // // Catch post ID
            // $post_id = post_exists( $post_title ) or wp_insert_post( $new_post );

            if ($post_id) {
                return [
                    "message" => "Post successfully published!",
                    "ID" => $post_id,
                    "profile" => $post_type,
                ];
            } else {
                return "Something went wrong, try again.";
            }

            return "Post created!";

        }

        /** perfis de empresas
         * url https://[site]/wp-json/company/v1/profile
         */
        function w2travel_post_company_profile(WP_REST_Request $request)
        {
            $parameters = $request->get_params();
            $action = $parameters['action'] ?? "get_company";
            $details = $parameters['details'] ?? $parameters['data'];
            $ID = $parameters['ID'];

            $resp = [

                status => 200,
                parameters => $parameters,

            ];

            if (!$ID && $action === 'create') {
                /** create profile */

                $parameters['name'] = $parameters['name'] ?? 'Minha empresa';
                $create = createNewProfile($parameters);
                $resp['action'] = 'create_new_company';
                $resp['ID'] = $create['ID'];

                return $resp;
            }

            $resp['ID'] = $ID;

            if ($ID && $action === "get_company") {
                /** create profile */

                $status = 200;
                $company = get_post($ID);

                $post_metas = get_post_meta($ID);
                $post_metas = array_combine(array_keys($post_metas), array_column($post_metas, '0'));

                $company_filtered = [
                    ID => $company->ID,
                    guid => $company->guid,
                    post_author => $company->post_author,
                    post_content => $company->post_content,
                    post_content_filtered => $company->post_content_filtered,
                    post_date => $company->post_date,
                    post_modified => $company->post_modified,
                    post_excerpt => $company->post_excerpt,
                    post_name => $company->post_name,
                    post_status => $company->post_status,
                    post_title => $company->post_title,
                    post_type => $company->post_type,
                ];

                if ($company->ID) {

                    return [
                        status => $status,
                        company => $company_filtered,
                        details => (array) $post_metas,
                        details_full => $post_metas,
                    ];
                }

                return [

                    status => 403,
                    code => 'empty_company',
                    message => 'Não foi possível localizar o perfil',

                ];

            } //get company method

            //save update company

            $data = array(
                'ID' => $ID,
                'post_content' => $details["description"] ?? "Minha empresa (conteúdo)",
                'post_title' => $details["firstName"] ?? "Minha empresa (nome)",
                // 'meta_input' => array(
                //     "MyAddress" => $details["MyAddress"],
                //     "city" => $details["city"],
                //     "district" => $details["district"],
                //     "location" => $details["location"],
                // ),
            );

            if ($details) {

                foreach ($details as $key => $value) {
                    # code...
                    update_post_meta($ID, $key, $value);
                }
            }

            $update = wp_update_post($data);

            if (is_wp_error($ID)) {
                $errors = $ID->get_error_messages();
            }

            $company = get_post($ID);

            $post_metas = get_post_meta($ID);
            $post_metas = array_combine(array_keys($post_metas), array_column($post_metas, '0'));
            $company_filtered = [
                ID => $company->ID,
                guid => $company->guid,
                post_author => $company->post_author,
                post_content => $company->post_content,
                post_content_filtered => $company->post_content_filtered,
                post_date => $company->post_date,
                post_modified => $company->post_modified,
                post_excerpt => $company->post_excerpt,
                post_name => $company->post_name,
                post_status => $company->post_status,
                post_title => $company->post_title,
                post_type => $company->post_type,
            ];

            $resp['action'] = 'update_company';
            $resp['status_update'] = $errors ?? 'update_ok';
            $resp['company'] = $company_filtered;
            $resp['details'] = $post_metas;

            return $resp;
        }

        function w2travel_post_account_profile_set_password(WP_REST_Request $request)
        {
            /** url https://[site]/wp-json/app/v1/profile/ */

            $error = false;
            $response = false;
            $code = '';
            $user_id = get_current_user_id();
            $parameters = $request->get_params();
            $action = $parameters['action'] ?? 'set_password';
            $password = $parameters['password'];

            $message = 'define password';

            if (!$password || empty($password)) {

                $error = true;
                $response = true;
                $message = 'Falatando password';

            }

            if ($action === 'set_password' && !$response) {

                $message = 'set password';
                $response = true;

                // $valPass = validatePassowrd($password);
                // $valPass = true;

                // if(!$valPass['status']){

                //     $response= true;
                //     $error = true;
                //     $message = $valPass['message'];

                // }

                if (!$error) {

                    $actionUser = wp_set_password($password, $user_id);
                    $response = true;
                    $message = 'Senha definida com sucesso!';

                }

            }

            if ($action === 'reset_password' && !$response) {
                $message = 'reset password';
            }

            $response = [

                status => $status ?? 200,
                data => [

                    status => $status ?? 200,
                    code => 'success',
                    message => $message,
                    action => $action,

                ],

            ];

            if ($error) {

                $status = 403;
                $response = [

                    status => $status,
                    data => [

                        status => $status,
                        code => $code ?? 'error',
                        message => $message,

                    ],

                ];
            }

            return $response;

        }

        function validatePassowrd($password)
        {

            $response = [
                status => true,
                message => 'ok',
            ];

            $number = preg_match('@[0-9]@', $password);
            $uppercase = preg_match('@[A-Z]@', $password);
            $lowercase = preg_match('@[a-z]@', $password);
            $specialChars = preg_match('@[^\w]@', $password);

            if (strlen($password) < 8 || !$number || !$uppercase || !$lowercase || !$specialChars) {

                $response['status'] = false;
                $response['message'] = "A senha deve ter pelo menos 8 caracteres e deve conter pelo menos um número, uma letra maiúscula, uma letra minúscula e um caractere especial.";
            } else {
                $response['message'] = "Senha Ok.";
            }

            return $response;
        }

        function w2travel_get_uploads_files(WP_REST_Request $request)
        {

            $post_id = $request->get_param('id');
            $AllMedias = get_attached_media('image', $post_id);
            $medias = [];
            foreach ($AllMedias as $key => $arrMedia) {
                # code...
                $medias[] = [
                    'ID' => $arrMedia->ID,
                    'guid' => $arrMedia->guid,
                ];

            }

            return $medias;

        }

        function w2travel_get_meta_post(WP_REST_Request $request)
        {

            $post_id = $request->get_param('id');
            $filter = $request->get_param('filter');

            $metas = get_post_meta($post_id);
            $response = [];

            foreach ($metas as $key => $meta) {
                $data = $meta[0];
                if (is_serialized($data)) {
                    $data = unserialize($data);
                }

                $response[$key] = $data;
            }

            if (!empty($filter)) {

                $filters = explode(',', $filter);
                $arr = [];
                foreach ($filters as $key => $meta) {
                    # code...
                    $arr[$meta] = $response[$meta];
                }

                return $arr;

            }
            return $response;

        }

        function w2travel_upload_files(WP_REST_Request $request)
        {
            // allowed routes
            $parameters = $request->get_params();
            $action = $parameters['action'];
            $meta_key = $parameters['meta_key'];

            if (isset($meta_key)) {

                return $meta_key;
            }

            $user_id = get_current_user_id();
            $user = new \WP_User($user_id);

            $post_id = $request->get_param('id');
            $mimes = array(
                'bmp' => 'image/bmp',
                'gif' => 'image/gif',
                'jpe' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'tif' => 'image/tiff',
                'tiff' => 'image/tiff',
            );

            if (!function_exists('wp_handle_upload')) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
            }

            $uploadedfile = $_FILES['uploadFile'];

            if (!isset($uploadedfile)) {

                $message = array(
                    "status" => 404,
                    "code" => __('File is invalid, and was error uploaded.', 'textdomain') . "\n",
                );

                return $message;

            }

            $upload_overrides = array(
                'mimes' => $mimes,
                'test_form' => false,
                'unique_filename_callback' => 'my_cust_filename',
            );

            $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

            if ($movefile && !isset($movefile['error'])) {
                $uploadedFileURL = $movefile['url'];
                $uploadedFileName = basename($movefile['url']);

                $ufiles[] = $movefile;
                update_post_meta($post_id, 'mf_my_files', $ufiles);

                // $filename should be the path to a file in the upload directory.

                $filename = $movefile['url'];

                // The ID of the post this attachment is for.
                $parent_post_id = $post_id;

                // Check the type of file. We'll use this as the 'post_mime_type'.
                $filetype = wp_check_filetype(basename($filename), null);

                // Get the path to the upload directory.
                $wp_upload_dir = wp_upload_dir();

                // Prepare an array of post data for the attachment.
                $attachment = array(
                    'guid' => $wp_upload_dir['url'] . '/' . basename($filename),
                    'post_mime_type' => $filetype['type'],
                    'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                    'post_content' => '',
                    'post_status' => 'inherit',
                );

                if ($action === 'avatar') {

                    return recordUserAvatar($attachment);

                }

                // Insert the attachment.
                $attach_id = wp_insert_attachment($attachment, $filename, $parent_post_id);

                $message = array(
                    "code" => __('File is valid, and was successfully uploaded.', 'textdomain') . "\n",
                    "file_url" => $uploadedFileURL,
                    "file_id" => $attach_id,
                );

                // var_dump( $movefile );
            } else {
                /*
                 * Error generated by _wp_handle_upload()
                 * @see _wp_handle_upload() in wp-admin/includes/file.php
                 */
                $message = $movefile['error'];
            }

            return $message;

            $parameters = $request->get_params(); //todoas od parameros

            // return new WP_Error('forbidden', 'Access forbidden.', array(
            //     'status' => 403,
            //     'data' => $response,
            //     'request' => $parameters,
            //     'folder' => $folderInfo,
            //     'file' => $fileInfo,
            //     'upload' => $upload_dir,
            // ));

            /**criando array de campos metafields **/

        }

        // renomer imagens
        function my_cust_filename($dir, $name, $ext)
        {
            $uniquesavename = "IMG-" . time() . uniqid(rand());
            return $uniquesavename . $ext;
        }
        // uploads files
        function my_files_save($post_id)
        {

            if (!isset($_FILES) || empty($_FILES) || !isset($_FILES['uploadFile'])) {
                return;
            }

            if (!function_exists('wp_handle_upload')) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
            }

            $upload_overrides = array('test_form' => false);

            $files = $_FILES['uploadFile'];
            $message = "enviando...";
            foreach ($files['name'] as $key => $value) {
                if ($files['name'][$key]) {
                    $uploadedfile = array(
                        'name' => $files['name'][$key],
                        'type' => $files['type'][$key],
                        'tmp_name' => $files['tmp_name'][$key],
                        'error' => $files['error'][$key],
                        'size' => $files['size'][$key],
                    );

                    $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

                    if ($movefile && !isset($movefile['error'])) {
                        $ufiles = get_post_meta($post_id, 'my_files', true);
                        if (empty($ufiles)) {
                            $ufiles = array();
                        }

                        $ufiles[] = $movefile;
                        update_post_meta($post_id, 'my_files', $ufiles);

                    } else {
                        $message = [
                            'status' => 403,
                            'code' => "upload-error",
                            'message' => "erro ao enviar o arquivo",
                            'file' => $uploadedfile['name'],
                        ];
                    }
                }
            }

            return $message;

        }

        // delete files receive resquest from  w2travel_delete_files
        function w2travel_delete_files(WP_REST_Request $request)
        {

            $parameters = $request->get_params(); //todoas od parameros

            $error = false;
            $noDeleted = [];
            foreach ($parameters as $key => $media_id) {
                # code...
                if (!wp_delete_attachment($media_id, true)) {

                    $error = true;
                    array_push($noDeleted, $media_id);

                }
            }

            $success = [
                'status' => 200,
                'code' => 'medias_deleted',
                'message' => 'Imagens deletadas com sucesso!',
                'no_deleted' => $noDeleted,
            ];

            $inError = [
                'status' => 400,
                'code' => 'medias_deleted',
                'message' => 'Imagens deletadas com sucesso!',
            ];

            return $success;

        }

        /**
         * rota aberta
         */
        function w2travel_initialSettings(WP_REST_Request $request)
        {
            $parameters = $request->get_params(); //todoas od parameros
            $ref = $parameters['ref'];

            $response = [];
            if ($ref) {
                $response = [

                    status => 200,
                    settings => [],
                    profiles => [
                        [id => 1, value => 'traveler', icon => ["fas", "id-badge"], text => 'Viajante', content => 'Participe de Chats, comente e curta viagens'],
                        [id => 2, value => 'agencia_turismo', icon => "project-diagram", text => 'Turismo', content => 'Oferte viagens, crie rede de contatos automaticamente e venda muito mais! '],
                        [id => 3, value => 'restaurante', icon => "utensils", text => 'Restaurante', content => 'Cadastre seu restaurante e deixe seus clientes conhecerem seu espaço.'],
                        [id => 4, value => 'hospedagem', icon => "hotel", text => 'hospedagem', content => 'Divulgue pousadas, hotéis ou casas para temporadas.'],
                        [id => 5, value => 'carro', icon => "car", text => 'Carros (Aluguel)', content => 'Se tem um veículo para aluguel, divulgue aqui.'],
                        [id => 6, value => 'passagem', icon => "ticket-alt", text => 'Passagem', content => 'Faça com que os viajantes conheçam seu serviço de venda de passagens.'],
                    ],
                ];
            };

            return $response;
        }

        /**
         * Custom register email
         */
        add_filter('simple_jwt_login_register_hook', 'wp_new_user_notification', 10, 3);
        // Redefine user notification function
        if (!function_exists('wp_new_user_notification')) {
            function wp_new_user_notification($user_id, $plaintext_pass = '')
            {
                $user = new WP_User($user_id);

                $user_login = stripslashes($user->user_login);
                $user_email = stripslashes($user->user_email);

                $message = sprintf(__('New user registration on your blog %s:'), get_option('blogname')) . "rnrn";
                $message .= sprintf(__('Username: %s'), $user_login) . "rnrn";
                $message .= sprintf(__('E-mail: %s'), $user_email) . "rn";

                @wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), get_option('blogname')), $message);

                if (empty($plaintext_pass)) {
                    return;
                }

                $message = __('Olá,') . "</br></br>";
                $message .= sprintf(__("Seja bem vindo à %s! Estes são seus dados de login:"), get_option('blogname')) . "</br>";

                $message .= sprintf(__('Usuário: %s'), $user_login) . "</br>";
                $message .= sprintf(__('Senha: %s'), $plaintext_pass) . "</br>";
                $message .= sprintf(__('Se houver qualquer problema, por favor entre em contato conosco: %s.'), get_option('admin_email')) . "</br>";
                $message .= __('Até logo!');

                wp_mail($user_email, sprintf(__('[%s] Seu usuário e senha'), get_option('blogname')), $message);

            }
        }

        function wpse27856_set_content_type()
        {
            return "text/html";
        }
        add_filter('wp_mail_content_type', 'wpse27856_set_content_type');

        function mod_jwt_auth_token_before_dispatch($response, $user)
        {

            $nonce = wp_create_nonce('my-nonce');

            $response['nonce'] = $nonce;

            return $response;

        }
        add_filter('jwt_auth_valid_credential_response', 'mod_jwt_auth_token_before_dispatch', 10, 2);

        function printText()
        {

            return 'ok';

        }

        function w2travel_teste_001()
        {

            return 'ok';

        }

        add_filter('bdpwr_code_email_text', function ($text, $email, $code, $expiry) {

            $date = new DateTime("@" . $expiry); // will snap to UTC because of the
            // "@timezone" syntax

            $date->setTimezone(new DateTimeZone('Pacific/Chatham'));
            $data = $date->format('Y-m-d H:i:sP'); // Pacific time

            $data = date('d/m/Y H:i:s', strtotime($data));

            $text = "Seu código para reset da senha: {$code}<br>";
            $text .= "Seu código expira em: {$data}";

            return $text;
        }, 10, 4);

        function queryArgument($filter)
        {

            if (!$filter['city']) {
                return null;
            }

            $metaQuery = array(
                // meta query takes an array of arrays, watch out for this!
                'relation' => 'AND',
                array(
                    'key' => 'city',
                    'value' => array($filter['city']),
                    'compare' => 'IN',
                ),
                'state_clause' => array(
                    'key' => 'state',
                    'value' => $filter['state'],
                ),
            );

            return $metaQuery;

        }
        /***
         * buscar postagens baseadas em parametros
         * Esta funcao tambem e usada nos roteiros | estabelecimentos na rota
         */





        function w2travel_app_get_business(WP_REST_Request $request)
        {
            $parameters = $request->get_params(); //todoas od parameros


            $id = $parameters['id'];

            if(!$id){
                
                $error = [
                    "status" => 403,
                    "code"   => "empty_id",
                    "message" => "necessário ter um id"
                ];

                return $error;

            }

            $roteiro = get_post_meta($id, "mf_all_roteiro", true);

        
            $locations = [];
            $business = [];

            $allowed  = ["state", "city"];
            foreach ($roteiro as $value) {
                # code...
                    $filtered = array_filter(
                        $value,
                        function ($key) use ($allowed) {
                            return in_array($key, $allowed);
                        },
                        ARRAY_FILTER_USE_KEY
                    );

                    array_push($locations, $filtered);

            }
            

            $type = $parameters['type'] ?? "viagens";
            $filter = $parameters['filter'] ?? false;
            $meta_query = [];

            $author = null;
         

                foreach($locations as $location){
                    
                    $stateArg = array(
                        'key' => 'state',
                        'value' => $location['state'],
                        'compare' => 'LIKE',
                    );

                    $cityArg = array(
                        'key' => 'city',
                        'value' => $location['city'],
                        'compare' => 'LIKE',
                    );


                    array_push($meta_query, $stateArg);
                    array_push($meta_query, $cityArg);

                    $args = array(
                        'fields' => array('ids'),
                        // 'meta_key'   => 'color',
                        // 'orderby'    => 'meta_value',
                        'order' => 'ASC',
                        'meta_query' => $meta_query,
                    );
                    
                    $args['post_type'] = ["restaurante", "hospedagens", "carros", "agencia_turismo"];

                    $posts = new WP_Query($args);

                    if($posts->posts) $business[$location['city']] = $posts->posts;
               
                }

                $allowed  = ["ID"];
                $response = filterThisArray($business, $allowed);


                return $business;



            
            if ($type === "business" || empty($type)) $args['post_type'] = ["restaurante", "hospedagens", "carros", "agencia_turismo"];
            if (isset($parameters['author'])) $args['author'] = get_current_user_id();
            
            if (isset($parameters['author_id']) && !empty($parameters['author_id'])) {
                $args['author'] = $parameters['author_id'];
            };
            
    


            $response = [];
            $i = 0;
            foreach ($posts->posts as $key => $value) {
                $response[$i]->ID = $value->ID;
                $response[$i]->post_type = $value->post_type;
                $response[$i]->post_title = $value->post_title;
                $response[$i]->post_meta->address = get_post_meta($value->ID, "formatted_address");
                $i++;
            }

            return $response;


            



        }

        function w2travel_app_search(WP_REST_Request $request)
        {
            $parameters = $request->get_params(); //todoas od parameros

            $type = $parameters['type'] ?? "viagens";
            $author = null;
            if (isset($parameters['author'])) {
                $author = get_current_user_id();
            };

            $filter = array(
                'country' => $parameters['type'] ?? null,
                'state' => $parameters['state'] ?? null,
                'city' => $parameters['city'] ?? null,
            );

            $args = array(
                'post_type' => $type,
                'fields' => array('ids'),
                'author' => $author,
                // 'meta_key'   => 'color',
                // 'orderby'    => 'meta_value',
                'order' => 'ASC',
                'meta_query' => queryArgument($filter),

            );

            $posts = new WP_Query($args);

            $response = [];
            $i = 0;
            foreach ($posts->posts as $key => $value) {
                $response[$i]->ID = $value->ID;
                $response[$i]->post_title = $value->post_title;
                $i++;
            }

            return $response;
        }

        function filterThisArray($array, $allowed){
            $response = [];
            foreach ($array as $value) {
                # code...
                    $filtered = array_filter(
                        $value,
                        function ($key) use ($allowed) {
                            return in_array($key, $allowed);
                        },
                        ARRAY_FILTER_USE_KEY
                    );

                    array_push($response, $filtered);

            }

            return $response;



        }

        function cptui_register_my_cpts()
        {

            /**
             * Post Type: Experiences.
             */

            $labels = array(
                "name" => __("Experiences", "twentynineteen"),
                "singular_name" => __("Experience", "twentynineteen"),
            );

            $args = array(
                "label" => __("Experiences", "twentynineteen"),
                "labels" => $labels,
                "description" => "",
                "public" => true,
                "publicly_queryable" => true,
                "show_ui" => true,
                "delete_with_user" => false,
                "show_in_rest" => true,
                "rest_base" => "",
                "rest_controller_class" => "WP_REST_Posts_Controller",
                "has_archive" => false,
                "show_in_menu" => true,
                "show_in_nav_menus" => true,
                "exclude_from_search" => false,
                "capability_type" => "post",
                "map_meta_cap" => true,
                "hierarchical" => false,
                "rewrite" => array("slug" => "experience", "with_front" => true),
                "query_var" => true,
                "supports" => array("title", "editor", "thumbnail", "custom-fields"),
            );

            register_post_type("experience", $args);
        }

        add_action('init', 'cptui_register_my_cpts');

        add_action('rest_api_init', 'register_experience_meta_fields');
        function register_experience_meta_fields()
        {

            register_meta('post', 'location', array(
                'type' => 'string',
                'description' => 'event location',
                'single' => true,
                'show_in_rest' => true,
            ));

            register_meta('post', 'date', array(
                'type' => 'string',
                'description' => 'event location',
                'single' => true,
                'show_in_rest' => true,
            ));

            register_meta('post', 'event_url', array(
                'type' => 'string',
                'description' => 'event location',
                'single' => true,
                'show_in_rest' => true,
            ));
            register_meta('post', 'profile_meta', array(
                'type' => 'string',
                'description' => 'event location',
                'single' => true,
                'show_in_rest' => true,
            ));

        }

    }

}
