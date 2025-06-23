<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\Subject;
use App\Models\Schoolclass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with(['subjects'])->get();
        $subjects = Subject::all();
        $schoolclasses = SchoolClass::with(['stream', 'level'])->where('status', 1)->get();
        return view('teachers', compact('teachers', 'subjects', 'schoolclasses'));
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
            'subjects' => 'nullable|array',
            'subjects.*' => 'integer|exists:subjects,id',
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

            // Create User for the teacher
            $user = User::create([
                'code' => 'TCHR' . str_pad($teacher->id, 5, '0', STR_PAD_LEFT),  // e.g. TCHR00001
                'role' => 'teacher',
                'email' => $teacher->email,
                'name' => $teacher->first_name . ' ' . $teacher->last_name,
                'phone' => $teacher->phone,
                'password' => Hash::make('Password'), // Default password - consider sending password reset email
                'status' => true,
            ]);

            // Link user_id in teacher record
            $teacher->update(['user_id' => $user->id]);

            // Attach subjects
            if (!empty($validated['subjects'])) {
                foreach ($validated['subjects'] as $subjectId) {
                    TeacherSubject::create([
                        'teacher_id' => $teacher->id,
                        'subject_id' => $subjectId,
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Teacher added successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error adding teacher: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while adding the teacher.');
        }
    }

}
