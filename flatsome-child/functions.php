<?php 
require get_theme_file_path() . '/includes/Bootstrap.php';
define('PATH_CHILD_THEME', dirname(__FILE__));
function translate_i18n($text) {
    return \includes\Bootstrap::bootstrap()->language->translateText($text);
}

function get_template_directory_child() {
    $directory_template = get_template_directory_uri(); 
    $directory_child = str_replace('flatsome', '', $directory_template) . 'flatsome-child	';

    return $directory_child;
}