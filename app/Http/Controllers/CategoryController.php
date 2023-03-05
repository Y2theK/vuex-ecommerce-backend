<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return CategoryResource::collection($categories);
    }
    public function getOnlyCategoriesName()
    {
        //get all categories but only name
        $categories = Category::pluck('name');
        return $categories;
    }
    public function store(CategoryRequest $request)
    {
        $validated_data = $request->validated();
        $category = Category::create($validated_data);
        return new CategoryResource($category);
    }

    
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    
    public function update(CategoryRequest $request, Category $category)
    {
        $validated_data = $request->validated();
        // return $validated_data;
        $category->update($validated_data);
        $category =  Category::find($category->id);
        return new CategoryResource($category);
    }

    
    public function destroy(Category $category)
    {
        // $category->products()->delete();  //delete related products

        if ($category->products()->count()) {
            return response()->json(['message' => 'Category cant delete since it has related products.'], 422);
        }
        
        $category->delete();
        return response()->json(['message' => 'Category deleted.'], 200);
    }
}
