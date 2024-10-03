<?php 

/**
 * WPB WooCommerce Related Products Slider
 * By WPbean
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * PHP implode with key and value ( Owl carousel data attr )
 */

if( !function_exists('wpb_wrps_carousel_data_attr_implode') ){
	function wpb_wrps_carousel_data_attr_implode( $array ){
		
		foreach ($array as $key => $value) {

			if( isset($value) && $value != '' ){
				$output[] = $key . '=' . '"' . esc_attr( $value ) . '"' ;
			}
		}

        return implode( ' ', $output );
	}
}


/**
 * Related Product Function
 */

if( !function_exists('wpb_wrps_related_products') ){
	function wpb_wrps_related_products(){

		global $post,$product;

		if ( empty( $product ) || ! $product->exists() ) {
			return;
		}

		$number_of_related_products = wpb_wrps_get_option( 'wpb_wrps_number_of_products', 'wpb_wrps_general', 100 );
		$related 					= wc_get_related_products( $product->get_id(), $number_of_related_products, $product->get_upsell_ids() );
		$wpb_wrps_theme 			= wpb_wrps_get_option( 'wpb_wrps_theme', 'wpb_wrps_general', 'wrps_theme_hover' );

		if ( $related ) {
		 
			$args = array(
				'post__not_in' 			=> 	array( get_the_ID() ),
				'post__in' 				=> 	$related,
				'posts_per_page'		=>	-1,
				'post_type' 			=> 	'product',
				'orderby' 				=> 	'rand',
				'order'   				=> 	'DESC',
				'ignore_sticky_posts' 	=> 	1,
				'no_found_rows'        	=> 	1,
			);

			$slider_attr = array(
	        	'data-items' 		=> wpb_wrps_get_option( 'wpb_wrps_number_of_columns', 'wpb_wrps_general', 3 ),
	        	'data-desktopsmall' => wpb_wrps_get_option( 'wpb_wrps_number_of_columns_desktop_small', 'wpb_wrps_general', 3 ),
	        	'data-tablet' 		=> wpb_wrps_get_option( 'wpb_wrps_number_of_columns_tablet', 'wpb_wrps_general', 2 ),
	        	'data-mobile' 		=> wpb_wrps_get_option( 'wpb_wrps_number_of_columns_mobile', 'wpb_wrps_general', 1 ),
	        	'data-direction' 	=> ( is_rtl() ? 'true' : '' ),
	        );

			$wp_query = new WP_Query( $args );

			?>
			<?php if ($wp_query->have_posts()):?>

				<div class="wrps_related_products_area">

					<h2 class="wrps_related_products_area_title"><span><?php echo( apply_filters( 'wpb_wrps_title', esc_html__( 'Related Products', 'wpb-wrps' ) ) ); ?></span></h2>

					<div class="wrps_related_products owl-carousel owl-theme <?php echo esc_attr( $wpb_wrps_theme ); ?>" <?php echo wpb_wrps_carousel_data_attr_implode( $slider_attr ); ?>>

						<?php while ($wp_query->have_posts()) : $wp_query->the_post();?>

							<?php 
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

						<?php endwhile; ?>

					</div><!-- wrps_related_products_area -->
						
				</div>
			<?php
			endif;
			wp_reset_postdata();

		}
	}
}





/**
 * Add plugin action links
 */

function wpb_wrps_plugin_actions_links( $links ) {
	if( is_admin() ){
		$links[] = '<a href="http://wpbean.com/support/" target="_blank">'. esc_html__('Support','wpb-wrps') .'</a>';
	}
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wpb_wrps_plugin_actions_links' );



/**
 * Getting ready the plugin settings 
 */

if( !function_exists('wpb_wrps_get_option') ){

	function wpb_wrps_get_option( $option, $section, $default = '' ) {
	 
	    $options = get_option( $section );
	 
	    if ( isset( $options[$option] ) ) {
	        return $options[$option];
	    }
	 
	    return $default;
	}

}



/**
 * WPBean Socail Info for Plugin settings page
 */

add_action( 'wpb_wrps_before_settings','wpb_wpbean_socail_info' );
if( !function_exists('wpb_wpbean_socail_info') ){
	function wpb_wpbean_socail_info(){
		?>
		<div class="wpb_wpbean_socials">
			<h3>For getting updates of our plugins, features update, WordPress new trend, New web technology etc. Follows Us.</h3>
			<a href="https://twitter.com/wpbean" title="Follow us on Twitter" class="wpb_twitter" target="_blank">Follow Us On Twitter</a>
			<a href="https://plus.google.com/u/0/+WpBean/posts" title="Follow us on Google+" class="wpb_googleplus" target="_blank">Follow Us On Google Plus</a>
			<a href="https://www.facebook.com/wpbean" title="Follow us on Facebook" class="wpb_facebook" target="_blank">Like Us On FaceBook</a>
			<a href="https://www.youtube.com/user/wpbean/videos" title="Follow us on Youtube" class="wpb_youtube" target="_blank">Subscribe Us on YouTube</a>
		</div>
		<?php
	}
}



/**
 * PRO version Info
 */

add_action( 'wpb_wrps_settings_content', 'wpb_wrps_pro_version_info' );
if( !function_exists( 'wpb_wrps_pro_version_info' ) ){
	function wpb_wrps_pro_version_info(){
		?>
		<h3 class="ik">PRO Version Feature</h3>
		<ul>
			<li>Select product specific custom relative products.</li>
			<li>Enable or disable slider pagination, navigation, auto play &amp; slider speed.</li>
			<li>Option for choosing the number of columns in slider for small screen devices.</li>
			<li>Style settings for changing colors of navigation, pagination, text, button and price.</li>
			<li>Life time free update.</li>
			<li>Free and quick support.</li>
			<li>Free installation service ( if needed ).</li>
		</ul>
		<a class="wpb_get_pro_btn" href="http://bit.ly/1IpFfXU" target="_blank">Get The Pro Version</a>
		<?php
	}
}