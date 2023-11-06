<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\PostService;
use App\Services\ProductService;

class HomeController extends Controller
{
    protected $categoryService;
    protected $productService;
    protected $postService;

    /**
     * This is the constructor declaration.
     * @param CategoryService $categoryService
     * @param ProductService $productService
     * @param PostService $postService
     */
    public function __construct(CategoryService $categoryService, ProductService $productService, PostService $postService)
    {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
        $this->postService = $postService;
    }

    /**
     * Show home page website
     * @return view home page
     */
    public function index()
    {
        $categories = $this->categoryService->getLimitCategories();
        $products = $this->productService->searchProduct();
        $postLimit = $this->postService->getLimitPost();
        return view('website.welcome', compact('categories', 'products', 'postLimit'));
    }

    /**
     * Show about page website
     * @return view about page
     */
    public function about()
    {
        return view('website.about');
    }
}
