<?php

namespace App\Http\Controllers;

use App\Models\leaves;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class LeavesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaves = Leaves::with('leaveType')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('leaves', compact('leaves'));
    }
    public function allLeaves()
    {
        $leaves = Leaves::with(['leaveType', 'user']) // include user relation
                    ->latest()
                    ->get();

        return view('leaves-admin', compact('leaves'));
    }
     public function approve($id)
    {
        $leave = Leaves::findOrFail($id);
        $leave->status = 'Approved';
        $leave->save();

        return redirect()->route('leaves-admin')->with('success', 'Leave approved.');
    }

    public function reject($id)
    {
        $leave = Leaves::findOrFail($id);
        $leave->status = 'Rejected';
        $leave->save();

        return redirect()->route('leaves-admin')->with('success', 'Leave rejected.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'from_date' => Carbon::createFromFormat('d-m-Y', $request->from_date)->format('Y-m-d'),
            'to_date' => Carbon::createFromFormat('d-m-Y', $request->to_date)->format('Y-m-d'),
        ]);
       $request->validate([
        'leave_type_id' => 'required|exists:leave_types,id',
        'from_date' => 'required|date',
        'to_date' => 'required|date|after_or_equal:from_date',
        'leave_mode' => 'required|in:Full Day,Half Day',
        'reason' => 'nullable|string',
        ]);

        $from = Carbon::parse($request->from_date);
        $to = Carbon::parse($request->to_date);
        $days = $from->diffInDays($to) + 1;

        if ($request->leave_mode == 'Half Day') {
            $days = 0.5;
        }

        Leaves::create([
            'user_id' => Auth::id(),
            'leave_type_id' => $request->leave_type_id,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'leave_mode' => $request->leave_mode,
            'reason' => $request->reason,
            'days' => $days,
            'status' => 'Pending',
        ]);

        return back()->with('success', 'Leave application submitted!');
    }

    /**
     * Display the specified resource.
     */
    public function show(leaves $leaves)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(leaves $leaves)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->merge([
            'from_date' => Carbon::createFromFormat('d-m-Y', $request->from_date)->format('Y-m-d'),
            'to_date' => Carbon::createFromFormat('d-m-Y', $request->to_date)->format('Y-m-d'),
        ]);

        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'leave_mode' => 'required|in:Full Day,Half Day',
            'reason' => 'nullable|string',
        ]);

        $leave = Leaves::findOrFail($id);

        $from = Carbon::parse($request->from_date);
        $to = Carbon::parse($request->to_date);
        $days = $from->diffInDays($to) + 1;

        if ($request->leave_mode === 'Half Day') {
            $days = 0.5;
        }

        $leave->update([
            'leave_type_id' => $request->leave_type_id,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'leave_mode' => $request->leave_mode,
            'reason' => $request->reason,
            'days' => $days,
            'status' => 'Pending', // Reset to pending if desired
        ]);

        return back()->with('success', 'Leave application updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $leaves = Leaves::findOrFail($request->id);
        $leaves->delete();

        return redirect()->back()->with('success', 'Application deleted successfully!');
    }
}
