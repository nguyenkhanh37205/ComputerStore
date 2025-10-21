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
// use Illuminate\Support\Facades\Route;
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
        Route::post('/{id}/update', [ProductsController::class, 'update'])->name('admin.products.update');
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
});