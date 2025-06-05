<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeSalary;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class EmployeeSalaryController extends Controller
{
   

    public function index()
    {

        $employees = Employee::latest()->get();
        $employees_view = EmployeeSalary::with('employee')->latest()->get();
        $employeeSalary = EmployeeSalary::latest()->get();

        
        return view('employee-salary', compact('employeeSalary','employees','employees_view'));
    }
    
    public function showPayslip(Request $request)
    {
        $id = Crypt::decryptString($request->id);
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
    public function edit($id) 
    {
        $employeeSalary = EmployeeSalary::findOrFail($id);
        $employees = Employee::all(); // Assuming your model is Employee
        return view('employee-salary', compact('employeeSalary', 'employees'));
    }
    public function update(Request $request)
    {
        $request->merge([
            'payment_date' => Carbon::createFromFormat('d-m-Y', $request->payment_date)->format('Y-m-d'),
        ]);

        $request->validate([
            'salary_id' => 'required|exists:employee_salaries,id',
            'payment_date' => 'required|date',
            'basic_salary' => 'required|numeric',
            'payment_method' => 'nullable|string',
            'reference_code' => 'nullable|string',
            'status' => 'required|string',
            'allowance1' => 'nullable|numeric',
            'allowance2' => 'nullable|numeric',
            'allowance3' => 'nullable|numeric',
            'bonus' => 'nullable|numeric',
            'deduction1' => 'nullable|numeric',
            'deduction2' => 'nullable|numeric',
            'deduction3' => 'nullable|numeric',
            'deduction4' => 'nullable|numeric',
            'others' => 'nullable|string',
            'others1' => 'nullable|string',
            'net_salary' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $salary = EmployeeSalary::findOrFail($request->salary_id);

        $salary->update([
            'payment_date' => $request->payment_date,
            'basic_salary' => $request->basic_salary,
            'payment_method' => $request->payment_method,
            'reference_code' => $request->reference_code,
            'status' => $request->status,
            'allowance1' => $request->allowance1,
            'allowance2' => $request->allowance2,
            'allowance3' => $request->allowance3,
            'bonus' => $request->bonus,
            'deduction1' => $request->deduction1,
            'deduction2' => $request->deduction2,
            'deduction3' => $request->deduction3,
            'deduction4' => $request->deduction4,
            'others' => $request->others,
            'others1' => $request->others1,
            'net_salary' => $request->net_salary,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Salary record updated successfully!');
    }
    public function destroy(Request $request)
    {
        $salary = EmployeeSalary::findOrFail($request->id);
        $salary->delete();
    
        return redirect()->back()->with('success', 'Salary record deleted successfully!');
    }
    
}
