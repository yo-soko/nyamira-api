<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $designations = Designation::all(); 
        return view('designation', compact('designations'));
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
        $request->validate([
            'designation' => 'required|string|max:255',
            'department' => 'required|string|max:255',
        ]);

        Designation::create([
            'designation' => $request->designation,
            'department' => $request->department,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'Designation added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Designation $designation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Designation $designation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'designation' => 'required|string|max:255',
            'department' => 'required|string|max:255',
        ]);

        $designation = Designation::findOrFail($request->id);
        $designation->update([
            'designation' => $request->designation,
            'department' => $request->department,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'Designation updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $designation = Designation::findOrFail($request->id);
        $designation->delete();
    
        return redirect()->back()->with('success', 'Designation deleted successfully!');
    }
    
}
