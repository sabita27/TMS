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
use App\Models\Role;
use App\Models\Designation;
use App\Models\Position;
use App\Models\Setting;
use App\Models\ClientService;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
use Yajra\DataTables\Facades\DataTables;

class MasterController extends Controller
{
    // Setup (Unified View)
    public function setup(Request $request)
    {
        $type = $request->query('type', 'category');
        $section = $request->query('section', 'identity');

        // Redirect role type to dedicated role management
        if ($type == 'role') {
            return redirect()->route('admin.roles');
        }

        $data = [];

        switch ($type) {
            case 'global':
                $data['settings'] = Setting::pluck('value', 'key')->toArray();
                break;
            case 'designation':
                $data['designations'] = Designation::latest()->get();
                break;
            case 'position':
                $data['positions'] = Position::latest()->get();
                break;
            case 'category':
                $data['categories'] = ProductCategory::with('subCategories')->latest()->get();
                break;
            case 'subcategory':
                $data['subcategories'] = ProductSubCategory::with('category')->latest()->get();
                $data['categories'] = ProductCategory::all();
                break;
            case 'status':
                $data['statuses'] = TicketStatus::latest()->get();
                break;
            case 'priority':
                $data['priorities'] = TicketPriority::latest()->get();
                break;
        }

        return view('admin.masters.setup', compact('type', 'data', 'section'));
    }

