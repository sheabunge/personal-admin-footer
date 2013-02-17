<?php

/**
 * Plugin Name: Personal Admin Footer
 * Plugin URI: http://bungeshea.com/plugins/personal-admin-footer/
 * Description: Changes the default 'Thank you for creating with WordPress' in your footer area to a more personal 'Thank you for visiting My Site' on all admin pages except the Network Admin.
 * Author: Shea Bunge
 * Author URI: http://bungeshea.com
 * Version: 1.0
 * Licence: MIT
 * License URI: http://opensource.org/licenses/mit-license.php
 */

function personal_admin_footer( $footer_text ) {
	if( ! is_network_admin() ) {
		$footer_text = str_replace(
			__( 'Thank you for creating with <a href="http://wordpress.org/">WordPress</a>.' ),
			sprintf ( __( 'Thank you for visiting <a href="%1$s">%2$s</a>.' ), get_home_url(), get_bloginfo( 'name' ) ),
			$footer_text
		);
	}
	return $footer_text;
}

add_filter( 'admin_footer_text', 'personal_admin_footer' );