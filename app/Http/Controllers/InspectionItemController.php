<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InspectionItem;

class InspectionItemController extends Controller
{
    public function failures()
    {
        $failures = InspectionItem::with(['inspection.vehicle', 'inspection.inspector'])
            ->where('status', 'Fail')
            ->latest()
            ->paginate(15);

        return view('failures', compact('failures'));
    }
}
