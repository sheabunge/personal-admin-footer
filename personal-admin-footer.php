<?php

/**
 * Plugin Name: Personal Admin Footer
 * Plugin URI:  http://bungeshea.com/plugins/personal-admin-footer/
 * Description: Changes the default 'Thank you for creating with WordPress' in your footer area to a more personal 'Thank you for visiting My Site' on all admin pages except the Network Admin.
 * Author:      Shea Bunge
 * Author URI:  http://bungeshea.com
 * Version:     1.1.0
 * Licence:     MIT
 * License URI: http://opensource.org/licenses/MIT
 * Text Domain: personal-admin-footer
 * Domain Path: /languages
 */

/**
 * Replace the admin footer text
 * @param  string $footer_text The current footer text
 * @return string              The new footer text
 */
function personal_admin_footer( $footer_text ) {
	global $wp_version;

	/* Don't change the footer text in the network admin */
	if ( ! is_network_admin() ) {

		/* The way of determining the default footer text was changed in 3.9 */
		if ( version_compare( $wp_version, '3.9', '<' ) ) {
			$old_text = __( 'Thank you for creating with <a href="http://wordpress.org/">WordPress</a>.' );
		} else {
			$old_text = sprintf( __( 'Thank you for creating with <a href="%s">WordPress</a>.' ), __( 'https://wordpress.org/' ) );
		}

		/* Define the new footer text */
		$new_text = __( 'Thank you for visiting <a href="%1$s">%2$s</a>.', 'personal-admin-footer' );

		/* Add the site name and link to the new footer text */
		$new_text = sprintf ( $new_text, get_home_url(), get_bloginfo( 'name' ) );

		/* Replace the old text with the new text */
		$footer_text = str_replace( $old_text, $new_text, $footer_text );
	}

	return $footer_text;
}

add_filter( 'admin_footer_text', 'personal_admin_footer' );

/**
 * Load the plugin textdomain
 */
function load_personal_admin_footer_textdomain() {
	load_plugin_textdomain( 'personal-admin-footer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'load_personal_admin_footer_textdomain' );
