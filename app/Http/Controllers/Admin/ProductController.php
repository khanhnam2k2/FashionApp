<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function index()
    {
        $categories = Category::where('status', 1)->get();
        return view('admin.product.index', compact('categories'));
    }

    public function search(Request $request)
    {
        $data = $this->productService->searchProduct($request->searchName, null, $request->categoryId);
        return view('admin.product.table', ['data' => $data]);
    }

    public function create(StoreProductRequest $request)
    {
        $this->productService->createProduct($request);
        return response()->json('ok');
    }

    public function update(UpdateProductRequest $request)
    {
        $this->productService->updateProduct($request);
        return response()->json('ok');
    }

    public function delete($id)
    {
        $this->productService->deleteProduct($id);
        return response()->json('ok');
    }
}
