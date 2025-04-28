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
                <a href="#" class="btn btn-primary" id="add-payroll-btn" data-bs-toggle="modal" data-bs-target="#add-payroll"><i class="ti ti-circle-plus me-1"></i>Add Payroll</a>
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
                                            @useronly
                                            <a class="p-2 me-2 edit-btn" href="javascript:void(0);" 
                                            
                                            data-id="{{ $employeeSalary->id }}"
                                            data-name="{{ $employeeSalary->employee->first_name . ' ' . $employeeSalary->employee->last_name }}"
                                            data-payment_date="{{ $employeeSalary->payment_date }}"
                                            data-basic_salary="{{ $employeeSalary->basic_salary }}"
                                            data-payment_method="{{ $employeeSalary->payment_method }}"
                                            data-reference_code="{{ $employeeSalary->reference_code }}"
                                            data-notes="{{ $employeeSalary->notes }}"
                                            data-status="{{ $employeeSalary->status }}"
                                            data-allowance1="{{ $employeeSalary->allowance1 }}"
                                            data-allowance2="{{ $employeeSalary->allowance2 }}"
                                            data-allowance3="{{ $employeeSalary->allowance3 }}"
                                            data-bonus="{{ $employeeSalary->bonus }}"
                                            data-others1="{{ $employeeSalary->others1 }}"
                                            data-deduction1="{{ $employeeSalary->deduction1 }}"
                                            data-deduction2="{{ $employeeSalary->deduction2 }}"
                                            data-deduction3="{{ $employeeSalary->deduction3 }}"
                                            data-deduction4="{{ $employeeSalary->deduction4 }}"
                                            data-others="{{ $employeeSalary->others }}"
                                            data-total_deduction="{{ $employeeSalary->total_deduction }}"
                                            data-total_allowance="{{ $employeeSalary->total_allowance }}"
                                            data-net_salary="{{ $employeeSalary->net_salary }}"

                                            data-bs-toggle="modal" 
                                            data-bs-target="#edit-employee-salary">
                                                <i data-feather="edit" class="feather-edit"></i>
                                            </a>
                                            <a data-bs-toggle="modal"
                                             data-bs-target="#delete-modal"
                                             data-id="{{ $employeeSalary->id }}"
                                             class="p-2 delete-btn" 
                                             href="javascript:void(0);">
                                                <i data-feather="trash-2" class="feather-trash-2"></i>
                                            </a>
                                            @enduseronly
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
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function () {
                const employeeName = this.dataset.name;

                // Set the employee name in the display field
                document.getElementById('selected-employee-name').textContent = employeeName;
                // Grab the data attributes
                const id = this.dataset.id;
                const payment_date = this.dataset.payment_date;
                const basic_salary = this.dataset.basic_salary;
                const payment_method = this.dataset.payment_method;
                const reference_code = this.dataset.reference_code;
                const status = this.dataset.status;
                const others = this.dataset.others;
                const others1 = this.dataset.others1;
                const net_salary = this.dataset.net_salary;
                const notes = this.dataset.notes;

                // Get allowance and deduction values
                let allowance1 = parseFloat(this.dataset.allowance1) || 0;
                let allowance2 = parseFloat(this.dataset.allowance2) || 0;
                let allowance3 = parseFloat(this.dataset.allowance3) || 0;
                let bonus = parseFloat(this.dataset.bonus) || 0;
                let deduction1 = parseFloat(this.dataset.deduction1) || 0;
                let deduction2 = parseFloat(this.dataset.deduction2) || 0;
                let deduction3 = parseFloat(this.dataset.deduction3) || 0;
                let deduction4 = parseFloat(this.dataset.deduction4) || 0;

                // Calculate totals
                const calculateTotals = () => {
                    const totalAllowance = allowance1 + allowance2 + allowance3 + bonus;
                    const totalDeduction = deduction1 + deduction2 + deduction3 + deduction4;
                    const calculatedNetSalary = parseFloat(basic_salary) + totalAllowance - totalDeduction;

                    // Update totals dynamically
                    document.getElementById('total_allowance').value = totalAllowance;
                    document.getElementById('total_deduction').value = totalDeduction;
                    document.getElementById('net_salary').value = calculatedNetSalary;
                };

                // Format payment date (d-m-Y to Y-m-d)
                const formattedPaymentDate = payment_date.split('-').reverse().join('-'); 

                // Assign values to the modal fields
                document.getElementById('salary_id').value = id;
                document.getElementById('payment_date').value = formattedPaymentDate;
                document.getElementById('basic_salary').value = basic_salary;
                document.getElementById('payment_method').value = payment_method;
                document.getElementById('reference_code').value = reference_code;
                document.getElementById('notes').value = notes;

                // Radio buttons for status
                if (status === 'paid') {
                    document.getElementById('status_paid').checked = true;
                } else {
                    document.getElementById('status_unpaid').checked = true;
                }

                // Allowances
                document.getElementById('allowance1').value = allowance1;
                document.getElementById('allowance2').value = allowance2;
                document.getElementById('allowance3').value = allowance3;
                document.getElementById('bonus').value = this.dataset.bonus;
                document.getElementById('others1').value = others1;

                // Deductions
                document.getElementById('deduction1').value = deduction1;
                document.getElementById('deduction2').value = deduction2;
                document.getElementById('deduction3').value = deduction3;
                document.getElementById('deduction4').value = this.dataset.deduction4;
                document.getElementById('others').value = others;

                // Call initial calculation
                calculateTotals();

                // Event listeners for dynamic changes
                document.getElementById('allowance1').addEventListener('input', function () {
                    allowance1 = parseFloat(this.value) || 0;
                    calculateTotals();
                });
                document.getElementById('allowance2').addEventListener('input', function () {
                    allowance2 = parseFloat(this.value) || 0;
                    calculateTotals();
                });
                document.getElementById('allowance3').addEventListener('input', function () {
                    allowance3 = parseFloat(this.value) || 0;
                    calculateTotals();
                });
                document.getElementById('bonus').addEventListener('input', function () {
                    bonus = parseFloat(this.value) || 0;
                    calculateTotals();
                });
                document.getElementById('deduction1').addEventListener('input', function () {
                    deduction1 = parseFloat(this.value) || 0;
                    calculateTotals();
                });
                document.getElementById('deduction2').addEventListener('input', function () {
                    deduction2 = parseFloat(this.value) || 0;
                    calculateTotals();
                });
                document.getElementById('deduction3').addEventListener('input', function () {
                    deduction3 = parseFloat(this.value) || 0;
                    calculateTotals();
                });
                document.getElementById('deduction4').addEventListener('input', function () {
                    deduction4 = parseFloat(this.value) || 0;
                    calculateTotals();
                });
            });
        });
        // Add to Payroll (New Record)
        document.getElementById('add-payroll-btn').addEventListener('click', function () {
            // Reset all fields for new payroll entry
            document.getElementById('salary_id1').value = '';
            document.getElementById('payment_date1').value = '';
            document.getElementById('basic_salary1').value = '';
            document.getElementById('payment_method1').value = '';
            document.getElementById('reference_code1').value = '';
            document.getElementById('notes1').value = '';

            // Clear the allowance and deduction fields
            document.getElementById('allowance11').value = '';
            document.getElementById('allowance22').value = '';
            document.getElementById('allowance33').value = '';
            document.getElementById('bonus1').value = '';
            document.getElementById('others11').value = '';

            document.getElementById('deduction11').value = '';
            document.getElementById('deduction22').value = '';
            document.getElementById('deduction33').value = '';
            document.getElementById('deduction44').value = '';
            document.getElementById('others22').value = '';

            // Reset totals
            document.getElementById('total_allowance1').value = '';
            document.getElementById('total_deduction1').value = '';
            document.getElementById('net_salary1').value = '';

            

            // Add dynamic event listeners for the new entry
            let allowance11 = 0, allowance22 = 0, allowance33 = 0, bonus1 = 0;
            let deduction11 = 0, deduction22 = 0, deduction33 = 0, deduction44 = 0;

            const calculateTotals = () => {
                const totalAllowance = allowance11 + allowance22 + allowance33 + bonus1;
                const totalDeduction = deduction11 + deduction22 + deduction33 + deduction44;
                const calculatedNetSalary = parseFloat(document.getElementById('basic_salary1').value) + totalAllowance - totalDeduction;

                // Update totals dynamically
                document.getElementById('total_allowance1').value = totalAllowance;
                document.getElementById('total_deduction1').value = totalDeduction;
                document.getElementById('net_salary1').value = calculatedNetSalary;
            };

            document.getElementById('allowance11').addEventListener('input', function () {
                allowance1 = parseFloat(this.value) || 0;
                calculateTotals();
            });
            document.getElementById('allowance22').addEventListener('input', function () {
                allowance2 = parseFloat(this.value) || 0;
                calculateTotals();
            });
            document.getElementById('allowance33').addEventListener('input', function () {
                allowance3 = parseFloat(this.value) || 0;
                calculateTotals();
            });
            document.getElementById('bonus1').addEventListener('input', function () {
                bonus = parseFloat(this.value) || 0;
                calculateTotals();
            });
            document.getElementById('deduction11').addEventListener('input', function () {
                deduction1 = parseFloat(this.value) || 0;
                calculateTotals();
            });
            document.getElementById('deduction22').addEventListener('input', function () {
                deduction2 = parseFloat(this.value) || 0;
                calculateTotals();
            });
            document.getElementById('deduction33').addEventListener('input', function () {
                deduction3 = parseFloat(this.value) || 0;
                calculateTotals();
            });
            document.getElementById('deduction44').addEventListener('input', function () {
                deduction4 = parseFloat(this.value) || 0;
                calculateTotals();
            });
        });

    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                document.getElementById('delete-id').value = id;
            });
        });
    });
 </script>



