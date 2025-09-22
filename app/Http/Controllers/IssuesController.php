<?php

namespace App\Http\Controllers;

use App\Models\Issues;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;

class IssuesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $issues = Issues::with(['vehicle', 'reporter', 'assignee'])->latest()->paginate(10);
        $vehicles = Vehicle::all();
        $users = User::all();

        return view('issues.index', compact('issues','vehicles','users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicles = Vehicle::all();
        $users = User::all();
        return view('issues.create', compact('vehicles', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'priority' => 'required|in:Low,Medium,High,Critical',
            'reported_at' => 'required|date',
            'summary' => 'required|string|max:255',
            'description' => 'nullable|string',
            'labels' => 'nullable|string',
            'reported_by' => 'required|exists:users,id',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date|after_or_equal:reported_at',
            'primary_meter_due' => 'nullable|integer',
        ]);

        Issues::create($data);

        return redirect()->route('issues.index')->with('success', 'Issue reported successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Issues $issues)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Issues $issues)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Issues $issues)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Issues $issues)
    {
        //
    }
}
