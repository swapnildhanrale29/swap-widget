<?php


/*
Plugin Name: Simple Address
Plugin URI: http://www.swapnildhanrale.com/
Description: A simple plugin that adds a simple address widget
Version: 1.0
Author: Swapnil Dhanrale
Author URI: http://www.swapnildhanrale.com/
License: GPL2
*/

//define( 'AST_EXT_WIDGETS_DIR', AST_EXT_DIR . '/extensions/widgets/' );
//define( 'AST_EXT_WIDGETS_URL', AST_EXT_URI . '/extensions/widgets/' );

/**
 * AST_Ext_Widgets initial setup
 *
 * @since 1.0.0
 */

if( !class_exists('AST_Ext_Widgets') ) {

	class AST_Ext_Widgets {

		private static $instance;

		/**
		*  Initiator
		*/
		public static function get_instance(){
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor function that initializes required actions and hooks
		 */
		public function __construct() {

			//require_once AST_EXT_WIDGETS_DIR . 'classes/functions.php';
		}
	}

	/**
	*  Kicking this off by calling 'get_instance()' method
	*/
	$AST_Ext_Widgets = AST_Ext_Widgets::get_instance();
}

