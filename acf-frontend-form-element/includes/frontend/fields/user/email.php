<?php

if( class_exists('acf_field_email') ) :

class acf_field_user_email extends acf_field_email {
	
	
	/*
	*  initialize
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function initialize() {
		
		// vars
		$this->name = 'user_email';
		$this->label = __("User Email",'acf');
        $this->category = 'User';
		$this->defaults = array(
            'default_value'	=> '',
			'placeholder'	=> '',
			'prepend'		=> '',
			'append'		=> ''
		);
        add_filter( 'acf/load_field/type=email',  [ $this, 'load_user_email_field'] );
		
    }
    
    function load_user_email_field( $field ){
        if( ! empty( $field['custom_email'] ) ){
            $field['type'] = 'user_email';
        }
        return $field;
    }

    public function load_value( $value, $post_id = false, $field = false ){
        $user = explode( 'user_', $post_id ); 

        if( empty( $user[1] ) ){
            return $value;
        }else{
            $user_id = $user[1]; 
            $edit_user = get_user_by( 'ID', $user_id );
            if( $edit_user instanceof WP_User ){
                $value = esc_html( $edit_user->user_email );
            }
        }
        return $value;
    }

    public function validate_value( $is_valid, $value, $field, $input ){        
        if( $field['required'] == 0 && $value == '' ){
            return $is_valid;
        }

        if( ! empty( $field['prepend'] ) ) $value = $field['prepend'] . $value;
        if( ! empty( $field['append'] ) ) $value .= $field['append'];

        if ( ! is_email( $value ) ){
            if( ! $value ) return __( 'This field is required', 'acf-frontend-form-element' );
            return sprintf( __( '%s is not a valid email address', 'acf-frontend-form-element' ),  $value );
        }

        if( empty( $_POST['_acf_post_id'] ) ) return $is_valid;

        $user_id = explode( 'user_', $_POST['_acf_post_id'] );
        if( empty( $user_id[1] ) ){
            $edit_user = false;
        }else{
            $user_id = $user_id[1];
            $edit_user = get_user_by( 'ID', $user_id );
        }

        if( email_exists( $value ) ){   
            if( ! empty( $edit_user->user_email ) && $edit_user->user_email == $value ){
                return $is_valid;
            }        
            return sprintf( __( 'The email %s is already assigned to an existing account. Please try a different email or login to your account', 'acf-frontend-form-element' ),  $value );
        }
        
        return $is_valid;
    }

    public function update_value( $value, $post_id = false, $field = false ){
        $user = explode( 'user_', $post_id ); 

        if( ! empty( $user[1] ) ){
            $user_id = $user[1]; 
            wp_update_user( array( 'ID' => $user_id, 'user_email' => esc_attr( $value ) ) );

            if( !empty( $field['set_as_username'] ) ){
                $user_object = get_user_by( 'ID', $user_id );
                if( $user_object->user_login == $value ) return null;

                if( get_current_user_id() == $user_id ){
                    wp_clear_auth_cookie();
                    $_POST['log_back_in'] = $user_id;
                }
                global $wpdb; 
                $wpdb->update( $wpdb->users, array( 'user_login' => $value ), ['ID' => $user_id ] );
            }            
        }
        return null;
    }

    function render_field( $field ){
        $field['type'] = 'email';
        parent::render_field( $field );
    }

            	/*
	*  render_field_settings()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/
	
	function render_field_settings( $field ) {
        parent::render_field_settings( $field );
		// default_value
        acf_render_field_setting( $field, array(
            'label'			=> __('Set as Username'),
            'instructions'	=> 'Save value as username as well.',
            'name'			=> 'set_as_username',
            'type'			=> 'true_false',
            'ui'			=> 1,
        ) );
    }
   
}

// initialize
acf_register_field_type( 'acf_field_user_email' );

endif;
	
?>