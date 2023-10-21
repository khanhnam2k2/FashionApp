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
    public function __construct(CategoryService $categoryService, ProductService $productService)
    {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
    }
    public function index()
    {
        $categories = $this->categoryService->getCategories();
        return view('website.shop.index', compact('categories'));
    }
    public function search(Request $request)
    {
        $data = $this->productService->searchProduct($request->searchName, $request->sortByPrice, $request->categoryId, $request->status);
        return view('website.shop.listProduct', compact('data'));
    }
    public function details($id)
    {
        $product = $this->productService->getProductById($id);
        return view('website.shop.details', compact('product'));
    }

    public function getQuantityOfSize($size, Request $request)
    {
        $data = $this->productService->getQuantityOfSize($size, $request);
        return response()->json(['data' => $data]);
    }
}
