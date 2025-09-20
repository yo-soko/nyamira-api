<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendancies;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\leaves;
use App\Models\Shift;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Term;
use App\Models\Exam;
use App\Models\ClassLevel;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Result;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hour = Carbon::now()->format('H');

        if ($hour < 12) {
            $greeting = 'Good Morning';
        } elseif ($hour < 18) {
            $greeting = 'Good Afternoon';
        } else {
            $greeting = 'Good Evening';
        }
        $today = Carbon::today();

        
        return view('index');
    }
}
