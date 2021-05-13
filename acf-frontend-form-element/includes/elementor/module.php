<?php

namespace ACFFrontend;

use  ACFFrontend\Plugin ;
use  Elementor\Core\Base\Module ;

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly
}


if ( !class_exists( 'ACFFrontend_Elementor' ) ) {
    class ACFFrontend_Elementor
    {
        public  $form_widgets = array() ;
        public  $submit_actions = array() ;
        public  $elementor_categories = array() ;
        public function get_name()
        {
            return 'acf_frontend_form';
        }
        
        public static function find_element_recursive( $elements, $widget_id )
        {
            foreach ( $elements as $element ) {
                if ( $widget_id == $element['id'] ) {
                    return $element;
                }
                
                if ( !empty($element['elements']) ) {
                    $element = self::find_element_recursive( $element['elements'], $widget_id );
                    if ( $element ) {
                        return $element;
                    }
                }
            
            }
            return false;
        }
        
        public function widgets()
        {
            $widget_list = array(
                'general' => array(
                'acf-frontend-form' => 'ACF_Frontend_Form_Widget',
                'edit_button'       => 'Edit_Button_Widget',
            ),
                'posts'   => array(
                'edit_post'      => 'Edit_Post_Widget',
                'new_post'       => 'New_Post_Widget',
                'duplicate_post' => 'Duplicate_Post_Widget',
                'delete_post'    => 'Delete_Post_Widget',
            ),
                'terms'   => array(
                'edit_term'   => 'Edit_Term_Widget',
                'new_term'    => 'New_Term_Widget',
                'delete_term' => 'Delete_Term_Widget',
            ),
                'users'   => array(
                'edit_user'   => 'Edit_User_Widget',
                'new_user'    => 'New_User_Widget',
                'delete_user' => 'Delete_User_Widget',
            ),
            );
            $elementor = acfef_get_elementor_instance();
            foreach ( $widget_list as $folder => $widgets ) {
                foreach ( $widgets as $filename => $classname ) {
                    require_once __DIR__ . "/widgets/{$folder}/{$filename}.php";
                    $classname = 'ACFFrontend\\Widgets\\' . $classname;
                    $elementor->widgets_manager->register_widget_type( new $classname() );
                }
            }
            do_action( 'acfef/widget_loaded' );
        }
        
        public function widget_categories( $elements_manager )
        {
            $categories = array(
                'acfef-general'    => array(
                'title' => __( 'FRONTEND SITE MANAGEMENT', 'acf-frontend-form-element' ),
                'icon'  => 'fa fa-plug',
            ),
                'acfef-posts'      => array(
                'title' => __( 'FRONTEND POSTS', 'acf-frontend-form-element' ),
                'icon'  => 'fa fa-plug',
            ),
                'acfef-users'      => array(
                'title' => __( 'FRONTEND USERS', 'acf-frontend-form-element' ),
                'icon'  => 'fa fa-plug',
            ),
                'acfef-taxonomies' => array(
                'title' => __( 'FRONTEND TAXONOMIES', 'acf-frontend-form-element' ),
                'icon'  => 'fa fa-plug',
            ),
                'acfef-templates'  => array(
                'title' => __( 'FORM FIELDS', 'acf-frontend-form-element' ),
                'icon'  => 'fa fa-plug',
            ),
            );
            foreach ( $categories as $name => $args ) {
                $this->elementor_categories[$name] = $args;
                $elements_manager->add_category( $name, $args );
            }
        }
        
        public function dynamic_tags( $dynamic_tags )
        {
            
            if ( class_exists( 'ElementorPro\\Modules\\DynamicTags\\Tags\\Base\\Data_Tag' ) ) {
                \Elementor\Plugin::$instance->dynamic_tags->register_group( 'acfef-user-data', [
                    'title' => 'User',
                ] );
                require_once __DIR__ . '/dynamic-tags/user-local-avatar.php';
                require_once __DIR__ . '/dynamic-tags/author-local-avatar.php';
                $dynamic_tags->register_tag( new DynamicTags\User_Local_Avatar_Tag() );
                $dynamic_tags->register_tag( new DynamicTags\Author_Local_Avatar_Tag() );
            }
        
        }
        
        public function document_types()
        {
        }
        
        public function icon_file()
        {
            wp_enqueue_style(
                'acfef-icon',
                ACFEF_URL . 'includes/assets/css/icon.css',
                array(),
                ACFEF_ASSETS_VERSION
            );
            wp_enqueue_style(
                'acfef-editor',
                ACFEF_URL . 'includes/assets/css/editor.min.css',
                array(),
                ACFEF_ASSETS_VERSION
            );
            
            if ( acfef()->dev_mode ) {
                $min = '';
            } else {
                $min = '.min';
            }
            
            wp_enqueue_script(
                'acfef-editor',
                ACFEF_URL . 'includes/assets/js/editor' . $min . '.js',
                array( 'elementor-editor' ),
                ACFEF_ASSETS_VERSION,
                true
            );
            wp_enqueue_style( 'acf-global' );
        }
        
        public function __construct()
        {
            require_once __DIR__ . '/classes/save_fields.php';
            require_once __DIR__ . '/classes/content_tab.php';
            require_once __DIR__ . '/classes/permissions_tab.php';
            require_once __DIR__ . '/classes/modal.php';
            add_action( 'elementor/elements/categories_registered', array( $this, 'widget_categories' ) );
            add_action( 'elementor/widgets/widgets_registered', [ $this, 'widgets' ] );
            add_action( 'elementor/dynamic_tags/register_tags', [ $this, 'dynamic_tags' ] );
            add_action( 'elementor/documents/register', [ $this, 'document_types' ] );
            add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'icon_file' ] );
        }
    
    }
    acfef()->elementor = new ACFFrontend_Elementor();
}
