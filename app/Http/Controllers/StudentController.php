<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\Student;
use App\Models\Guardian;
use App\Models\FeePayment;
use App\Models\FeeStructure;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\ClassLevel;
use App\Models\Term;
use App\Models\MealPlan;
use App\Models\StudentMeal;
use App\Models\StudentTransport;
use App\Models\StudentSubject;
use App\Models\TransportRoute;
use App\Models\Subject;
use App\Models\User;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with(['class.level', 'class.stream', 'term'])->get();
        $classes = SchoolClass::with(['level', 'stream'])
                    ->where('status', 1)
                    ->get();

        $terms = Term::where('status', 1)->get();
        $mealPlans = MealPlan::where('status', 1)->get();
        $transportRoutes = TransportRoute::where('status', 1)->get();
        $subjects = Subject::where('status', 1)->get();

        return view('students', compact('students', 'classes', 'terms', 'mealPlans', 'transportRoutes', 'subjects'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'student_class' => 'required',
            'student_reg_number' => 'required|string',
            'studentStatus' => 'required|boolean',
            'studentGender' => 'required|string',
            'studentTerm' => 'required|exists:terms,id',
            'guardian_first_name' => 'required|string',
            'guardian_last_name' => 'required|string',
            'guardian_relationship' => 'required|string',
            'guardian_first_phone' => 'required|string',
            'id_number' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $student = Student::create([
                'first_name' => $request->first_name,
                'second_name' => $request->second_name,
                'last_name' => $request->last_name,
                'student_age' => $request->student_age,
                'class_id' => $request->student_class,
                'student_reg_number' => $request->student_reg_number,
                'about' => $request->student_about,
                'status' => $request->studentStatus,
                'gender' => $request->studentGender,
                'term_id' => $request->studentTerm
            ]);

            // Transport
            if ($request->has('needs_transport') && $request->filled('transport_route_id')) {
                $route = TransportRoute::findOrFail($request->transport_route_id);
                $baseFee = $route->fee;
                $fee = $request->transport_option === 'one_way' ? ($baseFee / 2) + 500 : $baseFee;

                StudentTransport::create([
                    'student_id' => $student->id,
                    'term_id' => $request->studentTerm,
                    'class_id' => $request->student_class,
                    'route_id' => $route->id,
                    'transport_type' => $request->transport_option,
                    'transport_fee' => $fee
                ]);
            }

            // Meals
            $mealFee = 0;
            if ($request->has('needs_meals') && $request->filled('meal_plan_id')) {
                $meal = MealPlan::findOrFail($request->meal_plan_id);
                $mealFee = $meal->fee;

                StudentMeal::create([
                    'student_id' => $student->id,
                    'term_id' => $request->studentTerm,
                    'class_id' => $request->student_class,
                    'meal_plan_id' => $meal->id,
                    'meal_fee' => $mealFee
                ]);
            }

            // Calculate fee balance
            $feeStructure = FeeStructure::where('level_id', $student->class->level_id)
                                        ->where('term_id', $request->studentTerm)
                                        ->sum('amount');

            $paid = FeePayment::where('student_id', $student->id)
                           ->where('term_id', $request->studentTerm)
                           ->sum('amount_paid');

            $student->current_balance = ($feeStructure + $mealFee + ($fee ?? 0)) - $paid;
            $student->save();

          
            $user = User::create([
                'code' => $student->student_reg_number, 
                'role' => 'student',
                'email' => $request->email ?? null,
                'name' => $request->first_name . ' ' . $request->last_name,
                'phone' => $request->guardian_first_phone,
                'password' => Hash::make('Password'), 
                'status' => true,
            ]);

            $student->update([
                'user_id' => $user->id
            ]);
            // Guardian
            Guardian::create([
                'student_id' => $student->id,
                'guardian_first_name' => $request->guardian_first_name,
                'guardian_last_name' => $request->guardian_last_name,
                'address' => $request->address,
                'guardian_relationship' => $request->guardian_relationship,
                'first_phone' => $request->guardian_first_phone,
                'second_phone' => $request->guardian_second_phone,
                'id_number' => $request->id_number,
                'email' => $request->email,
                'guardian_about' => $request->guardian_about
            ]);

            // Subjects
            if ($request->filled('studentSubjects')) {
                foreach ($request->studentSubjects as $subjectId) {
                    StudentSubject::create([
                        'student_id' => $student->id,
                        'subject_id' => $subjectId
                    ]);
                }
            }

            DB::commit();
           return redirect()->back()->with('success', 'Student added successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
          return redirect()->back()->with('error', 'An error occured.');

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }

    // AJAX: Get students by class ID
    public function getByClass($classId)
    {
        $students = Student::where('class_id', $classId)
            ->select('id', 'first_name', 'middle_name', 'last_name')
            ->get()
            ->map(function ($student) {
                return [
                    'id' => $student->id,
                    'full_name' => trim("{$student->first_name} {$student->middle_name} {$student->last_name}")
                ];
            });

        return response()->json($students);
    }

    // AJAX: Get balance for student and term
    public function getBalance($studentId, $termId)
    {
        $student = Student::findOrFail($studentId);
        $classId = $student->class_id;

        $totalFees = FeeStructure::where('class_id', $classId)
            ->where('term_id', $termId)
            ->sum('amount');

        $totalPaid = Payment::where('student_id', $studentId)
            ->where('term_id', $termId)
            ->sum('amount_paid');

        return response()->json([
            'expected' => $totalFees,
            'paid' => $totalPaid,
            'balance' => $totalFees - $totalPaid,
        ]);
    }

}
