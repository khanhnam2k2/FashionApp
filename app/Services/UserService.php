<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use Exception;
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
                $customers->where('users.name', 'LIKE', '%' . $searchName . '%');
            }

            $customers = $customers->where('role', UserRole::USER)->latest()->paginate(5);

            return $customers;
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
