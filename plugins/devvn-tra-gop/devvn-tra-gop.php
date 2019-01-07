<?php
/*
* Plugin Name: Plugin Trả Góp
* Version: 1.0.0
* Description: Plugin tính toán tiền trả góp theo các công ty tài chính: HOME CREDIT, HD SAIGON và FE CREDIT
* Author: Hà Việt
* Author URI: www.haviet.net
* Plugin URI: www.haviet.net
* Text Domain: devvn-tra-gop
* Domain Path: /languages
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if (
    in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )
) {

    if (!class_exists('DevVN_Tra_Gop')) {
        class DevVN_Tra_Gop
        {
            protected static $instance;
            public $_version = '1.0.0';

            public $_optionName = 'tragop_options';
            public $_optionGroup = 'tragop-options-group';
            public $_defaultOptions = array(
                'tra_truoc' => array(30, 40, 50, 60, 70),
                'tra_truoc_default' => 50,
                'month' => array(6, 9, 12),
                'month_default' => 6,
                'giay_to' => array(
                    'CMND + Hộ Khẩu',
                    'CMND + Bằng lái xe / hộ khẩu',
                    'CMND + Hộ Khẩu + Hóa đơn điện',
                    'CMND + Bằng lái /Hộ khẩu + Hóa đơn điện'
                ),
                'rates' => array(
                    'fe_credit' => array(
                        'name' => 'FE Credit',
                        'rate' => '2.67',
                        'insurrance' => 5,
                        'thu_ho' => '11000',
                        'giay_to' => 'CMND + Bằng lái xe',
                        'active' => 1
                    ),
                    'home_credit' => array(
                        'name' => 'Home Credit',
                        'rate' => '1.68',
                        'insurrance' => 5,
                        'thu_ho' => '11000',
                        'giay_to' => 'CMND + Bằng lái xe',
                        'active' => 1
                    ),
                    'hd_saigon' => array(
                        'name' => 'HD Sài Gòn',
                        'rate' => '3.05',
                        'insurrance' => 5,
                        'thu_ho' => '11000',
                        'giay_to' => 'CMND + Bằng lái xe',
                        'active' => 1
                    )
                ),
                'insurrance_enable' => 0,
                'thuho_enable' => 0,
                'page_tragop'   =>  '',
                'tragop_enable' =>  1,
                'button_text1'  =>  'Mua trả góp',
                'button_text2'  =>  'Nhận máy ngay, không xét duyệt hồ sơ',
            );

            public $_tragop_base = '';

            public static function init()
            {
                is_null(self::$instance) AND self::$instance = new self;
                return self::$instance;
            }

            public function __construct()
            {
                $this->define_constants();

                global $tragop_settings;
                $tragop_settings = $this->get_options();

                add_shortcode('devvn_tragop', array($this, 'devvn_tragop_func'));

                $tragop_enable = $tragop_settings['tragop_enable'];
                $page_tragop = $tragop_settings['page_tragop'];

                if($tragop_enable && $page_tragop) {
                    $this->_tragop_base = get_post_field( 'post_name', $page_tragop );
                    add_action('init', array($this, 'add_rewrite_rules'));
                    add_filter('query_vars', array($this, 'query_vars'));
                    add_filter('template_redirect', array($this, 'tragop_template_redirect'));
                    add_action('woocommerce_single_product_summary', array($this, 'add_button_tragop'), 36);
                }

                add_action('wp_enqueue_scripts', array($this, 'load_plugins_scripts'));

                add_action('init', array($this, 'devvn_tra_gop'), 0);
                add_filter('admin_footer_text', array($this, 'admin_footer_text'), 1);

                add_action('admin_menu', array($this, 'admin_menu'));
                add_action('admin_init', array($this, 'register_mysettings'));
                add_filter('plugin_action_links_' . DEVVN_TRAGOP_BASENAME, array($this, 'add_action_links'), 10, 2);

                add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

                add_action('wp_ajax_calc_installment', array($this, 'calc_installment_func'));
                add_action('wp_ajax_nopriv_calc_installment', array($this, 'calc_installment_func'));

                add_action('wp_ajax_send_infor_tragop', array($this, 'send_infor_tragop_func'));
                add_action('wp_ajax_nopriv_send_infor_tragop', array($this, 'send_infor_tragop_func'));

                add_action('add_meta_boxes', array($this, 'tragop_meta_box'));
                add_action('save_post', array($this, 'tragop_save_meta_box_data'));

                add_action('after_dangky_mua_tragop', array($this, 'send_email_after_creat_tragop'), 10, 3);

                add_shortcode('button_tragop', array($this, 'button_tragop_func'));

            }

            public function define_constants()
            {
                if (!defined('DEVVN_TRAGOP_VERSION_NUM'))
                    define('DEVVN_TRAGOP_VERSION_NUM', $this->_version);
                if (!defined('DEVVN_TRAGOP_URL'))
                    define('DEVVN_TRAGOP_URL', plugin_dir_url(__FILE__));
                if (!defined('DEVVN_TRAGOP_BASENAME'))
                    define('DEVVN_TRAGOP_BASENAME', plugin_basename(__FILE__));
                if (!defined('DEVVN_TRAGOP_PLUGIN_DIR'))
                    define('DEVVN_TRAGOP_PLUGIN_DIR', plugin_dir_path(__FILE__));
            }

            function get_options()
            {
                return wp_parse_args(get_option($this->_optionName), $this->_defaultOptions);
            }

            function devvn_tragop_func()
            {
                ob_start();
                include 'templates/main-tragop.php';
                return ob_get_clean();
            }

            function add_rewrite_rules($flush)
            {
                add_rewrite_rule(
                    $this->_tragop_base . '/([0-9]{1,})-(.*)?$',
                    'index.php?pagename=' . $this->_tragop_base . '&tragop_prodid=$matches[1]',
                    'top'
                );
                if($flush){
                    flush_rewrite_rules();
                }
            }

            function query_vars($public_query_vars)
            {
                $public_query_vars[] = 'tragop_prodid';
                return $public_query_vars;
            }


            function tragop_template_redirect()
            {
                $prodid = get_query_var('tragop_prodid') ? intval(get_query_var('tragop_prodid')) : 0;
                if (is_page($this->_tragop_base) && !$prodid) {
                    wp_redirect(home_url());
                    die;
                }
            }

            function load_plugins_scripts()
            {
                wp_enqueue_style('jquery-ui-css', plugins_url('assets/css/jquery-ui.css', __FILE__), array(), $this->_version, 'all');
                wp_enqueue_style('devvn-tragop-style', plugins_url('assets/tragop-style.css', __FILE__), array(), $this->_version, 'all');
                wp_register_script('jquery.validate', plugins_url('assets/jquery.validate.min.js', __FILE__), array('jquery'), $this->_version, true);
                wp_register_script('devvn-tragop-script', plugins_url('assets/tragop-jquery.js', __FILE__), array('jquery', 'jquery.validate', 'jquery-ui-datepicker'), $this->_version, true);
                $array = array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'siteurl' => home_url(),
                );
                wp_localize_script('devvn-tragop-script', 'devvn_tragop_array', $array);
            }

            // Register Custom Post Type
            function devvn_tra_gop()
            {

                $labels = array(
                    'name' => _x('Mua trả góp', 'Post Type General Name', 'devvn-tra-gop'),
                    'singular_name' => _x('Mua trả góp', 'Post Type Singular Name', 'devvn-tra-gop'),
                    'menu_name' => __('Mua trả góp', 'devvn-tra-gop'),
                    'name_admin_bar' => __('Mua trả góp', 'devvn-tra-gop'),
                    'archives' => __('Item Archives', 'devvn-tra-gop'),
                    'attributes' => __('Item Attributes', 'devvn-tra-gop'),
                    'parent_item_colon' => __('Parent Item:', 'devvn-tra-gop'),
                    'all_items' => __('Toàn bộ đơn hàng', 'devvn-tra-gop'),
                    'add_new_item' => __('Add New Item', 'devvn-tra-gop'),
                    'add_new' => __('Thêm đơn hàng', 'devvn-tra-gop'),
                    'new_item' => __('New Item', 'devvn-tra-gop'),
                    'edit_item' => __('Sửa đơn hàng', 'devvn-tra-gop'),
                    'update_item' => __('Cập nhật đơn hàng', 'devvn-tra-gop'),
                    'view_item' => __('View Item', 'devvn-tra-gop'),
                    'view_items' => __('View Items', 'devvn-tra-gop'),
                    'search_items' => __('Search Item', 'devvn-tra-gop'),
                    'not_found' => __('Not found', 'devvn-tra-gop'),
                    'not_found_in_trash' => __('Not found in Trash', 'devvn-tra-gop'),
                    'featured_image' => __('Featured Image', 'devvn-tra-gop'),
                    'set_featured_image' => __('Set featured image', 'devvn-tra-gop'),
                    'remove_featured_image' => __('Remove featured image', 'devvn-tra-gop'),
                    'use_featured_image' => __('Use as featured image', 'devvn-tra-gop'),
                    'insert_into_item' => __('Insert into item', 'devvn-tra-gop'),
                    'uploaded_to_this_item' => __('Uploaded to this item', 'devvn-tra-gop'),
                    'items_list' => __('Items list', 'devvn-tra-gop'),
                    'items_list_navigation' => __('Items list navigation', 'devvn-tra-gop'),
                    'filter_items_list' => __('Filter items list', 'devvn-tra-gop'),
                );
                $args = array(
                    'label' => __('Mua trả góp', 'devvn-tra-gop'),
                    'description' => __('Mua trả góp qua các công ty tài chính hoặc thanh toán online qua visa hoặc mastercard', 'devvn-tra-gop'),
                    'labels' => $labels,
                    'supports' => array('title', 'editor'),
                    'hierarchical' => false,
                    'public' => false,
                    'show_ui' => true,
                    'show_in_menu' => true,
                    'menu_position' => 58,
                    'menu_icon' => 'dashicons-store',
                    'show_in_admin_bar' => false,
                    'show_in_nav_menus' => false,
                    'can_export' => true,
                    'has_archive' => false,
                    'exclude_from_search' => true,
                    'publicly_queryable' => false,
                    'rewrite' => false,
                    'capability_type' => 'page',
                    'show_in_rest' => false,
                );
                register_post_type('devvn_tra_gop', $args);

            }

            // function admin_footer_text($text)
            // {
            //     $current_screen = get_current_screen();
            //     if (isset($current_screen->post_type) && $current_screen->post_type == 'devvn_tra_gop') {
            //         $text = sprintf('Phát triển bởi %sLê Văn Toản%s.', '<a href="https://levantoan.com" target="_blank"><strong>', '</strong></a>');
            //     }
            //     return $text;
            // }

            function admin_menu()
            {
                add_submenu_page(
                    'edit.php?post_type=devvn_tra_gop',
                    __('Cài đặt mua trả góp', 'devvn-tra-gop'),
                    __('Cài đặt', 'devvn-tra-gop'),
                    'manage_options',
                    'tragop_settings',
                    array($this, 'form_setting')
                );
            }

            function form_setting()
            {
                include DEVVN_TRAGOP_PLUGIN_DIR . 'inc/settings.php';
            }

            function register_mysettings()
            {
                register_setting($this->_optionGroup, $this->_optionName);
            }

            public function add_action_links($links, $file)
            {
                if (strpos($file, 'devvn-tra-gop.php') !== false) {
                    $settings_link = '<a href="' . admin_url('edit.php?post_type=devvn_tra_gop&page=tragop_settings') . '" title="Cài đặt">' . __('Cài đặt', 'devvn-tra-gop') . '</a>';
                    array_unshift($links, $settings_link);
                }
                return $links;
            }

            public function admin_enqueue_scripts()
            {
                $current_screen = get_current_screen();
                if (isset($current_screen->post_type) && ($current_screen->post_type == 'devvn_tra_gop' || $current_screen->post_type == 'product')) {
                    wp_enqueue_style('tragop-admin-styles', plugins_url('/assets/admin/admin-style.css', __FILE__), array(), $this->_version, 'all');
                    wp_enqueue_script('tragop-admin-js', plugins_url('/assets/admin/admin-jquery.js', __FILE__), array('jquery', 'wp-util'), $this->_version, true);
                }
            }

            function cacl_fee($price_prod, $price_prepaid, $rate, $month, $insurrance)
            {
                $output = array(
                    'interest_rate' => '',
                    'price_per_month' => '',
                    'insurrance' => '',
                    'difference' => '',
                    'price_final' => '',
                );
                if ($price_prod && $price_prepaid && $month) {
                    $interest_rate_fe = (($price_prod - $price_prepaid) * $rate) / 100;
                    $price_per_month_fe = (($price_prod - $price_prepaid) / $month) + $interest_rate_fe;
                    $insurrance = (($price_per_month_fe * $insurrance) / 100);
                    $difference_fe = ($interest_rate_fe * $month);
                    $price_final_fe = ($price_per_month_fe * $month) + $price_prepaid;
                    $output = array(
                        'interest_rate' => $interest_rate_fe,
                        'price_per_month' => $price_per_month_fe,
                        'insurrance' => $insurrance,
                        'difference' => $difference_fe,
                        'price_final' => $price_final_fe,
                    );
                }
                return $output;
            }

            function calc_installment_func()
            {
                if (!wp_verify_nonce($_REQUEST['nonce'], "tragop_action_nonce")) {
                    exit(__('Có gì đó không đúng. Vui lòng thử lại sau!', 'devvn-tra-gop'));
                }

                global $tragop_settings;

                parse_str($_POST['data'], $params);

                $insurrance_enable = $tragop_settings['insurrance_enable'];
                $thuho_enable = $tragop_settings['thuho_enable'];
                $rates = $tragop_settings['rates'];

                $prepaid = isset($params['prepaid']) ? intval($params['prepaid']) : '';
                $month = isset($params['month']) ? intval($params['month']) : '';
                $insurrance_fe = isset($params['insurrance_fe']) ? intval($params['insurrance_fe']) : 0;
                $insurrance_home = isset($params['insurrance_home']) ? intval($params['insurrance_home']) : 0;
                $insurrance_hd = isset($params['insurrance_hd']) ? intval($params['insurrance_hd']) : 0;
                $prodID = isset($params['prodID']) ? intval($params['prodID']) : '';

                $product = wc_get_product($prodID);

                if (!$prepaid || !$month || !$product || is_wp_error($product) || !$rates) exit(__('Có gì đó không đúng. Vui lòng thử lại sau!', 'devvn-tra-gop'));

                $price_prod = $product->get_price();
                $price_prepaid = ($price_prod * $prepaid) / 100;

                $count_rates_enable = 0;

                $parentID = $product->get_parent_id();
                if (!$parentID) $parentID = $product->get_id();


                $devvn_tragop_type = get_post_meta($parentID, 'devvn_tragop_type', true);
                if ($devvn_tragop_type == 'no') exit();
                if ($devvn_tragop_type == 'yes') {
                    $tragop_prod = get_post_meta($parentID, 'tragop_prod_data', true);
                    $rates_prod = isset($tragop_prod['rates']) ? $tragop_prod['rates'] : array();

                    $thuho_enable = isset($tragop_prod['thuho_enable']) ? $tragop_prod['thuho_enable'] : 0;
                    $insurrance_enable = isset($tragop_prod['insurrance_enable']) ? $tragop_prod['insurrance_enable'] : 0;

                    $rates = wp_parse_args($rates_prod, $rates);
                }

                ?>
                <table class="devvn_table">
                    <tbody>
                    <tr>
                        <th><?php _e('Công ty', 'devvn-tra-gop'); ?></th>
                        <?php
                        foreach ($rates as $k => $cty):
                            $active = isset($cty['active']) ? $cty['active'] : 0;
                            if ($active):
                                $count_rates_enable++;
                                ?>
                                <td class="text-center"><img
                                            src="<?php echo DEVVN_TRAGOP_URL; ?>assets/images/<?php echo $k; ?>.png">
                                </td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th><?php _e('Giá máy gốc', 'devvn-tra-gop'); ?></th>
                        <td colspan="<?php echo $count_rates_enable; ?>"
                            class="text-center"><?php echo wc_price($price_prod); ?></td>
                    </tr>
                    <tr>
                        <th><?php _e('Tiền trả trước', 'devvn-tra-gop'); ?></th>
                        <?php foreach ($rates as $k => $v):
                            $active = isset($v['active']) ? $v['active'] : 0;
                            if ($active):
                                ?>
                                <td>
                                    <div class="deposit_money_<?php echo $k; ?>"><span
                                                class="color_df"><?php echo wc_price($price_prepaid); ?></span>
                                        (<?php echo $prepaid; ?>%)
                                    </div>
                                </td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th><?php _e('Số tiền trả mỗi tháng', 'devvn-tra-gop'); ?></th>
                        <?php foreach ($rates as $k => $v):
                            $active = isset($v['active']) ? $v['active'] : 0;
                            $rate = isset($v['rate']) ? $v['rate'] : 0;
                            $insurrance = isset($v['insurrance']) ? $v['insurrance'] : 0;
                            if ($active):
                                $fee = $this->cacl_fee($price_prod, $price_prepaid, $rate, $month, $insurrance);
                                ?>
                                <td>
                                    <span class="price_per_month_val_<?php echo $k; ?>"><?php echo wc_price($fee['price_per_month']); ?></span>
                                </td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                    <?php if ($thuho_enable): ?>
                        <tr>
                            <th><?php _e('Phí đóng tiền hàng tháng', 'devvn-tra-gop'); ?></th>
                            <?php foreach ($rates as $k => $v):
                                $active = isset($v['active']) ? $v['active'] : 0;
                                $thu_ho = isset($v['thu_ho']) ? $v['thu_ho'] : 0;
                                if ($active):
                                    ?>
                                    <td><?php echo wc_price($thu_ho); ?><?php _e('/tháng', 'devvn-tra-gop'); ?></td>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tr>
                    <?php endif; ?>
                    <?php if ($insurrance_enable): ?>
                        <tr>
                            <th><?php _e('Mua bảo hiểm gói vay', 'devvn-tra-gop'); ?></th>
                            <?php foreach ($rates as $k => $v):
                                $active = isset($v['active']) ? $v['active'] : 0;
                                $insurrance = isset($v['insurrance']) ? $v['insurrance'] : 0;
                                $rate = isset($v['rate']) ? $v['rate'] : 0;
                                $fee = $this->cacl_fee($price_prod, $price_prepaid, $rate, $month, $insurrance);
                                if ($active):
                                    switch ($k):
                                        case 'fe_credit':
                                            ?>
                                            <td><label><input type="checkbox" name="insurrance_fe"
                                                              value="1" <?php checked(1, $insurrance_fe); ?>> <span
                                                            id="insurrance_fe"><?php echo wc_price($fee['insurrance']); ?></span><?php _e('/tháng', 'devvn-tra-gop'); ?>
                                                </label></td>
                                            <?php
                                            break;
                                        case 'home_credit':
                                            ?>
                                            <td><label><input type="checkbox" name="insurrance_home"
                                                              value="1" <?php checked(1, $insurrance_home); ?>> <span
                                                            id="insurrance_fe"><?php echo wc_price($fee['insurrance']); ?></span><?php _e('/tháng', 'devvn-tra-gop'); ?>
                                                </label></td>
                                            <?php
                                            break;
                                        case 'hd_saigon':
                                            ?>
                                            <td><label><input type="checkbox" name="insurrance_hd"
                                                              value="1" <?php checked(1, $insurrance_hd); ?>> <span
                                                            id="insurrance_hd"><?php echo wc_price($fee['insurrance']); ?></span><?php _e('/tháng', 'devvn-tra-gop'); ?>
                                                </label></td>
                                            <?php
                                            break;
                                    endswitch;
                                endif;
                                ?>
                            <?php endforeach; ?>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <th><?php _e('Giấy tờ cần có', 'devvn-tra-gop'); ?></th>
                        <?php foreach ($rates as $k => $v):
                            $active = isset($v['active']) ? $v['active'] : 0;
                            $giay_to = isset($v['giay_to']) ? $v['giay_to'] : '';
                            if ($active):
                                ?>
                                <td><?php echo $giay_to; ?></td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th><?php _e('Tổng tiền phải trả', 'devvn-tra-gop'); ?></th>
                        <?php foreach ($rates as $k => $v):
                            $active = isset($v['active']) ? $v['active'] : 0;
                            $rate = isset($v['rate']) ? $v['rate'] : 0;
                            $insurrance = isset($v['insurrance']) ? $v['insurrance'] : 0;
                            if ($active):
                                $fee = $this->cacl_fee($price_prod, $price_prepaid, $rate, $month, $insurrance);
                                ?>
                                <td>
                                    <span class="total_price_val_<?php echo $k; ?>"><?php echo wc_price($fee['price_final']); ?></span>
                                </td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th><?php _e('Chênh lệch với mua trả thẳng', 'devvn-tra-gop'); ?></th>
                        <?php foreach ($rates as $k => $v):
                            $active = isset($v['active']) ? $v['active'] : 0;
                            $rate = isset($v['rate']) ? $v['rate'] : 0;
                            $insurrance = isset($v['insurrance']) ? $v['insurrance'] : 0;
                            if ($active):
                                $fee = $this->cacl_fee($price_prod, $price_prepaid, $rate, $month, $insurrance);
                                ?>
                                <td><?php echo wc_price($fee['difference']); ?></td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th></th>
                        <?php foreach ($rates as $k => $v):
                            $active = isset($v['active']) ? $v['active'] : 0;
                            $name = isset($v['name']) ? $v['name'] : '';
                            if ($active):
                                ?>
                                <td class="text-center">
                                    <button type="button" class="btn_chon cty_submit_form" data-id="<?php echo $k; ?>" data-name="<?php echo $name; ?>"><?php _e('Đặt mua', 'devvn-tra-gop'); ?></button>
                                </td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                    </tbody>
                </table>
                <?php
                die();
            }

            function send_infor_tragop_func()
            {
                if (!wp_verify_nonce($_REQUEST['nonce'], "tragop_action_nonce")) {
                    wp_send_json_error(__('Có gì đó không đúng. Vui lòng thử lại sau!', 'devvn-tra-gop'));
                }

                global $tragop_settings;

                parse_str($_POST['data'], $params);

                $rates = $tragop_settings['rates'];

                $type = isset($params['type']) ? sanitize_text_field(wc_clean($params['type'])) : 'cty';

                $prepaid = isset($params['prepaid']) ? intval($params['prepaid']) : '';
                $month = isset($params['month']) ? intval($params['month']) : '';
                $giayto = isset($params['giayto']) ? sanitize_text_field(wc_clean($params['giayto'])) : '';

                $insurrance_fe = isset($params['insurrance_fe']) ? intval($params['insurrance_fe']) : 0;
                $insurrance_home = isset($params['insurrance_home']) ? intval($params['insurrance_home']) : 0;
                $insurrance_hd = isset($params['insurrance_hd']) ? intval($params['insurrance_hd']) : 0;

                $prodID = isset($params['prodID']) ? intval($params['prodID']) : '';

                $cty_interest = isset($params['cty_interest']) ? sanitize_text_field(wc_clean($params['cty_interest'])) : '';
                $cty_interest_name = isset($params['cty_interest_name']) ? sanitize_text_field(wc_clean($params['cty_interest_name'])) : '';

                $tragop_fullname = isset($params['tragop_fullname']) ? sanitize_text_field(wc_clean($params['tragop_fullname'])) : '';
                $tragop_phone = isset($params['tragop_phone']) ? sanitize_text_field(wc_clean($params['tragop_phone'])) : '';
                $tragop_email = (isset($params['tragop_email']) && is_email($params['tragop_email'])) ? sanitize_email(wc_clean($params['tragop_email'])) : '';

                $tragop_cmnd = (isset($params['tragop_cmnd'])) ? sanitize_text_field(wc_clean($params['tragop_cmnd'])) : '';
                $tragop_birthday = (isset($params['tragop_birthday'])) ? sanitize_text_field(wc_clean($params['tragop_birthday'])) : '';
                $tragop_address = (isset($params['tragop_address'])) ? sanitize_text_field(wc_clean($params['tragop_address'])) : '';
                $tragop_state = (isset($params['tragop_state'])) ? sanitize_text_field(wc_clean($params['tragop_state'])) : '';

                $product = wc_get_product($prodID);

                if (!$prepaid || !$month || !$product || is_wp_error($product) || !$rates || !$tragop_fullname || !$tragop_phone || !$cty_interest || !$cty_interest_name) wp_send_json_error(__('Có gì đó không đúng. Vui lòng thử lại sau!', 'devvn-tra-gop'));
                if (!$tragop_cmnd || !$tragop_birthday || !$tragop_address || !$tragop_state) wp_send_json_error(__('Có gì đó không đúng. Vui lòng thử lại sau!', 'devvn-tra-gop'));

                $price_prod = $product->get_price();
                $price_prepaid = ($price_prod * $prepaid) / 100;

                $rate = ($rates[$cty_interest]['rate']) ? $rates[$cty_interest]['rate'] : 0;
                $insurrance = ($rates[$cty_interest]['insurrance']) ? $rates[$cty_interest]['insurrance'] : 0;
                $thu_ho = ($rates[$cty_interest]['thu_ho']) ? $rates[$cty_interest]['thu_ho'] : 0;

                $fee = $this->cacl_fee($price_prod, $price_prepaid, $rate, $month, $insurrance);

                ob_start();
                ?>
                <strong>Thông tin sản phẩm</strong>
                <table style="width: 100%; border-style: solid; border-color: #ddd; border-collapse: collapse;"
                       border="1" cellspacing="1" cellpadding="1">
                    <tbody>
                    <tr>
                        <td width="230">Tên sản phẩm</td>
                        <td><a href="<?php echo $product->get_permalink() ?>"
                               title=""><?php echo $product->get_name(); ?></a></td>
                    </tr>
                    <tr>
                        <td width="230">Giá sản phẩm</td>
                        <td><?php echo wc_price($price_prod); ?></td>
                    </tr>
                    </tbody>
                </table>
                <strong>Thông tin khách hàng</strong>
                <table style="width: 100%; border-style: solid; border-color: #ddd; border-collapse: collapse;"
                       border="1" cellspacing="1" cellpadding="1">
                    <tbody>
                    <tr>
                        <td width="230">Họ và tên</td>
                        <td><?php echo $tragop_fullname; ?></td>
                    </tr>
                    <tr>
                        <td width="230">Số điện thoại</td>
                        <td><?php echo $tragop_phone; ?></td>
                    </tr>
                    <tr>
                        <td width="230">Email</td>
                        <td><?php echo $tragop_email; ?></td>
                    </tr>
                    <tr>
                        <td width="230">CMND</td>
                        <td><?php echo $tragop_cmnd; ?></td>
                    </tr>
                    <tr>
                        <td width="230">Ngày sinh</td>
                        <td><?php echo $tragop_birthday; ?></td>
                    </tr>
                    <tr>
                        <td width="230">Địa chỉ</td>
                        <td><?php echo $tragop_address; ?></td>
                    </tr>
                    <tr>
                        <td width="230">Tỉnh thành</td>
                        <td><?php echo $tragop_state; ?></td>
                    </tr>
                    </tbody>
                </table>
                <strong>Thông tin trả góp</strong>
                <table style="width: 100%; border-style: solid; border-color: #ddd; border-collapse: collapse;"
                       border="1" cellspacing="1" cellpadding="1">
                    <tbody>
                    <tr>
                        <td width="230">Hình thức</td>
                        <td><?php echo ($type == 'cty') ? __('Qua công ty tài chính', 'devvn-tra-gop') : __('Qua thẻ tín dụng', 'devvn-tra-gop'); ?></td>
                    </tr>
                    <?php if ($type == 'cty'): ?>
                        <tr>
                            <td width="230">Tên công ty</td>
                            <td><?php echo ($rates[$cty_interest]['name']) ? $rates[$cty_interest]['name'] : ''; ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td width="230">Trả trước (<?php echo $prepaid; ?>%)</td>
                        <td><?php echo wc_price($price_prepaid); ?></td>
                    </tr>
                    <tr>
                        <td width="230">Số tháng  trả góp</td>
                        <td><?php echo $month; ?> tháng</td>
                    </tr>
                    <tr>
                        <td width="230">Giấy tờ</td>
                        <td><?php echo $giayto; ?></td>
                    </tr>
                    <tr>
                        <td width="230">Lãi suất (%)</td>
                        <td><?php echo $rate; ?></td>
                    </tr>
                    <tr>
                        <td width="230">Số tiền trả mỗi tháng</td>
                        <td><?php echo wc_price($fee['price_per_month']); ?></td>
                    </tr>
                    <tr>
                        <td width="230">Phí đóng tiền hàng tháng</td>
                        <td><?php echo wc_price($thu_ho); ?></td>
                    </tr>
                    <tr>
                        <td width="230">Bảo hiểm khoản vay</td>
                        <?php if ($insurrance_fe && $cty_interest == 'fe_credit'): ?>
                            <td><?php echo wc_price($fee['insurrance']); ?></td>
                        <?php elseif ($insurrance_home && $cty_interest == 'home_credit'): ?>
                            <td><?php echo wc_price($fee['insurrance']); ?></td>
                        <?php elseif ($insurrance_hd && $cty_interest == 'hd_saigon'): ?>
                            <td><?php echo wc_price($fee['insurrance']); ?></td>
                        <?php else: ?>
                            <td>Không mua bảo hiểm khoản vay</td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <td width="230">Tổng cuối</td>
                        <td><?php echo wc_price($fee['price_final']); ?></td>
                    </tr>
                    <tr>
                        <td width="230">Chênh lệch</td>
                        <td><?php echo wc_price($fee['difference']); ?></td>
                    </tr>
                    </tbody>
                </table>
                <?php
                $content = ob_get_clean();
                $my_post = array(
                    'post_type' => 'devvn_tra_gop',
                    'post_title' => 'Mua trả góp mới',
                    'post_status' => 'pending',
                    'post_content' => $content
                );
                $postID = wp_insert_post($my_post);

                if ($postID) {
                    update_post_meta($postID, 'full_tragop_data', $params);
                    $my_post2 = array(
                        'ID' => $postID,
                        'post_title' => '#' . $postID . ' - ' . $tragop_fullname . ' - ' . $tragop_phone,
                    );
                    wp_update_post($my_post2);

                    do_action('after_dangky_mua_tragop', $content, $rates, $params);

                    $html_output = '<p class="mess_success">' . __('Đăng ký mua trả góp thành công! Chúng tôi sẽ liên hệ với bạn sớm', 'devvn-tra-gop') . '</p>';
                    $html_output .= $content;
                    $html_output .= '<p class="text-center"><a href="' . home_url() . '" title="" class="btn_continue_buy">' . __('Tiếp tục mua hàng', 'devvn-tra-gop') . '</p>';

                    wp_send_json_success($html_output);
                }

                wp_send_json_error(__('Có lỗi xảy ra. Vui lòng thử lại sau!', 'devvn-tra-gop'));
                die();
            }

            function tragop_meta_box()
            {
                add_meta_box(
                    'devvn_tragop_meta',
                    __('Cài đặt trả góp', 'devvn-tra-gop'),
                    array($this, 'tragop_meta_box_callback'),
                    'product',
                    'normal',
                    'default'
                );
            }

            function tragop_meta_box_callback($post)
            {
                require_once(DEVVN_TRAGOP_PLUGIN_DIR . 'inc/tragop-metabox.php');
            }

            function tragop_save_meta_box_data($post_id)
            {

                $this_post = get_post($post_id) ;
                if(!is_wp_error($this_post) && is_a( $this_post, 'WP_Post' ) && has_shortcode( $this_post->post_content, 'devvn_tragop') ){
                    $this->_tragop_base = get_post_field( 'post_name', $post_id );
                    $this->add_rewrite_rules(true);
                }

                if (!isset($_POST['tragop_meta_box_nonce'])) {
                    return;
                }
                if (!wp_verify_nonce($_POST['tragop_meta_box_nonce'], 'tragop_save_meta_box_data')) {
                    return;
                }
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                    return;
                }
                if (isset($_POST['post_type']) && 'product' == $_POST['post_type']) {
                    if (!current_user_can('edit_page', $post_id)) {
                        return;
                    }
                } else {
                    if (!current_user_can('edit_post', $post_id)) {
                        return;
                    }
                }
                if (!isset($_POST['tragop_options']) || !isset($_POST['devvn_tragop_type'])) {
                    return;
                }

                $tragop_options = $_POST['tragop_options'];
                $devvn_tragop_type = sanitize_text_field($_POST['devvn_tragop_type']);

                update_post_meta($post_id, 'devvn_tragop_type', $devvn_tragop_type);
                update_post_meta($post_id, 'tragop_prod_data', $tragop_options);
            }

            function send_email_after_creat_tragop($content, $rates, $params)
            {
                $email_admin = apply_filters('tragop_admin_email', sanitize_email(get_option('admin_email')));
                if ($email_admin) {

                    $tragop_fullname = isset($params['tragop_fullname']) ? sanitize_text_field(wc_clean($params['tragop_fullname'])) : '';
                    $tragop_phone = isset($params['tragop_phone']) ? sanitize_text_field(wc_clean($params['tragop_phone'])) : '';
                    $tragop_email = (isset($params['tragop_email']) && is_email($params['tragop_email'])) ? sanitize_email(wc_clean($params['tragop_email'])) : '';

                    $subject = 'Có khách đăng ký mua trả góp - ' . $tragop_fullname . ' - ' . $tragop_phone;
                    $body = $content;

                    $headers = array('Content-Type: text/html; charset=UTF-8');

                    wp_mail($email_admin, $subject, $body, $headers);
                }
            }

            function tragop_url($product){
                global $tragop_settings;

                $id = $product->get_id();
                $slug = $product->get_slug();
                $tragop_enable = $tragop_settings['tragop_enable'];

                if($tragop_enable && $this->_tragop_base && $id && $slug){
                    $url = home_url($this->_tragop_base . '/' . $id . '-'. $slug . '/');
                    return $url;
                }
                return false;
            }

            function button_tragop_func($atts){
                $atts = shortcode_atts( array(
                    'id' => '',
                ), $atts, 'button_tragop' );

                global $product, $tragop_settings;

                $tragop_enable = $tragop_settings['tragop_enable'];

                $button_text1 = $tragop_settings['button_text1'];
                $button_text2 = $tragop_settings['button_text2'];

                $id = $atts['id'];

                if(!$id && $product) $id = $product->get_id();

                $product2 = wc_get_product( $id );

                if(!$product2 || is_wp_error($product2)) return;

                $devvn_tragop_type = get_post_meta($product2->get_id(), 'devvn_tragop_type', true);
                if($devvn_tragop_type == 'no') $tragop_enable = false;

                if(!$tragop_enable) return;

                ob_start();
                ?>
                <a rel="nofollow" id="devvn_tragop_button_<?php echo $id;?>" class="devvn_tragop_button" href="<?php echo $this->tragop_url($product2);?>" data-base="<?php echo home_url($this->_tragop_base);?>" data-id="<?php echo $id;?>" data-slug="<?php echo $product2->get_slug();?>">
                    <?php if($button_text1):?><span><?php echo $button_text1;?></span><?php endif;?>
                    <?php if($button_text2):?><small><?php echo $button_text2;?></small><?php endif;?>
                </a>
                <script type="text/javascript">
                    document.getElementById("devvn_tragop_button_<?php echo $id;?>").onclick = function () {
                        var id = parseInt(this.dataset.id);
                        var base = this.dataset.base;
                        var slug = this.dataset.slug;
                        var href = this.getAttribute('href');
                        var product_id =  parseInt(document.querySelector('form.cart input[name="product_id"]').value) || 0;
                        var variation_id =  parseInt(document.querySelector('form.variations_form.cart input.variation_id').value) || 0;
                        if(product_id == id && !variation_id){
                            return false;
                        }else if(product_id == id && variation_id){
                            var new_url = base + '/' + variation_id + '-' + slug;
                            window.location.href = new_url;
                        }else {
                            window.location.href = href;
                        }
                        return false;
                    };
                </script>
                <?php
                return ob_get_clean();
            }

            static function add_button_tragop(){
                echo do_shortcode('[button_tragop]');
            }
        }

    }
    $devvn_tra_gop = new DevVN_Tra_Gop();
}
