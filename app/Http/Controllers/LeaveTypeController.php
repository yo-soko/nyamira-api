<?php

namespace App\Http\Controllers;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::latest()->get();
        return view('leave-types', compact('leaveTypes'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'quota' => 'required|integer|min:1',
            'status' => 'nullable'
        ]);

        LeaveType::create([
            'type' => $validated['type'],
            'quota' => $validated['quota'],
            'status' => $request->has('status') ? 1 : 0
        ]);

        return redirect()->back()->with('success', 'Leave type added successfully!');
    }
    public function update(Request $request)
    {
        $leaveType = LeaveType::findOrFail($request->id);
        $data = $request->all();
        
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'quota' => 'required|integer|min:1',
            'status' => 'nullable'
        ]);
    
        

        $leaveType->type = $validated['type'];
        $leaveType->quota = $validated['quota'];
        $leaveType->status = $request->has('status') ? 1 : 0;
        $leaveType->save();
    
        return redirect()->back()->with('success', 'Leave type updated successfully!');
    }
    
    public function destroy(Request $request)
    {
        $leaveTypes = LeaveType::findOrFail($request->id);
        $leaveTypes->delete();
    
        return redirect()->back()->with('success', 'Leave type deleted successfully!');
    }
}
