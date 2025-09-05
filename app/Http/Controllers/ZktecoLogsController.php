<?php

namespace App\Http\Controllers;

use App\Models\zkteco_logs;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ZktecoLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = zkteco_logs::with('user');

        // 🔹 Date filter
        if ($request->filled('date')) {
            $query->whereDate('log_date', $request->date);
        }

        // 🔹 Sorting options
        if ($request->sort === 'asc') {
            $query->orderBy('pickup_time', 'asc');
        } elseif ($request->sort === 'desc') {
            $query->orderBy('pickup_time', 'desc');
        } else {
            $query->orderBy('pickup_time', 'desc'); // default
        }

        $logs = $query->paginate(20)->appends($request->all());

        return view('zkteco_logs.index', compact('logs'));
    }

    public function exportPdf(Request $request)
    {
        $logs = ZktecoLog::with('user')
            ->when($request->date, fn($q) => $q->whereDate('log_date', $request->date))
            ->orderBy('pickup_time', $request->sort ?? 'asc')
            ->get();

        $pdf = Pdf::loadView('zkteco_logs.pdf', compact('logs'));
        return $pdf->download('fingerprint_logs.pdf');
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(zkteco_logs $zkteco_logs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(zkteco_logs $zkteco_logs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, zkteco_logs $zkteco_logs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(zkteco_logs $zkteco_logs)
    {
        //
    }
}
