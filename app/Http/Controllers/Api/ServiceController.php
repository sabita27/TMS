<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * GET /api/services
     */
    public function index()
    {
        $services = Service::with(['category', 'subcategory'])->latest()->get();

        return response()->json([
            'status' => true,
            'data'   => $services
        ]);
    }

    /**
     * POST /api/services
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:255|unique:services',
            'category_id'    => 'nullable|exists:product_categories,id',
            'subcategory_id' => 'nullable|exists:product_sub_categories,id',
            'price'          => 'nullable|numeric|min:0',
            'description'    => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()
            ], 422);
        }

        $service = Service::create($request->all());

        return response()->json([
            'status'  => true,
            'message' => 'Service created successfully',
            'data'    => $service
        ]);
    }

    /**
     * GET /api/services/{id}
     */
    public function show($id)
    {
        $service = Service::with(['category', 'subcategory'])->find($id);

        if (!$service) {
            return response()->json([
                'status'  => false,
                'message' => 'Service not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => $service
        ]);
    }

    /**
     * POST /api/services/{id}
     */
    public function update(Request $request, $id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'status'  => false,
                'message' => 'Service not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'           => 'nullable|string|max:255|unique:services,name,' . $id,
            'category_id'    => 'nullable|exists:product_categories,id',
            'subcategory_id' => 'nullable|exists:product_sub_categories,id',
            'price'          => 'nullable|numeric|min:0',
            'description'    => 'nullable|string',
            'status'         => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()
            ], 422);
        }

        $service->update($request->all());

        return response()->json([
            'status'  => true,
            'message' => 'Service updated successfully',
            'data'    => $service
        ]);
    }

    /**
     * DELETE /api/services/{id}
     */
    public function destroy($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'status'  => false,
                'message' => 'Service not found'
            ], 404);
        }

        $service->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Service deleted successfully'
        ]);
    }
}