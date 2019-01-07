<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
global $tragop_settings , $post;

$rates = $tragop_settings['rates'];
$tragop_prod = get_post_meta($post->ID,'tragop_prod_data',true);

$rates_prod = isset($tragop_prod['rates']) ? $tragop_prod['rates'] : array();

$devvn_tragop_type = get_post_meta($post->ID,'devvn_tragop_type',true);
if(!$devvn_tragop_type) $devvn_tragop_type = 'default';

$rates = wp_parse_args($rates_prod, $rates);

$thuho_enable = isset($tragop_prod['thuho_enable']) ? $tragop_prod['thuho_enable'] : 0;
$insurrance_enable =isset($tragop_prod['insurrance_enable']) ? $tragop_prod['insurrance_enable'] : 0;

wp_nonce_field( 'tragop_save_meta_box_data', 'tragop_meta_box_nonce' );
?>
<div id="tragop_prod_wrap" class="<?php echo $devvn_tragop_type;?>">
    <div class="tragop_prod_box">
        <p><strong><?php _e('Tùy chỉnh trả góp','devvn-tra-gop');?></strong></p>
        <label>
            <input type="radio" name="devvn_tragop_type" value="default" <?php checked('default', $devvn_tragop_type);?>>
            <?php _e('Mặc định','devvn-tra-gop');?>
        </label>
        <label>
            <input type="radio" name="devvn_tragop_type" value="no" <?php checked('no', $devvn_tragop_type);?>>
            <?php _e('Không áp dụng trả góp','devvn-tra-gop');?>
        </label>
        <label>
            <input type="radio" name="devvn_tragop_type" value="yes" <?php checked('yes', $devvn_tragop_type);?>>
            <?php _e('Trả góp theo quy định riêng','devvn-tra-gop');?>
        </label>
    </div>
    <div class="tragop_prod_box tragop_prod_yes">
        <?php if($rates):?>
            <table class="devvn_rate_list">
                <tbody>
                <tr>
                    <th><?php _e('Công ty', 'devvn-tra-gop');?></th>
                    <?php foreach($rates as $k=>$v):?>
                        <td class="text-center">
                            <?php echo ($v['name']) ? $v['name'] : '';?>
                            <input type="hidden" name="<?php echo $this->_optionName?>[rates][<?php echo $k;?>][name]" value="<?php echo ($v['name']) ? $v['name'] : '';?>">
                        </td>
                    <?php endforeach;?>
                </tr>
                <tr>
                    <th><?php _e('Kích hoạt', 'devvn-tra-gop');?></th>
                    <?php foreach($rates as $k=>$v):
                        $active = isset($v['active']) ? $v['active'] : 0;
                        ?>
                        <td><input type="checkbox" value="1" name="<?php echo $this->_optionName?>[rates][<?php echo $k;?>][active]" <?php checked(1, $active);?>> <?php _e('Kích hoạt', 'devvn-tra-gop');?></td>
                    <?php endforeach;?>
                </tr>
                <tr>
                    <th><?php _e('Lãi suất', 'devvn-tra-gop');?></th>
                    <?php foreach($rates as $k=>$v):?>
                        <td><input type="number" step="any" min="0" name="<?php echo $this->_optionName?>[rates][<?php echo $k;?>][rate]" value="<?php echo ($v['rate']) ? $v['rate'] : '';?>"> %</td>
                    <?php endforeach;?>
                </tr>
                <tr>
                    <th>
                        <?php _e('Phí bảo hiểm', 'devvn-tra-gop');?>
                        <input type="checkbox" value="1" name="<?php echo $this->_optionName?>[insurrance_enable]" <?php checked(1, $insurrance_enable);?>>
                    </th>
                    <?php foreach($rates as $k=>$v):?>
                        <td><input type="number" step="any" min="0" name="<?php echo $this->_optionName?>[rates][<?php echo $k;?>][insurrance]" value="<?php echo ($v['insurrance']) ? $v['insurrance'] : '';?>"> %</td>
                    <?php endforeach;?>
                </tr>
                <tr>
                    <th>
                        <?php _e('Phí đóng tiền', 'devvn-tra-gop');?>
                        <input type="checkbox" value="1" name="<?php echo $this->_optionName?>[thuho_enable]" <?php checked(1, $thuho_enable);?>>
                    </th>
                    <?php foreach($rates as $k=>$v):?>
                        <td><input type="number" step="any" min="0" name="<?php echo $this->_optionName?>[rates][<?php echo $k;?>][thu_ho]" value="<?php echo ($v['thu_ho']) ? $v['thu_ho'] : '';?>"></td>
                    <?php endforeach;?>
                </tr>
                <tr>
                    <th><?php _e('Giấy tờ bắt buộc', 'devvn-tra-gop');?></th>
                    <?php foreach($rates as $k=>$v):?>
                        <td><input type="text" name="<?php echo $this->_optionName?>[rates][<?php echo $k;?>][giay_to]" value="<?php echo ($v['giay_to']) ? $v['giay_to'] : '';?>"></td>
                    <?php endforeach;?>
                </tr>
                </tbody>
            </table>
        <?php endif;?>
    </div>
</div>
