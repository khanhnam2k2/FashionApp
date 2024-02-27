<?php

namespace App\Services;

use App\Enums\Status;
use App\Enums\StatusOrder;
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
            if (!$product) {
                return false;
            }
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
                });

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
    public function searchProduct($searchName = null, $sortByPrice = null, $categoryId = null,$paginate = 3, $status = null, $size = null)
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
                });

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
                ->paginate($paginate);

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

    /**
     * Get the revenue of each product by id
     * @param $request 
     * @param number $id id of product 
     * @return array data revenue
     */
    public function getRevenueByProduct($request, $id)
    {
        try {
            $selectedMonth = $request->month;
            $selectedYear = $request->year;
            $productRevenue = Product::select(
                'products.id as product_id',
                'products.name as product_name',
                DB::raw('SUM(order_items.quantity * order_items.price) as revenue'),
                DB::raw('SUM(order_items.quantity) as total_quantity_sold'),
                DB::raw('COUNT(DISTINCT orders.id) as total_orders')
            )
                ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
                ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.status', 4)
                ->where('products.id', $id);


            if ($selectedMonth != null && $selectedMonth != '') {
                $productRevenue->whereMonth('orders.complete_date', $selectedMonth);
            }

            if ($selectedYear != null && $selectedYear != '') {
                $productRevenue->whereYear('orders.complete_date', $selectedYear);
            }



            $productRevenue = $productRevenue->groupBy('products.id', 'products.name')
                ->first();

            if (!$productRevenue) {
                return [];
            }

            return [
                'product_id' => $productRevenue->product_id,
                'product_name' => $productRevenue->product_name,
                'revenue' => $productRevenue->revenue,
                'total_quantity_sold' => $productRevenue->total_quantity_sold,
                'total_orders' => $productRevenue->total_orders
            ];
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Get list revenue product
     * @param $request 
     * @return array data list revenue products
     */
    public function searchRevenueProducts($request)
    {
        try {
            $selectedMonth = $request->month;
            $selectedYear = $request->year;
            $productRevenue = Product::select(
                'products.id as product_id',
                'products.name as product_name',
                'products.images as product_images',
                DB::raw('SUM(order_items.quantity * order_items.price) as revenue'),
                DB::raw('SUM(order_items.quantity) as total_quantity_sold'),
            )
                ->join('order_items', 'products.id', '=', 'order_items.product_id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.status', '=', StatusOrder::successfulDelivery);

            if ($selectedMonth != null && $selectedMonth != '') {
                $productRevenue->whereMonth('orders.complete_date', $selectedMonth);
            }

            if ($selectedYear != null && $selectedYear != '') {
                $productRevenue->whereYear('orders.complete_date', $selectedYear);
            }

            $productRevenue = $productRevenue->groupBy('products.id', 'products.name', 'products.images')
                ->paginate(4);
            
            return $productRevenue;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }
}
