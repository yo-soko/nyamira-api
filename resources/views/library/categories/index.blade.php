
<?php $page = 'designation'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
             <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Categories</h4>
                    <h6>Manage your book categories</h6>
                </div>
            </div>
            <div class="page-btn">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal"><i class="ti ti-circle-plus me-1"></i>Add Category</a>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="categoryTable" class="table datatable">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Category Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $i => $category)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <button
                                        class="btn btn-sm btn-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editCategoryModal"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        class="btn btn-sm btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">No categories found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>  
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
        <form method="POST" action="{{ route('library-categories.store') }}">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add Book Category</h5>
                <button type="button" class="btn-close btn-close-red" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success btn-sm">Save</button>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
  </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
        <form method="POST" id="editCategoryForm">
            @csrf
            @method('PUT')
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Edit Book Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Category Name</label>
                    <input type="text" name="name" id="editCategoryName" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-sm">Update</button>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <form method="POST" id="deleteCategoryForm">
            @csrf
            @method('DELETE')
            <div class="modal-header text-white bg-danger">
                <h5 class="modal-title">Delete Category</h5>
                <button type="button" class="btn-close btn-close-red" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteCategoryName"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger btn-sm">Yes, Delete</button>
            </div>
        </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
    // For delete
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const categoryId = button.getAttribute('data-id');
        const categoryName = button.getAttribute('data-name');
        document.getElementById('deleteCategoryName').textContent = categoryName;
        document.getElementById('deleteCategoryForm').action = `/library-categories/${categoryId}`;
    });

    // For edit
    const editModal = document.getElementById('editCategoryModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const categoryId = button.getAttribute('data-id');
        const categoryName = button.getAttribute('data-name');
        document.getElementById('editCategoryName').value = categoryName;
        document.getElementById('editCategoryForm').action = `/library-categories/${categoryId}`;
    });
</script>
@endpush
