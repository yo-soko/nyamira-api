<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Shift;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function index()
    {
        $shifts = Shift::all();
        $employees = Employee::latest()->get();
        $departments = Department::all();
        $designations = Designation::all();
        return view('add-employee', compact('employees','shifts','departments','designations'));
    }

    public function list()
    {
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 1)->count(); // assuming 1 = active
        $inactiveEmployees = Employee::where('status', 0)->count(); // assuming 0 = inactive
        $newJoiners = Employee::where('joining_date', '>=', Carbon::now()->subDays(30))->count();

        if (session('user_type') == 'Employee') {
            // Fetch only the logged-in employee's details
            $employees = Employee::where('user_id', session('user_id'))->get();
        } 
        else if (session('user_type') == 'Admin') {
            $employees = Employee::latest()->get();

        }
        else {
            // return an empty collection)
            $employees = collect(); 
        }
        return view('employees-list', compact('employees','totalEmployees', 'activeEmployees', 'inactiveEmployees', 'newJoiners'));
    }

    public function show($id)
    {
        // Retrieve the employee by ID
        $employee = Employee::findOrFail($id);

        // Pass the employee to the view
        return view('employee-details', compact('employee'));
    }

    public function edit($id)
    {
        // Find the employee by ID
        $employee = Employee::findOrFail($id);

        $departments = Department::all();
        $designations = Designation::all();
        $shifts = Shift::all();
        // Pass the employee data to the view
        return view('edit-employee', compact('employee', 'designations', 'shifts', 'departments'));
    }

    public function create()
    {
        $departments = Department::all();
        $designations = Designation::all();
        $shifts = Shift::all();

        return view('add-employee', compact('departments', 'designations', 'shifts'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'dob' => Carbon::createFromFormat('d-m-Y', $request->dob)->format('Y-m-d'),
            'joining_date' => Carbon::createFromFormat('d-m-Y', $request->joining_date)->format('Y-m-d'),
        ]);

        $data = $request->validate([
            'profile_photo' => 'nullable|image|max:2048',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'contact_number' => 'required',
            'emp_code' => 'required|unique:employees,emp_code',
            'dob' => 'required|date_format:Y-m-d',
            'gender' => 'required',
            'nationality' => 'required|string',
            'joining_date' => 'required|date_format:Y-m-d',
            'shift_id' => 'required',
            'department_id' => 'required',
            'designation_id' => 'required',
            'blood_group' => 'nullable|string',
            'about' => 'nullable|string|max:60',
            'address' => 'nullable|string',
            'country' => 'nullable|string',
            'zipcode' => 'nullable|string',
            'emergency_contact1' => 'nullable|string',
            'emergency_relation1' => 'nullable|string',
            'emergency_name1' => 'nullable|string',
            'emergency_contact2' => 'nullable|string',
            'emergency_relation2' => 'nullable|string',
            'emergency_name2' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'branch' => 'nullable|string',
            'password' => 'required|confirmed',
        ]);

        $nullableFields = [
            'profile_photo', 'blood_group', 'about', 'address', 'country', 'zipcode',
            'emergency_contact1', 'emergency_relation1', 'emergency_name1',
            'emergency_contact2', 'emergency_relation2', 'emergency_name2',
            'bank_name', 'account_number', 'branch',
        ];

        foreach ($nullableFields as $field) {
            if (!array_key_exists($field, $data)) {
                $data[$field] = null;
            }
        }

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $data['status'] = $request->has('status') ? 1 : 0;
        $data['password'] = Hash::make($data['password']);

        DB::beginTransaction();

        try {
            // Step 1: Create user
            $user = User::create([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['contact_number'],
                'code' => $data['emp_code'],
                'password' => $data['password'], // already hashed
                'role' => 'Employee',
                'status' => true,
                'profile_picture' => $data['profile_photo'] ?? null,
            ]);

            // Step 2: Attach user_id to employee data
            $data['user_id'] = $user->id;

            // Step 3: Create employee with user_id
            Employee::create($data);

            DB::commit();

            return redirect()->back()->with('success', 'Employee and user account added successfully!');
        } 
        catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating employee/user: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $employeeId = $request->input('employee_id', $id);
        $employee = Employee::findOrFail($employeeId);

        // Convert date formats before validation
        $request->merge([
            'dob' => Carbon::createFromFormat('d-m-Y', $request->dob)->format('Y-m-d'),
            'joining_date' => Carbon::createFromFormat('d-m-Y', $request->joining_date)->format('Y-m-d'),
        ]);

        $data = $request->validate([
            'profile_photo' => 'nullable|image|max:2048',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'contact_number' => 'required',
            'emp_code' => 'required|unique:employees,emp_code,' . $id . ',id',
            'dob' => 'required|date_format:Y-m-d',
            'gender' => 'required',
            'nationality' => 'nullable|string',
            'joining_date' => 'required|date_format:Y-m-d',
            'shift_id' => 'required',
            'department_id' => 'required',
            'designation_id' => 'required',
            'blood_group' => 'nullable|string',
            'about' => 'nullable|string|max:60',
            'address' => 'nullable|string',
            'country' => 'nullable|string',
            'zipcode' => 'nullable|string',
            'emergency_contact1' => 'nullable|string',
            'emergency_relation1' => 'nullable|string',
            'emergency_name1' => 'nullable|string',
            'emergency_contact2' => 'nullable|string',
            'emergency_relation2' => 'nullable|string',
            'emergency_name2' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'branch' => 'nullable|string',
            'password' => 'nullable|confirmed|min:4',
        ]);

        $nullableFields = [
            'profile_photo', 'blood_group', 'about', 'address', 'country', 'zipcode',
            'emergency_contact1', 'emergency_relation1', 'emergency_name1',
            'emergency_contact2', 'emergency_relation2', 'emergency_name2',
            'bank_name', 'account_number', 'branch',
        ];
        foreach ($nullableFields as $field) {
            if (!array_key_exists($field, $data)) {
                $data[$field] = null;
            }
        }

        // Handle profile photo
        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        } else {
            unset($data['profile_photo']);
        }

        $data['status'] = $request->has('status') ? 1 : 0;

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        DB::beginTransaction();

        try {
            // Update Employee
            $employee->update($data);

            // If linked to a user, update user too
            if ($employee->user_id) {
                $user = User::find($employee->user_id);
                if ($user) {
                    $user->update([
                        'name' => $data['first_name'] . ' ' . $data['last_name'],
                        'email' => $data['email'],
                        'phone' => $data['contact_number'],
                        'code' => $data['emp_code'],
                        'profile_picture' => $data['profile_photo'] ?? $user->profile_picture,
                        'status' => $data['status'],
                        'password' => $data['password'] ?? $user->password, // Only update if present
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('edit-employee', $id)->with('success', 'Employee and user updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('edit-employee', $id)->with('error', 'Update failed: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        // Delete profile photo if exists
        if ($employee->profile_photo && Storage::disk('public')->exists($employee->profile_photo)) {
            Storage::disk('public')->delete($employee->profile_photo);
        }

        if ($employee->delete()) {
            return redirect()->back()->with('success', 'Employee deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to delete employee. Please try again.');
        }
    }
    public function migrateEmployeesToUsers()
    {
    
        $employees = Employee::whereNull('user_id')->get();

        $count = 0;

        DB::beginTransaction();

        try {
            foreach ($employees as $employee) {
                if (User::where('email', $employee->email)->exists()) {
                    continue;
                }

                $user = User::create([
                    'name' => $employee->first_name . ' ' . $employee->last_name,
                    'email' => $employee->email,
                    'phone' => $employee->contact_number,
                    'code' => $employee->emp_code,
                    'role' => 'Employee',
                    'profile_picture' => $employee->profile_photo,
                    'password' => Hash::make('password123'),
                    'status' => 1,
                ]);

                $employee->user_id = $user->id;
                $employee->save();

                $count++;
            }

            DB::commit();
            return "✅ Migrated {$count} employees successfully!";
        } catch (\Exception $e) {
            DB::rollBack();
            return "❌ Migration failed: " . $e->getMessage();
        }
    }

}