    // Clients
    public function clients(Request $request)
    {
        if ($request->ajax()) {
            $clients = Client::with('services.service')->latest();
            
            // Handle filters
            if ($request->filled('product_id')) {
                $clients->whereJsonContains('product_id', (string)$request->product_id);
            }
            if ($request->filled('service_id')) {
                $clients->whereHas('services', function($q) use ($request){
                    $q->where('service_id', $request->service_id);
                });
            }

            // Get products and projects for mapping (ID => Name)
            $allProducts = Product::pluck('name', 'id')->toArray();
            $allProjects = Project::pluck('name', 'id')->toArray();

            return DataTables::of($clients)
                ->addIndexColumn()
                ->editColumn('name', function($row){
                    return '<div><div style="font-weight: 600; color: #1e293b;">'.e($row->name).'</div>
                            <div style="font-size: 0.75rem; color: #64748b;">'.e($row->state).($row->country ? ', '.e($row->country) : '').'</div></div>';
                })
                ->addColumn('contact_person', function($row){
                    return '<div><div style="font-weight: 500;">'.e($row->contact_person1_name ?? '-').'</div>
                            <div style="font-size: 0.75rem; color: #64748b;">'.e($row->contact_person1_phone ?? '-').'</div></div>';
                })
                ->addColumn('email_phone', function($row){
                    return '<div><div style="font-weight: 500;">'.e($row->email).'</div>
                            <div style="font-size: 0.75rem; color: #64748b;">'.e($row->phone ?? '-').'</div></div>';
                })
                ->addColumn('products_list', function($row) use ($allProducts){
                    $ids = $row->product_id ?? [];
                    $names = [];
                    foreach($ids as $id) {
                        if(isset($allProducts[$id])) $names[] = $allProducts[$id];
                    }
                    if (!empty($names)) {
                        $html = '<div style="display: flex; flex-wrap: wrap; gap: 0.25rem;">';
                        foreach ($names as $name) {
                            $html .= '<span class="badge" style="background: #eff6ff; color: #1d4ed8; font-weight: 600;">'.e($name).'</span>';
                        }
                        $html .= '</div>';
                        return $html;
                    }
                    return '<span style="color: #94a3b8; font-style: italic;">-</span>';
                })
                ->addColumn('projects_list', function($row) use ($allProjects){
                    $ids = $row->project_id ?? [];
                    $names = [];
                    foreach($ids as $id) {
                        if(isset($allProjects[$id])) $names[] = $allProjects[$id];
                    }
                    if (!empty($names)) {
                        $html = '<div style="display: flex; flex-wrap: wrap; gap: 0.25rem;">';
                        foreach ($names as $name) {
                            $html .= '<span class="badge" style="background: #f0fdf4; color: #15803d; font-weight: 600;">'.e($name).'</span>';
                        }
                        $html .= '</div>';
                        return $html;
                    }
                    return '<span style="color: #94a3b8; font-style: italic;">-</span>';
                })
                ->addColumn('services_list', function($row){
                    if ($row->services->isNotEmpty()) {
                        $html = '<div style="display: flex; flex-direction: column; gap: 0.25rem;">';
                        foreach ($row->services as $clientService) {
                            if ($clientService->service) {
                                $dateStr = '';
                                if ($clientService->start_date) {
                                    $dateStr = '<div style="font-size: 0.7rem; color: #701a75;">'
                                        . $clientService->start_date->format('d M Y') . ' - '
                                        . ($clientService->end_date ? $clientService->end_date->format('d M Y') : 'N/A')
                                        . '</div>';
                                }
                                $html .= '<div style="font-size: 0.8rem; background: #fdf4ff; color: #a21caf; padding: 0.2rem 0.5rem; border-radius: 4px; border: 1px solid #f5d0fe;">
                                            <strong>'.e($clientService->service->name).'</strong>' . $dateStr . '</div>';
                            }
                        }
                        $html .= '</div>';
                        return $html;
                    }
                    return '<span style="color: #94a3b8; font-style: italic;">-</span>';
                })
                ->editColumn('status', function($row){
                    return '<span class="badge '.($row->status ? 'badge-success' : 'badge-danger').'">'.($row->status ? 'Active' : 'Inactive').'</span>';
                })
                ->addColumn('action', function($row){
                    $btn = '<div style="display: flex; gap: 0.5rem;">';
                    $btn .= '<button onclick="viewClient('.$row->id.')" class="btn" style="padding: 0.4rem 0.7rem; font-size: 0.75rem; background: #10b981; color: white;"><i class="fas fa-eye"></i></button>';
                    $btn .= '<button onclick="editClient('.$row->id.')" class="btn btn-primary" style="padding: 0.4rem 0.7rem; font-size: 0.75rem;"><i class="fas fa-edit"></i></button>';
                    
                    if(auth()->user()->hasRole('admin')){
                        $btn .= '<form action="'.route('admin.clients.delete', $row->id).'" method="POST" onsubmit="return confirm(\'Are you sure you want to delete this client?\')" style="display:inline;">'.csrf_field().method_field('DELETE').'<button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.7rem; font-size: 0.75rem;"><i class="fas fa-trash"></i></button></form>';
                    }
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['name', 'contact_person', 'email_phone', 'products_list', 'projects_list', 'services_list', 'status', 'action'])
                ->make(true);
        }

        $products = Product::where('status', true)->get();
        $projects = Project::where('status', true)->get();
        $services = Service::where('status', true)->get();
        return view('admin.masters.clients', compact('products', 'projects', 'services'));
    }

    public function storeClient(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients',
            'phone' => 'required|string',
            'address' => 'required|string',
            'country' => 'required|string|max:100',
            'state' => 'required|string|max:100',
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

        $data = $request->except(['attachment', 'services']);
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('clients', 'public');
        }

        $client = Client::create($data);

        if ($request->has('services')) {
            foreach ($request->services as $service) {
                if (!empty($service['id'])) {
                    ClientService::create([
                        'client_id' => $client->id,
                        'service_id' => $service['id'],
                        'start_date' => $service['start_date'],
                        'end_date' => $service['end_date'],
                    ]);
                }
            }
        }

        return back()->with('success', 'Client added successfully.');
    }

    public function editClient(Client $client)
    {
        return response()->json($client->load('services.service'));
    }

