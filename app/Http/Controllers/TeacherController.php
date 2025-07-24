<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with(['subjects'])->get();
        $subjects = Subject::all();
        $schoolclasses = SchoolClass::with(['stream', 'level'])->where('status', 1)->get();
        $departments = \App\Models\Department::all();
        return view('teachers', compact('teachers', 'subjects', 'schoolclasses', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:100',
            'id_no' => 'required|string|max:255',
            'address' => 'required|string',
            'education_level' => 'required|string|max:255',
            'years_of_experience' => 'required|integer|min:0',
            'gender' => 'required|string|max:50',
            'department' => 'required|integer|exists:departments,id',
            'status' => 'required|boolean',
            'subject_class' => 'nullable|array',
            'subject_class.*.subject_id' => 'required|integer|exists:subjects,id',
            'subject_class.*.class_id' => 'required|integer|exists:school_classes,id',
        ]);

        DB::beginTransaction();

        try {
            $teacher = Teacher::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'id_no' => $validated['id_no'],
                'address' => $validated['address'],
                'education_level' => $validated['education_level'],
                'years_of_experience' => $validated['years_of_experience'],
                'gender' => $validated['gender'],
                'department_id' => $validated['department'],
                'status' => $validated['status'],
            ]);

            // Create User
            $user = User::create([
                'code' => 'TCHR' . str_pad($teacher->id, 5, '0', STR_PAD_LEFT),
                'role' => 'teacher',
                'email' => $teacher->email,
                'name' => $teacher->first_name . ' ' . $teacher->last_name,
                'phone' => $teacher->phone,
                'password' => Hash::make('Password'), // default password
                'status' => true,
            ]);


            // 🔐 Check if current user is teacher and attach user_id if yes
            if (auth()->check() && auth()->user()->hasRole('teacher')) {
                $teacher->user_id = auth()->id();
            } else {
                $teacher->user_id = $user->id;
            }

            $teacher->save();

            $teacher->update(['user_id' => $user->id]);

            // Attach subject-class pairs
            if (!empty($validated['subject_class'])) {
                foreach ($validated['subject_class'] as $pair) {
                    TeacherSubject::create([
                        'teacher_id' => $teacher->id,
                        'subject_id' => $pair['subject_id'],
                        'class_id'   => $pair['class_id'],
                    ]);
                }
            }

            // Create Employee Record
            \App\Models\Employee::create([
                'first_name' => $teacher->first_name,
                'last_name' => $teacher->last_name,
                'email' => $teacher->email,
                'contact_number' => $teacher->phone,
                'emp_code' => 'EMP' . str_pad($teacher->id, 5, '0', STR_PAD_LEFT),
                'dob' => $teacher->date_of_birth,
                'gender' => $teacher->gender,
                'nationality' => 'Kenyan', // default or derive from form
                'joining_date' => now()->format('Y-m-d'),
                'shift_id' => 1, // default shift or derive from form
                'department_id' => $teacher->department_id,
                'designation_id' => 1, // default designation (e.g., teacher)
                'address' => $teacher->address,
                'password' => Hash::make('Password'),
                'status' => true,
                'user_id' => $user->id,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Teacher added successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error adding teacher: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while adding the teacher.');
        }
    }

    public function edit($id)
    {
        $teacher = Teacher::with(['subjects', 'classes'])->findOrFail($id);
        $subjects = Subject::all();
        $schoolclasses = SchoolClass::with(['stream', 'level'])->where('status', 1)->get();
        $departments = \App\Models\Department::all();

        return view('teachers.edit', compact('teacher', 'subjects', 'schoolclasses', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'email' => 'required|email|unique:users,email,' . $id . ',id',
            'phone' => 'required|string|max:100',
            'id_no' => 'required|string|max:255',
            'address' => 'required|string',
            'education_level' => 'required|string|max:255',
            'years_of_experience' => 'required|integer|min:0',
            'gender' => 'required|string|max:50',
            'department' => 'required|integer|exists:departments,id',
            'status' => 'required|boolean',
            'subject_class' => 'nullable|array',
            'subject_class.*.subject_id' => 'required|integer|exists:subjects,id',
            'subject_class.*.class_id' => 'required|integer|exists:school_classes,id',
        ]);

        DB::beginTransaction();

        try {
            $teacher = Teacher::findOrFail($id);

            $teacher->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'id_no' => $validated['id_no'],
                'address' => $validated['address'],
                'education_level' => $validated['education_level'],
                'years_of_experience' => $validated['years_of_experience'],
                'gender' => $validated['gender'],
                'department_id' => $validated['department'],
                'status' => $validated['status'],
            ]);

            // Update User
            $user = User::find($teacher->user_id);
            if ($user) {
                $user->update([
                    'email' => $teacher->email,
                    'name' => $teacher->first_name . ' ' . $teacher->last_name,
                    'phone' => $teacher->phone,
                ]);
            }

            // Update Employee if exists
            $employee = \App\Models\Employee::where('user_id', $user->id)->first();
            if ($employee) {
                $employee->update([
                    'first_name' => $teacher->first_name,
                    'last_name' => $teacher->last_name,
                    'email' => $teacher->email,
                    'contact_number' => $teacher->phone,
                    'dob' => $teacher->date_of_birth,
                    'gender' => $teacher->gender,
                    'address' => $teacher->address,
                    'department_id' => $teacher->department_id,
                ]);
            }

            // Sync subject-class pairs
            TeacherSubject::where('teacher_id', $teacher->id)->delete();

            if (!empty($validated['subject_class'])) {
                foreach ($validated['subject_class'] as $pair) {
                    TeacherSubject::create([
                        'teacher_id' => $teacher->id,
                        'subject_id' => $pair['subject_id'],
                        'class_id'   => $pair['class_id'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating teacher: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the teacher.');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $teacher = Teacher::findOrFail($id);

            // Delete related subject-class links
            TeacherSubject::where('teacher_id', $teacher->id)->delete();

            // Delete user and employee accounts
            $user = User::find($teacher->user_id);
            if ($user) {
                \App\Models\Employee::where('user_id', $user->id)->delete();
                $user->delete();
            }

            $teacher->delete();

            DB::commit();
            return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting teacher: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the teacher.');
        }
    }



}
