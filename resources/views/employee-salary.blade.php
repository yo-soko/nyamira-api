<?php $page = 'employee-salary'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast') 
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Employee Salary</h4>
                    <h6>Manage your employee salaries</h6>
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
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Print"><i data-feather="printer" class="feather-rotate-ccw"></i></a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
                </li>
            </ul>
            <div class="page-btn">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-payroll"><i class="ti ti-circle-plus me-1"></i>Add Payroll</a>
            </div>
        </div>
            <!-- product list -->
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
                                    <a href="javascript:void(0);" class="dropdown-item rounded-1">Paid</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item rounded-1">Unpaid</a>
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
                                    <th class="no-sort">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>ID</th>
                                    <th>Employee</th>
                                    <th>Email</th>
                                    <th>Salary</th>
                                    <th>Status</th>
                                    <th class="no-sort"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees_view as $employeeSalary)
                                <tr>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>	
                                    <td>
                                    {{ $employeeSalary->employee->emp_code}}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ url('employee-details/' . $employeeSalary->id) }}" class="avatar avatar-md">
                                                <img 
                                                    src="{{ $employeeSalary->employee->profile_photo ? asset('storage/' . $employeeSalary->employee->profile_photo) : asset('build/img/users/user-33.png') }}" 
                                                    class="img-fluid" 
                                                    alt="Profile"
                                                >
                                            </a>
                                            <div class="ms-2">
                                                <p class="text-dark mb-0">
                                                    <a href="{{url('employee-details')}}">
                                                        {{ $employeeSalary->employee->first_name . ' ' . $employeeSalary->employee->last_name }}
                                                    </a>
                                                </p>
                                                <p><a>{{ $employeeSalary->employee->designation ?? 'N/A' }}</a></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $employeeSalary->employee->email ?? 'N/A' }}
                                    </td>
                                    <td>
                                        Ksh {{ number_format($employeeSalary->net_salary, 2) }}						
                                    </td>
                                    
                                    <td>
                                        <span class="badge badge-{{ $employeeSalary->status == 'paid' ? 'success' : 'danger' }} d-inline-flex align-items-center badge-xs">
                                            <i class="ti ti-point-filled me-1"></i>{{ ucfirst($employeeSalary->status) }}
                                        </span>
                                    </td>
                                    <td class="action-table-data">
                                        <div class="edit-delete-action">
                                            <a class="p-2 me-2" href="{{ url('payslip/' . $employeeSalary->id) }}">
                                                <i data-feather="eye" class="feather-eye"></i>
                                            </a>
                                            <a data-bs-toggle="modal" 
                                            data-bs-target="#edit-department" 
                                            data-id="{{ $employeeSalary->id }}"
                                            data-name="{{$employeeSalary->employee->name}}"
                                            data-image="{{ $employeeSalary->employee->profile_picture ? asset('storage/' . $user->profile_picture) : asset('build/img/users/default.png') }}"
                                            data-basic_salary="{{ $employeeSalary->employee->email }}"
                                            data-status="{{ $employeeSalary->status }}"
                                            class="p-2 me-2" href="javascript:void(0);">
                                                <i data-feather="edit" class="feather-edit"></i>
                                            </a>
                                            <a data-bs-toggle="modal" data-bs-target="#delete-modal" class="p-2" href="javascript:void(0);">
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
        <p class="mb-0">&copy; JavaPA. All Right Reserved</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div>
<script>
    function parseCurrency(input) {
        const value = parseFloat(input.value.replace(/[^0-9.-]+/g,""));
        return isNaN(value) ? 0 : value;
    }

    function calculateTotals() {
        // Get all allowances
        let totalAllowance = 0;
        document.querySelectorAll('.allowance').forEach(input => {
            totalAllowance += parseCurrency(input);
        });

        // Get all deductions
        let totalDeduction = 0;
        document.querySelectorAll('.deduction').forEach(input => {
            totalDeduction += parseCurrency(input);
        });

        // Get basic salary
        let basicSalary = parseCurrency(document.getElementById('basic_salary'));

        // Update totals
        document.getElementById('total_allowance').value = totalAllowance.toFixed(2);
        document.getElementById('total_deduction').value = totalDeduction.toFixed(2);

        // Calculate net salary
        const netSalary = basicSalary + totalAllowance - totalDeduction;
        document.getElementById('net_salary').value = netSalary.toFixed(2);
    }

    // Trigger calculation on input
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', calculateTotals);
    });
    document.querySelector('.btn-reset').addEventListener('click', () => {
        document.querySelectorAll('input').forEach(input => input.value = '');
    });

