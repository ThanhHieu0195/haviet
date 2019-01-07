<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
$prodid = get_query_var('tragop_prodid') ? intval(get_query_var('tragop_prodid')) : 0;
$product = wc_get_product( $prodid );
if(!$product || is_wp_error($product)) return;
wp_enqueue_script('jquery.validate');
wp_enqueue_script('devvn-tragop-script');
global $tragop_settings;
$price_prod = $product->get_price();
$giay_to = isset($tragop_settings['giay_to']) ? $tragop_settings['giay_to'] : array();
$giay_to = apply_filters('devvn_tragop_giayto', $giay_to);

$parentID = $product->get_parent_id();
if(!$parentID) $parentID = $product->get_id();

$devvn_tragop_type = get_post_meta($parentID, 'devvn_tragop_type', true);
if($devvn_tragop_type == 'no') return;

?>
<div class="devvn_tragop_wrap">
    <div class="devvn_tragop_box">
        <div class="devvn_tragop_prod">
            <h1><?php printf(__('Mua trả góp: %s', 'devvn-tra-gop'), '<a href="'.$product->get_permalink().'" title="'.$product->get_name().'">'.$product->get_name().'</a>')?></h1>
            <span><?php printf(__('Giá bán: %s', 'devvn-tra-gop'), '<strong>' . wc_price($price_prod) . '</strong>')?></span>
        </div>
        <div class="devvn_tragop_main" id="devvn_tragop_main">
            <div class="devvn_installment_type">
                <p  class="devvn_tragop_title"><?php _e('Chọn phương thức trả góp phù hợp','devvm-tra-gop');?></p>
                <ul id="choose_type">
                    <li>
                        <label>
                            <input type="radio" name="type" value="cty" checked>
                            <strong class="type type-cty active">
                                <?php _e('CÔNG TY TÀI CHÍNH','devvm-tra-gop');?>
                                <small><?php _e('Duyệt online trong 4 giờ','devvm-tra-gop');?></small>
                            </strong>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input type="radio" name="type" value="cc">
                            <strong class="type type-cc">
                                <?php _e('QUA THẺ TÍN DỤNG','devvm-tra-gop');?>
                                <small><?php _e('Không cần xét duyệt','devvm-tra-gop');?></small>
                            </strong>
                        </label>
                    </li>
                </ul>
            </div>
            <div class="devvn_installment_bank">
                <form action="" id="devvn_tragop_company" method="post">
                    <div class="devvn_tragop_company_step1">
                        <div class="devvn_tragop_box">
                            <div class="devvn_tragop_col1">
                                <label for="prepaid" class="devvn_tragop_title"><?php _e('1. Số tiền trả trước','devvn-tra-gop');?></label>
                                <select name="prepaid" id="prepaid">
                                    <?php
                                    $tra_truoc_default = isset($tragop_settings['tra_truoc_default']) ? $tragop_settings['tra_truoc_default'] : 50;
                                    $tra_truoc = isset($tragop_settings['tra_truoc']) ? $tragop_settings['tra_truoc'] : array($tra_truoc_default);
                                    foreach($tra_truoc as $percent):
                                        $price = ($price_prod*$percent) / 100;
                                        ?>
                                        <option <?php selected($percent, $tra_truoc_default);?> value="<?php echo $percent;?>" data-price="<?php echo $price;?>"><?php echo wc_price($price);?> (<?php echo $percent?>%)</option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="devvn_tragop_col2">
                                <label for="month" class="devvn_tragop_title"><?php _e('2. Số tháng trả góp','devvn-tra-gop');?></label>
                                <select name="month" id="month">
                                    <?php
                                    $month_default = isset($tragop_settings['month_default']) ? $tragop_settings['month_default'] : 6;
                                    $month = isset($tragop_settings['month']) ? $tragop_settings['month'] : array($month_default);
                                    foreach($month as $mon):
                                    ?>
                                    <option <?php selected($mon, $month_default);?> value="<?php echo $mon;?>"><?php printf(__('%d tháng', 'devvn-tra-gop'), $mon);?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <?php if($giay_to && !empty($giay_to)):?>
                        <div class="devvn_tragop_box">
                            <div class="devvn_tragop_col">
                                <label class="devvn_tragop_title"><?php _e('3. Chọn giấy tờ bạn có','devvn-tra-gop');?></label>
                                <ul class="devvn_list_giayto">
                                    <?php $i = 1; foreach($giay_to as $item):?>
                                    <li>
                                        <label>
                                            <input type="radio" name="giayto" value="<?php echo esc_attr($item);?>" <?php echo ($i == 1) ? 'checked' : '';?>>
                                            <span><?php echo $item;?></span>
                                        </label>
                                    </li>
                                    <?php $i++; endforeach;?>
                                </ul>
                            </div>
                        </div>
                        <?php endif;?>
                        <div class="devvn_table_responsive devvn_table_chon_tragop">

                        </div>
                        <p class="notice"><?php _e('Thông tin chỉ mang tính tham khảo','devvn-tra-gop');?></p>
                    </div>
                    <div class="devvn_tragop_company_step2">
                        <a href="javascript:void(0)" title="" class="backstep1"><?php _e('Quay lại','devvn-tra-gop');?></a>
                        <div class="devvn_tragop_box">
                            <ul class="listorder">
                                <li class="tragop-price">
                                    <span class="text-left">Trả trước (<span id="prepaid_text"></span>%):</span>
                                    <span class="text-right"><span id="deposit_money_text"></span></span>
                                    <span class="text-left">Góp mỗi tháng (trong <span id="month_text"></span> tháng):</span>
                                    <span class="text-right"><span id="price_per_month_text"></span></span>
                                    <span class="text-left"><b>TỔNG TIỀN:</b></span>
                                    <span class="text-right"><strong style="font-size: 16px;"><span id="total_price_text"></span></strong></span>
                                    <div class="clear"></div>
                                </li>
                                <li class="tragop-info">
                                    <span>Giấy tờ cần có: <b id="giayto_text"></b></span>
                                    <span>Công ty tài chính: <b id="name_cty_text"></b></span>
                                </li>
                            </ul>
                        </div>
                        <div class="devvn_tragop_box">
                            <div class="devvn_tragop_col tragop_input_wrap">
                                <label class="devvn_tragop_title"><?php _e('Thông tin người đăng ký mua trả góp','devvn-tra-gop');?></label>
                            </div>
                            <div class="devvn_tragop_col tragop_input_wrap">
                                <input type="text" name="tragop_fullname" id="tragop_fullname" required placeholder="<?php _e('Nhập họ và tên','devvn-tra-gop')?>">
                            </div>
                            <div class="devvn_tragop_col tragop_input_wrap">
                                <input type="text" name="tragop_phone" id="tragop_phone" required placeholder="<?php _e('Nhập số điện thoại','devvn-tra-gop')?>">
                            </div>
                            <div class="devvn_tragop_col tragop_input_wrap">
                                <input type="email" name="tragop_email" id="tragop_email" placeholder="<?php _e('Email (Không bắt buộc)','devvn-tra-gop')?>">
                            </div>
                        </div>
                        <div class="devvn_tragop_box">
                            <div class="devvn_tragop_col tragop_input_wrap">
                                <input type="text" name="tragop_cmnd" id="tragop_cmnd" required placeholder="<?php _e('Nhập chứng minh nhân dân','devvn-tra-gop')?>">
                            </div>
                            <div class="devvn_tragop_col tragop_input_wrap">
                                <input type="text" name="tragop_birthday" id="tragop_birthday" required placeholder="<?php _e('Nhập ngày sinh','devvn-tra-gop')?>">
                            </div>
                            <div class="devvn_tragop_col tragop_input_wrap">
                                <input type="text" name="tragop_address" id="tragop_address" required placeholder="<?php _e('Nhập địa chỉ','devvn-tra-gop')?>">
                            </div>
                            <?php
                            $vn_states = apply_filters('devvn_tragop_states', array(
                                'An Giang' =>   'An Giang',
                                'Bà Rịa - Vũng Tàu'  =>  'Bà Rịa - Vũng Tàu',
                                'Bắc Giang'  =>  'Bắc Giang',
                                'Bắc Kạn'  =>  'Bắc Kạn',
                                'Bạc Liêu'  =>  'Bạc Liêu',
                                'Bắc Ninh'  =>  'Bắc Ninh',
                                'Bến Tre'  =>  'Bến Tre',
                                'Bình Dương'  =>  'Bình Dương',
                                'Bình Phước'  =>  'Bình Phước',
                                'Bình Thuận'  =>  'Bình Thuận',
                                'Bình Định'  =>  'Bình Định',
                                'Cà Mau'  =>  'Cà Mau',
                                'Cao Bằng'  =>  'Cao Bằng',
                                'Gia Lai'  =>  'Gia Lai',
                                'Hà Giang'  =>  'Hà Giang',
                                'Hà Nam'  =>  'Hà Nam',
                                'Hà Nội'  =>  'Hà Nội',
                                'Hà Tĩnh'  =>  'Hà Tĩnh',
                                'Hải Dương'  =>  'Hải Dương',
                                'Hậu Giang'  =>  'Hậu Giang',
                                'Hồ Chí Minh'  =>  'Hồ Chí Minh',
                                'Hoà Bình'  =>  'Hoà Bình',
                                'Hưng Yên'  =>  'Hưng Yên',
                                'Khánh Hòa'  =>  'Khánh Hòa',
                                'Kiên Giang'  =>  'Kiên Giang',
                                'Kon Tum'  =>  'Kon Tum',
                                'Lai Châu'  =>  'Lai Châu',
                                'Lâm Đồng'  =>  'Lâm Đồng',
                                'Lạng Sơn'  =>  'Lạng Sơn',
                                'Lào Cai'  =>  'Lào Cai',
                                'Long An'  =>  'Long An',
                                'Nam Định'  =>  'Nam Định',
                                'Nghệ An'  =>  'Nghệ An',
                                'Ninh Bình'  =>  'Ninh Bình',
                                'Ninh Thuận'  =>  'Ninh Thuận',
                                'Phú Thọ'  =>  'Phú Thọ',
                                'Phú Yên'  =>  'Phú Yên',
                                'Quảng Bình'  =>  'Quảng Bình',
                                'Quảng Nam'  =>  'Quảng Nam',
                                'Quảng Ngãi'  =>  'Quảng Ngãi',
                                'Quảng Ninh'  =>  'Quảng Ninh',
                                'Quảng Trị'  =>  'Quảng Trị',
                                'Sóc Trăng'  =>  'Sóc Trăng',
                                'Sơn La'  =>  'Sơn La',
                                'Tây Ninh'  =>  'Tây Ninh',
                                'Thái Bình'  =>  'Thái Bình',
                                'Thái Nguyên'  =>  'Thái Nguyên',
                                'Thanh Hóa'  =>  'Thanh Hóa',
                                'Thành phố Cần Thơ'  =>  'Thành phố Cần Thơ',
                                'Thành phố Hải Phòng'  =>  'Thành phố Hải Phòng',
                                'Thành phố Đà Nẵng'  =>  'Thành phố Đà Nẵng',
                                'Thừa Thiên Huế'  =>  'Thừa Thiên Huế',
                                'Tiền Giang'  =>  'Tiền Giang',
                                'Trà Vinh'  =>  'Trà Vinh',
                                'Tuyên Quang'  =>  'Tuyên Quang',
                                'Vĩnh Long'  =>  'Vĩnh Long',
                                'Vĩnh Phúc'  =>  'Vĩnh Phúc',
                                'Yên Bái'  =>  'Yên Bái',
                                'Đắk Lắk'  =>  'Đắk Lắk',
                                'Đắk Nông'  =>  'Đắk Nông',
                                'Điện Biên'  =>  'Điện Biên',
                                'Đồng Nai'  =>  'Đồng Nai',
                                'Đồng Tháp'  =>  'Đồng Tháp',
                            ));
                            $default_state = apply_filters('devvn_tragop_default_state','Hồ Chí Minh');
                            if($vn_states && is_array($vn_states)):
                            ?>
                            <div class="devvn_tragop_col tragop_input_wrap">
                                <select name="tragop_state" id="tragop_state">
                                    <?php foreach($vn_states as $k=>$v):?>
                                        <option value="<?php echo $k;?>" <?php selected($k, $default_state);?>><?php echo $v;?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <?php endif;?>
                        </div>
                        <div class="devvn_tragop_box">
                            <div class="devvn_tragop_col submit_button">
                                <button type="submit" id="submit_button"><?php _e('Gửi hồ sơ mua trả góp','devvn-tra-gop')?></button>
                            </div>
                        </div>
                    </div>
                    <?php wp_nonce_field('tragop_action_nonce', 'tragop_nonce');?>
                    <input type="hidden" value="<?php echo $product->get_id();?>" name="prodID"/>
                    <input type="hidden" value="" name="cty_interest" id="cty_interest"/>
                    <input type="hidden" value="" name="cty_interest_name" id="cty_interest_name"/>
                    <input type="hidden" value="<?php echo $price_prod;?>" name="prod_price" id="prod_price"/>
                </form>
            </div>
        </div>
    </div>
    <div class="loading-cart">
        <span class="cswrap">
            <span class="csdot"></span>
            <span class="csdot"></span>
            <span class="csdot"></span>
        </span>
    </div>
</div>
