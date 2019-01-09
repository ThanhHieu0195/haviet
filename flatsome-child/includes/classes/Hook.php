<?php
namespace includes\classes;
use includes\Bootstrap;
use includes\interfaces\HookInterface;

class Hook implements HookInterface{
    const VERSION = '1.0';
    public $template = '';
    public function init() {
        $this->registerAction();
        $this->registerFilter();
        $this->registerAsset();
        $this->registerShortcodes();
    }

    public function registerAction() {
        // TODO: Implement registerAction() method.
        add_action('wp_ajax_admin_ajax', [$this, 'excuteAjaxAdmin']);
        add_action('wp_ajax_front', [$this, 'excuteAjax']);
        add_action('wp_ajax_nopriv_front', [$this, 'excuteAjax']);
        add_action('init', [$this, 'registerTaxonomy']);
        add_action('init', [$this, 'registerPostType']);
    }

    public function registerFilter() {
        // TODO: Implement registerFilter() method.
    }

    public function registerAsset() {
        add_action('wp_enqueue_scripts', [$this, 'addStyles']);
        add_action('wp_enqueue_scripts', [$this, 'addScripts']);
        add_action('admin_enqueue_scripts', [$this, 'addScriptsAdmin']);
    }

    public function addStyles() {
        $path = get_template_directory_child ();
        $styles = array(
          'bootstrap' => '/html/assets/css/bootstrap4/bootstrap.min.css',
          'slick' => '/html/assets/lib/js/slick/slick.css',
          'slick-theme' => '/html/assets/lib/js/slick/slick-theme.css',
          'style' => '/html/assets/css/style.css'
        );
        foreach ($styles as $style) {
            wp_enqueue_style($style, $path .'/'. $style, array(), self::VERSION);
        }
    }

    public function addScripts() {
        $path = get_template_directory_child();
        $scripts = array(
            'slick-js' => '/html/assets/lib/js/slick/slick.min.js',
            'main-js' => '/html/assets/js/main.js',
        );
        foreach ($scripts as $script) {
            wp_enqueue_script($script, $path .'/'. $script, array('jquery'), self::VERSION, true);
        }
    }

    public function addScriptsAdmin() {
        $path = get_template_directory_uri();
        $scripts = array(
        );
        foreach ($scripts as $script) {
            wp_enqueue_script($script, $path .'/'. $script, array('jquery'), self::VERSION, true);
        }
    }

    public function excuteAjax() {
        if ( isset($_GET['method']) ) {
            $method = $_GET['method'];
             switch ($method) {
                case 'get_data_product':
                    header('Content-Type: application/json');
                    $args = [
                        'post_per_page' => 20,
                        'post_type' => 'product',

                    ];
                    if (isset($_GET['slug']) && !empty($_GET['slug'])) {
                        $slug = $_GET['slug'];
                        $args['tax_query'] = [
                            [
                                'taxonomy' => 'product_cat',
                                'field' => 'slug',
                                'terms' => $slug
                            ]
                        ];
                    }

                    if (isset($_GET['filters']) && !empty($_GET['filters'])) {
                        $filters = $_GET['filters'];
                        $args['meta_query'] = [
                            'relation' => 'OR'
                        ];
                        foreach ($filters as $key) {
                            $args['meta_query'][] = [
                                'key'     => 'product_purpose',
                                'value'   => $key,
                                'compare' => 'LIKE',
                            ];
                        }

                    }

                    $products = get_posts($args);
                    foreach ($products as &$product) {
                        $product->permalink = get_permalink($product->ID);
                        $product->thumbnail_url = get_the_post_thumbnail_url($product->ID);
                        $product->price = number_format(get_post_meta($product->ID, '_price', true));
                        $product->sale_price = number_format(get_post_meta($product->ID, '_sale_price', true));
                        $product->rating = round(intval(get_post_meta($product->ID, '_wc_average_rating', true)));
                        $product->review =   number_format(get_post_meta($product->ID, '_wc_review_count', true));
                    }
                    echo json_encode($products);
                    exit(200);
                default:
                    break;
            }
        }

        if ( isset($_POST['method']) ) {
            $method = $_POST['method'];
            switch ($method) {
                case 'sendContact':
                    $params = $_POST['contact'];
                    $subject = '[CONTACT GUEST]';
                    $result = wp_mail($params['email'], $subject, $params['reason']);
                    header('Location: ' . $_SERVER['HTTP_REFERER'] . '?message=success');
                    exit;
                    break;
                default:
                    break;
            }
        }
        exit(200);
    }

    public function excuteAjaxAdmin() {
        exit(200);
    }

    public function registerPostType() {
        $dir_path = \includes\Bootstrap::getPath();
        foreach (glob($dir_path . "/posttypes/classes/*.php") as $filename)
        {
            $class_name = \includes\Bootstrap::bootstrap()->helper->getClassByPath($filename);
            /**
             * @var $model PostType
             */
            $class_name = '\\includes\\posttypes\\'.$class_name;
            $class_name::getInstance();
        }
    }

    public function registerTaxonomy() {
        $dir_path = \includes\Bootstrap::getPath();
        foreach (glob($dir_path . "/taxonomies/classes/*.php") as $filename)
        {
            $class_name = \includes\Bootstrap::bootstrap()->helper->getClassByPath($filename);
            /**
             * @var $class_name Taxonomy
             */
            $class_name = '\\includes\\taxonomies\\'.$class_name;
            $class_name::getInstance();
        }
    }

    public function registerShortcodes() {
        $dir_path = \includes\Bootstrap::getPath();
        foreach (glob($dir_path . "/shortcodes/classes/*.php") as $filename)
        {
            $class_name = \includes\Bootstrap::bootstrap()->helper->getClassByPath($filename);
            /**
             * @var $model Shortcode
             */
            $class_name = '\\includes\\shortcodes\\'.$class_name;
            $model = new $class_name();
            $model->register();
        }
    }
}