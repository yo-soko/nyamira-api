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
        $teacher->first_name = $request->first_name;
        $teacher->last_name = $request->last_name;
        $teacher->email = $request->email;
        // update other fields...
        $teacher->save();

        // Delete old assignments
        TeacherSubject::where('teacher_id', $teacher->id)->delete();

        if ($request->has('subject_class')) {
            foreach ($request->subject_class as $entry) {
                TeacherSubject::create([
                    'teacher_id' => $teacher->id,
                    'subject_id' => $entry['subject_id'],
                    'class_id' => $entry['class_id'],
                ]);
            }
        }


        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');
    }


    public function editSubjects(Teacher $teacher)
    {
        $subjects = Subject::all();
        $schoolclasses = SchoolClass::with(['level', 'stream'])->get();

        return view('teachers.partials.subject_class_edit', compact('teacher', 'subjects', 'schoolclasses'))->render();
    }

    public function getSubjects($id)
    {
        $teacher = Teacher::with('subjects')->findOrFail($id);
        $allSubjects = Subject::where('status', 1)->get();
        $allClasses = SchoolClass::where('status', 1)->get();

        // Fetch subject-class combinations assigned to the teacher
        $subjectClasses = DB::table('teacher_subject_class')
            ->where('teacher_id', $id)
            ->get();

        // Return raw HTML to be injected into the modal
        $html = '';
        $loopIndex = 0;
        foreach ($subjectClasses as $entry) {
            $html .= '<div class="row mb-2">';
            $html .= '<div class="col-md-6">';
            $html .= '<select name="subject_class[' . $loopIndex . '][subject_id]" class="form-control" required>';
            foreach ($allSubjects as $subject) {
                $selected = $subject->id == $entry->subject_id ? 'selected' : '';
                $html .= "<option value='{$subject->id}' {$selected}>{$subject->name}</option>";
            }
            $html .= '</select>';
            $html .= '</div>';

            $html .= '<div class="col-md-6">';
            $html .= '<select name="subject_class[' . $loopIndex . '][class_id]" class="form-control" required>';
            foreach ($allClasses as $class) {
                $selected = $class->id == $entry->class_id ? 'selected' : '';
                $html .= "<option value='{$class->id}' {$selected}>{$class->name}</option>";
            }
            $html .= '</select>';
            $html .= '</div>';
            $html .= '</div>';

            $loopIndex++;
        }

        return response($html);
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


//   public function getSubjectClassMap(Teacher $teacher)
//     {
//         $assignments = TeacherSubject::with(['subject', 'class'])
//             ->where('teacher_id', $teacher->id)
//             ->get();

//         $subjects = Subject::all();
//         $schoolclasses = SchoolClass::with(['level', 'stream'])->get();

//         $html = '';

//         foreach ($assignments as $index => $assignment) {
//             $html .= '<div class="row align-items-end teaching-entry mb-2">';
//             $html .= '<div class="col-md-5">';
//             $html .= '<select name="subject_class[' . $index . '][subject_id]" class="form-select" required>';
//             $html .= '<option value="" disabled>Select Subject</option>';
//             foreach ($subjects as $subject) {
//                 $selected = $assignment->subject_id == $subject->id ? 'selected' : '';
//                 $html .= '<option value="' . $subject->id . '" ' . $selected . '>' . $subject->subject_name . '</option>';
//             }
//             $html .= '</select>';
//             $html .= '</div>';

//             $html .= '<div class="col-md-5">';
//             $html .= '<select name="subject_class[' . $index . '][class_id]" class="form-select" required>';
//             $html .= '<option value="" disabled>Select Class</option>';
//             foreach ($schoolclasses as $class) {
//                 $selected = $assignment->class_id == $class->id ? 'selected' : '';
//                 $html .= '<option value="' . $class->id . '" ' . $selected . '>';
//                 $html .= ($class->level->level_name ?? '') . ' ' . ($class->stream->name ?? '');
//                 $html .= '</option>';
//             }
//             $html .= '</select>';
//             $html .= '</div>';

//             $html .= '<div class="col-md-2">';
//             $html .= '<button type="button" class="btn btn-danger remove-subject-class"><i class="ti ti-minus"></i></button>';
//             $html .= '</div>';
//             $html .= '</div>';
//         }

//         return response($html);
//     }




    // public function update(Request $request, $id)
    // {
    //     $validated = $request->validate([
    //         'first_name' => 'required|string|max:255',
    //         'last_name' => 'required|string|max:255',
    //         'date_of_birth' => 'required|date',
    //         'email' => 'required|email|unique:users,email,' . $id . ',id',
    //         'phone' => 'required|string|max:100',
    //         'id_no' => 'required|string|max:255',
    //         'address' => 'required|string',
    //         'education_level' => 'required|string|max:255',
    //         'years_of_experience' => 'required|integer|min:0',
    //         'gender' => 'required|string|max:50',
    //         'department' => 'required|integer|exists:departments,id',
    //         'status' => 'required|boolean',
    //         'subject_class' => 'nullable|array',
    //         'subject_class.*.subject_id' => 'required|integer|exists:subjects,id',
    //         'subject_class.*.class_id' => 'required|integer|exists:school_classes,id',
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         $teacher = Teacher::findOrFail($id);

    //         $teacher->update([
    //             'first_name' => $validated['first_name'],
    //             'last_name' => $validated['last_name'],
    //             'date_of_birth' => $validated['date_of_birth'],
    //             'email' => $validated['email'],
    //             'phone' => $validated['phone'],
    //             'id_no' => $validated['id_no'],
    //             'address' => $validated['address'],
    //             'education_level' => $validated['education_level'],
    //             'years_of_experience' => $validated['years_of_experience'],
    //             'gender' => $validated['gender'],
    //             'department_id' => $validated['department'],
    //             'status' => $validated['status'],
    //         ]);

    //         // Update User
    //         $user = User::find($teacher->user_id);
    //         if ($user) {
    //             $user->update([
    //                 'email' => $teacher->email,
    //                 'name' => $teacher->first_name . ' ' . $teacher->last_name,
    //                 'phone' => $teacher->phone,
    //             ]);
    //         }

    //         // Update Employee if exists
    //         $employee = \App\Models\Employee::where('user_id', $user->id)->first();
    //         if ($employee) {
    //             $employee->update([
    //                 'first_name' => $teacher->first_name,
    //                 'last_name' => $teacher->last_name,
    //                 'email' => $teacher->email,
    //                 'contact_number' => $teacher->phone,
    //                 'dob' => $teacher->date_of_birth,
    //                 'gender' => $teacher->gender,
    //                 'address' => $teacher->address,
    //                 'department_id' => $teacher->department_id,
    //             ]);
    //         }

    //         // Sync subject-class pairs
    //         TeacherSubject::where('teacher_id', $teacher->id)->delete();

    //         if (!empty($validated['subject_class'])) {
    //             foreach ($validated['subject_class'] as $pair) {
    //                 TeacherSubject::create([
    //                     'teacher_id' => $teacher->id,
    //                     'subject_id' => $pair['subject_id'],
    //                     'class_id'   => $pair['class_id'],
    //                 ]);
    //             }
    //         }

    //         DB::commit();
    //         return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         \Log::error('Error updating teacher: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'An error occurred while updating the teacher.');
    //     }
    // }

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
