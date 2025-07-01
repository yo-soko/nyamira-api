@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">

        <div class="page-header d-flex justify-content-between align-items-center">
            <h4 class="page-title">{{ $book->title }}</h4>
            <a href="{{ route('library.index') }}" class="btn btn-outline-secondary px-3 py-1">
                <i class="ti ti-arrow-left me-1"></i> Back to Library
            </a>
        </div>

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
        </div>

    </div>  
</div>    



<!-- Fullscreen script -->
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

    document.addEventListener('fullscreenchange', function() {
        if (document.fullscreenElement) {
            exitFullscreenBtn.classList.remove('d-none');
        } else {
            exitFullscreenBtn.classList.add('d-none');
        }
    });
</script>
@endsection
