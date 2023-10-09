<?php

namespace App\Services;

use App\Enums\Status;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Log;

class ProductService extends BaseService
{
    public function searchProduct($searchName)
    {
        try {
            $products = Product::select('products.*', 'categories.name as categoryName')
                ->join('categories', 'products.category_id', '=', 'categories.id');
            if ($searchName != null && $searchName != '') {
                $products->where('products.name', 'LIKE', '%' . $searchName . '%')
                    ->orWhere('products.price', 'LIKE', '%' . $searchName . '%')
                    ->orWhere('categories.name', 'LIKE', '%' . $searchName . '%');
            }
            $products = $products->latest()->paginate(4);
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
                'image' => $uploadImage,
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
