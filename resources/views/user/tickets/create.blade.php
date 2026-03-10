@extends('layouts.backend.master')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto; border-radius: 1rem; border: none; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);">
    <div class="card-header" style="background: white; padding: 1.5rem 2rem; border-bottom: 1px solid #f1f5f9;">
        @if ($errors->any())
            <div style="background: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem;">
                <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.875rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div style="background: #dcfce7; border: 1px solid #22c55e; color: #166534; padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; font-size: 0.875rem; font-weight: 600;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <h3 style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #1e293b;">
            <i class="fas fa-plus-circle" style="color: var(--primary-color); margin-right: 0.5rem;"></i> Raise New Ticket
        </h3>
        <p style="margin: 0.25rem 0 0 0; color: #64748b; font-size: 0.875rem;">Describe your issue and we'll get back to you as soon as possible.</p>
    </div>

    <div class="card-body" style="padding: 2rem;">
        <form action="{{ route('user.tickets.store') }}" method="POST" enctype="multipart/form-data" id="ticketForm">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; color: #334155; margin-bottom: 0.5rem; display: block;">Product</label>
                    <select name="product_id" class="form-control" style="height: 50px; border-radius: 0.75rem;">
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; color: #334155; margin-bottom: 0.5rem; display: block;">Project</label>
                    <select name="project_id" class="form-control" style="height: 50px; border-radius: 0.75rem;">
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; color: #334155; margin-bottom: 0.5rem; display: block;">Service</label>
                    <select name="service_id" class="form-control" style="height: 50px; border-radius: 0.75rem;">
                        <option value="">Select Service</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; color: #334155; margin-bottom: 0.5rem; display: block;">Priority <span style="color:red;">*</span></label>
                    <select name="priority" class="form-control" required style="height: 50px; border-radius: 0.75rem;">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label" style="font-weight: 700; color: #334155; margin-bottom: 0.5rem; display: block;">Subject <span style="color:red;">*</span></label>
                <input type="text" name="subject" class="form-control" required style="height: 50px; border-radius: 0.75rem;" placeholder="Brief summary of the issue">
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label" style="font-weight: 700; color: #334155; margin-bottom: 0.5rem; display: block;">Description <span style="color:red;">*</span></label>
                <textarea name="description" id="description" class="form-control" rows="5" required style="border-radius: 0.75rem;" placeholder="Detailed explanation..."></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label class="form-label" style="font-weight: 700; color: #334155; margin-bottom: 0.5rem; display: block;">Attachment</label>
                <div style="border: 2px dashed #e2e8f0; padding: 1.5rem; border-radius: 0.75rem; text-align: center; background: #f8fafc;">
                    <input type="file" name="attachment" class="form-control" style="background: transparent; border: none; padding: 0;">
                    <p style="margin: 0.5rem 0 0 0; color: #64748b; font-size: 0.75rem;">Accepted: JPG, PNG, PDF, DOCX (Max 20MB)</p>
                </div>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="padding: 1rem 2rem; border-radius: 0.75rem; font-weight: 800; flex: 1; height: auto; align-items:center; display: flex; justify-content: center;">
                    Submit Ticket
                </button>
                <a href="{{ route('user.tickets') }}" class="btn" style="padding: 1rem 2rem; border-radius: 0.75rem; font-weight: 700; background: #f1f5f9; color: #475569; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>


@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    const descriptionEditor = CKEDITOR.replace('description');
    
    // Sync CKEditor data to textarea before form submission
    document.getElementById('ticketForm').addEventListener('submit', function() {
        if (descriptionEditor) {
            descriptionEditor.updateElement();
        }
    });
</script>
@endsection
