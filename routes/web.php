<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return view('welcome');
})->name('home');


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
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
