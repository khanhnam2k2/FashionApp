<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;
use App\Services\BannerService;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    protected $bannerService;

    /**
     * This is the constructor declaration.
     * @param BannerService $bannerService
     */
    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
    }

    /**
     * Show banner page admin
     * @return view banner list management page
     */
    public function index()
    {
        return view('admin.banner.index');
    }

    /**
     * Show banner table admin
     * @param Request $request
     * @return view banner table
     */
    public function search(Request $request)
    {
        $data = $this->bannerService->searchBanner($request->searchName);
        return view('admin.banner.table', ['data' => $data]);
    }

    /**
     * Create new banner 
     * @param StoreBannerRequest $request 
     * @return response ok
     */
    public function create(StoreBannerRequest $request)
    {
        $this->bannerService->createBanner($request);
        return response()->json('ok');
    }

    /**
     * Update banner 
     * @param UpdateBannerRequest $request 
     * @return response ok
     */
    public function update(UpdateBannerRequest $request)
    {
        $this->bannerService->updateBanner($request);
        return response()->json('ok');
    }

    /**
     * Update status banner 
     * @param Request $request 
     * @return response ok
     */
    public function updateStatus(Request $request)
    {
        $this->bannerService->updateStatusBanner($request);
        return response()->json('ok');
    }

    /**
     * Delete banner 
     * @param number $id id of banner 
     * @return response ok
     */
    public function delete($id)
    {
        $this->bannerService->deleteBanner($id);
        return response()->json('ok');
    }
}
