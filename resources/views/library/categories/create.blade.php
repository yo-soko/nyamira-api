@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">

    <div class="page-header">
        <h4 class="page-title">Add Book Category</h4>
    </div>

    <form action="{{ route('library-categories.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Category Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <button class="btn btn-success">Save</button>
        <a href="{{ route('library-categories.index') }}" class="btn btn-secondary">Back</a>
    </form>

</div>
@endsection

