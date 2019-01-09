<?php
namespace includes\shortcodes;

class BannerShortcode extends \includes\classes\Shortcode {
    public $shortcode = 'banner';
    // public $full_attrs = true;
    // public $has_style = 1;
    
    public $attributes = [
		'img1' => '',
		'img2' => ''
    ];
}