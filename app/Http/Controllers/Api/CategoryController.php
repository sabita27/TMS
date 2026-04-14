<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // 📌 Get all categories (with subcategories)
    public function index()
    {
        $categories = ProductCategory::with('subCategories')->latest()->get();

        return response()->json([
            'status' => true,
            'data' => $categories
        ]);
    }

    // 📌 Store category
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:product_categories,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $category = ProductCategory::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Category created successfully',
            'data' => $category
        ]);
    }

    // 📌 Show single category
    public function show($id)
    {
        $category = ProductCategory::with('subCategories')->find($id);

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $category
        ]);
    }

    // 📌 Update category
    public function update(Request $request, $id)
    {
        $category = ProductCategory::find($id);

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:product_categories,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        $category->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Category updated successfully',
            'data' => $category
        ]);
    }

    // 📌 Delete category
    public function destroy($id)
    {
        $category = ProductCategory::find($id);

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found'
            ], 404);
        }

        // 🔥 Same logic as your controller
        if ($category->subCategories()->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot delete category with related subcategories'
            ], 400);
        }

        $category->delete();

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);
    }
}