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

use App\Http\Controllers\SdashboardController;
use App\Http\Controllers\TdashboardController;
use App\Http\Controllers\LibraryController;


Route::get('signin',            [CustomAuthController::class, 'index'])->name('signin');
Route::post('custom-login',     [CustomAuthController::class, 'customSignin'])->name('signin.custom');
Route::get('register',          [CustomAuthController::class, 'registration'])->name('register');
Route::post('custom-register',  [CustomAuthController::class, 'customRegister'])->name('register.custom');

Route::get('index',             [CustomAuthController::class, 'dashboard'])->middleware('auth');
Route::get('signout',           [CustomAuthController::class, 'signOut'])->name('signout');




Route::get('/', function () {
    return view('login');
})->name('login');
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::middleware(['auth'])->group(function () {

    Route::get('index/', [AttendanciesController::class, 'indexx'])->name('index.index');


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

    Route::get('/profile', function () {return view('profile');})->name('profile');
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
    Route::get('/exams', [ExamController::class, 'index'])->name('exams');

    Route::get('/submit-results', [ResultController::class, 'filterForm'])->name('submit-results');
    Route::get('/results-entry', [ResultController::class, 'entryForm'])->name('results.entry');
    Route::post('/results/store', [ResultController::class, 'store'])->name('results.store');
    Route::get('/results-filter', [ResultController::class, 'showFilterForm'])->name('results-filter');
    Route::post('/results-filter', [ResultController::class, 'filterResults'])->name('results-filter');
    Route::get('/results-view', [ResultController::class, 'viewResults'])->name('results-view');

    Route::get('/tdashboard', [TdashboardController::class, 'index'])->name('tdashboard');

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
    Route::get('/migrate-teachers', [TeacherController::class, 'migrateTeachersToUsers']);
    Route::get('/migrate-students', [StudentController::class, 'migrateStudentsToUsers']);
    //library
    Route::middleware(['auth'])->group(function () {
    Route::resource('library', LibraryController::class);
    Route::resource('library-categories', BookCategoryController::class)->except(['show']);});


    // Fees

    Route::get('/fee-structure', [FeeStructureController::class, 'index'])->name('fee-structure')->middleware('auth');

    Route::post('/fee-structure/store', [FeeStructureController::class, 'store'])->middleware('auth');
    Route::post('/fee-structure/show', [FeeStructureController::class, 'show'])->middleware('auth');
    Route::post('/fee-structure/update', [FeeStructureController::class, 'update'])->middleware('auth');
    Route::post('/fee-structure/delete', [FeeStructureController::class, 'destroy'])->middleware('auth');
    Route::get('/fee-payments', [FeePaymentsController::class, 'index'])->name('fee-payments');

    Route::resource('fee-payments', FeePaymentsController::class);
    Route::get('/students/balance', [FeePaymentsController::class, 'fetchBalance']);
    Route::get('/fee-payments/student/{student}', [FeePaymentsController::class, 'studentPayments'])->name('fee-payments.student');
    Route::post('/fee-payments/store', [FeePaymentsController::class, 'store'])->name('fee-payments.store');




    //Fees
    // ========== AJAX ROUTES (Used by JS) ==========

    // ✅ Get students by class (used in payment modal)
    Route::get('/students/by-class/{classId}', [StudentController::class, 'getByClass'])->name('students.byClass');

    // ✅ Get student balance for a specific term
    Route::get('/students/{studentId}/balance/{termId}', [StudentController::class, 'getBalance'])->name('students.balance');

    // ✅ Handle payment (already in your JS)
    Route::post('/ajax/payments', [PaymentController::class, 'handleAjax'])->name('ajax.payments');


    Route::post('/get-students', [FeePaymentsController::class, 'fetchStudents'])->name('get.students');
    Route::post('/get-balance', [FeePaymentsController::class, 'fetchBalance'])->name('get.balance');



});


Route::get('/delete-account', function () {
    return view('delete-account');
})->name('delete-account');

Route::get('/register-2', function () {
    return view('register-2');
})->name('register-2');

