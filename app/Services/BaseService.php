<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;

class BaseService
{

    /**
     * Upload files to storage
     *
     * @param $files
     * @return path image
     */
    public function uploadImage($files, $newFolder = null)
    {
        try {
            $imagePath = $files;
            $imageName = $imagePath->getClientOriginalName();
            $filename = explode('.', $imageName)[0];
            $extension = $imagePath->getClientOriginalExtension();
            $picName =  Str::slug(time() . "_" . $filename, "_") . "." . $extension;
            $folder = $newFolder ? 'uploads/' . $newFolder : 'uploads';
            $path = $files->storeAs($folder, $picName, 'public');
            return $path;
        } catch (Exception $e) {
            Log::error($e);
            throw $e;
        }
    }

    /**
     * Upload files to storage
     * @param $files
     * @return path files 
     */
    public function uploadFile($files, $newFolder = null)
    {
        try {
            $imagePath = $files;
            $imageName = $imagePath->getClientOriginalName();
            $typeFile = $imagePath->getMimeType();
            $filename = explode('.', $imageName)[0];
            $extension = $imagePath->getClientOriginalExtension();
            $picName =  Str::slug(time() . "_" . $filename, "_") . "." . $extension;
            $folder = $newFolder ? 'uploads/' . $newFolder : 'uploads';
            $path = $files->storeAs($folder, $picName, 'public');
            return [
                "path" => $path,
                "type" => $typeFile,
            ];
        } catch (Exception $e) {
            Log::error($e);
            throw $e;
        }
    }


    /**
     * Delete files to storage
     *
     * @param path files
     * @return true
     */
    public function deleteFile($path)
    {
        try {
            if (Storage::exists('public/' . $path)) {
                Storage::delete('public/' . $path);
            }
            return true;
        } catch (Exception $e) {
            Log::error($e);
            throw $e;
        }
    }

    /**
     * generate a random code
     * @param number $length length of code
     * @return code random code
     */
    public function generateRandomCode($length = 10)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $code;
    }

    /**
     * get list order detail by order id
     * @param number $orderId order id
     * @return array $orderDetail list order detail
     */
    public function getOrderDetails($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            $orderDetail = OrderItem::select(
                'order_items.*',
                'products.name as productName',
                'products.images as productImages',
            )
                ->join('products', 'products.id', '=', 'order_items.product_id')
                ->where('order_id', $order->id)->get();
            return $orderDetail;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }
}
