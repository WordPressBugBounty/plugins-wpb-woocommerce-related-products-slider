<?php
/**
 * Plugin Name:       WPB Related Products Slider for WooCommerce
 * Plugin URI:        http://wpbean.com/plugins/
 * Description:       Highly customizable related product slider plugin for WooCommerce. 
 * Version:           1.3.7
 * Author:            wpbean
 * Author URI:        http://wpbean.com
 * Text Domain:       wpb-wrps
 * Domain Path:       /languages
 */


defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}


/**
 * Define 
 */

if ( ! defined( 'WPB_WRPS_FREE_URI' ) ) {
	define( 'WPB_WRPS_FREE_URI', WP_CONTENT_URL. '/plugins/wpb-woocommerce-related-products-slider' );
}

if ( ! defined( 'WPB_WRPS_FREE_INIT' ) ) {
    define( 'WPB_WRPS_FREE_INIT', plugin_basename( __FILE__ ) );
}

/**
 * This version can't be activate if premium version is active
 */

if ( defined( 'WPB_WRPS_PREMIUM' ) ) {
    function wpb_wrps_install_free_admin_notice() {
        ?>
        <div class="error">
            <p><?php esc_html_e( 'You can\'t activate the free version of WPB Related Products Slider for WooCommerce while you are using the premium one.', 'wpb-wrps' ); ?></p>
        </div>
    <?php
    }

    add_action( 'admin_notices', 'wpb_wrps_install_free_admin_notice' );
    deactivate_plugins( plugin_basename( __FILE__ ) );
    return;
}

/**
 * Plugin Activation redirect 
 */

if( !function_exists( 'wpb_wrps_activation_redirect' ) ){
	function wpb_wrps_activation_redirect( $plugin ) {
	    if( $plugin == plugin_basename( __FILE__ ) ) {
	        exit( wp_redirect( admin_url( 'options-general.php?page=wpb_wrps_product_slider' ) ) );
	    }
	}
}
add_action( 'activated_plugin', 'wpb_wrps_activation_redirect' );



/**
 * Plugin Action Links
 */

function wpb_wrps_add_action_links ( $links ) {

	$links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=wpb_wrps_product_slider') ) .'">'. esc_html( 'Settings', 'wpb-wrps' ) .'</a>';
	$links[] = '<a style="color: #39b54a; font-weight: bold" href="'. esc_url( 'http://bit.ly/1IpFfXU' ) .'">'. esc_html( 'Go PRO!', 'wpb-wrps' ) .'</a>';

	return $links;
}


/**
 * Pro version discount
 */

function wpb_wrps_pro_discount_admin_notice() {
    $user_id = get_current_user_id();
    if ( !get_user_meta( $user_id, 'wpb_wrps_pro_discount_dismissed' ) ){
        printf('<div class="wpb-wrps-discount-notice updated" style="padding: 30px 20px;border-left-color: #27ae60;border-left-width: 5px;margin-top: 20px;"><p style="font-size: 18px;line-height: 32px">%s <a target="_blank" href="%s">%s</a>! %s <b>%s</b></p><a href="%s">%s</a></div>', esc_html__( 'Get a 10% exclusive discount on the premium version of the', 'wpb-wrps' ), 'https://wpbean.com/downloads/wpb-woocommerce-related-products-slider-pro/', esc_html__( 'WPB Related Products Slider for WooCommerce', 'wpb-wrps' ), esc_html__( 'Use discount code - ', 'wpb-wrps' ), '10PERCENTOFF', esc_url( add_query_arg( 'wpb-wrps-pro-discount-admin-notice-dismissed', 'true' ) ), esc_html__( 'Dismiss', 'wpb-wrps' ));
    }
}


function wpb_wrps_pro_discount_admin_notice_dismissed() {
    $user_id = get_current_user_id();
    if ( isset( $_GET['wpb-wrps-pro-discount-admin-notice-dismissed'] ) ){
        add_user_meta( $user_id, 'wpb_wrps_pro_discount_dismissed', 'true', true );
    }
}


/**
 * Plugin Deactivation
 */

function wpb_wrps_lite_plugin_deactivation() {
  $user_id = get_current_user_id();
  if ( get_user_meta( $user_id, 'wpb_wrps_pro_discount_dismissed' ) ){
  	delete_user_meta( $user_id, 'wpb_wrps_pro_discount_dismissed' );
  }
}



/**
 * Plugin Init
 */

function wpb_wrps_free_plugin_init(){
	load_plugin_textdomain( 'wpb-wrps', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wpb_wrps_add_action_links' );

	register_deactivation_hook( plugin_basename( __FILE__ ), 'wpb_wrps_lite_plugin_deactivation' );
	add_action( 'admin_notices', 'wpb_wrps_pro_discount_admin_notice' );
	add_action( 'admin_init', 'wpb_wrps_pro_discount_admin_notice_dismissed' );

	require_once dirname( __FILE__ ) . '/inc/wpb-wrps-filter.php';
	require_once dirname( __FILE__ ) . '/inc/wpb-wrps-functions.php';
	require_once dirname( __FILE__ ) . '/inc/wpb-wrps-scripts.php';
	require_once dirname( __FILE__ ) . '/admin/class.settings-api.php';
	require_once dirname( __FILE__ ) . '/admin/settings-config.php';
}
add_action( 'plugins_loaded', 'wpb_wrps_free_plugin_init' );