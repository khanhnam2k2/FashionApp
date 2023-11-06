<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    /**
     * This is the constructor declaration.
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Show category page admin
     * @return view category list management page
     */
    public function index()
    {
        return view('admin.category.index');
    }

    /**
     * Show category table admin
     * @param Request $request
     * @return view category table
     */
    public function search(Request $request)
    {
        $data = $this->categoryService->searchCategory($request->searchName);
        return view('admin.category.table', ['data' => $data]);
    }

    /**
     * Create new category 
     * @param StoreCategoryRequest $request 
     * @return response ok
     */
    public function create(StoreCategoryRequest $request)
    {
        $this->categoryService->createCategory($request);
        return response()->json('ok');
    }

    /**
     * Update category 
     * @param StoreCategoryRequest $request 
     * @return response ok
     */
    public function update(StoreCategoryRequest $request)
    {
        $this->categoryService->updateCategory($request);
        return response()->json('ok');
    }

    /**
     * Delete category 
     * @param number $id id of category 
     * @return response ok
     */
    public function delete($id)
    {
        $this->categoryService->deleteCategory($id);
        return response()->json('ok');
    }
}
