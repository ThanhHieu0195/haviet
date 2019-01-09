<?php
namespace includes\classes;

use includes\interfaces\TaxonomyInterface;
class Taxonomy implements TaxonomyInterface {
    private $object_type = [];
    public $name = '';
    static $list_type=array();
    public static function className()
    {
        return get_called_class();
    }

    public static function getInstance() {
        $class = self::className();
        if ( !in_array($class, self::$list_type) ) {
            $taxonomy = new $class();
            $taxonomy->registerTaxonomy();
            self::$list_type[$class] = $taxonomy;
        }
        return self::$list_type[$class];
    }

    public function registerTaxonomy() {
        register_taxonomy($this->name, $this->object_type, $this->getAttributes());
    }

    public function getAttributes() {
        return [];
    }

    public function getConfigs() {
        return [];
    }
}