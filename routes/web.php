<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Website\CartController;
use App\Http\Controllers\Website\ContactController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\ShopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::prefix('shop')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/{size}', [ShopController::class, 'getQuantityOfSize'])->name('shop.getQuantityOfSize');
    Route::get('/details/{id}', [ShopController::class, 'details'])->name('shop.details');
    Route::post('/search', [ShopController::class, 'search'])->name('shop.search');
});
Route::prefix('contacts')->group(function () {
    Route::get('/', [ContactController::class, 'showContact'])->name('contact.show');
    Route::post('/create', [ContactController::class, 'create'])->name('contact.create');
});
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    });
    Route::prefix('category')->group(function () {
        Route::get('/', [AdminCategoryController::class, 'index'])->name('category.index');
        Route::post('/search', [AdminCategoryController::class, 'search'])->name('category.search');
        Route::post('/create', [AdminCategoryController::class, 'create'])->name('category.create');
        Route::post('/update', [AdminCategoryController::class, 'update'])->name('category.update');
        Route::delete('/delete/{id}', [AdminCategoryController::class, 'delete'])->name('category.delete');
    });

    Route::prefix('products')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('product.index');
        Route::post('/search', [AdminProductController::class, 'search'])->name('product.search');
        Route::post('/create', [AdminProductController::class, 'create'])->name('product.create');
        Route::post('/update', [AdminProductController::class, 'update'])->name('product.update');
        Route::delete('/delete/{id}', [AdminProductController::class, 'delete'])->name('product.delete');
    });

    Route::prefix('posts')->group(function () {
        Route::get('/', [AdminPostController::class, 'index'])->name('post.index');
        Route::post('/search', [AdminPostController::class, 'search'])->name('post.search');
        Route::post('/create', [AdminPostController::class, 'create'])->name('post.create');
        Route::post('/update', [AdminPostController::class, 'update'])->name('post.update');
        Route::delete('/delete/{id}', [AdminPostController::class, 'delete'])->name('post.delete');
    });

    Route::prefix('contacts')->group(function () {
        Route::get('/', [AdminContactController::class, 'index'])->name('contact.index');
        Route::post('/search', [AdminContactController::class, 'search'])->name('contact.search');
        Route::delete('/delete/{id}', [AdminContactController::class, 'delete'])->name('contact.delete');
    });
});
Route::middleware('auth')->group(function () {

    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/', [CartController::class, 'search'])->name('cart.search');
        Route::post('/add_to_cart', [CartController::class, 'addToCart'])->name('cart.add');
        Route::post('/update_cart', [CartController::class, 'updateCart'])->name('cart.update');
        Route::delete('/remove', [CartController::class, 'removeProduct'])->name('cart.remove');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
