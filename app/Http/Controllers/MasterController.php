<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\Project;
use App\Models\TicketStatus;
use App\Models\TicketPriority;
use App\Models\Service;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    // Clients
    public function clients()
    {
        $clients = Client::latest()->paginate(10);
        $products = Product::where('status', true)->get();
        $projects = Project::where('status', true)->get();
        $services = Service::where('status', true)->get();
        return view('admin.masters.clients', compact('clients', 'products', 'projects', 'services'));
    }

    public function storeClient(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients',
            'phone' => 'required|string',
            'address' => 'required|string',
            'business_type' => 'required|string|in:product,project,service,both',
            'product_id' => 'nullable|array',
            'project_id' => 'nullable|array',
            'service_id' => 'nullable|array',
            'project_start_date' => 'nullable|date',
            'project_end_date' => 'nullable|date',
            'contact_person1_name' => 'required|string|max:255',
            'contact_person1_phone' => 'required|string|max:20',
            'contact_person2_name' => 'nullable|string|max:255',
            'contact_person2_phone' => 'nullable|string|max:20',
            'project_description' => 'nullable|string',
            'remarks' => 'nullable|string',
            'attachment' => 'nullable|file|max:2048',
        ]);

        $data = $request->except('attachment');
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('clients', 'public');
        }

        Client::create($data);
        return back()->with('success', 'Client added successfully.');
    }

    public function editClient(Client $client)
    {
        return response()->json($client);
    }

    public function updateClient(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone' => 'required|string',
            'address' => 'required|string',
            'business_type' => 'required|string|in:product,project,service,both',
            'product_id' => 'nullable|array',
            'project_id' => 'nullable|array',
            'service_id' => 'nullable|array',
            'project_start_date' => 'nullable|date',
            'project_end_date' => 'nullable|date',
            'contact_person1_name' => 'required|string|max:255',
            'contact_person1_phone' => 'required|string|max:20',
            'contact_person2_name' => 'nullable|string|max:255',
            'contact_person2_phone' => 'nullable|string|max:20',
            'project_description' => 'nullable|string',
            'remarks' => 'nullable|string',
            'attachment' => 'nullable|file|max:2048',
        ]);

        $data = $request->except('attachment');
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('clients', 'public');
        }

        $client->update($data);
        return back()->with('success', 'Client updated successfully.');
    }

    public function destroyClient(Client $client)
    {
        $client->delete();
        return back()->with('success', 'Client deleted successfully.');
    }

    // Categories
    public function categories()
    {
        $categories = ProductCategory::latest()->paginate(10);
        return view('admin.masters.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:product_categories']);
        ProductCategory::create($request->all());
        return back()->with('success', 'Category added successfully.');
    }

    public function editCategory(ProductCategory $category)
    {
        return response()->json($category);
    }

    public function updateCategory(Request $request, ProductCategory $category)
    {
        $request->validate(['name' => 'required|string|unique:product_categories,name,' . $category->id]);
        $category->update($request->all());
        return back()->with('success', 'Category updated successfully.');
    }

    public function destroyCategory(ProductCategory $category)
    {
        // Check if there are related products or subcategories before deleting (optional but good)
        if ($category->subCategories()->count() > 0) {
            return back()->with('error', 'Cannot delete category with related subcategories.');
        }
        $category->delete();
        return back()->with('success', 'Category deleted successfully.');
    }

    // Subcategories
    public function subCategories()
    {
        $categories = ProductCategory::all();
        $subcategories = ProductSubCategory::with('category')->latest()->paginate(10);
        return view('admin.masters.subcategories', compact('subcategories', 'categories'));
    }

    public function storeSubCategory(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string'
        ]);
        ProductSubCategory::create($request->all());
        return back()->with('success', 'Subcategory added successfully.');
    }

    public function editSubCategory(ProductSubCategory $subcategory)
    {
        return response()->json($subcategory);
    }

    public function updateSubCategory(Request $request, ProductSubCategory $subcategory)
    {
        $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string'
        ]);

        $subcategory->update($request->all());
        return back()->with('success', 'Subcategory updated successfully.');
    }

    public function destroySubCategory(ProductSubCategory $subcategory)
    {
        // Check for related products
        if (\App\Models\Product::where('sub_category_id', $subcategory->id)->exists()) {
            return back()->with('error', 'Cannot delete subcategory with related products.');
        }
        $subcategory->delete();
        return back()->with('success', 'Subcategory deleted successfully.');
    }

    // Products
    public function products()
    {
        $categories = ProductCategory::all();
        $subcategories = ProductSubCategory::all();
        $products = Product::with(['category', 'subCategory'])->latest()->paginate(10);
        return view('admin.masters.products', compact('products', 'categories', 'subcategories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'sub_category_id' => 'required|exists:product_sub_categories,id',
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);
        Product::create($request->all());
        return back()->with('success', 'Product added successfully.');
    }

    public function editProduct(Product $product)
    {
        return response()->json($product);
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'sub_category_id' => 'required|exists:product_sub_categories,id',
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $product->update($request->all());
        return back()->with('success', 'Product updated successfully.');
    }

    public function destroyProduct(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted successfully.');
    }

    // Services
    public function services()
    {
        $services = Service::with(['category', 'subcategory'])->latest()->paginate(10);
        $categories = ProductCategory::where('status', true)->get();
        return view('admin.masters.services', compact('services', 'categories'));
    }

    public function storeService(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:services',
            'category_id' => 'nullable|exists:product_categories,id',
            'subcategory_id' => 'nullable|exists:product_sub_categories,id',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Service::create($request->all());
        return back()->with('success', 'Service created successfully.');
    }

    public function editService(Service $service)
    {
        return response()->json($service);
    }

    public function updateService(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|unique:services,name,' . $service->id,
            'category_id' => 'nullable|exists:product_categories,id',
            'subcategory_id' => 'nullable|exists:product_sub_categories,id',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $service->update($request->all());
        return back()->with('success', 'Service updated successfully.');
    }

    public function destroyService(Service $service)
    {
        $service->delete();
        return back()->with('success', 'Service deleted successfully.');
    }

    public function getSubCategories(ProductCategory $category)
    {
        return response()->json($category->subCategories()->get());
    }

    // Projects
    public function projects()
    {
        $projects = Project::with(['category', 'subcategory', 'projectStatus', 'priority'])->latest()->paginate(10);
        $categories = ProductCategory::where('status', true)->get();
        $statuses = TicketStatus::where('status', true)->get();
        $priorities = TicketPriority::where('status', true)->get();
        return view('admin.masters.projects', compact('projects', 'categories', 'statuses', 'priorities'));
    }

    public function storeProject(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:projects',
            'category_id' => 'nullable|exists:product_categories,id',
            'subcategory_id' => 'nullable|exists:product_sub_categories,id',
            'status_id' => 'nullable|exists:ticket_statuses,id',
            'priority_id' => 'nullable|exists:ticket_priorities,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|max:2048',
            'remarks' => 'nullable|string',
        ]);

        $data = $request->except('attachment');
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('projects', 'public');
        }
        $data['status'] = true;

        Project::create($data);
        return back()->with('success', 'Project created successfully.');
    }

    public function editProject(Project $project)
    {
        return response()->json($project);
    }

    public function updateProject(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:projects,name,' . $project->id,
            'category_id' => 'nullable|exists:product_categories,id',
            'subcategory_id' => 'nullable|exists:product_sub_categories,id',
            'status_id' => 'nullable|exists:ticket_statuses,id',
            'priority_id' => 'nullable|exists:ticket_priorities,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|max:2048',
            'remarks' => 'nullable|string',
        ]);

        $data = $request->except('attachment');
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('projects', 'public');
        }
        $data['status'] = $request->status;

        $project->update($data);
        return back()->with('success', 'Project updated successfully.');
    }

    public function destroyProject(Project $project)
    {
        $project->delete();
        return back()->with('success', 'Project deleted successfully.');
    }

    // Ticket Statuses
    public function ticketStatuses()
    {
        $statuses = TicketStatus::latest()->paginate(10);
        return view('admin.masters.ticket_statuses', compact('statuses'));
    }

    public function storeTicketStatus(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:ticket_statuses',
            'color' => 'required|string|max:7'
        ]);
        TicketStatus::create($request->all());
        return back()->with('success', 'Ticket Status added successfully.');
    }

    public function editTicketStatus(TicketStatus $status)
    {
        return response()->json($status);
    }

    public function updateTicketStatus(Request $request, TicketStatus $status)
    {
        $request->validate([
            'name' => 'required|string|unique:ticket_statuses,name,' . $status->id,
            'color' => 'required|string|max:7'
        ]);
        $status->update($request->all());
        return back()->with('success', 'Ticket Status updated successfully.');
    }

    public function destroyTicketStatus(TicketStatus $status)
    {
        $status->delete();
        return back()->with('success', 'Ticket Status deleted successfully.');
    }

    // Ticket Priorities
    public function ticketPriorities()
    {
        $priorities = TicketPriority::latest()->paginate(10);
        return view('admin.masters.ticket_priorities', compact('priorities'));
    }

    public function storeTicketPriority(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:ticket_priorities',
            'color' => 'required|string|max:7'
        ]);
        TicketPriority::create($request->all());
        return back()->with('success', 'Ticket Priority added successfully.');
    }

    public function editTicketPriority(TicketPriority $priority)
    {
        return response()->json($priority);
    }

    public function updateTicketPriority(Request $request, TicketPriority $priority)
    {
        $request->validate([
            'name' => 'required|string|unique:ticket_priorities,name,' . $priority->id,
            'color' => 'required|string|max:7'
        ]);
        $priority->update($request->all());
        return back()->with('success', 'Ticket Priority updated successfully.');
    }

    public function destroyTicketPriority(TicketPriority $priority)
    {
        $priority->delete();
        return back()->with('success', 'Ticket Priority deleted successfully.');
    }
}
