<?php $page = 'attendance-employee'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast')
@php
    $hour = date('H');
    if ($hour < 12) {
        $greeting = 'Good Morning';
    } elseif ($hour < 18) {
        $greeting = 'Good Afternoon';
    } else {
        $greeting = 'Good Evening';
    }
@endphp
    <div class="page-wrapper">
        <div class="content">
            <div class="attendance-header">
                <div class="attendance-content">
                <img src="{{URL::asset('build/img/icons/hand01.svg')}}" class="hand-img" alt="img">
                  <h3>{{ $greeting }},<span> {{ $employee->first_name .' '. $employee->last_name }}</span></h3>
                </div>
                <div>
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
            </div>
            <div class="row">
                <div class="col-xl-4 col-lg-12 d-flex">
                    <div class="card w-100">
                        <div class="card-body">
                            <h5 class="mb-3 pb-3 border-bottom d-flex justify-content-between align-items-center fs-18">Attendance <span class="text-purple fs-14">{{ \Carbon\Carbon::now()->format('d F Y') }}</span></h5>
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-2">
                                    <img src="{{URL::asset('build/img/icons/time-big.svg')}}" alt="time-img">
                                </div>
                                <div>
                                    <h2 id="clock">--:--:--</h2>
                                    <p>Current Time</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                @if (!$alreadyClockedIn)
                                    <!-- CLOCK IN -->
                                    <form action="{{ route('attendance-employee.clockIn') }}" method="POST" class="w-100 me-2">
                                        @csrf
                                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                        <button type="submit" class="btn btn-primary w-100 me-2">Clock In</button>
                                    </form>
                                @elseif ($onBreak)
                                    <!-- BACK FROM BREAK -->
                                    <form action="{{ route('attendance-employee.backFromBreak') }}" method="POST" class="w-100 me-2">
                                        @csrf
                                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                        <button type="submit" class="btn bg-info-gradient w-100 me-2">Back From Break</button>
                                    </form>
                                @else
                                    @if (is_null($attendance->break_end))
                                    <!-- BREAK -->
                                    <form action="{{ route('attendance-employee.break') }}" method="POST" class="w-100 me-2">
                                        @csrf
                                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                        <button type="submit" class="btn btn-secondary w-100 me-2">Break</button>
                                    </form>
                                    @endif
                                    <!-- CLOCK OUT -->
                                    <form action="{{ route('attendance-employee.clockOut') }}" method="POST" class="w-100 me-2">
                                        @csrf
                                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                        <button type="submit" class="btn bg-danger-gradient w-100 me-2">Clock Out</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-12 d-flex">
                    <div class="card w-100">
                        <div class="card-body">
                            <h5 class="border-bottom pb-3 mb-3">Days Overview for {{ $previousMonth }}</h5>
                            <div class="row gy-3">
                                <div class="col-lg-2 col-md-3 col-sm-4 text-center">
                                    <div>
                                        <span class="d-flex align-items-center justify-content-center avatar avatar-xl bg-primary-transparent fw-bold fs-20 mb-2 mx-auto">{{ $totalWorkingDays }}</span>
                                        <p class="fs-14">Total Working <br> Days</p>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-4 text-center">
                                    <div>
                                        <span class="d-flex align-items-center justify-content-center avatar avatar-xl bg-danger-transparent fw-bold fs-20 mb-2 mx-auto">{{ $absentDays }}</span>
                                        <p class="fs-14">Absent <br> Days</p>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-4 text-center">
                                    <div>
                                        <span class="d-flex align-items-center justify-content-center avatar avatar-xl bg-purple-transparent text-purple fw-bold fs-20 mb-2 mx-auto">{{ $presentDays }}</span>
                                        <p class="fs-14">Present <br> Days</p>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-4 text-center">
                                    <div>
                                        <span class="d-flex align-items-center justify-content-center avatar avatar-xl bg-warning-transparent fw-bold fs-20 mb-2 mx-auto">{{ $halfDays }}</span>
                                        <p class="fs-14">Half <br> Days</p>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-4 text-center">
                                    <div>
                                        <span class="d-flex align-items-center justify-content-center avatar avatar-xl bg-cyan-transparent text-cyan fw-bold fs-20 mb-2 mx-auto">{{ $lateDays }}</span>
                                        <p class="fs-14">Late <br> Days</p>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-4 text-center">
                                    <div>
                                        <span class="d-flex align-items-center justify-content-center avatar avatar-xl bg-success-transparent text-success fw-bold fs-20 mb-2 mx-auto">{{ $holidayDays }}</span>
                                        <p class="fs-14">Holidays</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


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
                                    <a href="javascript:void(0);" class="dropdown-item rounded-1">Present</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item rounded-1">Absent</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item rounded-1">Holiday</a>
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
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Clock In</th>
                                    <th>Clock Out</th>
                                    <th>Hours Worked</th>
                                    <th>Break Duration</th>
                                    <th>Overtime</th>
                                    <th>Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendanceRecords as $attendance)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $attendance->clock_in ? 'success' : 'danger' }} d-inline-flex align-items-center badge-xs">
                                            <i class="ti ti-point-filled me-1"></i>
                                            {{ $attendance->clock_in ? 'Present' : 'Absent' }}
                                        </span>
                                    </td>
                                    <td>{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('h:i A') : '-' }}</td>
                                    <td>{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('h:i A') : '-' }}</td>
                                    <td>{{ $attendance->total_hours }} hrs</td>


                                    @php
                                        $breakDuration = \Carbon\Carbon::parse($attendance->break_start)
                                                        ->diffInSeconds(\Carbon\Carbon::parse($attendance->break_end));
                                    @endphp

                                    <td>{{ gmdate("H:i:s", $breakDuration) }} hrs</td>

                                    <td>{{ (float)$attendance->overtime }}h</td>

                                    <td>
                                        <div class="progress attendance bg-secondary-transparent">
                                            <div class="progress-bar progress-bar-success" role="progressbar" style="width:{{ ((float)$attendance->total_hours / 8) * 100 }}%">
                                            </div>
                                            <div class="progress-bar progress-bar-warning" role="progressbar" style="width:{{ ((float)$attendance->overtime / 8) * 100 }}%">
                                            </div>
                                            <div class="progress-bar progress-bar-danger" role="progressbar" style="width:{{ ((float)$attendance->break / 8) * 100 }}%">
                                            </div>
                                        </div>
                                    </td>
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
            <p class="mb-0"> &copy; JavaPA. All Right Reserved</p>
            <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
        </div>
    </div>
    <script>
    setInterval(() => {
        const now = new Date();
        const formatted = now.toLocaleTimeString();
        document.getElementById('clock').innerText = formatted;
    }, 1000);
</script>
@endsection
