<?php
/**
 * SWAP_Widgets_Init initial setup
 *
 * @since 1.0.0
 */

if( !class_exists('SWAP_Widgets_Init') ) {

	class SWAP_Widgets_Init {

		private static $instance;
		public static $branding;

		/**
		*  Initiator
		*/
		public static function get_instance(){
			if ( ! isset( self::$instance ) ) {
				self::$instance = new SWAP_Widgets_Init();
			}
			return self::$instance;
		}

		/**
		*  Constructor
		*/
		public function __construct() {

			/**
			 *	Include files.
			 */
			$this->includes();

			/**
			 *	Register widgets.
			 */
			$this->register_widgets();

			//	Enqueue scripts
			add_action( 'wp_loaded',             array( $this, 'register_admin_scripts' ) );
			add_action( 'wp_enqueue_scripts',    array( $this, 'load_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts_widgets' ) );
		}

		/**
		 *	Include files.
		 *
		 * @since 1.0.0
		 */
		function includes() {

			// Common functions
			require_once SWAP_WIDGETS_DIR . 'includes/extras.php';

			// Theme hooks
			require_once SWAP_WIDGETS_DIR . 'includes/theme-hooks.php';
		}

		/**
		 * Register widgets
		 *
		 * @since 1.0.0
		 */
		function register_widgets() {
			register_astra_widget( 'widget-address' );
			/*register_astra_widget( 'widget-flickr-gallery' );
			register_astra_widget( 'widget-instagram-gallery' );
			register_astra_widget( 'widget-progress-bar' );
			register_astra_widget( 'widget-info-box' );
			// register_astra_widget( 'widget-social-profiles' );
			// register_astra_widget( 'widget-newsletter' );
			register_astra_widget( 'widget-image' );
			register_astra_widget( 'widget-twitter-feeds' );
			register_astra_widget( 'widget-custom-menu' );
			register_astra_widget( 'widget-button' );
			register_astra_widget( 'widget-recent-posts' );*/
		}

		/**
		 * Enqueue scripts on the frontend
		 *
		 * @since 1.0.0
		 */
		function load_scripts() {

			// Astra Icons for Widget
			wp_enqueue_style( 'ast-widgets-icons-css', SWAP_WIDGETS_URL . 'assets/css/minified/astra-fonts.min.css' );
			
			wp_enqueue_style( 'ast-widgets-frontend-css', SWAP_WIDGETS_URL . 'assets/css/minified/widgets.min.css' );
			wp_enqueue_script( 'ast-widgets-frontend-js', SWAP_WIDGETS_URL . 'assets/js/unminified/widgets-frontend.js', array( 'jquery' ) );

			$theme_color = AstWidgetsThemeHooks::$_theme_color;
			$theme_color = array(
				'theme_color' => $theme_color,
			);

			wp_localize_script( 'ast-widgets-frontend-js', 'ast_widgets', $theme_color );
			wp_localize_script( 'ast-widgets-frontend-js', 'ast_cpwidget_ajax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
		}

		/**
		 *	Register admin scripts
		 *
		 * Used hook 'wp_loaded' to load scripts on backend and on frontend for Beaver Builder
		 *
		 * @since 1.0.0
		 */
		function register_admin_scripts() {

			// Astra Icons for Widget
			wp_enqueue_style( 'ast-widgets-icons-css', SWAP_WIDGETS_URL . 'assets/css/minified/astra-fonts.min.css' );

			wp_register_style(  'ast-widgets-backend-css', SWAP_WIDGETS_URL . 'admin/assets/css/minified/widgets-admin.min.css' );
			wp_register_script( 'ast-widgets-backend-js', SWAP_WIDGETS_URL . 'assets/js/unminified/widgets-backend.js' );
			wp_localize_script( 'ast-widgets-backend-js', 'ast_widgets', array(
				'repeater_msg_confirm' => __( "Do you want to close?", 'astra' ),
				'repeater_msg_error'   => __( "Could not removed. At least one item required.", 'astra' ),
			) );

			//	Load assets for Beaver Builder editor
			if ( class_exists( 'FLBuilderModel' ) && isset( $_GET['fl_builder'] ) ) {
				wp_enqueue_media();
				wp_enqueue_style( 'ast-widgets-backend-css' );
				wp_enqueue_script( 'ast-widgets-backend-js' );
			}
		}

		/**
		 *	load assets
		 *
		 * @since 1.0.0
		 */
		function load_scripts_widgets( $hook ) {

    		if( $hook === "widgets.php" ) {
	
				wp_enqueue_media();
				wp_enqueue_style(  'wp-color-picker');
				wp_enqueue_script( 'wp-color-picker');
				wp_enqueue_style( 'ast-widgets-backend-css' );
				wp_enqueue_script( 'ast-widgets-backend-js' );
			}
		}
	}
}

/**
*  Kicking this off by calling 'get_instance()' method
*/
$SWAP_Widgets_Init = SWAP_Widgets_Init::get_instance();
