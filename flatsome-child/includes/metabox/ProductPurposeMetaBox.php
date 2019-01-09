<?php

/**
 * Register a meta box using a class.
 */
class ProductPurposeMetaBox {
 
    /**
     * Constructor.
     */
    public function __construct() {
        if ( is_admin() ) {
            add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
            add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
        }
 
    }
 
    /**
     * Meta box initialization.
     */
    public function init_metabox() {
        add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
        add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );
    }
 
    /**
     * Adds the meta box.
     */
    public function add_metabox() {
        add_meta_box(
            'product-purpose',
            __( 'Purpost', 'flatsome' ),
            array( $this, 'render_metabox' ),
            'product',
            'advanced',
            'default'
        );
 
    }
 
    /**
     * Renders the meta box.
     */
    public function render_metabox( $post ) {
    	wp_nonce_field( 'custom_nonce_action', 'custom_nonce' );
    	$arr_sl = get_post_meta($post->ID, 'product_purpose', true);
    	if (empty($arr_sl)) {
    		$arr_sl = [];
    	}
        // Add nonce for security and authentication.
        $options = \includes\classes\Constants::OPTION_PRODUCT_PURPOSE;

        echo '<select multiple name="product_purpose[]">';
        foreach ($options as $key => $value) {
        	if (in_array($key, $arr_sl)) {
	        	echo '<option value="'.$key.'" selected>'.$value.'</option>';
        	} else {
	        	echo '<option value="'.$key.'">'.$value.'</option>';
        	}
        }
        echo '</select>';
    }
 
    /**
     * Handles saving the meta box.
     *
     * @param int     $post_id Post ID.
     * @param WP_Post $post    Post object.
     * @return null
     */
    public function save_metabox( $post_id, $post ) {
        // Add nonce for security and authentication.
        $nonce_name   = isset( $_POST['custom_nonce'] ) ? $_POST['custom_nonce'] : '';
        $nonce_action = 'custom_nonce_action';
 
        // Check if nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }
 
        // Check if user has permissions to save data.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
 
        // Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }
 
        // Check if not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }

        if (isset($_POST['product_purpose'])) {
        	$purpose = $_POST['product_purpose'];
        	update_post_meta($post_id, 'product_purpose', $purpose);
        }
    }
}
 
new ProductPurposeMetaBox();