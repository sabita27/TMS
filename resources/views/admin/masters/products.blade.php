@extends('layouts.backend.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Product Master</h3>
            <button onclick="document.getElementById('addProductModal').style.display='block'" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Product
            </button>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Sub Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>{{ $product->subCategory->name }}</td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td><span
                                    class="badge {{ $product->status ? 'badge-success' : 'badge-danger' }}">{{ $product->status ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <button onclick="editProduct({{ $product->id }})" class="btn btn-primary"
                                        style="padding: 0.4rem 0.7rem; font-size: 0.75rem;">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    @if(Auth::user()->hasRole('admin'))
                                        <form action="{{ route('admin.products.delete', $product->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this product?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                style="padding: 0.4rem 0.7rem; font-size: 0.75rem;">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1rem;">
            {{ $products->links() }}
        </div>
    </div>

    <!-- Add Product Modal -->
    <div id="addProductModal"
        style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); overflow-y: auto;">
        <div style="background: white; width: 500px; margin: 5% auto; padding: 2rem; border-radius: 0.5rem;">
            <div
                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
                <h3 style="margin: 0;">Add New Product</h3>
                <button type="button" onclick="document.getElementById('addProductModal').style.display='none'"
                    style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.products.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-row" style="display:flex; gap:1rem;">
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-control" required
                            onchange="fetchSubCategories(this.value, 'add')">
                            <option value="">Select Category</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">Sub Category</label>
                        <select name="sub_category_id" id="add_sub_category_id" class="form-control" required>
                            <option value="">Select Sub Category</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Price</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="add_description" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 2rem; border-top: 1px solid #eee; padding-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary"
                        style="flex:1; display:flex; justify-content:center; align-items:center;">
                        Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div id="editProductModal"
        style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); overflow-y: auto;">
        <div style="background: white; width: 500px; margin: 5% auto; padding: 2rem; border-radius: 0.5rem;">
            <div
                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
                <h3 style="margin: 0;">Edit Product</h3>
                <button type="button" onclick="document.getElementById('editProductModal').style.display='none'"
                    style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editProductForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
                <div class="form-row" style="display:flex; gap:1rem;">
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">Category</label>
                        <select name="category_id" id="edit_category_id" class="form-control" required
                            onchange="fetchSubCategories(this.value, 'edit')">
                            <option value="">Select Category</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">Sub Category</label>
                        <select name="sub_category_id" id="edit_sub_category_id" class="form-control" required>
                            <option value="">Select Sub Category</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Price</label>
                    <input type="number" step="0.01" name="price" id="edit_price" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" id="edit_status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary" style="flex:1;">Update Product</button>
                </div>
            </form>
        </div>
    </div>

@section('scripts')
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            CKEDITOR.replace('add_description');
            CKEDITOR.replace('edit_description');
        });

        function fetchSubCategories(categoryId, type, selectedSubCategoryId = null) {
            const subCategorySelect = document.getElementById(`${type}_sub_category_id`);
            subCategorySelect.innerHTML = '<option value="">Loading...</option>';

            if (!categoryId) {
                subCategorySelect.innerHTML = '<option value="">Select Sub Category</option>';
                return;
            }

            fetch(`/get-subcategories/${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    subCategorySelect.innerHTML = '<option value="">Select Sub Category</option>';
                    data.forEach(sub => {
                        const option = document.createElement('option');
                        option.value = sub.id;
                        option.text = sub.name;
                        if (selectedSubCategoryId && sub.id == selectedSubCategoryId) {
                            option.selected = true;
                        }
                        subCategorySelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching subcategories:', error);
                    subCategorySelect.innerHTML = '<option value="">Error loading</option>';
                });
        }

        function editProduct(id) {
            fetch(`/admin/products/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_name').value = data.name;
                    document.getElementById('edit_category_id').value = data.category_id;
                    document.getElementById('edit_price').value = data.price;
                    if (CKEDITOR.instances['edit_description']) {
                        CKEDITOR.instances['edit_description'].setData(data.description || '');
                    }
                    document.getElementById('edit_status').value = data.status;

                    // Fetch subcategories and select the current one
                    fetchSubCategories(data.category_id, 'edit', data.sub_category_id);

                    document.getElementById('editProductForm').action = `/admin/products/${id}`;
                    document.getElementById('editProductModal').style.display = 'block';
                });
        }
    </script>
@endsection
@endsection
