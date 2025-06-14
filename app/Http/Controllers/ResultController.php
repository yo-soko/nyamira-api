<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Subject;
use App\Models\ClassLevel;
use App\Models\SchoolClass;
use App\Models\Exam;
use App\Models\Term;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    public function filterForm()
    {
        $terms = Term::where('status', 1)->get();
        $classes = SchoolClass::with(['stream', 'level'])->where('status', 1)->get();
        $subjects = Subject::where('status', 1)->get();
        $exams = Exam::where('status', 1)->get();

        return view('submit-results', compact('terms', 'classes', 'subjects', 'exams'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Result $result)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Result $result)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Result $result)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Result $result)
    {
        //
    }
}
