<?php
namespace includes\classes;

use includes\interfaces\PostTypeInterface;
class PostType implements PostTypeInterface {
    static $list_type=array();
    public static function className()
    {
        return get_called_class();
    }
    public static function getInstance() {
        $class = self::className();
        if ( !in_array($class, self::$list_type) ) {
            $post_type = new $class();
            $post_type->registerPostType();
            self::$list_type[$class] = $post_type;
        }
        return self::$list_type[$class];
    }
    public function registerPostType() {
        $name = $this->getName();
        $configs = $this->getConfigs();
        register_post_type($name, $configs);
    }
    public function getAttributes() {
        return array();
    }
    public function getName() {
        return '';
    }
    public function getConfigs() {
        return array();
    }
}