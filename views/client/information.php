<?php
ob_start();
?>
  <!-- Nút quay về -->
  <div class="back-button-area">
    <a href="./client.php" class="btn-back-in-box"><i class="fa-solid fa-house"></i> Quay về trang chủ</a>
  </div>
<div class="product-detail-wrapper">
  <!-- Nội dung chi tiết sản phẩm -->
  <div class="product-detail">
    <div class="product-image-section">
      <img src="<?= $products['image'] ?>" alt="<?= $products['name'] ?>" class="product-detail-image">
    </div>
    <div class="product-info-section">
      <h2 class="product-title"><?= $products['name'] ?></h2>
      <p class="product-price"><?= number_format($products['price']) ?><sup>đ</sup></p>
      <p class="product-description"><?= $products['description'] ?? 'Chưa có mô tả chi tiết.' ?></p>

      <div class="product-buttons">
        <a href="index.php?controller=cart&action=add&id=<?= $products['id'] ?>">
          <button class="btn-detail-cart">Thêm vào giỏ hàng</button>
        </a>
      </div>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
include "./../views/client/layout.php";
?>