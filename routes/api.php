<?php
use Illuminate\Http\Request;
use App\Models\Pickup;

Route::post('/iclock/cdata', function(Request $request) {
    // log raw input
    $raw = file_get_contents('php://input');
    Log::info("ZKTeco RAW: " . $raw);

    // log parsed request
    Log::info("ZKTeco JSON: ", $request->all());

    return response()->json(['status' => 'received']);
});
