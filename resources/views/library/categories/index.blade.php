@extends('layout.mainlayout')
@section('content')
@include('layout.toast')

<div class="page-wrapper">

    <div class="page-header d-flex justify-content-between align-items-center">
        <h4 class="page-title">Book Categories</h4>
        <a href="{{ route('library-categories.create') }}" class="btn btn-primary">
            <i class="ti ti-circle-plus me-1"></i> Add Category
        </a>
    </div>

  
    <div class="table-responsive mt-3">
        <table class="table table-bordered">
            <thead class="table-light">
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
                        <a href="{{ route('library-categories.edit', $category->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('library-categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this category?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center">No categories found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
