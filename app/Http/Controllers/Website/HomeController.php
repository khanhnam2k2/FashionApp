<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $categoryService;
    protected $productService;
    public function __construct(CategoryService $categoryService, ProductService $productService)
    {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
    }
    public function index()
    {
        $categories = $this->categoryService->getLimitCategories();
        $products = $this->productService->getProducts();
        return view('welcome', compact('categories', 'products'));
    }
}
