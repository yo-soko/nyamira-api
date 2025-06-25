<?php $page = 'shift'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Attendance Register - {{ $today }}</h4>
                    <h6>Class: {{ $class->level->level_name ?? '-' }} {{ $class->stream->name ?? '-' }}</h6>
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
            @hasanyrole('admin|developer|manager|director|supervisor')
            <div class="page-btn">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-shift"><i class="ti ti-circle-plus me-1"></i>Add Shift</a>
            </div>
            @endhasanyrole
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
            <div class="mb-4">
            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#filterDayModal">
                Filter by Day
            </button>

            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#filterRangeModal">
                Filter by Date Range
            </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <form method="POST" action="{{ route('attendance.store') }}">
                    @csrf
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Learner Name</th>
                                <th>Class</th>
                                <th>Session</th>
                                <th>Present</th>
                                <th>Absent</th>
                                <th>Other (specify)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr data-student="{{ $student->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                <td>{{ $student->class->level->level_name }} {{ $student->class->stream->initials }}</td>

                                <td>
                                    <select name="attendance[{{ $student->id }}][session]" class="form-control" required>
                                        <option value="morning">Morning</option>
                                        <option value="afternoon">Afternoon</option>
                                    </select>
                                </td>

                                <td><input type="checkbox" class="attendance-checkbox" name="attendance[{{ $student->id }}][present]" value="present"></td>
                                <td><input type="checkbox" class="attendance-checkbox" name="attendance[{{ $student->id }}][absent]" value="absent"></td>
                                <td>
                                    <input type="checkbox" class="attendance-checkbox status-other" name="attendance[{{ $student->id }}][other]" value="other" data-id="{{ $student->id }}">
                                    <input type="text" name="attendance[{{ $student->id }}][reason]" class="form-control mt-1 reason-input" placeholder="Reason" style="display: none;">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-success mt-3">Submit Attendance</button>
                    </form>
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
<!-- Modal 1: Filter by Single Day -->
<div class="modal fade" id="filterDayModal" tabindex="-1" aria-labelledby="filterDayModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="GET" action="{{ route('attendance.filter') }}">
      <input type="hidden" name="class_id" value="{{ $class->id }}">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="filterDayModalLabel">Filter Attendance by Day</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="date" class="form-label">Select Date</label>
            <input type="date" name="date" id="date" class="form-control">
          </div>
        
          <input type="hidden" name="filter_type" value="date">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Filter</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal 2: Filter by Date Range -->
<div class="modal fade" id="filterRangeModal" tabindex="-1" aria-labelledby="filterRangeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="GET" action="{{ route('attendance.filter') }}">
      <input type="hidden" name="class_id" value="{{ $class->id }}">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="filterRangeModalLabel">Filter Attendance by Date Range</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="week_start" class="form-label">Start Date</label>
            <input type="date" name="week_start" id="week_start" class="form-control">
          </div>
          <div class="mb-3">
            <label for="week_end" class="form-label">End Date</label>
            <input type="date" name="week_end" id="week_end" class="form-control">
          </div>
        
          <input type="hidden" name="filter_type" value="week">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Filter</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
    document.querySelectorAll('.attendance-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const row = this.closest('tr');
            const checkboxes = row.querySelectorAll('.attendance-checkbox');

            // If this one is being checked, uncheck others
            if (this.checked) {
                checkboxes.forEach(cb => {
                    if (cb !== this) cb.checked = false;
                });
            }

            // Show/hide reason input for "Other"
            const reasonInput = row.querySelector('.reason-input');
            const isOtherChecked = row.querySelector('.status-other')?.checked;

            if (reasonInput) {
                reasonInput.style.display = isOtherChecked ? 'block' : 'none';
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get current hour
        let now = new Date();
        let hour = now.getHours();

        // Decide default session
        let defaultSession = (hour >= 6 && hour < 12) ? 'morning' : 'afternoon';

        // Loop through all session select elements and set default
        document.querySelectorAll('select[name^="attendance"][name$="[session]"]').forEach(function (select) {
            select.value = defaultSession;
        });
        
    });
</script>



@endsection
