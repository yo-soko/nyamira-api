<?php

namespace App\Http\Controllers;

use App\Models\ClassLevel;
use Illuminate\Http\Request;

class ClassLevelController extends Controller
{
    public function index()
    {
        $levels = ClassLevel::all();
        return view('class-levels', compact('levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'level_name' => 'required|string|max:100',
            'status' => 'required|integer',
        ]);

        ClassLevel::create($request->all());
        return redirect()->back()->with('success', 'Class level added successfully!');
    }

    public function update(Request $request, ClassLevel $classLevel)
    {
        $request->validate([
            'level_name' => 'required|string|max:100',
            'status' => 'required|integer',
        ]);

        $classLevel->update($request->all());
        return redirect()->back()->with('success', 'Class level updated successfully!');
    }

    public function destroy(ClassLevel $classLevel)
    {
        $classLevel->delete();
        return redirect()->back()->with('success', 'Class level deleted successfully!');
    }
}
