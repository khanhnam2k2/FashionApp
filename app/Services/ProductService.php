<?php

namespace App\Services;

use App\Enums\Status;
use App\Models\Product;
use App\Models\ProductSizeQuantity;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductService extends BaseService
{
    public function getProductById($id)
    {
        try {
            $product = Product::select(
                'products.id',
                'products.name',
                'products.price',
                'products.description',
                'products.sku',
                'products.image',
                'products.status',
                'categories.name as categoryName',
                DB::raw('GROUP_CONCAT(product_size_quantities.size) as sizes'),
                'categories.name as categoryName',
                'categories.id as categoryId',
            )
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->join('product_size_quantities', function ($join) {
                    $join->on('products.id', '=', 'product_size_quantities.product_id')
                        ->where('product_size_quantities.quantity', '>', 0);
                })
                ->where('products.id', $id)
                ->where('products.status', Status::ON)
                ->groupBy(
                    'products.id',
                    'products.name',
                    'products.price',
                    'products.description',
                    'products.sku',
                    'products.image',
                    'products.status',
                    'categories.id',
                    'categories.name'
                )
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
                ->get();
            return $products;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function searchProduct($searchName = null, $sortByPrice = null, $categoryId = null, $status = null)
    {
        try {
            $products = Product::select(
                'products.id',
                'products.name',
                'products.price',
                'products.category_id',
                'products.description',
                'products.sku',
                'products.image',
                'products.status',
                'categories.name as categoryName',
                DB::raw('GROUP_CONCAT(product_size_quantities.size) as sizes'),
                DB::raw('GROUP_CONCAT(product_size_quantities.quantity) as quantities')
            )
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->join('product_size_quantities', function ($join) {
                    $join->on('products.id', '=', 'product_size_quantities.product_id')
                        ->where('product_size_quantities.quantity', '>', 0);
                })
                ->whereNull('products.deleted_at');
            if ($status != null && $status != '') {
                $products->where('products.status', '=', $status);
            }
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

            $products = $products->groupBy(
                'products.id',
                'products.name',
                'products.price',
                'products.category_id',
                'products.description',
                'products.sku',
                'products.image',
                'products.status',
                'categories.name'
            )
                ->orderBy('products.created_at', 'desc')
                ->paginate(9);
            return $products;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function createProduct($request)
    {
        DB::beginTransaction();
        try {
            $uploadImage = $this->uploadFile($request->file('image'), 'products');
            $product = [
                'name' => $request->name,
                'price' => $request->price,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'sku' => $request->sku,
                'image' => $uploadImage,
                'status' => $request->statusProduct
            ];
            $product = Product::create($product);
            if ($product) {
                $sizes = $request->sizes;
                $quantities = $request->quantity;
                foreach ($sizes as $key => $size) {
                    $productSizeQuantity = new ProductSizeQuantity();
                    $productSizeQuantity->product_id = $product->id;
                    $productSizeQuantity->size = $size;
                    $productSizeQuantity->quantity = $quantities[$key];
                    $productSizeQuantity->save();
                }
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function updateProduct($request)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->productId);

            if (!empty($request->file('image'))) {
                $this->deleteFile($product->image);
                $uploadImage = $this->uploadFile($request->file('image'), 'products');
            }

            $productArr = [
                'name' => $request->name,
                'price' => $request->price,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'sku' => $request->sku,
                'image' => $uploadImage ?? $product->image,
                'status' => $request->statusProduct
            ];

            $product->update($productArr);

            ProductSizeQuantity::where('product_id', $product->id)->delete();

            $sizes = $request->sizes;
            $quantities = $request->quantity;
            foreach ($sizes as $key => $size) {
                $productSizeQuantity = new ProductSizeQuantity();
                $productSizeQuantity->product_id = $product->id;
                $productSizeQuantity->size = $size;
                $productSizeQuantity->quantity = $quantities[$key];
                $productSizeQuantity->save();
            }

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
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

    public function getQuantityOfSize($size, $request)
    {
        try {
            $data = ProductSizeQuantity::where('size', $size)
                ->where('product_id', $request->productId)
                ->pluck('quantity')->first();
            return $data;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }
}
