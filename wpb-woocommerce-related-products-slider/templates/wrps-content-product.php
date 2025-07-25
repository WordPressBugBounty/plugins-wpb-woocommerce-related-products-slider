<?php
/**
 * The template for displaying product content within loops with WPB WooCommerce Related Products Slider style
 *
 * This template can be overridden by copying it to yourtheme/wpb-woocommerce-related-products-slider/wrps-content-product.php.
 *
 */

defined( 'ABSPATH' ) || exit;
global $post, $product;
$price_html = $product->get_price_html();
?>

<div <?php post_class( 'wpb-wrps-item' ); ?>>

	<?php 
		if ( $product->is_on_sale() ) {
        	echo apply_filters( 'woocommerce_sale_flash', '<span class="wpb_wrps_onsale">' . esc_html__( 'Sale!', 'wpb-wrps' ) . '</span>', $post, $product );
        }
	?>

	<figure>
		<a href="<?php the_permalink(); ?>" class="wpb_wrps_img_url"><?php echo woocommerce_get_product_thumbnail(); ?></a>
		<figcaption>
			
			<?php do_action( 'wpb_wrps_before_title', $product ) ?>
			<h3 class="wpb_wrps_title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>
			<?php do_action( 'wpb_wrps_after_title', $product ) ?>

			<?php echo ( $price_html ? '<div class="wpb_wrps_price">'.$price_html.'</div>' : '' ); ?>

			<?php do_action( 'wpb_wrps_after_price', $product ) ?>

			<div class="wpb_wrps_cart_btn">
				<?php woocommerce_template_loop_add_to_cart(); ?>
			</div>

			<?php do_action( 'wpb_wrps_after_cart', $product ) ?>

		</figcaption>
	</figure>
</div>