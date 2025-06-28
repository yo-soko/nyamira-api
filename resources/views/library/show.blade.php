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
        </div> -->

        @if(!empty($book->table_of_contents))
        <div class="mb-4">
            <button class="btn btn-outline-primary mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#tocSection" aria-expanded="false" aria-controls="tocSection">
                View Table of Contents
            </button>
            <div class="collapse" id="tocSection">
                <div class="border p-3 rounded bg-light">
                    {!! nl2br(e($book->table_of_contents)) !!}
                </div>
            </div>
        </div>
        @endif

        <div class="mb-4 position-relative">
            <iframe id="pdfIframe" src="{{ asset('storage/' . $book->file_path) }}" width="100%" height="600px" style="border: 1px solid #ccc;"></iframe>
            <button id="fullscreenBtn" class="btn btn-secondary position-absolute" style="top: 10px; right: 10px;">
                <i class="ti ti-maximize"></i> Full Screen
            </button>
            <button id="exitFullscreenBtn" class="btn btn-danger position-absolute d-none" style="top: 10px; right: 140px;">
                <i class="ti ti-minimize"></i> Exit Full Screen
            </button>
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

<script>
    const fullscreenBtn = document.getElementById('fullscreenBtn');
    const exitFullscreenBtn = document.getElementById('exitFullscreenBtn');
    const iframe = document.getElementById('pdfIframe');

    fullscreenBtn.addEventListener('click', function() {
        if (iframe.requestFullscreen) {
            iframe.requestFullscreen();
        } else if (iframe.webkitRequestFullscreen) {
            iframe.webkitRequestFullscreen();
        } else if (iframe.msRequestFullscreen) {
            iframe.msRequestFullscreen();
        }
    });

    exitFullscreenBtn.addEventListener('click', function() {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    });

    // Listen for fullscreen changes to toggle the button
    document.addEventListener('fullscreenchange', function() {
        if (document.fullscreenElement) {
            exitFullscreenBtn.classList.remove('d-none');
        } else {
            exitFullscreenBtn.classList.add('d-none');
        }
    });
</script>
@endsection
