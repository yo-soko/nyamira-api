<?php $page = 'shift'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Attendance Register - </h4>
                    <h6>Stream: {{ $class->level->level_name ?? '-' }} {{ $class->stream->name ?? '-' }}</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li class="me-2">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img src="{{URL::asset('build/img/icons/pdf.svg')}}" alt="img"></a>
                </li>
                <li class="me-2">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img src="{{URL::asset('build/img/icons/excel.svg')}}" alt="img"></a>
                </li>
                <li class="me-2">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
                </li>
                <li class="me-2">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
                </li>
            </ul>
          
        </div>
        <!-- /product list -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                <div class="search-set">
                    <div class="search-input">
                        <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
                    </div>
                </div>
                <div class="d-flex table-dropdown my-xl-auto right-content align-items-center flex-wrap row-gap-3">
                    <div class="dropdown me-2">
                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center" data-bs-toggle="dropdown">
                            Select Status
                        </a>
                        <ul class="dropdown-menu  dropdown-menu-end p-3">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Active</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Inactive</a>
                            </li>
                        </ul>
                    </div>
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center" data-bs-toggle="dropdown">
                            Sort By : Last 7 Days
                        </a>
                        <ul class="dropdown-menu  dropdown-menu-end p-3">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Recently Added</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Ascending</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Desending</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Last Month</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Last 7 Days</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <style>
                .day-separator {
                    border-right: 2px solid #dee2e6; /* light grey border, adjust color/thickness as needed */
                }

                /* Optional: Add some padding or spacing */
                th.day-separator, td.day-separator {
                    padding-right: 12px;
                }

            </style>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Learner Name</th>
                                <th>Stream</th>
                                @if(!empty($dates))
                                    @foreach($dates as $date)
                                        <th colspan="2" class="text-center">{{ \Carbon\Carbon::parse($date)->format('M d') }}</th>
                                    @endforeach
                                @else
                                   <th>Morning</th>
                                   <th>Afternoon</th>

                                @endif
                            </tr>
                            @if(!empty($dates))
                                <tr>
                                    <th colspan="3"></th>
                                    @foreach($dates as $date)
                                        <th>Morning</th>
                                        <th class="day-separator">Afternoon</th>
                                    @endforeach
                                </tr>
                            @endif
                        </thead>

                        <tbody>
                            @foreach($students as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                    <td>{{ $student->class->level->level_name ?? '-' }} {{ $student->class->stream->initials ?? '-' }}</td>

                                    @if(!empty($dates))
                                       @foreach($dates as $date)
                                        @php
                                            $morning = $student->attendance->firstWhere(fn($att) => $att->date == $date && $att->session == 'morning');
                                            $afternoon = $student->attendance->firstWhere(fn($att) => $att->date == $date && $att->session == 'afternoon');
                                        @endphp
                                        <td class="text-center">
                                            @if($morning)
                                                @if($morning->status === 'Present') ✔
                                                @elseif($morning->status === 'Absent') ✘
                                                @elseif($morning->status === 'Excused') {{ $morning->reason ?? '-' }}
                                                @else -
                                                @endif
                                            @else N/A @endif
                                        </td>
                                        <td class="text-center day-separator">
                                            @if($afternoon)
                                                @if($afternoon->status === 'Present') ✔
                                                @elseif($afternoon->status === 'Absent') ✘
                                                @elseif($afternoon->status === 'Excused') {{ $afternoon->reason ?? '-' }}
                                                @else -
                                                @endif
                                            @else N/A @endif
                                        </td>
                                    @endforeach

                                    @else
                                @php
                                    $date = $filters['date'] ?? null;
                                    $morning = $student->attendance->firstWhere(fn($att) => $att->date == $date && $att->session == 'morning');
                                    $afternoon = $student->attendance->firstWhere(fn($att) => $att->date == $date && $att->session == 'afternoon');
                                @endphp
                                <td class="text-center">
                                    @if($morning)
                                        @if($morning->status === 'Present') ✔
                                        @elseif($morning->status === 'Absent') ✘
                                        @elseif($morning->status === 'Excused') {{ $morning->reason ?? '-' }}
                                        @else -
                                        @endif
                                    @else N/A @endif
                                </td>
                                <td class="text-center">
                                    @if($afternoon)
                                        @if($afternoon->status === 'Present') ✔
                                        @elseif($afternoon->status === 'Absent') ✘
                                        @elseif($afternoon->status === 'Excused') {{ $afternoon->reason ?? '-' }}
                                        @else -
                                        @endif
                                    @else N/A @endif
                                </td>
                                @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <!-- /product list -->
    </div>
    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0">&copy; JavaPA</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div> 



@endsection
