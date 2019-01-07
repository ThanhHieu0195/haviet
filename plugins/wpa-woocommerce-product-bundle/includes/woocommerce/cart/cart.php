<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.3.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>

<form action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

<?php do_action( 'woocommerce_before_cart_table' ); ?>

<table class="shop_table shop_table_responsive cart" cellspacing="0">
	<thead>
		<tr>
			<th class="product-remove">&nbsp;</th>
			<th class="product-thumbnail">&nbsp;</th>
			<th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
			<th class="product-price"><?php _e( 'Price', 'woocommerce' ); ?></th>
			<th class="product-quantity"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
			<th class="product-subtotal"><?php _e( 'Total', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
		$is_bundle = 0;
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   			= apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id 			= apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
			$product_bundle_class 	= isset( $cart_item['bundle-parent'] ) ? 'product-bundle-item' : '';
			$product_bundle_class 	.= isset( $cart_item['bundle-products'] ) ? 'has-product-bundle' : '';

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); echo ' '.$product_bundle_class ?>">
					<?php if ( ! empty( $cart_item['bundle-products'] ) ) :?>
						<tr class="product-bundle-title">
							<td colspan="6">
								<h4><?php esc_attr_e( 'Product bundles', 'wcpb' );?></h4>
							</td>
						</tr>
					<?php endif;?>
					<td class="product-remove">
						<?php
							echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
								'<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
								esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
								__( 'Remove this item', 'woocommerce' ),
								esc_attr( $product_id ),
								esc_attr( $_product->get_sku() )
							), $cart_item_key );
						?>
					</td>
					<?php
						if ( ! isset( $cart_item['bundle-parent'] ) ) :
					?>
						<td class="product-thumbnail">
							<?php
								$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

								if ( ! $product_permalink ) {
									echo $thumbnail;
								} else {
									printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
								}
							?>
						</td>

						<td class="product-name" data-title="<?php _e( 'Product', 'woocommerce' ); ?>">
							<?php
								if ( ! $product_permalink ) {
									echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;';
								} else {
									echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key );
								}

								// Meta data
								if ( version_compare( WC_VERSION, '3.3.0', '<' ) ) {
									$output .= WC()->cart->get_item_data( $cart_item );
								} else {
									$output .= wc_get_formatted_cart_item_data( $cart_item );
								}

								// Backorder notification
								if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
									echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>';
								}
							?>
						</td>
					<?php else:?>
						<td class="product-name" data-title="<?php _e( 'Product', 'woocommerce' ); ?>" colspan="2">
							<?php
								if ( ! $product_permalink ) {
									echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;';
								} else {
									echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key );
								}

								// Meta data
								if ( version_compare( WC_VERSION, '3.3.0', '<' ) ) {
									$output .= WC()->cart->get_item_data( $cart_item );
								} else {
									$output .= wc_get_formatted_cart_item_data( $cart_item );
								}

								// Backorder notification
								if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
									echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>';
								}
							?>
						</td>
					<?php endif;?>

					<td class="product-price" data-title="<?php _e( 'Price', 'woocommerce' ); ?>">
						<?php
							echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						?>
					</td>
					<?php
						if ( ! isset( $cart_item['bundle-parent'] ) ) :
					?>
						<td class="product-quantity" data-title="<?php _e( 'Quantity', 'woocommerce' ); ?>">
							<?php
								if ( $_product->is_sold_individually() ) {
									$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
								} else {
									$product_quantity = woocommerce_quantity_input( array(
										'input_name'  => "cart[{$cart_item_key}][qty]",
										'input_value' => $cart_item['quantity'],
										'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
										'min_value'   => '0'
									), $_product, false );
								}

								echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
							?>
						</td>
					<?php else:?>
						<td class="product-quantity" data-title="<?php _e( 'Quantity', 'woocommerce' ); ?>">
							<?php
								$product_quantity = $cart_item['quantity'];
								echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
							?>
						</td>
					<?php endif;?>
					<td class="product-subtotal" data-title="<?php _e( 'Total', 'woocommerce' ); ?>">
						<?php
							echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
						?>
					</td>
				</tr>

				<?php if ( ! empty( $cart_item['bundle-products'] ) ) :?>
				<tr class="bundle-products bundle-off-<?php echo $product_id;?>">
					<td colspan="6">
						<?php

							// Get Custom variable of bundle product
							$custom_variable 	= $cart_item['bundle-variable'];
							$bundles 			= get_post_meta( $product_id, 'wpa_wcpb', true );
							$bundles_added 		= explode( ',', $cart_item['bundle-products'] );
							if ( $bundles ) {
								echo '<ul>';
								foreach( $bundles as $key => $val ){
									if ( in_array( $val['product_id'], $bundles_added ) ) {
										$product_item = wc_get_product( intval( $val['product_id'] ) );
										echo '<li>';
											echo '<a href="'. $product_item->get_permalink() .'" title="'. $product_item->get_name() .'">'. $product_item->get_name() .'</a> ';
										if ( isset( $custom_variable[$val['product_id']] ) && count( $custom_variable[$val['product_id']] ) > 0 ) {
											// Custom variable before add produt bundle to cart
											echo '<span class="db" style="text-transform: capitalize;">';
												echo $custom_variable[$val['product_id']]['variable'];
											echo '</span>';
										}else {
											// Get variable default of product bundle
											if ( ! empty( $val['variable'] ) ) {
												$i = 0;
												foreach ( $val['variable'] as $key => $value ) { $i++;
													echo '<span class="db" style="text-transform: capitalize;">';
														if( $i == count( $val['variable'] ) ) {
															echo substr( $key, 13 ) . ': ' . $value;	
														}else {
															echo substr( $key, 13 ) . ': ' . $value .' + ';
														}
													echo '</span>';
												}
											}
										}
										echo '</li>';
									}
								}
								echo '</ul>';
							}
						?>	
					</td>
				</tr>
				<?php endif;?>
				<?php
			} 
		}

		do_action( 'woocommerce_cart_contents' );
		?>
		<tr>
			<td colspan="6" class="actions">

				<?php if ( wc_coupons_enabled() ) { ?>
					<div class="coupon">

						<label for="coupon_code"><?php _e( 'Coupon:', 'woocommerce' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <input type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply Coupon', 'woocommerce' ); ?>" />

						<?php do_action( 'woocommerce_cart_coupon' ); ?>
					</div>
				<?php } ?>

				<input type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update Cart', 'woocommerce' ); ?>" />

				<?php do_action( 'woocommerce_cart_actions' ); ?>

				<?php wp_nonce_field( 'woocommerce-cart' ); ?>
			</td>
		</tr>

		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	</tbody>
</table>

<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>

<div class="cart-collaterals">

	<?php do_action( 'woocommerce_cart_collaterals' ); ?>

</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