</script>

<!-- add payroll modal -->
<div class="modal fade" id="add-payroll">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="page-title">
                    <h4>Add Payroll</h4>
                </div>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('employee-salary')}}" method="POST">
                @csrf 
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Select Employee <span>*</span></label>
                                <select name="employee_id" class="select select2">
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">
                                            {{ $employee->first_name . ' ' . $employee->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="input-blocks">
                                <label>Payment Date<span class="text-danger ms-1">*</span></label>
                                <div class="input-groupicon calender-input">
                                    <i data-feather="calendar" class="info-img"></i>
                                    <input type="text" name="payment_date" class="datetimepicker form-control" placeholder="Select Date" >
                                </div>
                            </div>
                        </div>
                        <div class="text-title">
                            <h5 class="mb-2">Salary Information</h5>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Basic Salary <span>*</span></label>
                                <input type="text" name="basic_salary" class="text-form form-control">
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Payment Method <span>*</span></label>
                                <select class="select" name="payment_method">
                                    <option>Select</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Mpesa">Mpesa</option>
                                    <option value="Bank">Bank</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Reference Code<span>*</span></label>
                                <input type="text" name="reference_code" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Notes / Add. Info<span></span></label>
                                <textarea class="form-control" name="notes" rows="3"></textarea>									
                            </div>
                        </div>
                        <div class="mb-3 pb-3 border-bottom">
                            <p class="fw-semibold text-gray-9 mb-2">Status</p>
                            <div class="d-flex align-items-center">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="radio" name="status" value="paid" id="Radio-sm1" checked>
                                    <label class="form-check-label" for="Radio-sm1">
                                        Paid
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" value="unpaid" id="Radio-sm2">
                                    <label class="form-check-label" for="Radio-sm2">
                                        Unpaid
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="payroll-title">
                            <p class="fw-semibold text-gray-9 mb-2">Allowances</p>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">House Allowance <span>*</span></label>
                                <input type="text" name="allowance1" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Conveyance <span>*</span></label>
                                <input type="text" name="allowance2" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Medical Allowance <span>*</span></label>
                                <input type="text" name="allowance3" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Bonus <span>*</span></label>
                                <input type="text" name="bonus" class="form-control">
                            </div>
                        </div>
                        <div class="d-flex align-items-end border-bottom mb-3">
                            <div class="mb-3 flex-grow-1">
                                <label class="form-label">Others</label>
                                <input type="text" name="others1" class="text-form form-control">
                            </div>
                            <div class="subadd-btn mb-3 ms-3">
                                <a href="#" class="btn btn-icon btn-secondary btn-add"><i class="ti ti-circle-plus fs-16"></i></a>
                            </div>
                        </div>
                        <div class="payroll-title">
                            <p class="fw-semibold text-gray-9 mb-2">Deductions</p>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">PF <span>*</span></label>
                                <input type="text" name="deduction1" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Professional Tax <span>*</span></label>
                                <input type="text" name="deduction2" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">TDS <span>*</span></label>
                                <input type="text" name="deduction3" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Loans & Others <span>*</span></label>
                                <input type="text" name="deduction4" class="form-control">
                            </div>
                        </div>
                        <div class="d-flex align-items-end border-bottom mb-3">
                            <div class="mb-3 flex-grow-1">
                                <label class="form-label">Others</label>
                                <input type="text" name="others" class="text-form form-control">
                            </div>
                            <div class="subadd-btn mb-3 ms-3">
                                <a href="#" class="btn btn-icon btn-secondary btn-add"><i class="ti ti-circle-plus fs-16"></i></a>
                            </div>
                        </div>
                        <div class="payroll-title">
                            <p class="fw-semibold text-gray-9 mb-2">Deductions</p>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Total Allowance <span>*</span></label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Total Deduction <span>*</span></label>
                                <input type="text" name="total_deduction" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Net Salary <span>*</span></label>
                                <input type="text" name="net_salary" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="d-flex align-items-center justify-content-end">
                                <button type="button" class="btn btn-previw me-2">Preview</button>
                                <button type="button" class="btn btn-reset me-2">Reset</button>
                                <button type="submit" class="btn btn-save">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
 
@endsection