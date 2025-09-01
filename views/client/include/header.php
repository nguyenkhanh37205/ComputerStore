<!-- Chữ chạy -->
<div class="top-marquee">
    <marquee behavior="scroll" direction="left">Chào mừng bạn đến với Minh Thường Computer Mua sắm thả ga - Ưu đãi cực đã!
    </marquee>
</div>
<div class="header-od">
    <ul>
        
        <li></li>
    </ul>
</div>
<!-- Header: Logo - Tìm kiếm - Giỏ hàng - Đăng nhập -->
<div class="header-main container">
    <div class="logo">
        <a href="./client.php"><img src="./images/image.png" alt="Logo" /></a>
    </div>
    <div class="search">
        <form action="./client.php" method="GET" class="form_search">
            <input type="hidden" name="controller" value="client">
            <input type="hidden" name="action" value="search">

            <input type="text" class="txt_search" placeholder="Tìm kiếm" name="search"
                value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>" />
            <button class="btn_search" type="submit" link="./client.php?controller=client&action=search">Tìm kiếm</button>
        </form>
    </div>
    <div class="actions">
        <div class="cart">
            <i class="fas fa-shopping-cart"></i>
            <span>Giỏ hàng</span>
        </div>

        <div class="login">
            <?php session_start(); ?>
            <?php if (isset($_SESSION['client']) && $_SESSION['client']): ?>
                <li class="nav-li"><a href="./client.php?controller=client&action=logout" class="border_r">Đăng xuất</a>
                </li>
                <li class="nav-li use"><?= $_SESSION['client']['username'] ?></li>
            <?php else: ?>
                <li class="nav-li"><a href="./auth.php">Đăng nhập</a></li>
            <?php endif ?>
            <!-- <span>Đăng nhập</span>
        <div class="username-placeholder">
          <span id="username-display"></span>
        </div> -->
        </div>
    </div>
</div>

