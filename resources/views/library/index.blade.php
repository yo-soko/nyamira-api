@extends('layout.mainlayout')

@section('content')
@include('layout.toast')

<div class="page-wrapper">
    <div class="content">

        <div class="page-header d-flex justify-content-between align-items-center">
            <h4 class="page-title">E-Library</h4>
            <div class="page-btn">
                @if(in_array(auth()->user()->role, ['teacher', 'developer' , 'admin']))
                <a href="{{ route('library.create') }}" class="btn btn-primary">
                    <i class="ti ti-circle-plus me-1"></i> Upload Book
                </a>
                @endif
            </div>
        </div>

        {{-- 🔍 Search and Filter --}}
        <form method="GET" class="row g-2 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by title..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="category_id" class="form-control">
                    <option value="">-- Filter by Category --</option>
                    @foreach ($categories as $cat)
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

        {{-- 📚 Book Table --}}
        <div class="card">

            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table datatable">
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
                            @forelse ($items as $index => $book)
                            <tr>
                                <td>{{ $loop->iteration + ($items->currentPage() - 1) * $items->perPage() }}</td>
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->author }}</td>
                                <td>{{ $book->category->name }}</td>
                                <td>{{ $book->uploader->name ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('library.show', $book->id) }}" class="btn btn-sm btn-info">View</a>
                                    @if(auth()->user()->id == $book->uploaded_by || auth()->user()->role === 'admin')
                                    <form action="{{ route('library.destroy', $book->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No books found in the library.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>  
        {{-- 📄 Pagination --}}
        <div class="mt-3">
            {{ $items->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
