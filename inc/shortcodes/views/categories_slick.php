<?php 
$terms = get_terms( 'product_cat', array(
    'hide_empty' => false,
    'number' => intval($number)
) );
?>

 <section class="sc-tab-product">
      <div class="container">
        <div class="row">
          <ul class="nav nav-tabs" role="tablist">
          	<?php 
          	if (!empty($terms)) {
          		foreach ($terms as $term) {
                       	$thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
                       	$url = '';
                       	if (!empty($thumbnail_id)) {
                       		$url = wp_get_attachment_url($thumbnail_id);
                       	}
                       ?>
           		<li class="nav-item active"><a class="nav-link active" href="javascript:void(0)" role="tab" 	data-toggle="tab" onClick="getCategoriesProductData('<?= $term->slug ?>')">
                	<div class="block-image"><img src="<?= $url ?>" alt="#"></div>
                	<p><?= $term->name ?></p></a>
            	</li>
            <?php
          		}
          	}
        	?>
          </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="apple" role="tabpanel">
                  <section class="portfolio section">
                    <div class="container">
                      <div class="filters"><span class="top-side">
                          <h2>Hãy chọn mục đích sử dụng:</h2></span>
                        <ul>
                          <li class="active" data-filter="*">Hot nhất</li>
                          <li data-filter=".corporate">Mới ra mắt</li>
                          <li data-filter=".personal">Dưới 10 triệu đồng</li>
                          <li data-filter=".agency">Nhiếp ảnh</li>
                          <li data-filter=".portal">Pin khủng</li>
                          <li data-filter=".game">Chơi game</li>
                        </ul>
                      </div>
                      <div class="filters-content">
                        <div class="row grid js-sc-tab-product">
                        </div>
                      </div>
                    </div>
                  </section>
                </div>
              </div>
      </div>
  </div>
</section>