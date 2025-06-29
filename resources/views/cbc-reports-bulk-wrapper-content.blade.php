@foreach ($students as $student)
    @php
        $data = app(\App\Http\Controllers\SdashboardController::class)->prepareCbcReportData($student, $termId, $examId);
        extract($data); // unpack the data just like passing to single view
    @endphp

    @include('cbc-report') {{-- same design reused --}}
    <div style="page-break-after: always;"></div>
@endforeach
