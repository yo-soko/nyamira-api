<?php

namespace App\Http\Controllers;

use App\Models\BookCategory;
use Illuminate\Http\Request;

class BookCategoryController extends Controller
{
    public function index() {
        $categories = BookCategory::all();
        return view('library.categories.index', compact('categories'));
    }

    public function create() {
        return view('library.categories.create');
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required']);
        BookCategory::create($request->only('name'));
        return redirect()->route('library-categories.index')->with('success', 'Category added.');
    }

    public function edit(BookCategory $library_category) {
        return view('library.categories.edit', compact('library_category'));
    }

    public function update(Request $request, BookCategory $library_category) {
        $request->validate(['name' => 'required']);
        $library_category->update($request->only('name'));
        return redirect()->route('library-categories.index')->with('success', 'Category updated.');
    }

    public function destroy(BookCategory $library_category) {
        $library_category->delete();
        return back()->with('success', 'Category deleted.');
    }
}
