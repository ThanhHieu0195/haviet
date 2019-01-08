<?php

add_action( 'wp_ajax_flatsome_block_title', function () {
  global $wpdb;

  $block_id = $_GET['block_id'];
  $block_title = $wpdb->get_var( "SELECT post_title FROM $wpdb->posts WHERE post_type = 'blocks' AND id = '$block_id'");

  return wp_send_json_success( array(
    'block_title' => $block_title
  ) );
} );

    add_action( 'wp_ajax_get_data_product', function(){
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

    	$products = get_posts($args);
    	echo json_encode($products);
    	exit(200);
    } );
