<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $terms = Term::latest()->get();
        return view('term', compact('terms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not used because creation is done via modal in term.blade.php
        return redirect()->route('terms.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'term_name' => 'required|max:50',
            'year' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Term::create($request->all());

        return redirect()->route('terms.index')->with('success', 'Term created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Term $term)
    {
        // Optional: Not used unless you need a detailed view
        return view('term-show', compact('term'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Term $term)
    {
        // Not used because editing is done via modal in term.blade.php
        return redirect()->route('terms.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Term $term)
    {
        $request->validate([
            'term_name' => 'required|max:50',
            'year' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $term->update($request->all());

        return redirect()->route('terms.index')->with('success', 'Term updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Term $term)
    {
        $term->delete();

        return redirect()->route('terms.index')->with('success', 'Term deleted successfully.');
    }
}
