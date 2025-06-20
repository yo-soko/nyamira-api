<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use App\Models\Student;
use Illuminate\Http\Request;

class GuardianController extends Controller
{
    public function index()
    {
        $guardians = Guardian::with('student')->get();
        $students = Student::all();
        return view('guardians', compact('guardians', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'guardian_first_name' => 'required',
            'guardian_last_name' => 'required',
            'guardian_relationship' => 'required',
            'second_phone' => 'required',
            'email' => 'required|email|unique:guardians,email',
        ]);

        Guardian::create($request->all());
        return redirect()->back()->with('success', 'Guardian added successfully.');
    }

    public function update(Request $request, $id)
    {
        $guardian = Guardian::findOrFail($id);

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'guardian_first_name' => 'required',
            'guardian_last_name' => 'required',
            'guardian_relationship' => 'required',
            'second_phone' => 'required',
            'email' => 'required|email|unique:guardians,email,' . $guardian->id,
        ]);

        $guardian->update($request->all());
        return redirect()->back()->with('success', 'Guardian updated successfully.');
    }

    public function destroy($id)
    {
        Guardian::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Guardian deleted successfully.');
    }
}
