<?php
namespace includes\shortcodes;

class NewListShortcode extends \includes\classes\Shortcode {
    public $shortcode = 'newlist';
    // public $full_attrs = true;
    // public $has_style = 1;
    
    public $attributes = [
    	'number' => '4'
    ];
}