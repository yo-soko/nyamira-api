@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">

        <div class="page-header text-center">
            <h4 class="page-title">Upload New Book</h4>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="card shadow-sm rounded">
                    <div class="card-body">
                        <form action="{{ route('library.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input name="title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Author</label>
                                <input name="author" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-control" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Published Year</label>
                                <input name="published_year" class="form-control" type="number" min="1900" max="{{ date('Y') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Upload Book File (PDF)</label>
                                <input type="file" name="file" class="form-control" accept="application/pdf" required>
                            </div>
                            <div class="text-end">
                                <button class="btn btn-success">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>     
</div>  
@endsection
