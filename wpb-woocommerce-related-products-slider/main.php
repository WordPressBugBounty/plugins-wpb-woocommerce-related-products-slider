<?php

/**
 * Plugin Name:       WPB Related Products Slider for WooCommerce
 * Plugin URI:        http://wpbean.com/plugins/
 * Description:       Highly customizable related product slider plugin for WooCommerce. 
 * Version:           2.0
 * Author:            WPBean
 * Author URI:        http://wpbean.com
 * Text Domain:       wpb-wrps
 * Domain Path:       /languages
 */


defined('ABSPATH') || exit;

if (! function_exists('is_plugin_active')) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}


/**
 * Define 
 */

if (! defined('WPB_WRPS_FREE_URI')) {
    define('WPB_WRPS_FREE_URI', WP_CONTENT_URL . '/plugins/wpb-woocommerce-related-products-slider');
}

if (! defined('WPB_WRPS_FREE_INIT')) {
    define('WPB_WRPS_FREE_INIT', plugin_basename(__FILE__));
}

/**
 * This version can't be activate if premium version is active
 */

if (defined('WPB_WRPS_PREMIUM')) {
    function wpb_wrps_install_free_admin_notice()
    {
?>
        <div class="error">
            <p><?php esc_html_e('You can\'t activate the free version of WPB Related Products Slider for WooCommerce while you are using the premium one.', 'wpb-wrps'); ?></p>
        </div>
<?php
    }

    add_action('admin_notices', 'wpb_wrps_install_free_admin_notice');
    deactivate_plugins(plugin_basename(__FILE__));
    return;
}

/**
 * Plugin Activation redirect 
 */

if (!function_exists('wpb_wrps_activation_redirect')) {
    function wpb_wrps_activation_redirect($plugin)
    {
        if ($plugin == plugin_basename(__FILE__)) {
            if (! get_option('wpb_wrps_installed')) {
                update_option('wpb_wrps_installed', time());
            }
            exit(wp_redirect(admin_url('options-general.php?page=wpb_wrps_product_slider')));
        }
    }
}
add_action('activated_plugin', 'wpb_wrps_activation_redirect');



/**
 * Plugin Action Links
 */

function wpb_wrps_add_action_links($links)
{

    $links[] = '<a href="' . esc_url(get_admin_url(null, 'options-general.php?page=wpb_wrps_product_slider')) . '">' . esc_html('Settings', 'wpb-wrps') . '</a>';
    $links[] = '<a style="color: #39b54a; font-weight: bold" href="' . esc_url('https://wpbean.com/downloads/wpb-woocommerce-related-products-slider-pro/') . '">' . esc_html('Go PRO!', 'wpb-wrps') . '</a>';

    return $links;
}


/**
 * Plugin Deactivation
 */

function wpb_wrps_lite_plugin_deactivation()
{
    delete_option('wpb_wrps_installed');
}

/**
 * Include a template by precedance
 *
 * Looks at the theme directory first
 *
 * @param  string  $template_name
 * @param  array   $args
 *
 * @return void
 */

function wpb_wrps_get_template($template_name, $args = array())
{

    $plugin_path    = untrailingslashit(plugin_dir_path(__FILE__));
    $template_path  = $plugin_path . '/templates/';
    $theme_dir_path = apply_filters('wpb_wrps_theme_dir_path', 'wpb-woocommerce-related-products-slider/');

    if ($args && is_array($args)) {
        extract($args);
    }

    $template = locate_template(array(
        $theme_dir_path . $template_name,
        $template_name
    ));

    if (! $template) {
        $template = $template_path . $template_name;
    }

    if (file_exists($template)) {
        include $template;
    }
}

/**
 * Plugin Init
 */

function wpb_wrps_free_plugin_init()
{
    load_plugin_textdomain('wpb-wrps', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wpb_wrps_add_action_links');

    register_deactivation_hook(plugin_basename(__FILE__), 'wpb_wrps_lite_plugin_deactivation');

    require_once dirname(__FILE__) . '/inc/wpb-wrps-filter.php';
    require_once dirname(__FILE__) . '/inc/wpb-wrps-functions.php';
    require_once dirname(__FILE__) . '/inc/wpb-wrps-scripts.php';
    require_once dirname(__FILE__) . '/admin/class.settings-api.php';
    require_once dirname(__FILE__) . '/admin/settings-config.php';
    require_once dirname(__FILE__) . '/admin/class-discount-notice.php';
    require_once dirname(__FILE__) . '/admin/class-review-notice.php';

    if (is_admin()) {
        new WPB_WRPS_Discount_Notice();
        new WPB_WRPS_Review_Notice();
    }
}
add_action('plugins_loaded', 'wpb_wrps_free_plugin_init');
