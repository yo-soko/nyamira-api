<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LibraryController extends Controller
{
    /**
     * Display a listing of the library items with search and filter.
     */
    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $items = $query->latest()->paginate(10);
        $categories = BookCategory::all();

        return view('library.index', compact('items', 'categories'));
    }

    /**
     * Show the form for creating a new library item.
     */
    public function create()
    {
        // Restrict to teacher/admin roles only
        if (!in_array(auth()->user()->role, ['teacher', 'developer','admin'])) {
            abort(403, 'Unauthorized');
        }

        $categories = BookCategory::all();
        return view('library.create', compact('categories'));
    }

    /**
     * Store a newly uploaded book in the library.
     */
    public function store(Request $request)
    {
        // Restrict to teacher/admin roles only
        if (!in_array(auth()->user()->role, ['teacher', 'developer','admin'])) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'category_id' => 'required|exists:book_categories,id',
            'file' => 'required|mimes:pdf|max:10000',
        ]);

        $filePath = $request->file('file')->store('library', 'public');

        Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'category_id' => $request->category_id,
            'file_path' => $filePath,
            'description' => $request->description,
            'published_year' => $request->published_year,
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('library.index')->with('success', 'Library item uploaded successfully!');
    }

    /**
     * Display the specified library item.
     */
    public function show(Book $library)
    {
        return view('library.show', ['book' => $library]);
    }

    /**
     * Remove the specified library item from storage.
     */
    public function destroy(Book $library)
    {
        // Optional: allow only uploader or admin to delete
        if (
            auth()->user()->id !== $library->uploaded_by &&
            !in_array(auth()->user()->role, ['admin','developer'])
        ) {
            abort(403, 'Unauthorized to delete');
        }

        Storage::disk('public')->delete($library->file_path);
        $library->delete();

        return back()->with('success', 'Library item deleted successfully!');
    }
}
