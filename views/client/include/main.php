<div id="main">

    <div class="main_top">
        <h2 class="text_title note">LapTop</h2>
        <div class="content-mt">
            <div class="content1">
                <button class="carousel-btn-left" onclick="scrollCarousel(this, -1)">&#10094;</button>
                <?php foreach ($laptops as $pr): ?>
                    <div class="content12">
                        <a href="./client.php?controller=product&action=viewproducts&id=<?= $pr['id'] ?>">
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
            <button class="carousel-btn-right" onclick="scrollCarousel (this, 1)">&#10095;</button>
        </div>
    </div>
    <div class="main_top">
        <h2 class="text_title note">PC</h2>
        <!-- <button class="scroll-btn right">&gt;</button> -->
        <div class="content-mt">
            <div class="content1">
                <button class="carousel-btn-left" onclick="scrollCarousel(this, -1)">&#10094;</button>
                <?php foreach ($pcs as $pr): ?>
                    <div class="content12">
                        <a href="./client.php?controller=product&action=viewproducts&id=<?= $pr['id'] ?>">
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
                <button class="carousel-btn-right" onclick="scrollCarousel (this, 1)">&#10095;</button>
            </div>
        </div>
        <!-- <button class="scroll-btn right">&gt;</button> -->
    </div>
    <div class="main_top">
        <h2 class="text_title note">Mainboard</h2>
        <div class="content-mt">
            <div class="content1">
                <button class="carousel-btn-left" onclick="scrollCarousel(this, -1)">&#10094;</button>
                <?php foreach ($mainboards as $pr): ?>
                    <div class="content12">
                        <a href="./client.php?controller=product&action=viewproducts&id=<?= $pr['id'] ?>">
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
                <button class="carousel-btn-right" onclick="scrollCarousel (this, 1)">&#10095;</button>
            </div>
        </div>
    </div>
    <div class="main_top">
        <h2 class="text_title note">VGA</h2>
        <div class="content-mt">

            <div class="content1">
                <button class="carousel-btn-left" onclick="scrollCarousel(this, -1)">&#10094;</button>
                <?php foreach ($vgas as $pr): ?>
                    <div class="content12">
                        <a href="./client.php?controller=product&action=viewproducts&id=<?= $pr['id'] ?>">
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
                <button class="carousel-btn-right" onclick="scrollCarousel (this, 1)">&#10095;</button>
            </div>
        </div>
    </div>
    <div class="main_top">
        <h2 class="text_title note">CPU</h2>
        <div class="content-mt">
            <div class="content1">
                <button class="carousel-btn-left" onclick="scrollCarousel(this, -1)">&#10094;</button>
                <?php foreach ($cpus as $pr): ?>
                    <div class="content12">
                        <a href="./client.php?controller=product&action=viewproducts&id=<?= $pr['id'] ?>">
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
                <button class="carousel-btn-right" onclick="scrollCarousel (this, 1)">&#10095;</button>
            </div>
        </div>
    </div>
    <div class="main_top">
        <h2 class="text_title note">RAM</h2>
        <div class="content-mt">
            <div class="content1">
                <button class="carousel-btn-left" onclick="scrollCarousel(this, -1)">&#10094;</button>
                <?php foreach ($rams as $pr): ?>
                    <div class="content12">
                        <a href="./client.php?controller=product&action=viewproducts&id=<?= $pr['id'] ?>">
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
                <button class="carousel-btn-right" onclick="scrollCarousel (this, 1)">&#10095;</button>
            </div>
        </div>
    </div>
    <div class="main_top">
        <h2 class="text_title note">Chuột, Bàn phím</h2>
        <div class="content-mt">
            <div class="content1">
                <button class="carousel-btn-left" onclick="scrollCarousel(this, -1)">&#10094;</button>
                <?php foreach ($MouseKeyboards as $pr): ?>
                    <div class="content12">
                        <a href="./client.php?controller=product&action=viewproducts&id=<?= $pr['id'] ?>">
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
                <button class="carousel-btn-right" onclick="scrollCarousel (this, 1)">&#10095;</button>
            </div>
        </div>
    </div>
    <div class="main_top">
        <h2 class="text_title note">Thiết bị âm thanh</h2>
        <div class="content-mt">
            <div class="content1">
                <button class="carousel-btn-left" onclick="scrollCarousel(this, -1)">&#10094;</button>
                <?php foreach ($Audios as $pr): ?>
                    <div class="content12">
                        <a href="./client.php?controller=product&action=viewproducts&id=<?= $pr['id'] ?>">
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
                <button class="carousel-btn-right" onclick="scrollCarousel (this, 1)">&#10095;</button>
            </div>
        </div>
    </div>
    <div class="main_top">
        <h2 class="text_title note">Thiết Bị Mạng</h2>
        <div class="content-mt">
            <div class="content1">
                <button class="carousel-btn-left" onclick="scrollCarousel(this, -1)">&#10094;</button>
                <?php foreach ($Networks as $pr): ?>
                    <div class="content12">
                        <a href="./client.php?controller=product&action=viewproducts&id=<?= $pr['id'] ?>">
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
                <button class="carousel-btn-right" onclick="scrollCarousel (this, 1)">&#10095;</button>
            </div>
        </div>
    </div>


    <script src="./js/scrollCarousel.js"></script>
</div>