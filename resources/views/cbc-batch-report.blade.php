@extends('layout.mainlayout')
@section('content')
    <div class="container">
        <h4 class="mb-4">CBC Reports (Bulk)</h4>

        @foreach($reports as $report)
            <div class="mb-5 page-break">
                @include('partials.cbc-report-partial', (array) $report)
            </div>
        @endforeach
    </div>
@endsection

@push('styles')
<style>
    .page-break {
        page-break-after: always;
    }
</style>
@endpush
