@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">

        <div class="page-header d-flex justify-content-between align-items-center">
            <h4 class="page-title">{{ $book->title }}</h4>
            <a href="{{ route('library.index') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left"></i> Back to Library
            </a>
        </div>

        <!-- <div class="d-flex flex-wrap mb-4 gap-4 align-items-start border p-3 rounded bg-light">
            <div><strong>Author:</strong> {{ $book->author }}</div>
            <div><strong>Category:</strong> {{ $book->category->name }}</div>
            <div><strong>Year:</strong> {{ $book->published_year ?? 'N/A' }}</div>
            <div><strong>Description:</strong> {{ $book->description ?? 'No description' }}</div>
        </div>
 -->

        <div class="mb-4">
            <iframe src="{{ asset('storage/' . $book->file_path) }}" width="100%" height="600px" style="border: 1px solid #ccc;"></iframe>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ asset('storage/' . $book->file_path) }}" class="btn btn-primary" download>
                <i class="ti ti-download"></i> Download PDF
            </a>

            @if(auth()->user()->id === $book->uploaded_by || auth()->user()->role === 'admin')
            <form action="{{ route('library.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this book?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">
                    <i class="ti ti-trash"></i> Delete
                </button>
            </form>
            @endif
        </div>
    </div>  
</div>    
@endsection
