<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = Expenses::with(['vehicle','vendor'])->latest()->paginate(10);
        $vehicles = Vehicle::all();
        $vendors = User::all();

        return view('expenses.index', compact('expenses','vehicles','vendors'));
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
        $data = $request->validate([
            'vehicle_id'   => 'nullable|exists:vehicles,id',
            'expense_type' => 'required|string|max:255',
            'vendor_id'    => 'nullable|exists:users,id',
            'amount'       => 'required|numeric|min:0',
            'frequency'    => 'required|in:single,monthly,annual',
            'date'         => 'required|date',
            'notes'        => 'nullable|string',
            'photos.*'     => 'nullable|image|max:2048',
            'documents.*'  => 'nullable|mimes:pdf,doc,docx,jpg,png|max:4096',
        ]);

        // Handle uploads
        $photos = [];
        if($request->hasFile('photos')){
            foreach($request->file('photos') as $file){
                $photos[] = $file->store('expenses/photos','public');
            }
        }

        $documents = [];
        if($request->hasFile('documents')){
            foreach($request->file('documents') as $file){
                $documents[] = $file->store('expenses/documents','public');
            }
        }

        $data['photos'] = $photos;
        $data['documents'] = $documents;

        Expenses::create($data);

        return redirect()->route('expenses.index')->with('success','Expense recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expenses $expenses)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expenses $expenses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expenses $expenses)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expenses $expenses)
    {
        //
    }
}