<!-- add payroll modal -->
 @useronly
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
                    <input type="hodden" id="Salary_id1" >

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
                                        <input type="text" name="payment_date" id="payment_date1" class="datetimepicker form-control" placeholder="Select Date" >
                                    </div>
                                </div>
                            </div>
                            <div class="text-title">
                                <h5 class="mb-2">Salary Information</h5>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Basic Salary <span>*</span></label>
                                    <input type="text" name="basic_salary" id="basic_salary1" class="text-form form-control">
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
                                    <input type="text" name="reference_code" id="reference_code1" class="form-control">
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
                                    <input type="text" name="allowance1" id="allowance11" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label">Conveyance <span>*</span></label>
                                    <input type="text" name="allowance2" id="allowance22" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label">Medical Allowance <span>*</span></label>
                                    <input type="text" name="allowance3" id="allowance33" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label">Bonus <span>*</span></label>
                                    <input type="text" name="bonus" id="bonus1" class="form-control">
                                </div>
                            </div>
                            <div class="d-flex align-items-end border-bottom mb-3">
                                <div class="mb-3 flex-grow-1">
                                    <label class="form-label">Others</label>
                                    <input type="text" name="others1" id="others11" class="text-form form-control">
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
                                    <input type="text" name="deduction1" id="deduction11" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label">Professional Tax <span>*</span></label>
                                    <input type="text" name="deduction2" id="deduction22" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label">TDS <span>*</span></label>
                                    <input type="text" name="deduction3" id="deduction33" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label">Loans & Others <span>*</span></label>
                                    <input type="text" name="deduction4" id="deduction44" class="form-control">
                                </div>
                            </div>
                            <div class="d-flex align-items-end border-bottom mb-3">
                                <div class="mb-3 flex-grow-1">
                                    <label class="form-label">Others</label>
                                    <input type="text" name="others" id="others22" class="text-form form-control">
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
                                    <input type="text" name="total_allowance" id="total_allowance1" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label">Total Deduction <span>*</span></label>
                                    <input type="text" name="total_deduction" id="total_deduction1" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label">Net Salary <span>*</span></label>
                                    <input type="text" name="net_salary" id="net_salary1" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="d-flex align-items-center justify-content-end">
                                    <button type="button" class="btn btn-reset me-2"data-bs-dismiss="modal">Back</button>
                                    <button type="submit" class="btn btn-save">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
 @enduseronly
@endsection