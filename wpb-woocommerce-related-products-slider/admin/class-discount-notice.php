<?php

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Discount notice class.
 *
 * Shows a notice immediately after installation and respects user feedback:
 *  - "Claim Discount" → opens Pro purchase page, permanently dismissed
 *  - "Maybe Later"    → snoozed for 1 week
 *  - "No Thanks"      → permanently dismissed
 */
class WPB_WRPS_Discount_Notice
{

    const META_DISMISSED  = 'wpb_wrps_discount_dismissed';
    const META_LATER      = 'wpb_wrps_discount_later';
    const DISCOUNT_CODE   = 'NewCustomer';
    const NONCE_ACTION    = 'wpb_wrps_discount_notice_action';
    const PRO_URL         = 'https://wpbean.com/downloads/wpb-woocommerce-related-products-slider-pro/';

    public function __construct()
    {
        add_action('admin_notices', array($this, 'maybe_show_notice'));
        add_action('admin_init', array($this, 'handle_notice_action'));
    }

    /**
     * Decide whether to render the notice.
     */
    public function maybe_show_notice()
    {
        $user_id = get_current_user_id();

        if (get_user_meta($user_id, self::META_DISMISSED, true)) {
            return;
        }

        $later_time = get_user_meta($user_id, self::META_LATER, true);
        if ($later_time && (time() - (int) $later_time) < WEEK_IN_SECONDS) {
            return;
        }

        $this->render_notice();
    }

    /**
     * Output the notice HTML.
     */
    private function render_notice()
    {
        $nonce = wp_create_nonce(self::NONCE_ACTION);

        $pro_url     = add_query_arg(
            array(
                'utm_content'  => 'Discount+Notice',
                'utm_campaign' => 'adminnotice',
                'utm_medium'   => 'discount-notice',
                'utm_source'   => 'FreeVersion',
            ),
            self::PRO_URL
        );

        $claim_url   = esc_url(add_query_arg(array('wpb_wrps_discount_action' => 'claim',   '_wpnonce' => $nonce)));
        $later_url   = esc_url(add_query_arg(array('wpb_wrps_discount_action' => 'later',   '_wpnonce' => $nonce)));
        $dismiss_url = esc_url(add_query_arg(array('wpb_wrps_discount_action' => 'dismiss', '_wpnonce' => $nonce)));
?>
        <div class="wpb-wrps-discount-notice notice" style="border-left: 4px solid #27ae60; padding: 0; margin: 15px 0; border-radius: 3px; box-shadow: 0 1px 3px rgba(0,0,0,.06);">
            <div style="display: flex; align-items: center; padding: 14px 18px; gap: 14px;">
                <div style="font-size: 32px; line-height: 1; flex-shrink: 0; opacity: .9;">&#127991;</div>
                <div style="flex: 1; min-width: 0;">
                    <p style="margin: 0 0 4px; font-size: 13.5px; font-weight: 600; color: #1d2327;">
                        <?php esc_html_e('Exclusive discount — Upgrade to WPB Related Products Slider Pro!', 'wpb-wrps'); ?>
                    </p>
                    <p style="margin: 0 0 11px; font-size: 13px; color: #50575e; line-height: 1.55;">
                        <?php
                        printf(
                            /* translators: %s: discount code */
                            esc_html__('Get 10%% off the Pro version and unlock powerful features. Use code %s at checkout.', 'wpb-wrps'),
                            '<strong style="color: #27ae60; font-family: monospace; font-size: 13px; background: #f0faf4; padding: 1px 6px; border-radius: 3px; border: 1px solid #b2dfcc;">' . esc_html(self::DISCOUNT_CODE) . '</strong>'
                        );
                        ?>
                    </p>
                    <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                        <a href="<?php echo $claim_url; ?>"
                            data-pro-url="<?php echo esc_url($pro_url); ?>"
                            id="wpb-wrps-claim-discount"
                            style="display:inline-flex;align-items:center;gap:4px;background:#27ae60;color:#fff;text-decoration:none;padding:6px 13px;border-radius:3px;font-size:12.5px;font-weight:600;line-height:1.4;">
                            &#127873; <?php esc_html_e('Claim Discount', 'wpb-wrps'); ?>
                        </a>
                        <a href="<?php echo $later_url; ?>"
                            style="display:inline-flex;align-items:center;text-decoration:none;padding:6px 13px;font-size:12.5px;color:#50575e;background:#fff;border:1px solid #c3c4c7;border-radius:3px;line-height:1.4;">
                            <?php esc_html_e('Maybe Later', 'wpb-wrps'); ?>
                        </a>
                        <a href="<?php echo $dismiss_url; ?>"
                            style="display:inline-flex;align-items:center;text-decoration:none;padding:6px 10px;font-size:12.5px;color:#787c82;line-height:1.4;">
                            <?php esc_html_e('No Thanks', 'wpb-wrps'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <script>
        (function () {
            var btn = document.getElementById('wpb-wrps-claim-discount');
            if (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    window.open(btn.getAttribute('data-pro-url'), '_blank', 'noopener,noreferrer');
                    window.location.href = btn.getAttribute('href');
                });
            }
        })();
        </script>
<?php
    }

    /**
     * Process the action from query string and redirect to a clean URL.
     */
    public function handle_notice_action()
    {
        if (empty($_GET['wpb_wrps_discount_action'])) {
            return;
        }

        $nonce  = ! empty($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '';
        $action = sanitize_text_field(wp_unslash($_GET['wpb_wrps_discount_action']));

        if (! wp_verify_nonce($nonce, self::NONCE_ACTION)) {
            die(esc_html__('Nonce Error!!!', 'wpb-wrps'));
        }

        $user_id = get_current_user_id();

        switch ($action) {
            case 'claim':
            case 'dismiss':
                update_user_meta($user_id, self::META_DISMISSED, 'true');
                break;

            case 'later':
                update_user_meta($user_id, self::META_LATER, time());
                break;
        }

        wp_safe_redirect(remove_query_arg(array('wpb_wrps_discount_action', '_wpnonce')));
        exit;
    }
}