    public function updateClient(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone' => 'required|string',
            'address' => 'required|string',
            'country' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'business_type' => 'required|string|in:product,project,service,both',
            'product_id' => 'nullable|array',
            'project_id' => 'nullable|array',
            'contact_person1_name' => 'required|string|max:255',
            'contact_person1_phone' => 'required|string|max:20',
            'contact_person2_name' => 'nullable|string|max:255',
            'contact_person2_phone' => 'nullable|string|max:20',
            'project_description' => 'nullable|string',
            'remarks' => 'nullable|string',
            'attachment' => 'nullable|file|max:2048',
        ]);

        $data = $request->except(['attachment', 'services']);
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('clients', 'public');
        }

        $client->update($data);

        // Update services
        $client->services()->delete();
        if ($request->has('services')) {
            foreach ($request->services as $service) {
                if (!empty($service['id'])) {
                    ClientService::create([
                        'client_id' => $client->id,
                        'service_id' => $service['id'],
                        'start_date' => $service['start_date'],
                        'end_date' => $service['end_date'],
                    ]);
                }
            }
        }

        return back()->with('success', 'Client updated successfully.');
    }

    public function destroyClient(Client $client)
    {
        $client->delete();
        return back()->with('success', 'Client deleted successfully.');
    }

    public function sendReminder(ClientService $clientService)
    {
        try {
            // Load relationships
            $clientService->load('client', 'service');

            if (!$clientService->client || !$clientService->client->email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client email address not found. Please update this client with a valid email first.'
                ], 422);
            }

            // Load SMTP settings from DB
            $settings = Setting::whereIn('key', [
                'mail_host', 'mail_port', 'mail_encryption',
                'mail_username', 'mail_password', 'mail_from_address', 'mail_from_name'
            ])->pluck('value', 'key')->toArray();

            // Resolve values — default to Gmail if host not saved
            $smtpHost       = !empty($settings['mail_host'])         ? trim($settings['mail_host'])         : 'smtp.gmail.com';
            $smtpPort       = !empty($settings['mail_port'])         ? (int) $settings['mail_port']         : 587;
            $smtpEncryption = !empty($settings['mail_encryption'])   ? strtolower(trim($settings['mail_encryption'])) : 'tls';
            $smtpPassword   = $settings['mail_password']  ?? null;
            $fromAddress    = !empty($settings['mail_from_address']) ? trim($settings['mail_from_address']) : null;
            $fromName       = !empty($settings['mail_from_name'])    ? trim($settings['mail_from_name'])    : config('app.name');
            // For Gmail: auth username must be the sender email address
            $smtpUsername   = $fromAddress ?? (!empty($settings['mail_username']) ? trim($settings['mail_username']) : null);

            if (empty($smtpUsername) || empty($smtpPassword)) {
                return response()->json([
                    'success' => false,
                    'message' => 'SMTP not configured. Please set credentials in Setup Hub → SMTP Setting.'
                ], 422);
            }

            // Render the Blade email template to HTML string
            $html = view('emails.service_reminder', [
                'clientService' => $clientService
            ])->render();

            // Build PHPMailer instance
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = $smtpHost;
            $mail->Port       = $smtpPort;
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtpUsername;
            $mail->Password   = $smtpPassword;

            // Encryption
            if ($smtpEncryption === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            // Sender & Recipient
            $mail->setFrom($fromAddress ?? $smtpUsername, $fromName);
            $mail->addAddress($clientService->client->email, $clientService->client->name);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Service Renewal Reminder: ' . ($clientService->service->name ?? 'Your Service');
            $mail->Body    = $html;
            $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>'], "\n", $html));

            $mail->send();

            return response()->json([
                'success' => true,
                'message' => 'Reminder sent successfully to ' . $clientService->client->email
            ]);

        } catch (PHPMailerException $e) {
            \Log::error('PHPMailer Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Mail error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            \Log::error('Reminder Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
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

    // Global Settings
    public function updateSettings(Request $request)
    {
        $data = $request->except('_token', 'logo', 'favicon');

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('branding', 'public');
            Setting::updateOrCreate(['key' => 'system_logo'], ['value' => $path]);
        }

        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('branding', 'public');
            Setting::updateOrCreate(['key' => 'system_favicon'], ['value' => $path]);
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
