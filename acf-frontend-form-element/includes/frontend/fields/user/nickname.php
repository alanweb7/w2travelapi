<?php

if( class_exists('acf_field_text') ) :

class acf_field_nickname extends acf_field_text {
	
	
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
		$this->name = 'nickname';
		$this->label = __("Nickname",'acf');
        $this->category = 'User';
		$this->defaults = array(
			'default_value'	=> '',
			'maxlength'		=> '',
			'placeholder'	=> '',
			'prepend'		=> '',
			'append'		=> ''
		);
        add_filter( 'acf/load_field/type=text',  [ $this, 'load_nickname_field'] );
		
    }
    
    function load_nickname_field( $field ){
        if( ! empty( $field['custom_nickname'] ) ){
            $field['type'] = 'nickname';
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
                $value = $edit_user->nickname;
            }        
        }
        return $value;
    }

    public function update_value( $value, $post_id = false, $field = false ){
        if( $field['name'] == 'nickname' ) return $value;

        $user = explode( 'user_', $post_id ); 
        if( ! empty( $user[1] ) ){
            $user_id = $user[1]; 
            wp_update_user( array( 'ID' => $user_id, 'nickname' => $value ) );
        }
        return null;
    }

    function render_field( $field ){
        $field['type'] = 'text';
        parent::render_field( $field );
    }

   
}

// initialize
acf_register_field_type( 'acf_field_nickname' );

endif;
	
?>