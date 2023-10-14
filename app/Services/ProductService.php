<?php

namespace App\Services;

use App\Enums\Status;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Log;

class ProductService extends BaseService
{
    public function getProductById($id)
    {
        try {
            $product = Product::select('products.*', 'categories.name as categoryName', 'categories.id as categoryId')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->where('products.id', $id)
                ->where('products.status', Status::ON)
                ->first();
            return $product;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function getProducts()
    {
        try {
            $products = Product::select('products.*', 'categories.name as categoryName', 'categories.id as categoryId')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->where('products.status', Status::ON)
                ->where('products.quantity', '>', 0)
                ->get();
            return $products;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function searchProduct($searchName, $sortByPrice = null, $categoryId = null)
    {
        try {
            $products = Product::select('products.*', 'categories.name as categoryName')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->where('products.quantity', '>', 0);
            if ($searchName != null && $searchName != '') {
                $products->where('products.name', 'LIKE', '%' . $searchName . '%')
                    ->orWhere('products.price', 'LIKE', '%' . $searchName . '%')
                    ->orWhere('categories.name', 'LIKE', '%' . $searchName . '%');
            }
            if ($sortByPrice != null && $sortByPrice != '') {
                $products->orderBy('price', $sortByPrice);
            }
            if ($categoryId != null && $categoryId != '') {
                $products->where('products.category_id', $categoryId);
            }
            $products = $products->latest()->paginate(9);
            return $products;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function createProduct($request)
    {
        try {
            $uploadImage = $this->uploadFile($request->file('image'), 'products');
            $product = [
                'name' => $request->name,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'sku' => $request->sku,
                'image' => $uploadImage,
                'sizes' => json_encode($request->sizes),
                'status' => $request->statusProduct
            ];
            $data = Product::create($product);
            return $data;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function updateProduct($request)
    {
        try {
            $data = Product::findOrFail($request->productId);
            if (!empty($request->file('image'))) {
                $uploadImage = $this->uploadFile($request->file('image'), 'products');
            }
            $product = [
                'name' => $request->name,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'sku' => $request->sku,
                'image' => $uploadImage ?? $data->image,
                'status' => $request->statusProduct
            ];
            $data = $data->update($product);
            return $data;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function deleteProduct($id)
    {
        try {
            $data = Product::findOrFail($id);
            $this->deleteFile($data->image);
            $data->delete();
            return $data;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }
}
