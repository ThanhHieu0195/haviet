<?php
/**
 * Description
 *
 * @package WPA_WCVS
 * @version 1.0.0
 * @author  WPAddon
 */
if ( ! defined('ABSPATH' ) ) {
	exit;
}

/**
 * Class description.
 *
 * @version 1.0.0
 */
class WPA_WCVS_Template_Hooks {
	/**
	 * Initialize.
	 *
	 * @return  void
	 */
	public static function init() {
		$show     = WPA_WCVS_Settings::data( 'show_product_list' );
		$position = WPA_WCVS_Settings::data( 'show_product_list_position' );

		if ( $show == 'yes' ) {
			if ( $position == 'before' ) {
				add_action( 'woocommerce_shop_loop_item_title', array( __CLASS__, 'add_swatch_on_product_list' ) );
			} else {
				add_action( 'woocommerce_after_shop_loop_item_title', array( __CLASS__, 'add_swatch_on_product_list' ) );
			}
		}
	}

	/**
	 * Show color swatch on product list.
	 *
	 * @return  string
	 */
	public static function add_swatch_on_product_list() {
		global $wpdb, $product, $jassc;

		$attributes = $product->get_attributes();
		$output = $tmp_arr = $style = $flip_thumb_attr = '';

		if ( $product->is_type( 'variable' ) ) {
			$used_colors = array();
			foreach ( $attributes as $attribute_name => $options ) {
				$attr = current(
					$wpdb->get_results(
						"SELECT attribute_type FROM {$wpdb->prefix}woocommerce_attribute_taxonomies " .
						"WHERE attribute_name = '" . substr( $attribute_name, 3 ) . "' LIMIT 0, 1;"
					)
				);

				if ( ! empty( $attr ) && $attr->attribute_type == 'color' && $options['options'] ) {
					$output .= '<div class="swatch__list is-flex" data-attribute="' . esc_attr( $attribute_name) . '">';
						// Get terms if this is a taxonomy - ordered. We need the names too.
						$terms = wc_get_product_terms( $product->get_id(), $attribute_name, array( 'fields' => 'all' ) );

						$variations = $product->get_available_variations();
						foreach ( $terms as $term ) {
							$color = get_woocommerce_term_meta( $term->term_id, 'wpa_color' );
							$image = get_woocommerce_term_meta( $term->term_id, 'wpa_image' );
							$image_custom = WPA_WCVS_Frontend::get_image( $term->term_id, $product->get_id() );

							if ( ! empty( $image_custom ) ) {
								$image = $image_custom;
							}

							if ( $image ) {
								$style = 'background-image: url( ' . esc_url( $image ) . ' )';
							} else {
								$style = 'background: ' . $color . ';';
							}

							foreach ( $variations as $key => $variation ) {
								if ( isset( $variation['attributes']['attribute_' . $attribute_name] ) ) {
									if ( $term->slug == $variation['attributes']['attribute_' . $attribute_name] ) {
										$variation_color = $variation['attributes']['attribute_' . $attribute_name];
										
										if ( ! in_array( $variation_color, $used_colors ) ) {
											$used_colors[]  = $variation_color;
											$meta_key       = "_product_image_gallery_{$term->taxonomy}-{$variation_color}";
											$image_gallery  = get_post_meta( $product->get_id(), $meta_key, true );
											$attachment_ids = array_filter( explode( ',', $image_gallery ) );

											$galleries = WPA_WCVS_Frontend::image_galleries( $product->get_id(), $variations );

											if ( ! empty( $variation['image_id'] ) ) {
												$thumbnail_id = $variation['image_id'];
											} elseif ( ! empty($attachment_ids ) ) {
												$thumbnail_id = ( int ) $attachment_ids[1];
											}

											if ( $thumbnail_id ) {
												$tmp_arr = wp_get_attachment_image_src( $thumbnail_id, 'shop_catalog' );
											}

											$flip_thumb = ( $jassc && isset( $jassc['flip'] ) ) ? $jassc['flip'] : ( function_exists( 'cs_get_option' ) && cs_get_option( 'wc-flip-thumb' ) );

											if ( $flip_thumb && $galleries && $image_gallery ) {
												if ( isset( $galleries[$meta_key][0]['catalog'] ) ) {
													$flip_thumb_attr = 'data-thumb-flip="' . esc_url( $galleries[$meta_key][0]['catalog'] ) . '"';
												} else {
													$flip_thumb_attr = '';
												}
											}

											$output .= '<span data-thumb="' . esc_url( $tmp_arr[0] ) . '" data-variation="' . esc_attr( $term->slug ) . '" ' . $flip_thumb_attr . ' class="swatch__list--item is-relative u-small">';
											$output .= '<span class="swatch__value" style="' . $style . '"></span>';
											$output .= '</span>';
										}
									}
								}
							}
						}
					$output .= '</div>';
				}
			}
		}

		echo apply_filters( 'add_swatch_on_product_list', $output );
	}
}

WPA_WCVS_Template_Hooks::init();