<?php

namespace App\Services;

use App\Enums\Status;
use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\Log;

class CategoryService
{
    /**
     * Get category list with status on
     * @return Array category list
     */
    public function getCategories($limit = null)
    {
        try {
            $categories = Category::where('status', Status::ON)->orderBy('id', 'asc');;
            if($limit != null && $limit != ''){
                $categories =  $categories->take($limit)->get();
            }else{
                $categories =  $categories->get();
            }
            return $categories;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }


    /**
     * Get category list paginate
     * @param String $searchName keyword search
     * @return Array category list
     */
    public function searchCategory($searchName = null)
    {
        try {
            $categories = Category::select('categories.*');

            if ($searchName != null && $searchName != '') {
                $categories = $categories->where('categories.name', 'LIKE', '%' . $searchName . '%');
            }

            $categories = $categories->latest()->paginate(6);

            return $categories;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Create category
     * @param $request
     * @return true
     */
    public function createCategory($request)
    {
        try {
            $category = [
                'name' => $request->name,
                'status' => $request->statusCategory,
            ];

            Category::create($category);

            return true;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Update category
     * @param $request
     * @return true
     */
    public function updateCategory($request)
    {
        try {
            $data = Category::findOrFail($request->categoryId);

            $category = [
                'name' => $request->name,
                'status' => $request->statusCategory,
            ];

            $data->update($category);

            return true;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Update status category
     * @param $request
     * @return true
     */
    public function updateStatusCategory($request)
    {
        try {
            $category = Category::findOrFail($request->categoryId);

            $category->update([
                'status' => $request->status
            ]);

            return true;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Delete category
     * @param number $id id of category
     * @return true
     */
    public function deleteCategory($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return true;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }
}
