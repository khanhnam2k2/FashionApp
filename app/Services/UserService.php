<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    /**
     * Get customer list paginate
     * @param String $searchName keyword search
     * @return Array customer list
     */
    public function searchCustomer($searchName = null)
    {
        try {
            $customers = User::select('users.*');

            if ($searchName != null && $searchName != '') {
                $customers = $customers->where('users.name', 'LIKE', '%' . $searchName . '%');
            }

            $customers = $customers->latest()->paginate(5);

            return $customers;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Create new account 
     * @param Request $request
     * @return true
     */
    public function createAccount($request)
    {
        try {
            $userCurrent = Auth::user();
            if ($userCurrent->role == UserRole::ADMIN) {
                User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'role' => $request->role,
                ]);
                return response()->json(['success' => 'Tạo mới tài khoản thành công']);
            } else {
                return response()->json(['error' => 'Bạn không có quyền tạo mới tài khoản']);
            }
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Update account to admin role
     * @param Request $request
     * @return true
     */
    public function updateAccountToAdmin($request)
    {
        try {
            $userCurrent = Auth::user();
            if ($userCurrent->role == UserRole::ADMIN) {
                $id = $request->accountId;

                $user = User::findOrFail($id);

                $user->update([
                    'role' => UserRole::ADMIN,
                ]);

                return response()->json(['success' => 'Nâng cấp tài khoản lên vai trò admin thành công']);
            } else {
                return response()->json(['error' => 'Bạn không có quyền nâng cấp tài khoản']);
            }
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }


    /**
     * Delete customer
     * @param number $id id of customer
     * @return true
     */
    public function deleteCustomer($id)
    {
        try {
            $customer = User::findOrFail($id);
            $customer->delete();

            return true;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }
}
