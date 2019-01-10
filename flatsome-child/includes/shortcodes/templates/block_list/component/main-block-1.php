<?php 
$date_remind = \includes\Bootstrap::bootstrap()->helper->time2remind(strtotime($blog->post_date));
?>
 <div class="inewsfeatured inewsfeatured-large">
  <a href="<?= get_permalink($blog->ID) ?>" title="<?= $blog->post_title ?>">
    <div class="post-info">
      <h4 class="post-title">
        <?= $blog->post_title ?>
      </h4>
      <div class="post-meta">
        <date></date><i class="ico ico-date"></i> <?= $date_remind ?> trước
      </div>
      <p><?= $blog->post_excerpt ?></p>
    </div>
    <div class="post-image">
      <div class="image"><img src="<?= esc_url(get_the_post_thumbnail_url($blog->ID)) ?>" alt="<?= $blog->post_title ?>">
      </div>
    </div>
  </a>
</div>