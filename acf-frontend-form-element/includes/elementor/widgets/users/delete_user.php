<?php

namespace ACFFrontend\Widgets;

use  ACFFrontend\Plugin ;
use  ACFFrontend\Classes ;
use  Elementor\Controls_Manager ;
use  Elementor\Widget_Base ;
use  ElementorPro\Modules\QueryControl\Module as Query_Module ;
/**
 * Elementor ACF Frontend Form Widget.
 *
 * Elementor widget that inserts an ACF frontend form into the page.
 *
 * @since 1.0.0
 */
class Delete_User_Widget extends Widget_Base
{
    /**
     * Get widget name.
     *
     * Retrieve acf ele form widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'delete_user';
    }
    
    /**
     * Get widget title.
     *
     * Retrieve acf ele form widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __( 'Delete User', 'acf-frontend-form-element' );
    }
    
    /**
     * Get widget icon.
     *
     * Retrieve acf ele form widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'fas fa-trash-alt frontend-icon';
    }
    
    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the acf ele form widget belongs to.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return array( 'acfef-users' );
    }
    
    /**
     * Register acf ele form widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls()
    {
        $this->start_controls_section( 'delete_button_section', [
            'label' => __( 'Trash Button', 'acf-frontend-form-element' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );
        $this->add_control( 'main_action', [
            'type'    => Controls_Manager::HIDDEN,
            'default' => 'delete_user',
        ] );
        $this->add_control( 'delete_button_text', [
            'label'       => __( 'Delete Button Text', 'acf-frontend-form-element' ),
            'type'        => Controls_Manager::TEXT,
            'default'     => __( 'Delete', 'acf-frontend-form-element' ),
            'placeholder' => __( 'Delete', 'acf-frontend-form-element' ),
        ] );
        $this->add_control( 'delete_button_icon', [
            'label' => __( 'Delete Button Icon', 'acf-frontend-form-element' ),
            'type'  => Controls_Manager::ICONS,
        ] );
        $this->end_controls_section();
        $this->start_controls_section( 'actions_section', [
            'label' => __( 'Actions', 'acf-frontend-form-element' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );
        
        if ( !class_exists( 'ElementorPro\\Modules\\QueryControl\\Module' ) ) {
            $this->add_control( 'reassign_posts', [
                'label'       => __( 'Reassign Posts To...', 'acf-frontend-form-element' ),
                'description' => __( 'Enter user ID. If left empty, all of the user\'s posts will be deleted.', 'acf-frontend-form-element' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __( '18', 'acf-frontend-form-element' ),
            ] );
        } else {
            $this->add_control( 'reassign_posts', [
                'label'        => __( 'Reassign Posts To...', 'acf-frontend-form-element' ),
                'description'  => __( 'If left empty, all of the user\'s posts will be deleted.', 'acf-frontend-form-element' ),
                'label_block'  => true,
                'type'         => Query_Module::QUERY_CONTROL_ID,
                'autocomplete' => [
                'object'  => Query_Module::QUERY_OBJECT_USER,
                'display' => 'detailed',
            ],
            ] );
        }
        
        $this->add_control( 'confirm_delete_message', [
            'label'       => __( 'Confirm Delete Message', 'acf-frontend-form-element' ),
            'type'        => Controls_Manager::TEXT,
            'default'     => __( 'The user will be deleted. Are you sure?', 'acf-frontend-form-element' ),
            'placeholder' => __( 'The user will be deleted. Are you sure?', 'acf-frontend-form-element' ),
        ] );
        $this->add_control( 'show_delete_message', [
            'label'        => __( 'Show Success Message', 'acf-frontend-form-element' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __( 'Yes', 'acf-frontend-form-element' ),
            'label_off'    => __( 'No', 'acf-frontend-form-element' ),
            'default'      => 'true',
            'return_value' => 'true',
        ] );
        $this->add_control( 'delete_message', [
            'label'       => __( 'Success Message', 'acf-frontend-form-element' ),
            'type'        => Controls_Manager::TEXTAREA,
            'default'     => __( 'You have deleted this user', 'acf-frontend-form-element' ),
            'placeholder' => __( 'You have deleted this user', 'acf-frontend-form-element' ),
            'dynamic'     => [
            'active'    => true,
            'condition' => [
            'show_delete_message' => 'true',
        ],
        ],
        ] );
        $this->add_control( 'delete_redirect', [
            'label'   => __( 'Redirect After Delete', 'acf-frontend-form-element' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'custom_url',
            'options' => [
            'current'     => __( 'Reload Current Url', 'acf-frontend-form-element' ),
            'custom_url'  => __( 'Custom Url', 'acf-frontend-form-element' ),
            'referer_url' => __( 'Referer', 'acf-frontend-form-element' ),
        ],
        ] );
        $this->add_control( 'redirect_after_delete', [
            'label'         => __( 'Custom URL', 'acf-frontend-form-element' ),
            'type'          => Controls_Manager::URL,
            'placeholder'   => __( 'Enter Url Here', 'acf-frontend-form-element' ),
            'show_external' => false,
            'required'      => true,
            'dynamic'       => [
            'active' => true,
        ],
            'condition'     => [
            'delete_redirect' => 'custom_url',
        ],
        ] );
        $this->end_controls_section();
        $this->start_controls_section( 'user_section', [
            'label' => __( 'User', 'acf-frontend-form-element' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );
        acfef()->local_actions['user']->action_controls( $this );
        $this->end_controls_section();
        do_action( 'acfef/permissions_section', $this );
        $this->start_controls_section( 'style_promo_section', [
            'label' => __( 'Styles', 'acf-frontend-form-elements' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'styles_promo', [
            'type'            => Controls_Manager::RAW_HTML,
            'raw'             => __( '<p><a target="_blank" href="https://www.frontendform.com/"><b>Go Pro</b></a> to unlock styles.</p>', 'acf-frontend-form-element' ),
            'content_classes' => 'acf-fields-note',
        ] );
        $this->end_controls_section();
    }
    
    /**
     * Render acf ele form widget output on the frontend.
     *
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $display = false;
        $wg_id = $this->get_id();
        $current_post_id = acfef_get_current_post_id();
        global  $post ;
        $active_user = wp_get_current_user();
        $settings = $this->get_settings_for_display();
        $user_id = $active_user->ID;
        
        if ( $settings['user_to_edit'] == 'select_user' ) {
            $user_id = $settings['user_select'];
        } elseif ( $settings['user_to_edit'] == 'current_author' ) {
            
            if ( is_author() ) {
                $author_id = get_queried_object_id();
            } else {
                $author_id = get_the_author_meta( 'ID' );
            }
            
            $user_id = $author_id;
        } elseif ( $settings['user_to_edit'] == 'url_query' && isset( $_GET[$settings['url_query_user']] ) ) {
            $user_id = $_GET[$settings['url_query_user']];
        }
        
        $hidden_fields = [
            'screen_id'  => $current_post_id,
            'element_id' => $wg_id,
        ];
        $btn_args = array(
            'user_id'        => $user_id,
            'hidden_fields'  => $hidden_fields,
            'kses'           => true,
            'delete_message' => $settings['confirm_delete_message'],
            'delete_icon'    => $settings['delete_button_icon'],
            'delete_text'    => $settings['delete_button_text'],
            'reassign_posts' => $settings['reassign_posts'],
        );
        $delete_redirect_url = $settings['redirect_after_delete']['url'];
        
        if ( $settings['delete_redirect'] ) {
            switch ( $settings['delete_redirect'] ) {
                case 'custom_url':
                    if ( $delete_redirect_url ) {
                        $delete_redirect = $delete_redirect_url;
                    }
                    break;
                case 'referer_url':
                    if ( wp_get_referer() ) {
                        $delete_redirect = wp_get_referer();
                    }
                    break;
                default:
                    global  $wp ;
                    $delete_redirect = home_url( $wp->request );
            }
            $btn_args['redirect'] = $delete_redirect;
        }
        
        $display = apply_filters(
            'acfef/show_widget',
            $wg_id,
            $settings,
            $btn_args
        );
        if ( $display ) {
            acfef_delete_button( $btn_args );
        }
    }

}