<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanciesController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\EmployeeSalaryController;
use App\Http\Controllers\LeavesController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\FeeStructureController;
use App\Http\Controllers\FeePaymentsController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ClassLevelController;
use App\Http\Controllers\StreamController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SchoolCalendarController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SdashboardController;
use App\Http\Controllers\TdashboardController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\BookCategoryController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\FeeDashboardController;
use App\Http\Controllers\ZktecoLogsController;
use App\Models\User;


Route::get('signin',            [CustomAuthController::class, 'index'])->name('signin');
Route::post('custom-login',     [CustomAuthController::class, 'customSignin'])->name('signin.custom');
Route::get('register',          [CustomAuthController::class, 'registration'])->name('register');
Route::post('custom-register',  [CustomAuthController::class, 'customRegister'])->name('register.custom');


Route::get('signout',           [CustomAuthController::class, 'signOut'])->name('signout');




Route::get('/', function () {
    return view('login');
})->name('login');
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::middleware(['auth'])->group(function () {

    Route::get('index/', [DashboardController::class, 'index'])->name('index.index');

    /*===========================calendar======================================================*/

    Route::get('/calendar', [SchoolCalendarController::class, 'index'])->name('calendar.index');
    Route::prefix('school-calendar')->group(function () {
        Route::get('/data', [SchoolCalendarController::class, 'getCalendarData'])->name('school-calendar.data');
        Route::get('/events/{event}', [SchoolCalendarController::class, 'show'])->name('school-calendar.events.show');
        Route::post('/', [SchoolCalendarController::class, 'store'])->name('school-calendar.store');
        Route::put('/{event}', [SchoolCalendarController::class, 'update'])->name('school-calendar.update');
        Route::delete('/{event}', [SchoolCalendarController::class, 'destroy'])->name('school-calendar.destroy');
        Route::post('/{event}/update-date', [SchoolCalendarController::class, 'updateEventDate'])->name('school-calendar.update-date');
    });
    /*===========================calendar======================================================*/

    /*===========================Timetable======================================================*/

    // Route::prefix('timetable')->group(function () {
    //     Route::get('/', [TimetableController::class, 'index'])->name('timetable.index');
    //     Route::get('/create', [TimetableController::class, 'create'])->name('timetable.create');
    //     Route::post('/', [TimetableController::class, 'store'])->name('timetable.store');
    //     Route::get('/{timetable}/edit', [TimetableController::class, 'edit'])->name('timetable.edit');
    //     Route::put('/{timetable}', [TimetableController::class, 'update'])->name('timetable.update');
    //     Route::delete('/{timetable}', [TimetableController::class, 'destroy'])->name('timetable.destroy');
    //     Route::get('/timetable/generate', [TimetableController::class, 'generate'])->name('timetable.generate');

    //     // Class-specific timetables
    //     Route::get('/class/{class}', [TimetableController::class, 'classTimetable'])->name('timetable.class');

    //     // Teacher-specific timetables
    //     Route::get('/teacher/{teacher}', [TimetableController::class, 'teacherTimetable'])->name('timetable.teacher');

    //     // Room-specific timetables
    //     Route::get('/room/{room}', [TimetableController::class, 'roomTimetable'])->name('timetable.room');

    //     // Print timetables
    //     Route::get('/print/class/{class}', [TimetableController::class, 'printClassTimetable'])->name('timetable.print.class');
    //     Route::get('/print/teacher/{teacher}', [TimetableController::class, 'printTeacherTimetable'])->name('timetable.print.teacher');
    // });
    // /*===========================Timetable======================================================*/

    /*===========================Transport======================================================*/

    Route::prefix('transport')->group(function () {
        // Routes Management
        Route::get('/routes', [TransportController::class, 'index'])->name('transport');
        Route::post('/routes', [TransportController::class, 'storeRoute'])->name('transport.routes.store');
        Route::get('/routes/{id}/edit', [TransportController::class, 'editRoute'])->name('transport.routes.edit');
        Route::put('/routes/{id}', [TransportController::class, 'updateRoute'])->name('transport.routes.update');
        Route::delete('/routes/{id}', [TransportController::class, 'destroyRoute'])->name('transport.routes.destroy');

        Route::get('/transport/dashboard-counters', [TransportController::class, 'getDashboardCounters'])->name('transport.dashboard.counters');
        Route::get('/transport/initial-data', [TransportController::class, 'getInitialData'])->name('transport.initial.data');

        // Route Stops
        Route::get('/routes/{routeId}/stops', [TransportController::class, 'getStops'])->name('transport.routes.stops');
        Route::post('/stops', [TransportController::class, 'storeStop'])->name('transport.stops.store');
        Route::put('/stops/{id}', [TransportController::class, 'updateStop'])->name('transport.stops.update');
        Route::delete('/stops/{id}', [TransportController::class, 'destroyStop'])->name('transport.stops.destroy');

        // Vehicles Management
        Route::post('/vehicles', [TransportController::class, 'storeVehicle'])->name('transport.vehicles.store');
        Route::get('/vehicles/{id}/edit', [TransportController::class, 'editVehicle'])->name('transport.vehicles.edit');
        Route::put('/vehicles/{id}', [TransportController::class, 'updateVehicle'])->name('transport.vehicles.update');
        Route::delete('/vehicles/{id}', [TransportController::class, 'destroyVehicle'])->name('transport.vehicles.destroy');

        // Driver Assignments
        Route::get('/vehicles/{vehicleId}/driver', [TransportController::class, 'getDriver'])->name('transport.driver.get');
        Route::post('/drivers', [TransportController::class, 'assignDriver'])->name('transport.driver.assign');

        // Student Transport
        Route::post('/students', [TransportController::class, 'storeStudentTransport'])->name('transport.students.store');
        Route::get('/students/{id}/edit', [TransportController::class, 'editStudentTransport'])->name('transport.students.edit');
        Route::put('/students/{id}', [TransportController::class, 'updateStudentTransport'])->name('transport.students.update');
        Route::delete('/students/{id}', [TransportController::class, 'destroyStudentTransport'])->name('transport.students.destroy');

        // Payments
        Route::get('/students/{id}/payment', [TransportController::class, 'getPaymentInfo'])->name('transport.payment.info');
        Route::post('/students/{id}/payment', [TransportController::class, 'recordPayment'])->name('transport.payment.record');

        // ✅ Updated Attendance Routes
        Route::get('/attendance', [TransportController::class, 'getAttendance'])->name('transport.attendance.get');
        Route::post('/attendance', [TransportController::class, 'saveAttendance'])->name('transport.attendance.save');
        Route::put('/attendance/{id}', [TransportController::class, 'updateAttendance'])->name('transport.attendance.update');

        // Reports
        Route::get('/reports', [TransportController::class, 'generateReport'])->name('transport.reports');
        Route::get('/reports-search', [TransportController::class, 'report'])->name('transport.reports.search');
        Route::get('/reports/export', [TransportController::class, 'exportReport'])->name('transport.reports.export');
    });

    /*===========================Transport======================================================*/

    /*===========================Notifications======================================================*/

    Route::get('/notifications/unread', [NotificationsController::class, 'unread']);
    Route::post('/notifications/mark-read', [NotificationsController::class, 'markAsRead']);
    /*===========================Notifications======================================================*/

    Route::get('/general-settings', function () {
        return view('general-settings');
    })->name('general-settings');

    Route::get('/department-grid', [DepartmentController::class, 'index'])->name('department-grid');
    Route::post('/departments/store', [DepartmentController::class, 'store'])->name('departments.store');
    Route::put('/department/{id}', [DepartmentController::class, 'update'])->name('department.update');
    Route::post('/department/delete', [DepartmentController::class, 'destroy'])->name('department.delete');

    Route::post('/designation', [DesignationController::class, 'store'])->name('designation.store');
    Route::get('/designation', [DesignationController::class, 'index'])->name('designation');
    Route::post('/designation/update', [DesignationController::class, 'update']);
    Route::post('/designation/delete', [DesignationController::class, 'destroy'])->name('designation.delete');

    Route::get('/shift', [ShiftController::class, 'index'])->name('shift');
    Route::post('/shift/store', [ShiftController::class, 'store'])->name('shift.store');
    Route::put('/shift/update', [ShiftController::class, 'update'])->name('shift.update');
    Route::post('/shift/delete', [ShiftController::class, 'destroy'])->name('shift.delete');

    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/delete', [UserController::class, 'destroy'])->name('users.delete');
        Route::post('/users/upgrade', [UserController::class, 'upgrade'])->name('users.upgrade');


    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');

    Route::get('/leave-types', [LeaveTypeController::class, 'index'])->name('leave-types');
    Route::post('/leave-types', [LeaveTypeController::class, 'store'])->name('leave-types.store');
    Route::put('/leave-types/{id}', [LeaveTypeController::class, 'update'])->name('leave-types.update');
    Route::post('/leave-types/delete', [LeaveTypeController::class, 'destroy'])->name('leave-types.delete');

    Route::get('/holidays', [HolidayController::class, 'index'])->name('holidays');
    Route::post('/holidays', [HolidayController::class, 'store'])->name('holidays.store');
    Route::put('/holidays/{id}', [HolidayController::class, 'update'])->name('holidays.update');
    Route::post('/holidays/delete', [HolidayController::class, 'destroy'])->name('holidays.delete');

    Route::post('/employee-salary', [EmployeeSalaryController::class, 'store']);
    Route::get('/employee-salary', [EmployeeSalaryController::class, 'index'])->name('employee-salary');
    Route::post('/payslip', [EmployeeSalaryController::class, 'showPayslip'])->name('payslip.view');
    Route::put('/employee-salary/update', [EmployeeSalaryController::class, 'update'])->name('employee-salary.update');
    Route::post('/employee-salary/delete', [EmployeeSalaryController::class, 'destroy'])->name('employee-salary.delete');

    Route::get('/hrdashboard', [AttendanciesController::class, 'indexx']);
    Route::get('attendance-admin/', [AttendanciesController::class, 'index'])->name('attendance-admin.index');
    Route::get('/attendance-employee', [AttendanciesController::class, 'markAttendance'])->name('attendance.mark');
    Route::post('attendance-employee', [AttendanciesController::class, 'markAttendance'])->name('attendance-employee');
    Route::post('/attendance-employee/clock-in', [AttendanciesController::class, 'clockIn'])->name('attendance-employee.clockIn');
    Route::post('/attendance-employee/clock-out', [AttendanciesController::class, 'clockOut'])->name('attendance-employee.clockOut');
    Route::post('/attendance-employee/break', [AttendanciesController::class, 'break'])->name('attendance-employee.break');
    Route::post('/attendance-employee/backFromBreak', [AttendanciesController::class, 'backFromBreak'])->name('attendance-employee.backFromBreak');

    Route::get('/add-employee', [EmployeeController::class, 'index'])->name('add-employee');  // List all employees
    Route::get('/employee/create', [EmployeeController::class, 'create'])->name('employee.create');  // Add new employee
    Route::post('/employees/store', [EmployeeController::class, 'store'])->name('employees.store');  // Store employee
    Route::get('/employees-list', [EmployeeController::class, 'list'])->name('employees-list');  // List of employees
    Route::post('/employee-details', [EmployeeController::class, 'show'])->name('employee.details');
    Route::post('/edit-employee', [EmployeeController::class, 'edit'])->name('edit-employee');
    Route::put('/update-employee/{id}', [EmployeeController::class, 'update'])->name('update-employee');  // Update employee
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');  // Delete employee

    Route::get('/migrate-employees', [EmployeeController::class, 'migrateEmployeesToUsers']);
    Route::get('/auto-clockout', [AttendanciesController::class, 'autoClockOutForgottenEmployees']);

    Route::get('/leaves', [LeavesController::class, 'index'])->name('leaves');
    Route::post('/leaves', [LeavesController::class, 'store'])->name('leaves.store');
    Route::put('/leaves/{id}', [LeavesController::class, 'update'])->name('leaves.update');
    Route::post('/leaves/delete', [LeavesController::class, 'destroy'])->name('leaves.delete');
    Route::get('/leaves-admin', [LeavesController::class, 'allLeaves'])->name('leaves-admin');
    Route::get('/leave/{id}/approve', [LeavesController::class, 'approve'])->name('leave.approve');
    Route::get('/leave/{id}/reject', [LeavesController::class, 'reject'])->name('leave.reject');

    Route::get('/roles-permissions', [RoleController::class, 'index'])->name('roles-permissions');
    Route::post('/roles-permissions', [RoleController::class, 'store'])->name('roles-permissions.store');
    Route::delete('/roles-permissions/{id}', [RoleController::class, 'destroy'])->name('roles-permissions.destroy');
    Route::patch('/roles-permissions/{id}/toggle', [RoleController::class, 'toggleStatus'])->name('roles-permissions.toggle');
    Route::delete('/users/destroy', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions');
    Route::post('/permissions/set-role', [PermissionController::class, 'setRole'])->name('permissions.setRole');
    Route::put('/roles-permissions/{role}', [PermissionController::class, 'update'])->name('roles.permissions.update');

    Route::get('/sdashboard', [SdashboardController::class, 'index'])->name('sdashboard');

    Route::get('/students', [StudentController::class, 'index'])->name('students');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::put('/students/update', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

    Route::get('/attendance', [AttendanceController::class, 'index']);
    Route::get('/attendance-all', [AttendanceController::class, 'index'])->name('attendance-all');
    Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/filter', [AttendanceController::class, 'filter'])->name('attendance.filter');

    Route::post('/attendance', [AttendanceController::class, 'attendanceAll'])->name('attendance-all');
    //exams
    Route::get('/exams', [ExamController::class, 'index'])->name('exams');
    Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');
    Route::put('/exams/{id}', [ExamController::class, 'update'])->name('exams.update');
    Route::delete('/exams/{id}', [ExamController::class, 'destroy'])->name('exams.destroy');
    Route::get('/classes/{ids}/subjects', [ExamController::class, 'getSubjectsForClasses']);
    Route::get('/exam/class-subjects/{classId}', [App\Http\Controllers\ExamController::class, 'getClassSubjects'])->name('exam.class-subjects');


    Route::get('/submit-results', [ResultController::class, 'filterForm'])->name('submit-results');
    Route::get('/results-entry', [ResultController::class, 'entryForm'])->name('results.entry');
    Route::post('/results/store', [ResultController::class, 'store'])->name('results.store');
    Route::get('/results-filter', [ResultController::class, 'showFilterForm'])->name('results-filter');
    Route::post('/results-filter', [ResultController::class, 'filterResults'])->name('results-filter');
    Route::get('/results-view', [ResultController::class, 'viewResults'])->name('results-view');

    Route::get('/tdashboard', [TdashboardController::class, 'index'])->name('tdashboard');


    Route::get('/cbc-report', [SdashboardController::class, 'cbcReport'])->name('cbc.report');
    Route::post('/cbc-report/view', [SdashboardController::class, 'viewCBCReport'])->name('cbc-report.view');
    Route::post('/cbc-reports/batch', [SdashboardController::class, 'generateBulkReports'])->name('cbc.reports.batch');
    Route::get('/cbc-reports/html-bulk', [SdashboardController::class, 'viewBulkReports'])->name('cbc.reports.bulk.html');

    //terms
    Route::get('/terms', [TermController::class, 'index'])->name('terms.index');
    Route::post('/terms', [TermController::class, 'store'])->name('terms.store');
    Route::put('/terms/{term}', [TermController::class, 'update'])->name('terms.update');
    Route::delete('/terms/{term}', [TermController::class, 'destroy'])->name('terms.destroy');

    //subjects
    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::post('/subjects/store', [SubjectController::class, 'store'])->name('subjects.store');
    Route::post('/subjects/update/{subject}', [SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/subjects/delete/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy');

    //classlevel
    Route::get('/class-levels', [ClassLevelController::class, 'index'])->name('class-levels');
    Route::post('/class-levels', [ClassLevelController::class, 'store'])->name('class-levels.store');
    Route::put('/class-levels/{classLevel}', [ClassLevelController::class, 'update'])->name('class-levels.update');
    Route::delete('/class-levels/{classLevel}', [ClassLevelController::class, 'destroy'])->name('class-levels.destroy');
    //streams
    Route::resource('streams', StreamController::class)->except(['create', 'show', 'edit']);

    //school Class

    Route::resource('school-classes', SchoolClassController::class);

    //guardians
    Route::resource('guardians', GuardianController::class);

    //teachers
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
    Route::get('/teachers/{id}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
    Route::put('/teachers/{id}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/teachers/{id}', [TeacherController::class, 'destroy'])->name('teachers.destroy');
    Route::get('/teachers/{teacher}/subjects', [TeacherController::class, 'getSubjectClassMap']);
    Route::get('/teachers/{teacher}/subjects', [TeacherController::class, 'getSubjects']);
    Route::get('/teachers/{id}/subjects', [TeacherController::class, 'getSubjects']);
    Route::put('/teachers/{teacher}', [TeacherController::class, 'update'])->name('teachers.update');




    Route::get('/migrate-teachers', [TeacherController::class, 'migrateTeachersToUsers']);
    Route::get('/migrate-students', [StudentController::class, 'migrateStudentsToUsers']);
    Route::post('/students/promote', [StudentController::class, 'promote'])
    ->name('students.promote');
    Route::get('/under-maintenance', [CustomAuthController::class, 'showUpdateCredentials'])->name('update.credentials');
    Route::post('/under-maintenance', [CustomAuthController::class, 'updateCredentials'])->name('update.credentials.save');

    Route::get('/zkteco_logs', [ZktecoLogsController::class, 'index'])->name('zkteco_logs.index');
Route::get('/zkteco-logs/pdf', [ZktecoLogController::class, 'exportPdf'])->name('zkteco_logs.pdf');

    //library
    Route::middleware(['auth'])->group(function () {
    Route::resource('library', LibraryController::class);
    Route::resource('library-categories', BookCategoryController::class)->except(['show', 'edit', 'create']);


   });
    //teacher dash
    Route::get('/teacher/dashboard', [App\Http\Controllers\TeacherDashboardController::class, 'index'])->name('teacher.dashboard');

    //timetable
    // Route::get('/timetable', [App\Http\Controllers\TimetableController::class, 'index'])->name('timetable.index');
    // Route::post('/timetable', [App\Http\Controllers\TimetableController::class, 'store'])->name('timetable.store');

    Route::get('/timetable', [TimetableController::class, 'index'])->name('timetable.index');
    // Route::get('/timetable/autogenerate', [TimetableController::class, 'autoGenerate'])->name('timetable.autogenerate');
    Route::post('/timetable/autogenerate', [TimetableController::class, 'autoGenerate'])->name('timetable.autogenerate');
    Route::get('/timetable/export/pdf/{classId}', [TimetableController::class, 'exportPdf'])->name('timetable.export.pdf');
    Route::get('/timetable/export/excel/{classId}', [TimetableController::class, 'exportExcel'])->name('timetable.export.excel');

    // Fees

    Route::get('/fee-structure', [FeeStructureController::class, 'index'])->name('fee-structure')->middleware('auth');

    Route::post('/fee-structure/store', [FeeStructureController::class, 'store'])->name('fee-structure.store')->middleware('auth');

    Route::post('/fee-structure/show', [FeeStructureController::class, 'show'])->middleware('auth');
    Route::post('/fee-structure/update', [FeeStructureController::class, 'update'])->name('fee-structure.update')->middleware('auth');

    Route::post('/fee-structure/delete', [FeeStructureController::class, 'destroy'])->name('fee-structure.delete')->middleware('auth');

    Route::get('/fee-payments', [FeePaymentsController::class, 'index'])->name('fee-payments');

    Route::resource('fee-payments', FeePaymentsController::class);
    Route::get('/students/balance', [FeePaymentsController::class, 'fetchBalance']);
    Route::get('/fee-payments/student/{student}', [FeePaymentsController::class, 'studentPayments'])->name('fee-payments.student');
    Route::post('/fee-payments/store', [FeePaymentsController::class, 'store'])->name('fee-payments.store');


    Route::middleware(['auth'])->group(function () {
        Route::get('/student/fee-payments', [App\Http\Controllers\SFeePaymentsController::class, 'index'])->name('student.fee-payments');
    });
    // routes/web.php
    Route::post('/student/payment-options', [StudentController::class, 'getPaymentOptions'])->name('get.payment.options');
    Route::get('/fee-dashboard', [FeeDashboardController::class, 'dashboard'])->name('fee.dashboard')->middleware('auth');
    Route::get('/fee-dashboard/print', [FeeDashboardController::class, 'print'])->name('fee.dashboard.print')->middleware('auth');
    Route::get('/fee-dashboard/download', [\App\Http\Controllers\FeePaymentsController::class, 'download'])->name('fee.dashboard.download');
    Route::post('/get-payment-options', [FeePaymentsController::class, 'getPaymentOptions'])->name('get.payment.options');
    Route::get('/print/fee-balances', [App\Http\Controllers\FeePaymentsController::class, 'printBalances'])->name('print.fee.balances');



    //Fees
    // ========== AJAX ROUTES (Used by JS) ==========

    // ✅ Get students by class (used in payment modal)
    Route::get('/students/by-class/{classId}', [StudentController::class, 'getByClass'])->name('students.byClass');

    // ✅ Get student balance for a specific term
    Route::get('/students/{studentId}/balance/{termId}', [StudentController::class, 'getBalance'])->name('students.balance');

    // ✅ Handle payment (already in your JS)
    //Route::post('/ajax/payments', [PaymentController::class, 'handleAjax'])->name('ajax.payments');


    Route::post('/get-students', [FeePaymentsController::class, 'fetchStudents'])->name('get.students');
    Route::post('/get-balance', [FeePaymentsController::class, 'fetchBalance'])->name('get.balance');
    Route::get('/fees/print-balances', [FeePaymentsController::class, 'printBalances'])->name('print.fee.balances');
    Route::get('/fees/print', [FeePaymentsController::class, 'printFeeBalance'])->name('fees.print');



    //human resource management


});

Route::get('/under-maintenance', function () {
    $userId = session('force_update_user');

    $user = $userId ? User::find($userId) : null;

    return view('under-maintenance', compact('user'));
})->name('under-maintenance');
Route::post('/under-maintenance', [CustomAuthController::class, 'updateCredentials'])->name('update.credentials');

Route::get('/register-2', function () {
    return view('register-2');
})->name('register-2');

Route::get('/php', function () {
    return view('php');
})->name('php');