<?php

namespace includes\classes;

use includes\Bootstrap;
use includes\interfaces\HelperInterface;

class Language implements HelperInterface {
    public $lang = 'en';
    public $data=[];
    public $format='';
    public function init() {
        // TODO: Implement init() method.
        $arr = [];
        if (function_exists('get_blog_details')) {
            $blog = get_blog_details(get_current_blog_id());
            $path = $blog->path;
            preg_match('/\/vi\/$/', $path, $arr);
        }
        if (count($arr) > 0) {
            $this->lang = 'vi';
            $path= '/vi/';
            $this->format = Bootstrap::DATET_FM_VI;
        } else {
            $path = '/en/';
            $this->format = Bootstrap::DATET_FM_EN;
        }
        $dir_path = Bootstrap::getPath();
        $filename = $dir_path . '/language_i18n' .  $path . 'language.json';
        if (file_exists($filename)) {
            $file = fopen($filename, 'r');
            $data = fread($file,filesize($filename));
            $this->data = json_decode($data, true);
        }
    }

    public function translateText($key) {
        $_SESSION['i18n'][$key] = $key;
        $arr = explode('/', $key);
        $value = $this->data;
        for ($i=0; $i<count($arr); $i++) {
            if (isset($value[$arr[$i]])) {
                $value = $value[$arr[$i]];
            } else {
                $value = $key;
                break;
            }
        }
        return $value;
    }
}


