<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Services\ProfileService;

class ProfileController extends Controller
{
    protected $profileService;

    /**
     * This is the constructor declaration.
     * @param ProfileService $profileService
     */
    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Show profile page in admin
     * @return view profile page
     */
    public function index()
    {
        return view('admin.profile.index');
    }

    /**
     * Update profile admin
     * @param UpdateProfileRequest $request
     * @return response ok
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $this->profileService->updateProfile($request);
        return response()->json('ok');
    }
}
