<?php

namespace App\Http\Controllers\Api\Admin\V1\CategoryManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('stories')->get(); 
        return CategoryResource::collection($categories);
    }

    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return new CategoryResource($category->loadCount('stories'));
    }

    public function show(Category $category)
    {
        return new CategoryResource($category->loadCount('stories'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return new CategoryResource($category->loadCount('stories'));
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->noContent();
    }
}
