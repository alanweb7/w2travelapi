<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://aconline.com.br
 * @since      1.0.0
 *
 * @package    Library_Functions_Mega
 * @subpackage Library_Functions_Mega/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Library_Functions_Mega
 * @subpackage Library_Functions_Mega/includes
 * @author     Alan Silva <alanweb7@gmail.com>
 */
class Library_Functions_Mega_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'library-functions-mega',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
