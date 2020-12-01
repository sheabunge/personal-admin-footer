<?php

/**
 * Plugin Name: Personal Admin Footer
 * Plugin URI:  https://sheabunge.com/plugins/personal-admin-footer/
 * Description: Changes the default 'Thank you for creating with WordPress' in your footer area to a more personal 'Thank you for visiting My Site' on all admin pages except the Network Admin.
 * Author:      Shea Bunge
 * Author URI:  https://sheabunge.com
 * Version:     1.2.0
 * Licence:     MIT
 * License URI: https://opensource.org/licenses/MIT
 * Text Domain: personal-admin-footer
 * Domain Path: /languages
 */

/* Don't enable this plugin in the Network Admin */
if ( is_network_admin() ) {
	return;
}

/**
 * Plugin class
 */
class Personal_Admin_Footer {

	/**
	 * Register actions and filters
	 */
	public function run() {
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_filter( 'admin_init', array( $this, 'register_setting' ) );
		add_filter( 'admin_footer_text', array( $this, 'replace_footer_text' ) );
	}

	/**
	 * Register the plugin setting field
	 */
	public function register_setting() {

		register_setting( 'general', 'personal_admin_footer_text' );

		add_settings_field(
			'personal_admin_footer_text',
			__( 'Admin Footer Text', 'personal-admin-footer' ),
			array( $this, 'render_setting' ),
			'general'
		);
	}

	/**
	 * Render the setting field
	 */
	public function render_setting() {
		echo '<textarea id="personal_admin_footer_text" name="personal_admin_footer_text" cols="80">',
			esc_textarea( $this->get_custom_text() ),
			'</textarea>';
	}

	/**
	 * Retrieve the original footer text
	 *
	 * @return string
	 */
	private function get_original_text() {
		global $wp_version;

		/* The way of determining the default footer text was changed in 3.9 */
		if ( version_compare( $wp_version, '3.9', '<' ) ) {
			$text = __( 'Thank you for creating with <a href="http://wordpress.org/">WordPress</a>.' );
		} else {
			/* translators: %s: https://wordpress.org/ */
			$text = sprintf( __( 'Thank you for creating with <a href="%s">WordPress</a>.' ), __( 'https://wordpress.org/' ) );
		}

		return $text;
	}

	/**
	 * Retrieve the new footer text
	 *
	 * @return string
	 */
	private function get_custom_text() {
		$text = get_option( 'personal_admin_footer_text' );

		if ( false === $text ) {
			$text = __( 'Thank you for visiting <a href="%1$s">%2$s</a>.', 'personal-admin-footer' );
			$text = sprintf( $text, get_home_url(), get_bloginfo( 'name' ) );
		}

		return $text;
	}

	/**
	 * Replace the admin footer text
	 *
	 * @param string $footer_text The current footer text
	 *
	 * @return string The new footer text
	 */
	function replace_footer_text( $footer_text ) {
		return str_replace( $this->get_original_text(), $this->get_custom_text(), $footer_text );
	}

	/**
	 * Load the plugin textdomain
	 */
	function load_textdomain() {
		$translations_path = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
		load_plugin_textdomain( 'personal-admin-footer', false, $translations_path );
	}
}

$plugin = new Personal_Admin_Footer();
$plugin->run();
