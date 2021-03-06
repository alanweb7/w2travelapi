<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! class_exists('acfef_group_field_hooks') ) :

	class acfef_text_field_hooks {
		
        public function update_value_with_prepend( $value, $post_id = false, $field = false ){
            if( empty( $field['save_prepend'] ) ) return $value;

			return $field['prepend'] . $value;
		}

        public function update_value_with_append( $value, $post_id = false, $field = false ){
            if( empty( $field['save_append'] ) ) return $value;

			return $value . $field['append'];
		}

        public function prepare_field_without_prepend( $field ){
            if( empty( $field['save_prepend'] ) || empty( $field['prepend'] ) ) return $field;

            $field_value = explode( $field['save_prepend'], $field['value'] ); 
            if( !empty( $field_value[1] ) ){
                $field['value'] = $field_value[1];
            }

            return $field;
        }
        public function prepare_field_without_append( $field ){
            if( empty( $field['save_append'] ) || empty( $field['append'] ) ) return $field;

            $field_value = explode( $field['save_append'], $field['value'] ); 
            $field['value'] = $field_value[0];

            return $field;
        }
        

		public function field_settings( $field ) {         

            $types = array( 'email', 'password', 'number', 'range', 'text' );

            acf_render_field_setting( $field, array(
                'label'			=> __('Save Prepend'),
                'name'			=> 'save_prepend',
                'type'			=> 'true_false',
                'ui'			=> 1,
            ) );	
            acf_render_field_setting( $field, array(
                'label'			=> __('Save Append'),
                'name'			=> 'save_append',
                'type'			=> 'true_false',
                'ui'			=> 1,
            ) );	
		}
	

		public function __construct() {
           // add_action( 'acf/render_field_settings',  array( $this, 'field_settings' ), 15, 1 );

			add_filter( 'acf/update_value', array( $this, 'update_value_with_prepend' ), 15, 3 );	
			add_filter( 'acf/update_value', array( $this, 'update_value_with_append' ), 16, 3 );	
            add_filter( 'acf/prepare_field', array( $this, 'prepare_field_without_prepend' ), 1 );	
			add_filter( 'acf/prepare_field', array( $this, 'prepare_field_without_append' ), 2 );	
		}
	}

	new acfef_text_field_hooks();

endif;

