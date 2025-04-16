<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee; 

class EmployeeController extends Controller
{
    // Store method to handle the POST request from the form
    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:employees',
            'contact_number' => 'required|string|max:15',
            // Add more validation rules as needed
        ]);

        // Create a new employee record
        $employee = new Employee();
        $employee->first_name = $request->first_name;
        $employee->last_name = $request->last_name;
        $employee->email = $request->email;
        $employee->contact_number = $request->contact_number;
        // Add other fields as necessary

        // Save to the database
        $employee->save();

        // Redirect back with a success message
        return redirect()->route('employee.index')->with('success', 'Employee added successfully!');
    }
}
