@extends('layouts.backend.master')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">Product Sub Categories</h3>
        <button onclick="document.getElementById('addSubCatModal').style.display='block'" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Sub Category
        </button>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Sub Category</th>
                    <th>Parent Category</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subcategories as $sub)
                <tr>
                    <td>{{ $sub->name }}</td>
                    <td><span class="badge badge-info">{{ $sub->category->name }}</span></td>
                    <td><span class="badge badge-success">Active</span></td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="editSubCategory({{ $sub->id }})" class="btn btn-primary" style="padding: 0.4rem 0.7rem; font-size: 0.75rem;">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.subcategories.delete', $sub->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this subcategory?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.7rem; font-size: 0.75rem;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1rem;">
        {{ $subcategories->links() }}
    </div>
</div>

<!-- Add Sub Category Modal -->
<div id="addSubCatModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; width: 400px; margin: 10% auto; padding: 2rem; border-radius: 0.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
            <h3 style="margin: 0;">Add New Sub Category</h3>
            <button type="button" onclick="document.getElementById('addSubCatModal').style.display='none'" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('admin.subcategories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Parent Category</label>
                <select name="category_id" class="form-control" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Sub Category Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Laptops" required>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Save Sub Category</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Sub Category Modal -->
<div id="editSubCatModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; width: 400px; margin: 10% auto; padding: 2rem; border-radius: 0.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
            <h3 style="margin: 0;">Edit Sub Category</h3>
            <button type="button" onclick="document.getElementById('editSubCatModal').style.display='none'" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editSubCatForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Parent Category</label>
                <select name="category_id" id="edit_category_id" class="form-control" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Sub Category Name</label>
                <input type="text" name="name" id="edit_sub_name" class="form-control" required>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Update Sub Category</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    function editSubCategory(id) {
        fetch(`/admin/subcategories/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_category_id').value = data.category_id;
                document.getElementById('edit_sub_name').value = data.name;
                document.getElementById('editSubCatForm').action = `/admin/subcategories/${id}`;
                document.getElementById('editSubCatModal').style.display = 'block';
            })
            .catch(error => console.error('Error:', error));
    }
</script>
@endsection
@endsection
