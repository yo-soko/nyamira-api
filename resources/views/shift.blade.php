<?php $page = 'shift'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999">
    @foreach (['success', 'error', 'warning', 'info'] as $msg)
        @if(session($msg))
            @php
                $bgColor = match($msg) {
                    'success' => 'bg-success text-white',
                    'error' => 'bg-danger text-white',
                    'warning' => 'bg-warning text-dark',
                    'info' => 'bg-info text-dark',
                    default => 'bg-secondary text-white'
                };
            @endphp
            <div class="toast align-items-center {{ $bgColor }} border-0 show mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session($msg) }}
                    </div>
                    <button type="button" class="btn-close {{ $msg == 'warning' || $msg == 'info' ? '' : 'btn-close-white' }} me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
    @endforeach
</div>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Shifts</h4>
                    <h6>Manage your shifts</h6>
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
            <div class="page-btn">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-shift"><i class="ti ti-circle-plus me-1"></i>Add Shift</a>
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
            <div class="card-body p-0">
                <div class="table-responsive no-pagination">
                    <table class="table datatable">
                        <thead class="thead-light">
                            <tr>
                                <th class="no-sort">
                                    <label class="checkboxs">
                                        <input type="checkbox" id="select-all">
                                        <span class="checkmarks"></span>
                                    </label>
                                </th>
                                <th>Shift Name</th>
                                <th>Timing</th>
                                <th>Work days</th>
                                <th>Off Day</th>
                                <th>Created On</th>
                                <th>Status</th>
                                <th class="no-sort"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shift as $shiftItem)
                                <tr>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <span class="text-gray-900">{{ $shiftItem->shift_name }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($shiftItem->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($shiftItem->end_time)->format('h:i A') }}</td>
                                    <td>
                                        {{ implode(', ', explode(',', $shiftItem->days)) }}
                                    </td>
                                    <td>
                                        <span class="text-gray-900">{{ $shiftItem->day_off }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($shiftItem->created_at)->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge badge-success d-inline-flex align-items-center badge-xs">
                                            <i class="ti ti-point-filled me-1"></i>{{ $shiftItem->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="action-table-data">
                                        <div class="edit-delete-action">
                                            <a class="me-2 p-2 edit-btn" href="#" 
                                                data-id="{{ $shiftItem->id }}"
                                                data-shift_name="{{ $shiftItem->shift_name }}"
                                                data-start_time="{{ $shiftItem->start_time }}"
                                                data-end_time="{{ $shiftItem->end_time }}"
                                                data-day_off="{{ $shiftItem->day_off }}"
                                                data-description="{{ $shiftItem->description }}"
                                                data-status="{{ $shiftItem->status }}"
                                                data-bs-toggle="modal" data-bs-target="#edit-shift">
                                                    <i data-feather="edit" class="feather-edit"></i>
                                            </a>
                                            <a class="p-2" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#delete-modal">
                                                <i data-feather="trash-2" class="feather-trash-2"></i>
                                            </a>
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
        <p class="mb-0">2014 - 2025 &copy; DreamsPOS. All Right Reserved</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">Dreams</a></p>
    </div>
</div> 
<script>
    document.querySelector('form').addEventListener('submit', function(e) {
    let days = [];

    ['day4', 'day5', 'day6', 'day7'].forEach((id) => {
        if (document.getElementById(id).checked) {
            let sessions = [];
            document.querySelectorAll(`#${id} ~ .status-label`)
                .forEach((el) => {
                    let wrapper = el.closest('tr').querySelectorAll('.modal-table-check input');
                    wrapper.forEach((chk, index) => {
                        if (chk.checked) sessions.push(chk.parentElement.innerText.trim());
                    });
                });

            days.push({ name: document.querySelector(`label[for=${id}] + span`).innerText.trim(), sessions });
        }
    });

    // Append to form as hidden input
    let input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'days';
    input.value = JSON.stringify(days);
    this.appendChild(input);
});


</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('[data-day]').forEach(dayContainer => {
            const allBox = dayContainer.querySelector('.check-all');
            const sessionBoxes = dayContainer.querySelectorAll('.session-check');

            allBox.addEventListener('change', () => {
                sessionBoxes.forEach(cb => cb.checked = allBox.checked);
            });

            sessionBoxes.forEach(cb => {
                cb.addEventListener('change', () => {
                    allBox.checked = [...sessionBoxes].every(input => input.checked);
                });
            });
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const mondayRow = document.querySelector('#days1').closest('tr'); // Get the whole row for Monday
        const allCheckbox = mondayRow.querySelector('label:has(span:contains("All")) input[type="checkbox"]');
        const sessionCheckboxes = Array.from(mondayRow.querySelectorAll('label:has(span:not(:contains("All"))) input[type="checkbox"]'));

        // When "All" is clicked
        allCheckbox.addEventListener('change', function () {
            sessionCheckboxes.forEach(cb => cb.checked = this.checked);
        });

        // When any session checkbox is clicked
        sessionCheckboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                allCheckbox.checked = sessionCheckboxes.every(cb => cb.checked);
            });
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const shift_name = this.dataset.shift_name;
            const start_time = this.dataset.start_time;
            const end_time = this.dataset.end_time;
            const day_off = this.dataset.day_off;
            const description = this.dataset.description;
            const status = this.dataset.status;
            
            document.getElementById('edit-id').value = id;
            document.getElementById('shift_name').value = shift_name;
            document.getElementById('start_time').value = start_time;
            document.getElementById('end_time').value = end_time;
            document.getElementById('day_off').value = day_off;
            document.getElementById('description').value = description;
            document.getElementById('status').checked = status == 1;

            // Set form action dynamically
            document.getElementById('editForm').action = `/shift/${id}`;
        });
    });
});

</script>


@endsection
