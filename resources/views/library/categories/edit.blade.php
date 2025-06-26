@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">

    <div class="page-header">
        <h4 class="page-title">Edit Book Category</h4>
    </div>

    <form action="{{ route('library-categories.update', $library_category->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Category Name</label>
            <input type="text" name="name" class="form-control" value="{{ $library_category->name }}" required>
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('library-categories.index') }}" class="btn btn-secondary">Cancel</a>
    </form>

</div>
@endsection
