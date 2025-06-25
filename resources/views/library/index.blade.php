@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">

    <div class="page-header d-flex justify-content-between align-items-center">
        <h4 class="page-title">E-Library</h4>
        <div class="page-btn">
            @if(in_array(auth()->user()->role, ['teacher', 'admin']))
            <a href="{{ route('library.create') }}" class="btn btn-primary">
                <i class="ti ti-circle-plus me-1"></i> Upload Book
            </a>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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

    {{-- 📚 Book Cards --}}
    <div class="row mt-2">
        @forelse ($items as $book)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $book->title }}</h5>
                        <p class="text-muted mb-1">Author: {{ $book->author }}</p>
                        <p>Category: {{ $book->category->name }}</p>

                        <a href="{{ route('library.show', $book->id) }}" class="btn btn-sm btn-info">View</a>

                        @if(auth()->user()->id == $book->uploaded_by || auth()->user()->role === 'admin')
                        <form action="{{ route('library.destroy', $book->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <p>No books found in the library.</p>
            </div>
        @endforelse
    </div>

    {{-- 📄 Pagination --}}
    <div class="mt-3">
        {{ $items->withQueryString()->links() }}
    </div>

</div>
@endsection
