<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeSalary;
use App\Models\Employee;
use Carbon\Carbon;


class EmployeeSalaryController extends Controller
{
   

    public function index()
    {
        $employees = Employee::latest()->get();
        $employees_view = EmployeeSalary::with('employee')->latest()->get();
        $employeeSalary = EmployeeSalary::latest()->get();
        return view('employee-salary', compact('employeeSalary','employees','employees_view'));
    }
    
    public function showPayslip($id)
    {

        $salary = EmployeeSalary::with('employee')->findOrFail($id);
        return view('payslip', compact('salary'));
    }
    

    public function store(Request $request)
    {
        $request->merge([
            'payment_date' => Carbon::createFromFormat('d-m-Y', $request->payment_date)->format('Y-m-d'),
        ]);
        $validated = $request->validate([
            'employee_id' => 'required|integer',
            'payment_date' => 'required|date_format:Y-m-d',
            'basic_salary' => 'nullable|numeric',
            'payment_method' => 'nullable|string',
            'reference_code' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:paid,unpaid',

            // Allowances
            'allowance1' => 'nullable|numeric',
            'allowance2' => 'nullable|numeric',
            'allowance3' => 'nullable|numeric',
            'bonus' => 'nullable|numeric',
            'others1' => 'nullable|numeric',

            // Deductions
            'deduction1' => 'nullable|numeric',
            'deduction2' => 'nullable|numeric',
            'deduction3' => 'nullable|numeric',
            'deduction4' => 'nullable|numeric',
            'others' => 'nullable|numeric',

            'total_deduction' => 'nullable|numeric',
            'net_salary' => 'nullable|numeric',
        ]);

        EmployeeSalary::create($validated);

        return redirect()->back()->with('success', 'Salary record added successfully!');
    }
    public function edit($id) {
        $employee_salary = EmployeeSalary::findOrFail($id);
        $employees = Employee::all();
        return view('employee-salary', compact('employee_salary','employees'));
    }
}
