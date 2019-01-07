<?php
/**
 * Flatsome functions and definitions
 *
 * @package flatsome
 */

require get_template_directory() . '/inc/init.php';

add_filter( 'add_to_cart_text', 'woo_custom_cart_button_text' );                                // < 2.1
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text' );    // 2.1 +

function woo_custom_cart_button_text() {

        return __( 'MUA NGAY', 'woocommerce' );

}

add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
      unset($fields['billing']['billing_company']);
      unset($fields['billing']['billing_country']);
      unset($fields['billing']['billing_address_2']);
      unset($fields['billing']['billing_city']);
      unset($fields['billing']['billing_state']);
      unset($fields['billing']['billing_postcode']);
      unset($fields['billing']['billing_last_name']);
    return $fields;
}

// Redirect đường dẫn đăng nhập ra trang chủ
    add_filter( 'login_url', 'my_login_page', 10, 3 );
    function my_login_page( $login_url, $redirect, $force_reauth ) {
        return home_url( '/');
    }

    // Thay đổi đường dẫn khi admin quên mật khẩu
    function custom_lost_password_url($lostpassword_url) {
      return '/secure/login.php?action=lostpassword';
    }

    add_filter('lostpassword_url', 'custom_lost_password_url', 10, 2);

    // Thay đổi lại đường dẫn khi admin logout
    add_filter( 'logout_url', 'custom_logout_url');
    function custom_logout_url( $login_url)
    {
       $url = str_replace( 'wp-login', '/secure/login', $login_url );
       return $url;
    }


    function renderView( $_file_, $_params_ = [] ) {
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush( false );
        extract( $_params_, EXTR_OVERWRITE );
        try {
            include $_file_;
            $result = ob_get_clean();

            return $result;
        } catch ( \Exception $e ) {
            while ( ob_get_level() > $_obInitialLevel_ ) {
                if ( ! ob_end_clean() ) {
                    ob_clean();
                }
            }
            throw $e;
        } catch ( \Throwable $e ) {
            while ( ob_get_level() > $_obInitialLevel_ ) {
                if ( ! ob_end_clean() ) {
                    ob_clean();
                }
            }
            throw $e;
        }
    }
/**
 * Note: It's not recommended to add any custom code here. Please use a child theme so that your customizations aren't lost during updates.
 * Learn more here: http://codex.wordpress.org/Child_Themes
 */
