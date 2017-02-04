<?php

/**
 * 	Theme Hooks
 *
 * @package         Astra
 * @author          Astra <support@brainstormforce.com>
 * @copyright       2016 Astra
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( 'AstWidgetsThemeHooks' ) ) {

	class AstWidgetsThemeHooks {
		private static $instance;

		public static $_theme_color;

		public static function instance() {

			if ( ! self::$instance ) {
				self::$instance = new self;
				self::$instance->hooks();
			}

			return self::$instance;
		}

		private function hooks() {
			self::$_theme_color = ast_get_option_widgets( 'next-theme-color', '', '#ffdd00' );
		}

	}

	AstWidgetsThemeHooks::instance();
}

