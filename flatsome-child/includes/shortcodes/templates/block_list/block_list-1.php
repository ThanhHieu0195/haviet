<?php 
extract($params);
$ids = explode(',', $post_ids);
$posts = get_posts([
  'includes' => $ids
]);

$main_post = $posts[0];
unset($posts[0]);
?>
 <div class="sc-block <?= $extra_class ?>">
<div class="heading-blog">
  <h1><?= $title ?></h1>
</div>
 <?php 
 $view = dirname(__FILE__) . '/component/';
 if (!empty($main_post)) {
  echo \includes\Bootstrap::bootstrap()->helper->render($view . 'main-block-1.php', ['blog' => $main_post]); 
 }

 if (!empty($posts)) {
  foreach ($posts as $post) {
    echo \includes\Bootstrap::bootstrap()->helper->render($view . 'block-item-1.php', ['blog' => $post]); 
  }
 }
 ?>
  </div>