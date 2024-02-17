<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Services\BannerService;
use App\Services\CategoryService;
use App\Services\PostService;
use App\Services\ProductService;

class HomeController extends Controller
{
    protected $categoryService;
    protected $productService;
    protected $postService;
    protected $bannerService;

    /**
     * This is the constructor declaration.
     * @param CategoryService $categoryService
     * @param ProductService $productService
     * @param PostService $postService
     * @param BannerService $bannerService
     */
    public function __construct(
        CategoryService $categoryService,
        ProductService $productService,
        PostService $postService,
        BannerService $bannerService
    ) {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
        $this->postService = $postService;
        $this->bannerService = $bannerService;
    }

    /**
     * Show home page website
     * @return view home page
     */
    public function index()
    {
        $categories = $this->categoryService->getCategories(4);
        $products = $this->productService->searchProduct();
        $postLimit = $this->postService->getLimitPost();
        $bannerLimit = $this->bannerService->getLimitBanner(3);
        return view('website.welcome', compact('categories', 'products', 'postLimit', 'bannerLimit'));
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
