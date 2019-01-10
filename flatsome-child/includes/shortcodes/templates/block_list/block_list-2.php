<?php 
extract($params);
$ids = explode(',', $post_ids);
$posts = get_posts([
  'includes' => $ids
]);

$main_post = $posts[0];
unset($posts[0]);
$date_remind = \includes\Bootstrap::bootstrap()->helper->time2remind(strtotime($main_post->post_date));
?>
 <div class="custom-post <?= $extra_class ?>">
  <div class="custom-heading">
    <h3><?= $title ?></h3>
  </div>
  <div class="block-content">
    <ul>
      <li class="item-full">
        <a href="<?= get_permalink($main_post->ID) ?>" title="<?= $main_post->post_title ?>">
          <div class="post-image">
            <div class="image">
              <img src="<?= esc_url(get_the_post_thumbnail_url($main_post->ID)) ?>" alt="<?= $main_post->post_title ?>">
            </div>
          </div>
        </a>

        <div class="post-info">
          <h4 class="post-title"><?= $main_post->post_title ?></h4>
          <div class="post-meta">
            <date><i class="ico ico-date"></i> <?= $date_remind ?> trước</date>
          </div>
          <p><?= $main_post->post_excerpt ?></p>
        </div>
      </li>
      <?php 
      if (!empty($posts)) {
        foreach ($posts as $post) {
          echo ' <li class="item"><a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'">'.$post->post_title.'</a></li>' ;
        }
      }
      ?>
    </ul>
  </div>
</div>