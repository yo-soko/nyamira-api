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
use Illuminate\Validation\Rule;


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
        $teacher = Teacher::with('subjects', 'classes')->findOrFail($id);

        $subjects = Subject::where('status', 1)->get();
        $classes = SchoolClass::where('status', 1)->get();

        $assignedSubjects = TeacherSubject::where('teacher_id', $id)
            ->get(['subject_id', 'class_id'])
            ->toArray();

        return view('teachers.edit', compact(
            'teacher',
            'subjects',
            'classes',
            'assignedSubjects'
        ));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            // Personal Info
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'email' => ['required', 'email', Rule::unique('teachers')->ignore($id)],
            'phone' => 'nullable|string|max:20',
            'id_no' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',

            // Employment Info
            'education_level' => 'nullable|string|max:100',
            'years_of_experience' => 'nullable|integer|min:0|max:100',
            'gender' => 'required|in:Male,Female,Other',
            'department' => 'required|exists:departments,id',
            'status' => 'required|in:0,1',

            // Subject-Class Assignments
            'subject_class' => 'required|array|min:1',
            'subject_class.*.subject_id' => 'required|integer|exists:subjects,id',
            'subject_class.*.class_id' => 'required|integer|exists:school_classes,id',
        ]);

        $teacher = Teacher::findOrFail($id);

        // Update teacher info (use the correct column names)
        $teacher->update([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'id_no'      => $request->id_no,
            'address'    => $request->address,
            'education_level' => $request->education_level,
            'years_of_experience' => $request->years_of_experience,
            'gender'     => $request->gender,
            'department_id' => $request->department,   // <-- correct column name
            'status'     => $request->status,
        ]);

        // Replace old mappings with the new ones
        TeacherSubject::where('teacher_id', $teacher->id)->delete();

        foreach ($request->subject_class as $entry) {
            TeacherSubject::create([
                'teacher_id' => $teacher->id,
                'subject_id' => $entry['subject_id'],
                'class_id'   => $entry['class_id'],
            ]);
        }

        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');
    }




    public function getSubjects($id)
    {
        $teacher = Teacher::with('subjects')->findOrFail($id);
        $subjects = Subject::all();
        $schoolclasses = SchoolClass::all();

        return view('teachers.partials.subject_class_map', compact(
            'teacher',
            'subjects',
            'schoolclasses'
        ));
    }





    public function getSubjectClassMap(Teacher $teacher)
    {
        $teacher->load('subjects');
        $subjects = Subject::all();
        $schoolclasses = SchoolClass::with(['level', 'stream'])->get();

        $html = '';

        foreach ($teacher->subjects as $index => $subject) {
            $class_id = $subject->pivot->class_id;

            $html .= '<div class="row align-items-end teaching-entry mb-2">';
            $html .= '<div class="col-md-5"><select name="subject_class['.$index.'][subject_id]" class="form-select" required>';
            $html .= '<option value="" disabled>Select Subject</option>';

            foreach ($subjects as $subj) {
                $selected = $subj->id == $subject->id ? 'selected' : '';
                $html .= '<option value="'.$subj->id.'" '.$selected.'>'.$subj->subject_name.'</option>';
            }

            $html .= '</select></div>';

            $html .= '<div class="col-md-5"><select name="subject_class['.$index.'][class_id]" class="form-select" required>';
            $html .= '<option value="" disabled>Select Class</option>';

            foreach ($schoolclasses as $class) {
                $selected = $class->id == $class_id ? 'selected' : '';
                $html .= '<option value="'.$class->id.'" '.$selected.'>'
                    . ($class->level->level_name ?? '') . ' ' . ($class->stream->name ?? '') . '</option>';
            }

            $html .= '</select></div>';

            $html .= '<div class="col-md-2"><button type="button" class="btn btn-danger remove-subject-class"><i class="ti ti-minus"></i></button></div>';
            $html .= '</div>';
        }

        // Add the plus button at the end
        $html .= '<div><button type="button" class="btn btn-success add-subject-class"><i class="ti ti-plus"></i></button></div>';

        return response($html);
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
