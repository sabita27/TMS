@extends('layouts.backend.master')

@section('page_title', 'Raise New Ticket')
@section('header_height', '85px')
@section('header_padding', '0 2.5rem')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div class="card" style="border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-radius: 1.25rem;">
        <div class="card-header" style="padding: 2rem; border-bottom: 2px solid #f8fafc;">
            <h3 class="card-title" style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">Create Support Request</h3>
            <p style="margin: 0.25rem 0 0 0; color: #64748b;">Please fill out the details below to initiate a support case.</p>
        </div>
        
        <form action="{{ route('user.tickets.store') }}" method="POST" enctype="multipart/form-data" style="padding: 2rem;">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; color: #1e293b;">Related Product (Optional)</label>
                    <select name="product_id" class="form-control" style="border-radius: 0.75rem; border: 1.5px solid #e2e8f0; height: 50px;">
                        <option value="">General Support</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; color: #1e293b;">Support Category</label>
                    <select name="category_id" id="category_id" class="form-control" required style="border-radius: 0.75rem; border: 1.5px solid #e2e8f0; height: 50px;">
                        <option value="" disabled selected>Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; color: #1e293b;">Sub Category</label>
                    <select name="sub_category_id" id="sub_category_id" class="form-control" required style="border-radius: 0.75rem; border: 1.5px solid #e2e8f0; height: 50px;">
                        <option value="" disabled selected>Select Sub Category</option>
                    </select>
                    <small id="subcat-loading" style="display: none; color: var(--primary-color); font-size: 0.75rem;"><i class="fas fa-spinner fa-spin"></i> Loading subcategories...</small>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label" style="font-weight: 700; color: #1e293b;">Subject</label>
                <input type="text" name="subject" class="form-control" placeholder="Briefly describe the issue" required style="border-radius: 0.75rem; border: 1.5px solid #e2e8f0; height: 50px; padding: 0 1rem;">
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label" style="font-weight: 700; color: #1e293b;">Problem Description</label>
                <textarea name="description" class="form-control" rows="5" placeholder="Please provide as much detail as possible to help us resolve the issue faster..." required style="border-radius: 0.75rem; border: 1.5px solid #e2e8f0; padding: 1rem;"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; color: #1e293b;">Priority Level</label>
                    <select name="priority" class="form-control" required style="border-radius: 0.75rem; border: 1.5px solid #e2e8f0; height: 50px;">
                        <option value="low">Low - Routine Issue</option>
                        <option value="medium" selected>Medium - Important</option>
                        <option value="high">High - Urgent Case</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; color: #1e293b;">Attachment (Optional)</label>
                    <div class="file-upload-wrapper">
                        <input type="file" name="attachment" id="attachment" class="file-input">
                        <label for="attachment" class="file-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span id="file-name">Choose a file...</span>
                        </label>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="padding: 1rem 2.5rem; border-radius: 0.75rem; font-weight: 800; font-size: 1rem; flex: 1; box-shadow: 0 10px 15px rgba(79, 70, 229, 0.2);">
                    <i class="fas fa-paper-plane"></i> Submit Ticket
                </button>
                <a href="{{ route('user.dashboard') }}" class="btn" style="padding: 1rem 2rem; border-radius: 0.75rem; background: #f3f4f6; color: #4b5563; font-weight: 700; text-decoration: none;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
    .file-upload-wrapper { position: relative; width: 100%; height: 50px; }
    .file-input { position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 2; }
    .file-label { 
        position: absolute; top: 0; left: 0; width: 100%; height: 100%; 
        background: #f8fafc; border: 2px dashed #e2e8f0; border-radius: 0.75rem;
        display: flex; align-items: center; justify-content: center; gap: 0.75rem;
        color: #64748b; font-weight: 600; font-size: 0.9rem; transition: 0.2s;
    }
    .file-input:hover + .file-label { border-color: var(--primary-color); color: var(--primary-color); background: #f5f3ff; }
</style>

<script>
    // File name display
    document.getElementById('attachment').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : 'Choose a file...';
        document.getElementById('file-name').textContent = fileName;
    });

    // Dynamic Sub-Category Loading
    document.getElementById('category_id').addEventListener('change', function() {
        const categoryId = this.value;
        const subCategorySelect = document.getElementById('sub_category_id');
        const loadingIndicator = document.getElementById('subcat-loading');

        // Clear subcategory
        subCategorySelect.innerHTML = '<option value="" disabled selected>Select Sub Category</option>';
        
        if (categoryId) {
            loadingIndicator.style.display = 'block';
            
            // Use dynamic URL from Blade with placeholder
            const url = "{{ route('api.subcategories', ':id') }}".replace(':id', categoryId);
            
            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    loadingIndicator.style.display = 'none';
                    if (data && data.length > 0) {
                        data.forEach(subcat => {
                            const option = document.createElement('option');
                            option.value = subcat.id;
                            option.textContent = subcat.name;
                            subCategorySelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.value = "";
                        option.textContent = "No subcategories found";
                        option.disabled = true;
                        subCategorySelect.appendChild(option);
                    }
                })
                .catch(error => {
                    loadingIndicator.style.display = 'none';
                    console.error('Error fetching subcategories:', error);
                    alert('Failed to load subcategories. Please try again.');
                });
        }
    });
</script>
@endsection
