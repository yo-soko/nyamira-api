<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use Illuminate\Http\Request;

class StreamController extends Controller
{
    public function index()
    {
        $streams = Stream::all();
        return view('streams', compact('streams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'initials' => 'required|string|max:10',
            'name' => 'required|string|max:255',
        ]);

        Stream::create($request->all());

        return redirect()->back()->with('success', 'Stream added successfully.');
    }

    public function update(Request $request, Stream $stream)
    {
        $request->validate([
            'initials' => 'required|string|max:10',
            'name' => 'required|string|max:255',
        ]);

        $stream->update($request->all());

        return redirect()->back()->with('success', 'Stream updated successfully.');
    }

    public function destroy(Stream $stream)
    {
        $stream->delete();

        return redirect()->back()->with('success', 'Stream deleted successfully.');
    }
}
