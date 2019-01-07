<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
global $tragop_settings;
$tra_truoc = $tragop_settings['tra_truoc'];
$thuho_enable = $tragop_settings['thuho_enable'];
$insurrance_enable = $tragop_settings['insurrance_enable'];
$rates = $tragop_settings['rates'];

$page_tragop = $tragop_settings['page_tragop'];
$tragop_enable = $tragop_settings['tragop_enable'];
$button_text1 = $tragop_settings['button_text1'];
$button_text2 = $tragop_settings['button_text2'];
flush_rewrite_rules();
?>
<div class="wrap devvn_tragop_wrap">
    <h1><?php _e('Cài đặt mua trả góp','devvn-tra-gop')?></h1>
    <form method="post" action="options.php" novalidate="novalidate">
        <?php
        settings_fields( $this->_optionGroup );
        ?>
        <h2><?php _e('Cài đặt chung','devvn-tra-gop');?></h2>
        <table class="form-table">
            <tbody>
                <tr>
                    <th><?php _e('Kích hoạt', 'devvn-tra-gop');?></th>
                    <td>
                        <label><input type="radio" name="<?php echo $this->_optionName?>[tragop_enable]" value="1" <?php checked(1,$tragop_enable);?>> Có</label>
                        <label><input type="radio" name="<?php echo $this->_optionName?>[tragop_enable]" value="0" <?php checked(0,$tragop_enable);?>> Không</label>
                    </td>
                </tr>
                <tr>
                    <th><?php _e('Chọn trang trả góp', 'devvn-tra-gop');?></th>
                    <td>
                        <?php
                        $args = array(
                            'selected'              => $page_tragop,
                            'name'                  => $this->_optionName . '[page_tragop]',
                            'show_option_none'     => __('Chọn một trang', 'devvn-tra-gop'),
                        );
                        wp_dropdown_pages( $args );
                        ?>
                    </td>
                </tr>
                <tr>
                    <th><?php _e('Text 1', 'devvn-tra-gop');?></th>
                    <td>
                        <input type="text" name="<?php echo $this->_optionName?>[button_text1]" value="<?php echo $button_text1;?>">
                    </td>
                </tr>
                <tr>
                    <th><?php _e('Text 2', 'devvn-tra-gop');?></th>
                    <td>
                        <input type="text" name="<?php echo $this->_optionName?>[button_text2]" value="<?php echo $button_text2;?>">
                    </td>
                </tr>
            </tbody>
        </table>
        <?php if($rates):?>
        <h2><?php _e('Cài đặt lãi suất của các công ty tài chính','devvn-tra-gop');?></h2>
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
        <?php do_settings_sections($this->_optionGroup, 'default'); ?>
        <?php submit_button();?>
    </form>
</div>