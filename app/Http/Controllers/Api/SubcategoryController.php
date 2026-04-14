<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductSubCategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    // 📌 List all subcategories
    public function index()
    {
        $subcategories = ProductSubCategory::with('category')->latest()->get();

        return response()->json([
            'status' => true,
            'data'   => $subcategories,
        ]);
    }

    // 📌 Store new subcategory
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'name'        => 'required|string|max:255',
        ]);

        $subcategory = ProductSubCategory::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Subcategory created successfully',
            'data'    => $subcategory,
        ]);
    }

    // 📌 Show single subcategory (by ID)
    public function show($id)
    {
        $subcategory = ProductSubCategory::with('category')->find($id);

        if (! $subcategory) {
            return response()->json([
                'status'  => false,
                'message' => 'Subcategory not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => $subcategory,
        ]);
    }

    // 📌 Update subcategory
    public function update(Request $request, $id)
    {
        $subcategory = ProductSubCategory::find($id);

        if (! $subcategory) {
            return response()->json([
                'status'  => false,
                'message' => 'Subcategory not found',
            ], 404);
        }

        $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'name'        => 'required|string|max:255',
        ]);

        $subcategory->update([
            'category_id' => $request->category_id,
            'name'        => $request->name,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Subcategory updated successfully',
            'data'    => $subcategory,
        ]);
    }

    // 📌 Delete subcategory
    public function destroy($id)
    {
        $subcategory = ProductSubCategory::find($id);

        if (! $subcategory) {
            return response()->json([
                'status'  => false,
                'message' => 'Subcategory not found',
            ], 404);
        }

        $subcategory->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Subcategory deleted successfully',
        ]);
    }

    // 📌 Get subcategories by category_id (IMPORTANT 🔥)
    public function getByCategory($category_id)
    {
        $subcategories = ProductSubCategory::where('category_id', $category_id)
            ->with('category')
            ->get();

        return response()->json([
            'status' => true,
            'data'   => $subcategories,
        ]);
    }
}
