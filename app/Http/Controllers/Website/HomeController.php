<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\PostService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $categoryService;
    protected $productService;
    protected $postService;

    public function __construct(CategoryService $categoryService, ProductService $productService, PostService $postService)
    {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
        $this->postService = $postService;
    }

    public function index()
    {
        $categories = $this->categoryService->getLimitCategories();
        $products = $this->productService->searchProduct();
        $postLimit = $this->postService->getLimitPost();
        return view('website.welcome', compact('categories', 'products', 'postLimit'));
    }

    public function about()
    {
        return view('website.about');
    }
}
