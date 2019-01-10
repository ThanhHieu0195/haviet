<?php

namespace includes\classes;

use includes\interfaces\HelperInterface;

class Helper implements HelperInterface {

    public function init() {
        // TODO: Implement init() method.
    }

    public function getPathTemplate($name) {
        return $template = \includes\Bootstrap::getPath() . '/templates/' . $name . '.php';
    }

    public function getPathSingle($name) {
        return $template = \includes\Bootstrap::getPath() . '/templates/singles/' . $name . '.php';
    }

    public function render( $_file_, $_params_ = [] ) {
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush( false );
        extract( $_params_, EXTR_OVERWRITE );
        try {
            include $_file_;
            $result = ob_get_clean();

            return $result;
        } catch ( \Exception $e ) {
            while ( ob_get_level() > $_obInitialLevel_ ) {
                if ( ! ob_end_clean() ) {
                    ob_clean();
                }
            }
            throw $e;
        } catch ( \Throwable $e ) {
            while ( ob_get_level() > $_obInitialLevel_ ) {
                if ( ! ob_end_clean() ) {
                    ob_clean();
                }
            }
            throw $e;
        }
    }

    public function minifyHtml($html) {
        return preg_replace(['/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s'],['>','<','\\1'], $html);
    }

    public function getClassByPath($path) {
        preg_match('/\/([a-zA-Z0-9]*).php$/', $path, $matches);
        $class_name = '';
        if (count($matches) > 0) {
            $class_name = $matches[1];
        }
        return $class_name;
    }

    public function translate($text) {
        return $text;
    }

    public function time2remind($time)
    {
        $date_remind = (time() - $time) / 60;
        if ($date_remind < 60) {
          $date_remind = round($date_remind) . ' phút';
        } else {
          $date_remind /= 60;
          if ($date_remind < 24) {
            $date_remind = round($date_remind) . ' giờ';
          } else {
            $date_remind /= 24;
            if ($date_remind < 365) {
                $date_remind = round($date_remind) . ' ngày';
            } else {
                $date_remind = round($date_remind / 365) . ' năm';
            }
          }
        }
        return $date_remind;
    }
}