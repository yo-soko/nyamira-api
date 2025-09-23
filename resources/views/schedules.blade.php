@extends('layout.mainlayout')

@section('content')
@include('layout.toast')

 <div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Inspection Schedules</h4>
                    <h6>Manage inspection schedules</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img src="{{URL::asset('build/img/icons/pdf.svg')}}" alt="img"></a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img src="{{URL::asset('build/img/icons/excel.svg')}}" alt="img"></a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
                </li>
            </ul>
            
        </div> 

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Vehicle</th>
                            <th>Plate Number</th>
                            <th>Next Inspection Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($schedules as $schedule)
                            <tr>
                                <td>{{ $schedule->name ?? ''}}</td>
                                <td>{{ $schedule->license_plate ?? '' }}</td>
                                <td>
                                    @if($schedule->next_inspection_date)
                                        {{ \Carbon\Carbon::parse($schedule->next_inspection_date)->format('d M Y') }}
                                    @else
                                        <span class="text-danger">No inspections yet</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No schedules found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
