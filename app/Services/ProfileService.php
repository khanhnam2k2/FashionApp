<?php

namespace App\Services;

use App\Enums\Status;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfileService extends BaseService
{
    /**
     * Update profile
     * @param $request
     * @return true
     */
    public function updateProfile($request)
    {
        try {
            $user = Auth::user();
            $user = User::findOrFail($user->id);

            if (!empty($request->file('avatar'))) {
                $this->deleteFile($user->avatar);
                $uploadImage = $this->uploadImage($request->file('avatar'), 'users');
                $user->avatar = $uploadImage;
            }

            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->save();

            return true;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }
}
