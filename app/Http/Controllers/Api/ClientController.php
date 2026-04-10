<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\ClientService;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    /**
     * GET /api/clients
     */
    public function index()
    {
        $clients = Client::with('services')->latest()->get();

        return response()->json([
            'status' => true,
            'data' => $clients
        ], 200);
    }

    /**
     * POST /api/clients
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients',
            'phone' => 'required|string',
            'address' => 'required|string',
            'country' => 'required|string|max:100',
            'state' => 'required|string|max:100',

            // ✅ IMPORTANT (MATCH YOUR UI BUTTONS)
            'business_type' => 'required|in:product,project,service,both',

            'product_id' => 'nullable|array',
            'project_id' => 'nullable|array',
            'service_id' => 'nullable|array',

            'project_start_date' => 'nullable|date',
            'project_end_date' => 'nullable|date',

            'contact_person1_name' => 'required|string|max:255',
            'contact_person1_phone' => 'required|string|max:20',
            'contact_person2_name' => 'nullable|string|max:255',
            'contact_person2_phone' => 'nullable|string|max:20',

            'remarks' => 'nullable|string',
            'attachment' => 'nullable|file|max:2048',

            // ✅ SERVICES ARRAY
            'services' => 'nullable|array',
            'services.*.id' => 'required|exists:services,id',
            'services.*.start_date' => 'nullable|date',
            'services.*.end_date' => 'nullable|date',
        ]);

        // ✅ STORE FILE
        $data = $request->except(['attachment', 'services']);

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('clients', 'public');
        }

        // ✅ CREATE CLIENT
        $client = Client::create($data);

        // ✅ STORE MULTIPLE SERVICES
        if ($request->has('services')) {
            foreach ($request->services as $service) {
                ClientService::create([
                    'client_id' => $client->id,
                    'service_id' => $service['id'],
                    'start_date' => $service['start_date'] ?? null,
                    'end_date' => $service['end_date'] ?? null,
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Client created successfully',
            'data' => $client
        ], 201);
    }

    /**
     * GET /api/clients/{id}
     */
    public function show($id)
    {
        $client = Client::with('services')->find($id);

        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => 'Client not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $client
        ], 200);
    }

    /**
     * PUT /api/clients/{id}
     */
    public function update(Request $request, $id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => 'Client not found'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:clients,email,$id",
            'phone' => 'required|string',
            'address' => 'required|string',
            'country' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'business_type' => 'required|in:product,project,service,both',

            'product_id' => 'nullable|array',
            'project_id' => 'nullable|array',
            'service_id' => 'nullable|array',

            'contact_person1_name' => 'required|string|max:255',
            'contact_person1_phone' => 'required|string|max:20',
            'contact_person2_name' => 'nullable|string|max:255',
            'contact_person2_phone' => 'nullable|string|max:20',

            'remarks' => 'nullable|string',
            'attachment' => 'nullable|file|max:2048',

            'services' => 'nullable|array',
        ]);

        $data = $request->except(['attachment', 'services']);

        // ✅ FILE UPDATE
        if ($request->hasFile('attachment')) {
            if ($client->attachment && Storage::disk('public')->exists($client->attachment)) {
                Storage::disk('public')->delete($client->attachment);
            }

            $data['attachment'] = $request->file('attachment')->store('clients', 'public');
        }

        $client->update($data);

        // ✅ UPDATE SERVICES (DELETE + INSERT)
        if ($request->has('services')) {
            ClientService::where('client_id', $client->id)->delete();

            foreach ($request->services as $service) {
                ClientService::create([
                    'client_id' => $client->id,
                    'service_id' => $service['id'],
                    'start_date' => $service['start_date'] ?? null,
                    'end_date' => $service['end_date'] ?? null,
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Client updated successfully',
            'data' => $client
        ], 200);
    }

    /**
     * DELETE /api/clients/{id}
     */
    public function destroy($id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => 'Client not found'
            ], 404);
        }

        // ✅ DELETE FILE
        if ($client->attachment && Storage::disk('public')->exists($client->attachment)) {
            Storage::disk('public')->delete($client->attachment);
        }

        // ✅ DELETE SERVICES
        ClientService::where('client_id', $client->id)->delete();

        $client->delete();

        return response()->json([
            'status' => true,
            'message' => 'Client deleted successfully'
        ], 200);
    }
}