<?php

/**
 * WPB WooCommerce Related Products Slider
 * By WPbean
 */


if (! defined('ABSPATH')) exit; // Exit if accessed directly


/**
 * Configure The Settings
 */

if (!class_exists('wpb_wrps_settings')):
    class wpb_wrps_settings
    {

        private $settings_api;

        function __construct()
        {
            $this->settings_api = new WPB_wrps_WeDevs_Settings_API;

            add_action('admin_init', array($this, 'admin_init'));
            add_action('admin_menu', array($this, 'admin_menu'), 9999);
            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
            add_action('wpb_wrps_after_settings_page', array($this, 'pro_sidebar'));
        }

        function enqueue_admin_styles($hook)
        {
            if ($hook !== 'woocommerce_page_wpb_wrps_product_slider') {
                return;
            }
            wp_enqueue_style(
                'wpb-wrps-settings',
                WPB_WRPS_FREE_URI . '/admin/assets/css/settings.css',
                array(),
                null
            );
        }

        function admin_init()
        {

            //set the settings
            $this->settings_api->set_sections($this->get_settings_sections());
            $this->settings_api->set_fields($this->get_settings_fields());

            //initialize settings
            $this->settings_api->admin_init();
        }

        function admin_menu()
        {
            add_submenu_page(
                'woocommerce',
                esc_html__('WPB WooCommerce Related Products Slider', 'wpb-wrps'),
                esc_html__('WPB Related Products Slider', 'wpb-wrps'),
                'manage_options',
                'wpb_wrps_product_slider',
                array($this, 'plugin_page'),
                999,
            );
        }
        // setings tabs
        function get_settings_sections()
        {
            $sections = array(
                array(
                    'id'    => 'wpb_wrps_general',
                    'title' => esc_html__('General Settings', 'wpb-wrps')
                )
            );
            return $sections;
        }

        /**
         * Returns all the settings fields
         *
         * @return array settings fields
         */
        function get_settings_fields()
        {
            $settings_fields = array(

                'wpb_wrps_general' => array(
                    array(
                        'name'      => 'wpb_wrps_enable_related',
                        'label'     => esc_html__('Enable Related Products Slider', 'wpb-wrps'),
                        'desc'      => esc_html__('Yes Please!', 'wpb-wrps'),
                        'type'      => 'checkbox',
                        'default'   => 'on',
                    ),
                    array(
                        'name'         => 'wpb_wrps_theme',
                        'label'     => esc_html__('Slider Theme', 'wpb-wrps'),
                        'desc'         => esc_html__('Choose a theme for related products slider, Default: Hover Effect Theme.', 'wpb-wrps'),
                        'type'         => 'select',
                        'default'     => 'no',
                        'options'     => array(
                            'wrps_theme_hover'     => esc_html__('Hover Effect Theme', 'wpb-wrps'),
                            'wrps_theme_box'    => esc_html__('Box Theme', 'wpb-wrps'),
                        )
                    ),
                    array(
                        'name'              => 'wpb_wrps_number_of_products',
                        'label'             => esc_html__('Number of Related Products', 'wpb-wrps'),
                        'desc'              => esc_html__('Number of Related Products to show in this slider.', 'wpb-wrps'),
                        'type'              => 'number',
                        'default'           => 100,
                        'sanitize_callback' => 'intval'
                    ),
                    array(
                        'name'              => 'wpb_wrps_number_of_columns',
                        'label'             => esc_html__('Number of columns in Slider', 'wpb-wrps'),
                        'desc'              => esc_html__('Default: 3 columns.', 'wpb-wrps'),
                        'type'              => 'number',
                        'default'           => 3,
                        'sanitize_callback' => 'intval'
                    ),
                    array(
                        'name'              => 'wpb_wrps_number_of_columns_desktop_small',
                        'label'             => esc_html__('Number of Columns in Slider [ Desktop Small 980px ]', 'wpb-wrps'),
                        'desc'              => esc_html__('Default: 3 columns.', 'wpb-wrps'),
                        'type'              => 'number',
                        'default'           => 3,
                        'sanitize_callback' => 'intval'
                    ),
                    array(
                        'name'              => 'wpb_wrps_number_of_columns_tablet',
                        'label'             => esc_html__('Number of Columns in Slider [ Tablet 768px ]', 'wpb-wrps'),
                        'desc'              => esc_html__('Default: 2 columns.', 'wpb-wrps'),
                        'type'              => 'number',
                        'default'           => 2,
                        'sanitize_callback' => 'intval'
                    ),
                    array(
                        'name'              => 'wpb_wrps_number_of_columns_mobile',
                        'label'             => esc_html__('Number of Columns in Slider [ Mobile 479px ]', 'wpb-wrps'),
                        'desc'              => esc_html__('Default: 1 columns.', 'wpb-wrps'),
                        'type'              => 'number',
                        'default'           => 1,
                        'sanitize_callback' => 'intval'
                    ),
                )

            );
            return $settings_fields;
        }

        // warping the settings
        function plugin_page()
        {
            $plugin_data = function_exists('get_plugin_data') ? get_plugin_data(WP_PLUGIN_DIR . '/' . WPB_WRPS_FREE_INIT, false, false) : array();
            $version     = isset($plugin_data['Version']) ? $plugin_data['Version'] : '';
            $pro_url     = add_query_arg(
                array(
                    'utm_content'  => 'Related+Products+Slider+Pro',
                    'utm_campaign' => 'adminlink',
                    'utm_medium'   => 'settings-header',
                    'utm_source'   => 'FreeVersion',
                ),
                'https://wpbean.com/downloads/wpb-woocommerce-related-products-slider-pro/'
            );

            echo '<div id="wpb-wrps-settings" class="wpb-plugin-settings-wrap wrap">';
?>

            <div class="wpb-wrps-page-header">
                <div class="wpb-wrps-header-brand">
                    <span class="wpb-wrps-header-icon">
                        <span class="dashicons dashicons-slides"></span>
                    </span>
                    <div class="wpb-wrps-header-info">
                        <h1><?php esc_html_e('WPB Related Products Slider', 'wpb-wrps'); ?></h1>
                        <div class="wpb-wrps-header-meta">
                            <span class="wpb-wrps-plan-badge"><?php esc_html_e('Free Plan', 'wpb-wrps'); ?></span>
                            <?php if ($version) : ?>
                                <span class="wpb-wrps-version"><?php echo esc_html('v' . $version); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="wpb-wrps-header-actions">
                    <a href="<?php echo esc_url($pro_url); ?>" target="_blank" rel="noopener noreferrer" class="wpb-wrps-header-upgrade-btn">
                        <span class="dashicons dashicons-star-filled"></span>
                        <?php esc_html_e('Upgrade to Pro', 'wpb-wrps'); ?>
                    </a>
                </div>
            </div>
            <hr class="wp-header-end">

            <div class="wpb-wrps-layout-row">
                <div class="wpb-wrps-col-main">
                    <?php
                    settings_errors();
                    $this->settings_api->show_navigation();
                    $this->settings_api->show_forms();
                    ?>
                </div>
                <aside class="wpb-wrps-col-sidebar">
                    <?php do_action('wpb_wrps_after_settings_page'); ?>
                </aside>
            </div>

<?php
            echo '</div>';
        }

        function pro_sidebar()
        {
            $pro_url = add_query_arg(
                array(
                    'utm_content'  => 'Related+Products+Slider+Pro',
                    'utm_campaign' => 'adminlink',
                    'utm_medium'   => 'settings-sidebar',
                    'utm_source'   => 'FreeVersion',
                ),
                'https://wpbean.com/downloads/wpb-woocommerce-related-products-slider-pro/'
            );
?>
            <div class="wpb-wrps-pro-sidebar-card">
                <div class="wpb-wrps-pro-card-header">
                    <span class="wpb-wrps-pro-card-crown dashicons dashicons-star-filled"></span>
                    <h3><?php esc_html_e('Upgrade to Pro', 'wpb-wrps'); ?></h3>
                    <p><?php esc_html_e('Unlock powerful features for your store', 'wpb-wrps'); ?></p>
                </div>
                <div class="wpb-wrps-pro-card-body">
                    <ul class="wpb-wrps-pro-features-list">
                        <li><?php esc_html_e('Select custom related products for each product', 'wpb-wrps'); ?></li>
                        <li><?php esc_html_e('Configure related product order and sorting options', 'wpb-wrps'); ?></li>
                        <li><?php esc_html_e('Display related products based on product tags', 'wpb-wrps'); ?></li>
                        <li><?php esc_html_e('Support WooCommerce upsells and cross-sells slider', 'wpb-wrps'); ?></li>
                        <li><?php esc_html_e('Automatically hide out-of-stock products', 'wpb-wrps'); ?></li>
                        <li><?php esc_html_e('Display WC Marketplace vendor products in sliders', 'wpb-wrps'); ?></li>
                        <li><?php esc_html_e('Control pagination, navigation, autoplay, and speed settings', 'wpb-wrps'); ?></li>
                        <li><?php esc_html_e('Set responsive slider columns for mobile devices', 'wpb-wrps'); ?></li>
                        <li><?php esc_html_e('Customize colors for navigation, pagination, text, buttons, and prices', 'wpb-wrps'); ?></li>
                        <li><?php esc_html_e('Add the related products slider to product tabs', 'wpb-wrps'); ?></li>
                    </ul>
                    <a href="<?php echo esc_url($pro_url); ?>" target="_blank" rel="noopener noreferrer" class="wpb-wrps-pro-card-cta">
                        <span class="dashicons dashicons-cart"></span>
                        <?php esc_html_e('Upgrade to Pro', 'wpb-wrps'); ?>
                    </a>
                </div>
            </div>
<?php
        }

        /**
         * Get all the pages
         *
         * @return array page names with key value pairs
         */
        function get_pages()
        {
            $pages = get_pages();
            $pages_options = array();
            if ($pages) {
                foreach ($pages as $page) {
                    $pages_options[$page->ID] = $page->post_title;
                }
            }
            return $pages_options;
        }
    }
endif;

$settings = new wpb_wrps_settings();
