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
    /**
     * Get product by id
     * @param number $id id of product
     * @return Array product 
     */
    public function getProductById($id)
    {
        try {
            $product = Product::select(
                'products.id',
                'products.name',
                'products.price',
                'products.description',
                'products.sku',
                'products.images',
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
                    'products.images',
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

    /**
     * Get product list
     * @return Array product list
     */
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

    /**
     * Get product list paginate
     * @param String $searchName keyword search
     * @param String $sortByPrice 
     * @param number $categoryId id of category
     * @param number $status status product
     * @return Array product list
     */
    public function searchProduct($searchName = null, $sortByPrice = null, $categoryId = null, $status = null, $size = null)
    {
        try {
            $products = Product::select(
                'products.id',
                'products.name',
                'products.price',
                'products.category_id',
                'products.description',
                'products.sku',
                'products.images',
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
                $products = $products->where('products.status', '=', $status);
            }

            if ($size != null && $size != '') {
                $products = $products->where('product_size_quantities..size', '=', $size);
            }

            if ($searchName != null && $searchName != '') {
                $products = $products->where('products.name', 'LIKE', '%' . $searchName . '%');
            }

            if ($sortByPrice != null && $sortByPrice != '') {
                $products = $products->orderBy('price', $sortByPrice);
            }

            if ($categoryId != null && $categoryId != '') {
                $products = $products->where('products.category_id', $categoryId);
            }

            $products = $products->groupBy(
                'products.id',
                'products.name',
                'products.price',
                'products.category_id',
                'products.description',
                'products.sku',
                'products.images',
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

    /**
     * Create product
     * @param $request
     * @return true
     */
    public function createProduct($request)
    {
        DB::beginTransaction();

        try {
            $images = [];
            foreach ($request->file('images') as $file) {
                $uploadImage = $this->uploadImage($file, 'products');
                array_push($images, $uploadImage);
            }
            $product = [
                'name' => $request->name,
                'price' => $request->price,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'sku' => $request->sku,
                'images' => json_encode($images),
                'status' => $request->statusProduct
            ];

            $data = Product::create($product);

            $sizes = $request->sizes;
            $quantities = $request->quantity;

            foreach ($sizes as $key => $size) {
                $productSizeQuantity = new ProductSizeQuantity();
                $productSizeQuantity->product_id = $data->id;
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

    /**
     * Update product
     * @param $request
     * @return true
     */
    public function updateProduct($request)
    {
        DB::beginTransaction();

        try {
            $product = Product::findOrFail($request->productId);
            if (!empty($request->file('images'))) {
                $images = [];
                foreach ($request->file('images') as $file) {
                    $uploadImage = $this->uploadImage($file, 'products');
                    array_push($images, $uploadImage);
                }
                $arrayOldImages = json_decode($product->images);
                foreach ($arrayOldImages as $fileOld) {
                    $this->deleteFile($fileOld);
                }
                $product->images = json_encode($images);
            } else {
                $product->images = $product->images;
            }

            $product->name = $request->name;
            $product->price = $request->price;
            $product->category_id = $request->category_id;
            $product->description = $request->description;
            $product->sku = $request->sku;
            $product->status = $request->statusProduct;
            $product->save();

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

    /**
     * Update status category
     * @param $request
     * @return true
     */
    public function updateStatusProduct($request)
    {
        try {
            $product = Product::findOrFail($request->productId);

            $product->update([
                'status' => $request->status
            ]);

            return true;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }
    /**
     * Delete product
     * @param number $id id of product
     * @return true
     */
    public function deleteProduct($id)
    {
        try {
            $data = Product::findOrFail($id);
            $this->deleteFile($data->image);
            $data->delete();

            return true;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * get quantity of size
     * @param String $size size of product
     * @param @request
     */
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
