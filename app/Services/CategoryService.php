<?php

namespace App\Services;

use App\Enums\Status;
use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\Log;

class CategoryService
{
    public function getCategories()
    {
        try {
            $categories = Category::where('status', Status::ON)->orderBy('id', 'asc')->get();
            return $categories;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }
    public function getLimitCategories()
    {
        try {
            $categories = Category::where('status', Status::ON)->orderBy('id', 'asc')->take(3)->get();
            return $categories;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }
    public function searchCategory($searchName)
    {
        try {
            $categories = Category::select('categories.*');
            if ($searchName != null && $searchName != '') {
                $categories->where('categories.name', 'LIKE', '%' . $searchName . '%');
            }
            $categories = $categories->latest()->paginate(6);
            return $categories;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function createCategory($request)
    {
        try {
            $category = [
                'name' => $request->name,
                'status' => $request->status,
            ];
            $data = Category::create($category);
            return $data;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function updateCategory($request)
    {
        try {
            $data = Category::findOrFail($request->categoryId);
            $category = [
                'name' => $request->name,
                'status' => $request->status,
            ];
            $data = $data->update($category);
            return $data;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function deleteCategory($id)
    {
        try {
            $data = Category::where('id', $id)->delete();
            return $data;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }
}
