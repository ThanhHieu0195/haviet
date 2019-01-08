<?php 
// [accordion]
function ux_categories_slick($atts, $content=null, $code) {
	$params = shortcode_atts([
		'number' => 10
	], $atts);
	
	return renderView( dirname(__FILE__) . '/views/categories_slick.php' ,$params);
}
add_shortcode('categories_slick', 'ux_categories_slick');