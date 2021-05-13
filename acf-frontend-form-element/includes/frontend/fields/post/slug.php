<?php

if( class_exists('acf_field_text') ) :

class acf_field_post_slug extends acf_field_text {
	
	
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
		$this->name = 'post_slug';
		$this->label = __("Slug",'acf');
        $this->category = 'Post';
		$this->defaults = array(
			'default_value'	=> '',
			'maxlength'		=> '',
			'placeholder'	=> '',
			'prepend'		=> '',
			'append'		=> ''
		);
        add_filter( 'acf/load_field/type=text',  [ $this, 'load_post_slug_field'] );
		
	}
    
    function load_post_slug_field( $field ){
        if( ! empty( $field['custom_slug'] ) ){
            $field['type'] = 'post_slug';
        }
        return $field;
    }

    public function load_value( $value, $post_id = false, $field = false ){
        if( $post_id && is_numeric( $post_id ) ){  
            $edit_post = get_post( $post_id );
            $value = $edit_post->post_name == 'auto-draft' ? '' : $edit_post->post_name;
        }
        return $value;
    }

    public function update_value( $value, $post_id = false, $field = false ){
        if( $post_id && is_numeric( $post_id ) ){  
            $post_to_edit = [
                'ID' => $post_id,
            ];
            $post_to_edit['post_name'] = sanitize_text_field( $value );
            remove_filter( 'acf/update_value/type=post_slug', [ $this, 'update_value'], 9, 3 );
            remove_filter( 'acf/update_value/key=' .$field['key'], [ $this, 'update_value'], 9, 3 );
            remove_filter( 'acf/update_value/name=' .$field['name'], [ $this, 'update_value'], 9, 3 );
            wp_update_post( $post_to_edit );
            add_filter( 'acf/update_value/type=post_slug', [ $this, 'update_value'], 9, 3 );
            add_filter( 'acf/update_value/key=' .$field['key'], [ $this, 'update_value'], 9, 3 );
            add_filter( 'acf/update_value/name=' .$field['name'], [ $this, 'update_value'], 9, 3 );
        }
        return $value;
    }

    function render_field( $field ){
        $field['type'] = 'text';
        parent::render_field( $field );
    }

   
}

// initialize
acf_register_field_type( 'acf_field_post_slug' );

endif;
	
?>