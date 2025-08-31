<?php
use Illuminate\Http\Request;
use App\Models\Pickup;

Route::post('/pickup', function(Request $request) {
    // log raw input
    $raw = file_get_contents('php://input');
    Log::info("ZKTeco RAW: " . $raw);

    // log parsed request
    Log::info("ZKTeco JSON: ", $request->all());

    return response()->json(['status' => 'received']);
});
