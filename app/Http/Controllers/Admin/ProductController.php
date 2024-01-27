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

    /**
     * This is the constructor declaration.
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Show product page admin
     * @return view product list management page
     */
    public function index()
    {
        $categories = Category::where('status', 1)->get();
        return view('admin.product.index', compact('categories'));
    }

    /**
     * Show product table admin
     * @param Request $request
     * @return view product table
     */
    public function search(Request $request)
    {
        $data = $this->productService->searchProduct($request->searchName, null, $request->categoryId);
        return view('admin.product.table', ['data' => $data]);
    }

    /**
     * Create new product 
     * @param StoreProductRequest $request 
     * @return response ok
     */
    public function create(StoreProductRequest $request)
    {
        $this->productService->createProduct($request);
        return response()->json('ok');
    }

    /**
     * Update product 
     * @param UpdateProductRequest $request 
     * @return response ok
     */
    public function update(UpdateProductRequest $request)
    {
        $this->productService->updateProduct($request);
        return response()->json('ok');
    }

    /**
     * Update status product 
     * @param Request $request 
     * @return response ok
     */
    public function updateStatus(Request $request)
    {
        $this->productService->updateStatusProduct($request);
        return response()->json('ok');
    }


    /**
     * Delete product 
     * @param number $id id of product 
     * @return response ok
     */
    public function delete($id)
    {
        $this->productService->deleteProduct($id);
        return response()->json('ok');
    }

    /**
     * Get the revenue of each product by id
     * @param Request $request 
     * @param number $id id of product 
     * @return response data revenue
     */
    public function getRevenueByProduct(Request $request, $id)
    {
        $data = $this->productService->getRevenueByProduct($request, $id);
        if (!empty($data)) {
            return response()->json([
                'product_id' => $data['product_id'],
                'product_name' => $data['product_name'],
                'revenue' => $data['revenue'],
                'total_quantity_sold' => $data['total_quantity_sold'],
                'total_orders' => $data['total_orders']
            ]);
        }
    }

    /**
     * Show revenue product table admin
     * @param Request $request
     * @return view revenue product table
     */
    public function searchRevenueProducts(Request $request)
    {
        $data = $this->productService->searchRevenueProducts($request);
        return view('admin.product.tableRevenueProducts', compact('data'));
    }
}
