@extends('layouts.backend.master')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">Product Categories</h3>
        <button onclick="document.getElementById('addCatModal').style.display='block'" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Category
        </button>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Category Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $cat)
                <tr>
                    <td>{{ $cat->name }}</td>
                    <td><span class="badge badge-success">Active</span></td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="editCategory({{ $cat->id }})" class="btn btn-primary" style="padding: 0.4rem 0.7rem; font-size: 0.75rem;">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.categories.delete', $cat->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?')">
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
        {{ $categories->links() }}
    </div>
</div>

<!-- Add Category Modal -->
<div id="addCatModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; width: 400px; margin: 10% auto; padding: 2rem; border-radius: 0.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
            <h3 style="margin: 0;">Add New Category</h3>
            <button type="button" onclick="document.getElementById('addCatModal').style.display='none'" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Category Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Software" required>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Save Category</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editCatModal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; width: 400px; margin: 10% auto; padding: 2rem; border-radius: 0.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
            <h3 style="margin: 0;">Edit Category</h3>
            <button type="button" onclick="document.getElementById('editCatModal').style.display='none'" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editCatForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Category Name</label>
                <input type="text" name="name" id="edit_cat_name" class="form-control" placeholder="e.g. Software" required>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Update Category</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    function editCategory(id) {
        fetch(`/admin/categories/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_cat_name').value = data.name;
                document.getElementById('editCatForm').action = `/admin/categories/${id}`;
                document.getElementById('editCatModal').style.display = 'block';
            })
            .catch(error => console.error('Error:', error));
    }
</script>
@endsection
@endsection
