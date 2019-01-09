<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/23/18
 * Time: 12:08 AM
 */

namespace includes\classes;

use includes\interfaces\ShortcodeInterface;

class Shortcode implements ShortcodeInterface
{
    public $shortcode = 'shortcode';
    public $default_values = [];
    public $attributes = [];
    public $full_attrs = false;
    public $params = [];
    public $has_style = 0;

    public function register()
    {
        // TODO: Implement register() method.
        add_shortcode($this->shortcode, [$this, 'render']);
    }

    public function render($attrs)
    {
        $this->params = $attrs;
        // TODO: Implement render() method.
        if (!$this->full_attrs) {
            $this->attributes = shortcode_atts($this->attributes, $attrs, $this->shortcode );
        } else {
            $this->attributes = $attrs;
        }

        $view = $this->getPathView($this->getView());
        add_action(str_replace('-', '_' , $this->shortcode) .'before_render', [$this, 'customBeforeRender']);
        return \includes\Bootstrap::bootstrap()->helper->render($view, ['params' => $this->attributes]);
    }

    public function getView() {
        $view = $this->shortcode;
        if ($this->has_style && isset($this->attributes['style'])) {
            $style = $this->shortcode .'-' . $this->attributes['style'];
            $view .= "/{$style}";
        }
        return $view;
    }
    public function getPathView($filename='') {
        $path_view = \includes\Bootstrap::getPath();
        return $path_view . '/shortcodes/templates/' . $filename . '.php';
    }

    public function customBeforeRender() {

    }
}