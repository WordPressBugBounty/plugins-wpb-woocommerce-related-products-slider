<?php 

/**
 * WPB WooCommerce Related Products Slider
 * By WPbean
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


add_action( 'wp', 'wpb_wrps_plugin_init', 10000 );

if( !function_exists('wpb_wrps_plugin_init') ){
	function wpb_wrps_plugin_init(){

		// Removing WooCommerce default relative products form @woocommerce_after_single_product_summary.
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
		
		$wpb_wrps_enable_related = wpb_wrps_get_option( 'wpb_wrps_enable_related', 'wpb_wrps_general', 'on' );

		if( 'on' === $wpb_wrps_enable_related ){

			// Adding this plugin relative products slider to @woocommerce_after_single_product_summary.
			add_action( 'woocommerce_after_single_product_summary', 'wpb_wrps_related_products', 22 );
		}
	}
}