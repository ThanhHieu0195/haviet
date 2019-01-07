<?php 
$arr1 = isset($img1) ? explode(',', $img1) : [];
$arr2 = isset($img2) ? explode(',', $img2) : [];
?>

<section class="sc-block-banner">
  <div class="container">
    <div class="row">
      <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
        <div class="banner-carousel">
         <?php 
         if (!empty($arr1)) {
         	foreach ($arr1 as $url) {
         		echo '<div class="item"><img src="' .esc_url($url). '" alt="#"></div>';	
         	}
         }
          ?>
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
      	 <?php 
         if (!empty($arr2)) {
         	foreach ($arr2 as $url) {
         		echo '<div class="twobanner"><a href="#"><img src="' .$url. '" alt="#"></a></div>';	
         	}
         }
          ?>
      </div>
    </div>
  </div>
</section>