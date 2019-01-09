<?php
namespace includes\shortcodes;

class AboutShortcode extends \includes\classes\Shortcode {
    public $shortcode = 'about';
    // public $full_attrs = true;
    // public $has_style = 1;
    
    public $attributes = [
        'title' => '',
        'subtitle' => '',
        'description' => '',
        'background_url' => ''
    ];
}