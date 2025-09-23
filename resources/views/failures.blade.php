@extends('layout.mainlayout')
@section('content')
@include('layout.toast')
<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h4 class="fw-bold">Inspection Item Failures</h4>
                <h6>All items marked as <span class="text-danger fw-bold">Fail</span></h6>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Vehicle</th>
                            <th>Inspector</th>
                            <th>Item</th>
                            <th>Remark</th>
                            <th>Attachment</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($failures as $failure)
                            <tr>
                                <td>{{ $failure->id }}</td>
                                <td>{{ $failure->inspection->vehicle->name ?? $failure->inspection->vehicle->model }}</td>
                                <td>{{ $failure->inspection->inspector->name }}</td>
                                <td><span class="fw-bold text-danger">{{ $failure->item_name }}</span></td>
                                <td>{{ $failure->remark ?? '-' }}</td>
                                <td>
                                    @if($failure->attachment)
                                        <a href="{{ asset('storage/'.$failure->attachment) }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="ti ti-file"></i> View
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $failure->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No failed items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $failures->links() }}
        </div>
    </div>
</div>
@endsection
