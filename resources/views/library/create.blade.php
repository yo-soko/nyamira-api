@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">

    <div class="page-header">
        <h4 class="page-title">Upload New Book</h4>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('library.store') }}" method="POST" enctype="multipart/form-data" class="mt-3">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Title</label>
                <input name="title" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Author</label>
                <input name="author" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Category</label>
                <select name="category_id" class="form-control" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Published Year</label>
                <input name="published_year" class="form-control" type="number" min="1900" max="{{ date('Y') }}">
            </div>
            <div class="col-md-12 mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="col-md-12 mb-3">
                <label>Upload Book File (PDF)</label>
                <input type="file" name="file" class="form-control" accept="application/pdf" required>
            </div>
            <div class="col-12">
                <button class="btn btn-success">Upload</button>
            </div>
        </div>
    </form>
</div>  
@endsection
