<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    public function index()
    {
        return view('admin.category.index');
    }

    public function search()
    {
        $data = $this->categoryService->searchCategory();
        return view('admin.category.table', ['data' => $data]);
    }

    public function create(StoreCategoryRequest $request)
    {
        $this->categoryService->createCategory($request);
        return response()->json('ok');
    }

    public function update(StoreCategoryRequest $request)
    {
        $this->categoryService->updateCategory($request);
        return response()->json('ok');
    }

    public function delete($id)
    {
        $this->categoryService->deleteCategory($id);
        return response()->json('ok');
    }
}
