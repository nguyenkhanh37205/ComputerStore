<!-- Nội dung dưới header -->
<div class="content-below">
  <!-- Sidebar danh mục -->
  <div class="sidebar">
    <ul>
      <?php foreach ($category as $cat) : ?>
        <li class="menu_li">
          <a href="./client.php?controller=client&action=findCategory&category_id=<?= $cat['id'] ?>">
            <?= $cat['name'] ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>

  <!-- Banner chính -->
  <div class="main-banner">
    <img id="banner-img" src="./images/banneranh.webp" alt="Banner chính">
  </div>

  <!-- Banner phụ -->
  <div class="side-banners">
    <img src="./images/laptop.webp" alt="Banner phụ 1">
    <img src="./images/buildpc.webp" alt="Banner phụ 2">
    <img src="./images/manhinh.webp" alt="Banner phụ 3">
  </div>
  <script src="./js/banner.js"></script>
</div>