<?php $page = 'payslip'; ?>
@extends('layout.mainlayout')
@section('content')
@php
    $basic = $salary->basic_salary ?? 0;
    $allow1 = $salary->allowance1 ?? 0;
    $allow2 = $salary->allowance2 ?? 0;
    $allow3 = $salary->allowance3 ?? 0;
    $bonus = $salary->bonus ?? 0;

    $deduct1 = $salary->deduction1 ?? 0;
    $deduct2 = $salary->deduction2 ?? 0;
    $deduct3 = $salary->deduction3 ?? 0;
    $deduct4 = $salary->deduction4 ?? 0;
    $others1 = $salary->others1 ?? 0;

    $total_earnings = $basic + $allow1 + $allow2 + $allow3 + $bonus;
    $total_deductions = $deduct1 + $deduct2 + $deduct3 + $deduct4 + $others1;
    $net_salary = $total_earnings - $total_deductions;
@endphp

<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Payslip</h4>
                </div>
            </div>
            <ul class="table-top-head">
                <li>
                <button type="button" class="btn btn-primary me-2"><i class="ti ti-mail me-2"></i>Send Email</button>
                </li>
                
                <li>
                    <a data-bs-toggle="tooltip" onclick="downloadPayslipPDF()" data-bs-placement="top" title="Pdf"><img src="{{URL::asset('build/img/icons/pdf.svg')}}" alt="img"></a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" onclick="printPayslip()" data-bs-placement="top" title="Print"><i data-feather="printer" class="feather-rotate-ccw"></i></a>
                </li>
                <li class="me-2">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
                </li>
            </ul>
            <div class="page-btn">
                <a href="{{url('employee-salary')}}" class="btn btn-secondary"><i data-feather="arrow-left" class="me-2"></i>Back to Payroll</a>
            </div>
        </div>
        

        <!-- Invoices -->
        <div id="printArea">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Payslip for the Month of {{ \Carbon\Carbon::parse($salary->payment_date)->format('F Y') }}</h4>
                    
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <p class="mb-1">Employee Name : <span class="text-gray-9">{{ $salary->employee->first_name }} {{ $salary->employee->last_name }}</span></p>
                            <p class="mb-1">Employee ID :  <span class="text-gray-9">{{ $salary->employee->emp_code }} </span></p>
                            <p class="mb-1">Payment Mode:  <span class="text-gray-9">{{ $salary->payment_method }} </span></p>
                            <p class="mb-1">Reference Code:  <span class="text-gray-9">{{ $salary->reference_code }} </span></p>
                            <p >Add. Info:  <span class="text-gray-9">{{ $salary->notes }} </span></p>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="mb-3">
                            <p class="mb-1">Location :  <span class="text-gray-9">{{ $salary->employee->nationality }} </span></p>
                            <p>Pay Period :   <span class="text-gray-9">{{ \Carbon\Carbon::parse($salary->payment_date)->format('F Y') }}</span></p>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="card">
                        <div class="row p-3">
                            <div class="col-6">
                                <h4>Earnings</h4>
                            </div>
                            <div class="col-6">
                                <h4>Deductions</h4>
                            </div>
                        </div>
                        <div class="table-responsive mb-3">
                            <div>
                                
                            </div>
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Pay Type</th>
                                        <th>Amount</th>
                                        <th>Pay Type</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Basic Salary</td>
                                        <td>Ksh {{ number_format($salary->basic_salary ?? 0, 2) }}</td>
                                        <td>PF</td>
                                        <td>Ksh {{ number_format($salary->deduction1 ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>House Allowance</td>
                                        <td>Ksh {{ number_format($salary->allowance1 ?? 0, 2) }}</td>
                                        <td>Professional  Tax</td>
                                        <td>Ksh {{ number_format($salary->deduction2 ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Conveyance</td>
                                        <td>Ksh {{ number_format($salary->allowance2 ?? 0, 2) }}</td>
                                        <td>TDS</td>
                                        <td>Ksh {{ number_format($salary->deduction3 ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Medical Allowance</td>
                                        <td>Ksh {{ number_format($salary->allowance3 ?? 0, 2) }}</td>
                                        <td>Loans & Others</td>
                                        <td>Ksh {{ number_format($salary->deduction4 ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Bonus</td>
                                        <td>Ksh {{ number_format($salary->bonus ?? 0, 2) }}</td>
                                        <td>Bonus</td>
                                        <td>Ksh {{ number_format($salary->others1 ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td><h6>Total Earnings</h6></td>
                                        <td><h6>Ksh {{ number_format($total_earnings, 2) }}</h6></td>
                                        <td><h6>Total Deductions</h6></td>
                                        <td><h6>Ksh {{ number_format($salary->total_deduction ?? 0, 2) }}</h6></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center border-bottom mb-3">
                    <div class="mb-3 me-3">
                        <h6 class="mb-2">Net Salary</h6>
                    </div>
                    <div class="mb-3">
                        <h6 class="mb-2">Ksh {{ number_format($salary->net_salary ?? 0, 2) }} Only</h6>
                    </div>
                </div>
                <div class="text-center">
                    <div class="mb-3">
                        <img src="{{URL::asset('build/img/logo.png')}}" width="130" class="img-fluid" alt="logo">
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!-- /Invoices -->
    </div>
    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0"> &copy; JavaPA. All Right Reserved</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div>
<script>
    function printPayslip() {
        var printContents = document.getElementById('printArea').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;

        // Reload to restore event listeners or dynamic components
    }
    function downloadPayslipPDF() {
        var printContents = document.getElementById('printArea').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>

@endsection
