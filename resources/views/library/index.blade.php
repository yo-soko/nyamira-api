@extends('layout.mainlayout')

@section('content')
@include('layout.toast')

<div class="page-wrapper">
  <div class="content">

    {{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-center">
      <h4 class="page-title">E-Library</h4>
      <div class="page-btn">
        @if(in_array(auth()->user()->role, ['teacher','developer','admin']))
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadBookModal">
          <i class="ti ti-circle-plus me-1"></i> Upload Book
        </button>
        @endif
      </div>
    </div>

    {{-- Search/Filter --}}
    <form method="GET" class="row g-2 mb-4">
      <!-- <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Search by title..." value="{{ request('search') }}">
      </div> -->
      <div class="col-md-4">
        <select name="category_id" class="form-control">
          <option value="">-- Filter by Category --</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
              {{ $cat->name }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <button class="btn btn-secondary w-100">Filter</button>
      </div>
      <div class="col-md-2">
        <a href="{{ route('library.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
      </div>
    </form>

    {{-- Book Table --}}
    <div class="card">
      <div class="card-body p-0">
        @if($items->count())
        <div class="table-responsive">
          <table class="table datatable" >
            <thead class="thead-light">
              <tr>
                <th>#</th>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Uploaded By</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($items as $book)
              <tr>
                <td>{{ $loop->iteration + ($items->currentPage() - 1) * $items->perPage() }}</td>
                <td>{{ $book->title }}</td>
                <td>{{ $book->author }}</td>
                <td>{{ $book->category?->name ?? 'N/A' }}</td>
                <td>{{ $book->uploader?->name ?? 'N/A' }}</td>
                <td>
                  <a href="{{ route('library.show', $book->id) }}" class="btn btn-sm btn-info">View</a>
                  @if(auth()->user()->id == $book->uploaded_by || auth()->user()->role === 'admin')
                  <button class="btn btn-sm btn-danger"
                          data-bs-toggle="modal"
                          data-bs-target="#deleteModal"
                          data-id="{{ $book->id }}"
                          data-title="{{ $book->title }}">
                      Delete
                  </button>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
          <div class="alert alert-warning m-3">
            No books found.
          </div>
        @endif
      </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
      {{ $items->withQueryString()->links() }}
    </div>

  </div>
</div>

{{-- Upload Book Modal --}}
<div class="modal fade" id="uploadBookModal" tabindex="-1" aria-labelledby="uploadBookModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('library.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Upload New Book</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif

          <div class="mb-3">
            <label>Title</label>
            <input name="title" class="form-control" value="{{ old('title') }}" required>
          </div>
          <div class="mb-3">
            <label>Author</label>
            <input name="author" class="form-control" value="{{ old('author') }}" required>
          </div>
          <div class="mb-3">
            <label>Category</label>
            <select name="category_id" class="form-control" required>
              <option value="">Select Category</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                  {{ $cat->name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label>Published Year</label>
            <input name="published_year" class="form-control" type="number" min="1900" max="{{ date('Y') }}" value="{{ old('published_year') }}">
          </div>
          <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
          </div>
          <div class="mb-3">
            <label>Upload File (PDF)</label>
            <input type="file" name="file" class="form-control" accept="application/pdf" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-success">Upload</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" id="deleteForm">
        @csrf
        @method('DELETE')
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Confirm Delete</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete <strong id="bookTitle"></strong>?
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
document.addEventListener('DOMContentLoaded', function () {
  const deleteModal = document.getElementById('deleteModal');
  deleteModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const bookId = button.getAttribute('data-id');
    const bookTitle = button.getAttribute('data-title');
    const form = document.getElementById('deleteForm');
    form.action = `/library/${bookId}`;
    document.getElementById('bookTitle').textContent = bookTitle;
  });

  @if($errors->any())
  const uploadModal = new bootstrap.Modal(document.getElementById('uploadBookModal'));
  uploadModal.show();
  @endif

  $('#libraryTable').DataTable({
    responsive: true
  });
});
</script>
@endpush
