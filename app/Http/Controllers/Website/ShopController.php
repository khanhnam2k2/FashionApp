<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    protected $categoryService;
    protected $productService;

    /**
     * This is the constructor declaration.
     * @param CategoryService $categoryService
     * @param ProductService $productService
     */
    public function __construct(CategoryService $categoryService, ProductService $productService)
    {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
    }

    /**
     * Show shop page in website
     * @return view shop page
     */
    public function index()
    {
        $categories = $this->categoryService->getCategories();
        return view('website.shop.index', compact('categories'));
    }

    /**
     * Show product table
     * @param Request $request
     * @return view product table
     */
    public function search(Request $request)
    {
        $data = $this->productService->searchProduct($request->searchName, $request->sortByPrice, $request->categoryId, $request->status, $request->size);
        return view('website.shop.listProduct', compact('data'));
    }
    /**
     * Show product details
     * @param number $id id of product
     * @return view product details
     */
    public function details($id)
    {
        $product = $this->productService->getProductById($id);
        if ($product) {
            return view('website.shop.details', compact('product'));
        } else {
            abort(404);
        }
    }

    /**
     * Get quantity and size of product
     * @param String $size size of product
     * @param Request $request
     * @return response data quantity and size of product
     */
    public function getQuantityOfSize($size, Request $request)
    {
        $data = $this->productService->getQuantityOfSize($size, $request);
        return response()->json(['data' => $data]);
    }
}
