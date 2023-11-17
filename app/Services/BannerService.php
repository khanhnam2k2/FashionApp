<?php

namespace App\Services;

use App\Enums\Status;
use App\Models\Banner;
use Exception;
use Illuminate\Support\Facades\Log;

class BannerService extends BaseService
{

    /**
     * Get banner list limit
     * @return Array banner list
     */
    public function getLimitBanner($limit = 3)
    {
        try {
            $banners = Banner::where('status', Status::ON)->orderBy('id', 'asc')->take($limit)->get();
            return $banners;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Get banner list paginate
     * @param String $searchName keyword search
     * @return Array banner list
     */
    public function searchBanner($searchName = null)
    {
        try {
            $banners = Banner::select('banners.*');

            if ($searchName != null && $searchName != '') {
                $banners = $banners->where('banners.title', 'LIKE', '%' . $searchName . '%');
            }

            $banners = $banners->latest()->paginate(3);

            return $banners;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Create banner
     * @param $request
     * @return true
     */
    public function createBanner($request)
    {
        try {
            $uploadImage = $this->uploadImage($request->file('image'), 'banner');
            $banner = [
                'title' => $request->title,
                'image' => $uploadImage,
                'description' => $request->description,
                'status' => $request->statusBanner
            ];

            Banner::create($banner);

            return true;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Update banner
     * @param $request
     * @return true
     */
    public function updateBanner($request)
    {
        try {
            $banner = Banner::findOrFail($request->bannerId);
            if (!empty($request->file('image'))) {
                $this->deleteFile($banner->image);
                $uploadImage = $this->uploadImage($request->file('image'), 'banner');
                $banner->image = $uploadImage;
            }
            $banner->title = $request->title;
            $banner->description = $request->description;
            $banner->status = $request->statusBanner;

            $banner->save();

            return true;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Delete banner
     * @param number $id id of banner
     * @return true
     */
    public function deleteBanner($id)
    {
        try {
            $banner = Banner::findOrFail($id);

            $this->deleteFile($banner->image);

            $banner->delete();

            return true;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }
}
