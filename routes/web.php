<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\VariantsController;
use App\Http\Controllers\WishlistsController;
use App\Http\Controllers\Auth\ClientController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\CheckoutController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// phần trang chủ
Route::get('/', function () {
    return view('home');
});
// Đăng nhập - Đăng ký - Đăng xuất
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form')->middleware('guest');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post')->middleware('guest');

// phần người dùng
Route::get('/', [ClientController::class, 'getAllProducts'])->name('client.home');
// Hiển thị giỏ (nếu bạn dùng indexadmin hoặc showCart -> đặt tên cart.show)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// Thêm sản phẩm vào giỏ (POST)
Route::post('/cart/store', [CartController::class, 'store'])->name('cart.store');

// Cập nhật/xóa (nếu dùng)
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/destroy/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
// Cập nhật số lượng sản phẩm trong giỏ hàng (AJAX)
Route::post('/cart/update-quantity', [App\Http\Controllers\CartController::class, 'updateQuantity'])
    ->name('cart.updateQuantity');

//CHỉnh sửa thông tin người dùng
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('client.profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
});

// // Đổi email và số điện thoại
// Route::middleware(['auth'])->group(function () {
//     // Form đổi email
//     Route::get('/change-email', [ProfileController::class, 'showChangeEmailForm'])->name('change.email.form');
//     Route::post('/change-email', [ProfileController::class, 'sendChangeEmailOtp'])->name('change.email.post');
//     Route::post('/verify-email-otp', [ProfileController::class, 'verifyEmailOtp'])->name('verify.email.otp');

//     // Form đổi số điện thoại
//     Route::get('/change-phone', [ProfileController::class, 'showChangePhoneForm'])->name('change.phone.form');
//     Route::post('/change-phone', [ProfileController::class, 'sendChangePhoneOtp'])->name('change.phone.post');
//     Route::post('/verify-phone-otp', [ProfileController::class, 'verifyPhoneOtp'])->name('verify.phone.otp');
// });

//chi tiết sản phẩm
// Route::get('/product/{productId}', [ProductsController::class, 'showProductDetail'])->name('product.detail');
Route::get('/category/{id}', [ProductsController::class, 'showProducts'])->name('category.show');

// Tìm kiếm sản phẩm

Route::get('/search', [ProductsController::class, 'search'])->name('search');


Route::get('/product/{id}', [ProductsController::class, 'showProductDetail'])->name('product.show');
// Route::get('/search', [ProductsController::class, 'search'])->name('products.search');

// Route::get('/autocomplete', [ProductsController::class, 'autocomplete'])->name('auth.autocomplete');

//

