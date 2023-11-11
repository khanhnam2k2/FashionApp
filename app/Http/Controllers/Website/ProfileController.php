<?php

namespace App\Http\Controllers\Website;

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
     * Show profile page 
     * @return view profile page
     */
    public function index()
    {
        return view('website.profile.index');
    }

    /**
     * Update profile 
     * @param UpdateProfileRequest $request
     * @return response ok
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $this->profileService->updateProfile($request);
        return response()->json('ok');
    }
}
