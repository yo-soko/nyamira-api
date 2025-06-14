<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeeStructure;
use App\Models\ClassLevel;
use App\Models\Term;

class FeeStructureController extends Controller
{
    public function index()
    {
        $feeStructures = FeeStructure::with('classLevel', 'term')->get();
        $classLevels = ClassLevel::all();
        $terms = Term::all();

        return view('fees.fee_structure', compact('feeStructures', 'classLevels', 'terms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'level_id' => 'required|exists:class_levels,id',
            'term_id' => 'required|exists:terms,id',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'feeStatus' => 'required|in:active,inactive',
        ]);

        $fee = new FeeStructure();
        $fee->level_id = $request->level_id;
        $fee->term_id = $request->term_id;
        $fee->amount = $request->amount;
        $fee->description = $request->description;
        $fee->status = $request->feeStatus;
        $fee->save();

        return response()->json(['success' => true]);
    }

    public function show(Request $request)
    {
        $fee = FeeStructure::find($request->fee_id);

        if ($fee) {
            return response()->json([
                'success' => true,
                'data' => $fee
            ]);
        }

        return response()->json(['success' => false, 'error' => 'Fee not found']);
    }

    public function update(Request $request)
    {
        $request->validate([
            'fee_id' => 'required|exists:fee_structures,id',
            'level_id' => 'required|exists:class_levels,id',
            'term_id' => 'required|exists:terms,id',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'feeStatus' => 'required|in:active,inactive',
        ]);

        $fee = FeeStructure::find($request->fee_id);
        $fee->level_id = $request->level_id;
        $fee->term_id = $request->term_id;
        $fee->amount = $request->amount;
        $fee->description = $request->description;
        $fee->status = $request->feeStatus;
        $fee->save();

        return response()->json(['success' => true]);
    }

    public function destroy(Request $request)
    {
        $fee = FeeStructure::find($request->fee_id);

        if ($fee) {
            $fee->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'error' => 'Fee not found']);
    }
}
