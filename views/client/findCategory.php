<?php
ob_start();
?>


<div class="main_top" style="margin: 0 auto;
  padding: 0 15px; max-width: 1410px;">
    <h2 class="text_title note">Sản phẩm <?= $findByCat['name'] ?></h2>
    <div class="content-mt">
        <div class="content11">
            <?php foreach ($products as $pr): ?>
                <div class="content12">
                    <a href="#">
                        <img src="<?= $pr['image'] ?>" alt="" class="product-img" />
                    </a>
                    <span class="product-name"><?= $pr['name'] ?></span>
                    <span class="product-price"><?= number_format($pr['price']) ?><sup>đ</sup></span>
                    <div class="product-buttons">
                        <a href="index.php?controller=cart&action=add&id=<?= $pr['id'] ?>">
                            <button class="btn_giohang">Thêm vào giỏ</button>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php

$content = ob_get_clean();
include "./../views/client/layout.php";
?>