// phần wishlist(danh sách yêu thích)
Route::middleware(['auth'])->group(function () {
    Route::get('/wishlist', [WishlistsController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist', [WishlistsController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{id}', [WishlistsController::class, 'destroy'])->name('wishlist.destroy');
});
Route::delete('/wishlist/{id}/ajax', [WishlistsController::class, 'destroyAjax'])
    ->name('wishlist.destroy.ajax')
    ->middleware('auth');

//Trang thanh toán
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.place');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

// -------------------------------

// phần admin 
Route::get('/index', [IndexController::class, 'index']);
Route::prefix('admin')->group(function () {
    // phần dashboard
    Route::get('/', action: [DashboardController::class, 'index'])->name('admin.dashboard');
    // phần quản lý sản phẩm
    Route::prefix('/products')->group(function () {
        Route::get('/', [ProductsController::class, 'index'])->name('admin.products.index');
        Route::get('/create', [ProductsController::class, 'create'])->name('admin.products.create');
        Route::post('/store', [ProductsController::class, 'store'])->name('admin.products.store');
        Route::get('/{id}/edit', [ProductsController::class, 'edit'])->name('admin.products.edit');
        Route::put('/{id}/update', [ProductsController::class, 'update'])->name('admin.products.update');
        Route::delete('/{id}/delete', [ProductsController::class, 'destroy'])->name('admin.products.destroy');
    });
    // phần quản lý danh mục
    Route::prefix('/categories')->group(function () {
        Route::get('/', [CategoriesController::class, 'index'])->name('admin.categories.index');
        Route::get('/create', [CategoriesController::class, 'create'])->name('admin.categories.create');
        Route::post('/store', [CategoriesController::class, 'store'])->name('admin.categories.store');
        Route::get('/{id}/edit', [CategoriesController::class, 'edit'])->name('admin.categories.edit');
        Route::post('/{id}/update', [CategoriesController::class, 'update'])->name('admin.categories.update');
        Route::delete('/{id}/delete', [CategoriesController::class, 'destroy'])->name('admin.categories.destroy');
    });
    // phần quản lý thương hiệu
    Route::prefix('/brands')->group(function () {
        Route::get('/', [BrandsController::class, 'index'])->name('admin.brands.index');
        Route::get('/create', [BrandsController::class, 'create'])->name('admin.brands.create');
        Route::post('/store', [BrandsController::class, 'store'])->name('admin.brands.store');
        Route::get('/{id}/edit', [BrandsController::class, 'edit'])->name('admin.brands.edit');
        Route::post('/{id}/update', [BrandsController::class, 'update'])->name('admin.brands.update');
        Route::delete('/{id}/delete', [BrandsController::class, 'destroy'])->name('admin.brands.destroy');
    });
    // phần quản lý người dùng
    Route::prefix('/users')->group(function () {
        Route::get('/', [UsersController::class, 'index'])->name('admin.users.index');
        Route::get('/create', [UsersController::class, 'create'])->name('admin.users.create');
        Route::post('/store', [UsersController::class, 'store'])->name('admin.users.store');
        Route::get('/{id}/edit', [UsersController::class, 'edit'])->name('admin.users.edit');
        Route::post('/{id}/update', [UsersController::class, 'update'])->name('admin.users.update');
        Route::delete('/{id}/delete', [UsersController::class, 'destroy'])->name('admin.users.destroy');
    });
    // phần quản lý giỏ hàng
    Route::prefix('/carts')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('admin.cart.indexAdmin');
        Route::post('/store', [CartController::class, 'store'])->name('admin.cart.store');
        Route::post('/{id}/update', [CartController::class, 'update'])->name('admin.cart.update');
        Route::delete('/{id}/delete', [CartController::class, 'destroy'])->name('admin.cart.destroy');
    });
    // quản lý đơn hàng
    Route::prefix('/orders')->group(function () {
        Route::get('/', [OrdersController::class, 'index'])->name('admin.orders.index');
        Route::get('/{id}', [OrdersController::class, 'show'])->name('admin.orders.show');
        Route::post('/{id}/update-status', [OrdersController::class, 'updateStatus'])->name('admin.orders.updateStatus');
        Route::delete('/{id}/delete', [OrdersController::class, 'destroy'])->name('admin.orders.destroy');
    });
    // phần quản lý biến thể sản phẩm
    Route::prefix('/variants')->group(function () {
        Route::get('/', [VariantsController::class, 'index'])->name('admin.variants.index');
        Route::get('/create', [VariantsController::class, 'create'])->name('admin.variants.create');
        Route::post('/store', [VariantsController::class, 'store'])->name('admin.variants.store');
        Route::get('/{id}/edit', [VariantsController::class, 'edit'])->name('admin.variants.edit');
        Route::post('/{id}/update', [VariantsController::class, 'update'])->name('admin.variants.update');
        Route::delete('/{id}/delete', [VariantsController::class, 'destroy'])->name('admin.variants.destroy');
    });
    // phần quản lý danh sách yêu thích
    Route::prefix('/wishlists')->group(function () {
        Route::get('/', [WishlistsController::class, 'index'])->name('admin.wishlists.index');
        Route::get('/create', [WishlistsController::class, 'create'])->name('admin.wishlists.create');
        Route::post('/store', [WishlistsController::class, 'store'])->name('admin.wishlists.store');
        Route::get('/{id}/edit', [WishlistsController::class, 'edit'])->name('admin.wishlists.edit');
        Route::post('/{id}/update', [WishlistsController::class, 'update'])->name('admin.wishlists.update');
        Route::delete('/{id}/delete', [WishlistsController::class, 'destroy'])->name('admin.wishlists.destroy');
    });
});