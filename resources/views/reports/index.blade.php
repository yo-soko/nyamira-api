@extends('layout.mainlayout')


@section('content')
@include('layout.toast')
<div class="page-wrapper">
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="page-title">Standard Reports</h4>
        </div>

        <div class="container">
            @foreach($categories as $category => $reports)
                <div class="mb-5">
                    <h4 class="text-primary mb-3">{{ $category }}</h4>
                    <div class="row">
                        @foreach($reports as $report)
                            <div class="col-md-4 col-lg-3 mb-3">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-title">{{ $report['name'] }}</h6>
                                        <p class="text-muted small mb-3">
                                            {{ $report['description'] ?? 'Click to view report.' }}
                                        </p>
                                        <div class="mt-auto">
                                            <a href="{{ route($report['route']) }}" class="btn btn-sm btn-outline-primary w-100">
                                                <i class="ti ti-eye"></i> View Report
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
