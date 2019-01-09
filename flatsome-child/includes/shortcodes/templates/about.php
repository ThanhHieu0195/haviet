<?php
extract($params);
?>
<div class="nheading__wrap">
    <div class="ncontainer">
        <h5 class="nabout__title heading-h5 f--400 txt--up cl--grayblack inlineb-t"><?= $subtitle ?></h5>
        <br />
        <div class="nbrand">
            <img src="<?= $background_url ?>" alt="" class="nimg nbrand__logo">
        </div>
        <br />
        <h2 class="heading-h2 f--600 txt--cap lh--12 cl--black inlineb-t">
            <?= $title ?>
            <div class="nline-title"></div>
        </h2>
        <p class="ntext-des f--16">
            <?= $description ?>
        </p>
    </div>
</div>
