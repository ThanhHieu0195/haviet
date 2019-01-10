<?php
namespace includes\shortcodes;

class BlockListShortcode extends \includes\classes\Shortcode {
    public $shortcode = 'block_list';
    // public $full_attrs = true;
    public $has_style = 1;
    
    public $attributes = [
        'style' => ''	,
        'post_ids' => '',
        'title' => '',
        'extra_class' => ''
    ];
}