<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CKEditorController;
use App\Http\Controllers\Website\CartController;
use App\Http\Controllers\Website\CheckoutController;
use App\Http\Controllers\Website\CommentController;
use App\Http\Controllers\Website\ContactController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\OrderController;
use App\Http\Controllers\Website\PostController;
use App\Http\Controllers\Website\ProfileController as WebsiteProfileController;
use App\Http\Controllers\Website\ShopController;
use Illuminate\Support\Facades\Route;



Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');

Route::prefix('post')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('post.index');
    Route::get('/details/{id}', [PostController::class, 'details'])->name('post.details');
    Route::post('/search', [PostController::class, 'search'])->name('post.search');
});

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

Route::prefix('comment')->group(function () {
    Route::post('/searchPost', [CommentController::class, 'searchCommentPost'])->name('comment.searchCommentPost');
    Route::post('/searchProduct', [CommentController::class, 'searchCommentProduct'])->name('comment.searchCommentProduct');
});



Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::post('/getTotalOrderInYear', [DashboardController::class, 'getTotalOrderInYear'])->name('admin.getTotalOrderInYear');
    });

    Route::prefix('customer')->group(function () {
        Route::get('/', [AdminCustomerController::class, 'index'])->name('admin.customer.index');
        Route::post('/search', [AdminCustomerController::class, 'search'])->name('admin.customer.search');
        Route::delete('/delete/{id}', [AdminCustomerController::class, 'delete'])->name('admin.customer.delete');
    });

    Route::prefix('category')->group(function () {
        Route::get('/', [AdminCategoryController::class, 'index'])->name('admin.category.index');
        Route::post('/search', [AdminCategoryController::class, 'search'])->name('admin.category.search');
        Route::post('/create', [AdminCategoryController::class, 'create'])->name('admin.category.create');
        Route::post('/update', [AdminCategoryController::class, 'update'])->name('admin.category.update');
        Route::delete('/delete/{id}', [AdminCategoryController::class, 'delete'])->name('admin.category.delete');
    });

    Route::prefix('products')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('admin.product.index');
        Route::post('/search', [AdminProductController::class, 'search'])->name('admin.product.search');
        Route::post('/create', [AdminProductController::class, 'create'])->name('admin.product.create');
        Route::post('/update', [AdminProductController::class, 'update'])->name('admin.product.update');
        Route::delete('/delete/{id}', [AdminProductController::class, 'delete'])->name('admin.product.delete');
    });

    Route::prefix('posts')->group(function () {
        Route::get('/', [AdminPostController::class, 'index'])->name('admin.post.index');
        Route::post('/search', [AdminPostController::class, 'search'])->name('admin.post.search');
        Route::post('/create', [AdminPostController::class, 'create'])->name('admin.post.create');
        Route::post('/update', [AdminPostController::class, 'update'])->name('admin.post.update');
        Route::delete('/delete/{id}', [AdminPostController::class, 'delete'])->name('admin.post.delete');
    });

    Route::prefix('contacts')->group(function () {
        Route::get('/', [AdminContactController::class, 'index'])->name('admin.contact.index');
        Route::post('/search', [AdminContactController::class, 'search'])->name('admin.contact.search');
        Route::delete('/delete/{id}', [AdminContactController::class, 'delete'])->name('admin.contact.delete');
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('admin.order.index');
        Route::post('/search', [AdminOrderController::class, 'search'])->name('admin.order.search');
        Route::post('/updateStatus', [AdminOrderController::class, 'updateStatus'])->name('admin.order.updateStatus');
        Route::delete('/delete/{id}', [AdminOrderController::class, 'delete'])->name('admin.order.delete');
    });

    Route::prefix('orders/details')->group(function () {
        Route::get('/{id}', [AdminOrderController::class, 'details'])->name('admin.order.details');
        Route::post('/search', [AdminOrderController::class, 'searchDetails'])->name('admin.order.searchDetails');
    });

    Route::post('ckeditor/upload', [CKEditorController::class, 'upload'])->name('ckeditor.upload');
});
Route::middleware('auth')->group(function () {

    Route::prefix('profile')->group(function () {
        Route::get('/', [WebsiteProfileController::class, 'index'])->name('profile.index');
        Route::post('/updateProfile', [WebsiteProfileController::class, 'updateProfile'])->name('profile.updateProfile');
    });

    Route::prefix('order')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('order.index');
        Route::post('/search', [OrderController::class, 'search'])->name('order.search');
        Route::post('/searchDetails', [OrderController::class, 'searchDetails'])->name('order.searchDetails');
    });

    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/', [CartController::class, 'search'])->name('cart.search');
        Route::post('/searchLimit', [CartController::class, 'searchLimit'])->name('cart.searchLimit');
        Route::post('/add_to_cart', [CartController::class, 'addToCart'])->name('cart.add');
        Route::post('/update_cart', [CartController::class, 'updateCart'])->name('cart.update');
        Route::delete('/remove', [CartController::class, 'removeProduct'])->name('cart.remove');
    });

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/placeOrder', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');

    Route::prefix('comment')->group(function () {
        Route::post('/create', [CommentController::class, 'create'])->name('comment.create');
        Route::post('/update', [CommentController::class, 'update'])->name('comment.update');
        Route::delete('/delete/{id}', [CommentController::class, 'delete'])->name('comment.delete');
    });
});



require __DIR__ . '/auth.php';
