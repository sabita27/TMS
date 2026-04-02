<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * 📋 GET ALL PRODUCTS
     */
    public function index()
    {
        $products = Product::with(['category', 'subCategory'])->latest()->get();

        return response()->json([
            'status'  => true,
            'message' => 'Products fetched successfully',
            'data'    => $products,
        ]);
    }

    /**
     * ➕ CREATE PRODUCT
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|max:255',
            'category_id'     => 'required|exists:product_categories,id',
            'sub_category_id' => 'required|exists:product_sub_categories,id',
            'price'           => 'required|numeric',
            'status'          => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $product = Product::create($request->all());

        return response()->json([
            'status'  => true,
            'message' => 'Product created successfully',
            'data'    => $product,
        ]);
    }

    /**
     * 🔍 GET SINGLE PRODUCT
     */
    public function show($id)
    {
        $product = Product::with(['category', 'subCategory'])->find($id);

        if (!$product) {
            return response()->json([
                'status'  => false,
                'message' => 'Product not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => $product,
        ]);
    }

    /**
     * ✏️ UPDATE PRODUCT
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status'  => false,
                'message' => 'Product not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'            => 'nullable|string|max:255',
            'category_id'     => 'nullable|exists:product_categories,id',
            'sub_category_id' => 'nullable|exists:product_sub_categories,id',
            'price'           => 'nullable|numeric',
            'status'          => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $product->update($request->only([
            'name',
            'category_id',
            'sub_category_id',
            'price',
            'status'
        ]));

        return response()->json([
            'status'  => true,
            'message' => 'Product updated successfully',
            'data'    => $product,
        ]);
    }

    /**
     * ❌ DELETE PRODUCT
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status'  => false,
                'message' => 'Product not found',
            ], 404);
        }

        $product->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Product deleted successfully',
        ]);
    }
}