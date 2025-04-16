<?php

namespace App\Http\Controllers;

use App\Models\Department; 
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::all();
        $hods = User::where('role', 'Manager') // or whatever role qualifies as HOD
                    ->orWhere('role', 'hods')  // optional extra filter
                    ->get();
    
        return view('department-grid', compact('departments', 'hods'));
        dd($hods);
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
            'name' => 'required|string|max:255',
            'hod_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
        ]);

        Department::create([
            'name' => $request->name,
            'hod_id' => $request->hod_id,
            'description' => $request->description,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'Department added successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $department->name = $request->name;
        $department->hod_id = $request->hod_id;
        $department->description = $request->description;
        $department->status = $request->has('status');

        $department->save();

        return redirect()->route('department-grid')->with('success', 'Department updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $department = Department::findOrFail($request->id);
        $department->delete();
    
        return redirect()->back()->with('success', 'Department deleted successfully!');
    }
}
