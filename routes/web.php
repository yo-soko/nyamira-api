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
    Route::get('/payslip/{id}', [EmployeeSalaryController::class, 'showPayslip']);
    Route::put('/employee-salary/update', [EmployeeSalaryController::class, 'update'])->name('employee-salary.update');
    Route::post('/employee-salary/delete', [EmployeeSalaryController::class, 'destroy'])->name('employee-salary.delete');

    Route::get('attendance-admin/', [AttendanciesController::class, 'index'])->name('attendance-admin.index');
    Route::get('attendance-employee/{id}', [AttendanciesController::class, 'markAttendance'])->name('attendance-employee');
    Route::post('/attendance-employee/clock-in', [AttendanciesController::class, 'clockIn'])->name('attendance-employee.clockIn');
    Route::post('/attendance-employee/clock-out', [AttendanciesController::class, 'clockOut'])->name('attendance-employee.clockOut');
    Route::post('/attendance-employee/break', [AttendanciesController::class, 'break'])->name('attendance-employee.break');
    Route::post('/attendance-employee/backFromBreak', [AttendanciesController::class, 'backFromBreak'])->name('attendance-employee.backFromBreak');

    Route::get('/add-employee', [EmployeeController::class, 'index'])->name('add-employee');  // List all employees
    Route::get('/employee/create', [EmployeeController::class, 'create'])->name('employee.create');  // Add new employee
    Route::post('/employees/store', [EmployeeController::class, 'store'])->name('employees.store');  // Store employee
    Route::get('/employees-list', [EmployeeController::class, 'list'])->name('employees-list');  // List of employees
    Route::get('/employee-details/{id}', [EmployeeController::class, 'show'])->name('employee.details');  // View employee details
    Route::get('/edit-employee/{id}', [EmployeeController::class, 'edit'])->name('edit-employee');  // Edit employee
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
});


Route::get('/payslip', function () {                         
    return view('payslip');
})->name('payslip'); 



Route::get('/ui-sweetalerts', function () {
    return view('ui-sweetalerts');
})->name('ui-sweetalerts');








Route::get('/roles-permissions', function () {
    return view('roles-permissions');
})->name('roles-permissions');

Route::get('/delete-account', function () {
    return view('delete-account');
})->name('delete-account');

Route::get('/permissions', function () {
    return view('permissions');
})->name('permissions');

Route::get('/form-select', function () {
    return view('form-select');
})->name('form-select');

Route::get('/form-mask', function () {
    return view('form-mask');
})->name('form-mask');

Route::get('/form-fileupload', function () {
    return view('form-fileupload');
})->name('form-fileupload');

Route::get('/form-horizontal', function () {
    return view('form-horizontal');
})->name('form-horizontal');

Route::get('/form-vertical', function () {
    return view('form-vertical');
})->name('form-vertical');

Route::get('/form-floating-labels', function () {
    return view('form-floating-labels');
})->name('form-floating-labels');

Route::get('/form-validation', function () {
    return view('form-validation');
})->name('form-validation');

Route::get('/form-select2', function () {
    return view('form-select2');
})->name('form-select2');

Route::get('/form-wizard', function () {
    return view('form-wizard');
})->name('form-wizard');

Route::get('/tables-basic', function () {
    return view('tables-basic');
})->name('tables-basic');

Route::get('/data-tables', function () {
    return view('data-tables');
})->name('data-tables');

Route::get('/sales-report', function () {
    return view('sales-report');
})->name('sales-report');

Route::get('/purchase-report', function () {
    return view('purchase-report');
})->name('purchase-report');

Route::get('/inventory-report', function () {
    return view('inventory-report');
})->name('inventory-report');

Route::get('/invoice-report', function () {
    return view('invoice-report');
})->name('invoice-report');

Route::get('/supplier-report', function () {
    return view('supplier-report');
})->name('supplier-report');

Route::get('/customer-report', function () {
    return view('customer-report');
})->name('customer-report');

Route::get('/expense-report', function () {
    return view('expense-report');
})->name('expense-report');

Route::get('/income-report', function () {
    return view('income-report');
})->name('income-report');

Route::get('/tax-reports', function () {
    return view('tax-reports');
})->name('tax-reports');

Route::get('/profit-and-loss', function () {
    return view('profit-and-loss');
})->name('profit-and-loss');



Route::get('/under-maintenance', function () {
    return view('under-maintenance');
})->name('under-maintenance');

Route::get('/blank-page', function () {
    return view('blank-page');
})->name('blank-page');

Route::get('/coming-soon', function () {
    return view('coming-soon');
})->name('coming-soon');

Route::get('/countries', function () {
    return view('countries');
})->name('countries');

Route::get('/states', function () {
    return view('states');
})->name('states');

Route::get('/error-404', function () {
    return view('error-404');
})->name('error-404');

Route::get('/error-500', function () {
    return view('error-500');
})->name('error-500');

Route::get('/lock-screen', function () {
    return view('lock-screen');
})->name('lock-screen');

Route::get('/two-step-verification-3', function () {
    return view('two-step-verification-3');
})->name('two-step-verification-3');

Route::get('/two-step-verification-2', function () {
    return view('two-step-verification-2');
})->name('two-step-verification-2');

Route::get('/two-step-verification', function () {
    return view('two-step-verification');
})->name('two-step-verification');

Route::get('/email-verification-3', function () {
    return view('email-verification-3');
})->name('email-verification-3');

Route::get('/email-verification-2', function () {
    return view('email-verification-2');
})->name('email-verification-2');

Route::get('/email-verification', function () {
    return view('email-verification');
})->name('email-verification');

Route::get('/reset-password-3', function () {
    return view('reset-password-3');
})->name('reset-password-3');

Route::get('/reset-password-2', function () {
    return view('reset-password-2');
})->name('reset-password-2');

Route::get('/reset-password', function () {
    return view('reset-password');
})->name('reset-password');

Route::get('/forgot-password-3', function () {
    return view('forgot-password-3');
})->name('forgot-password-3');

Route::get('/forgot-password-2', function () {
    return view('forgot-password-2');
})->name('forgot-password-2');

Route::get('/forgot-password', function () {
    return view('forgot-password');
})->name('forgot-password');

Route::get('/register-3', function () {
    return view('register-3');
})->name('register-3');

Route::get('/register-2', function () {
    return view('register-2');
})->name('register-2');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/signin-3', function () {
    return view('signin-3');
})->name('signin-3');



Route::get('/signin', function () {
    return view('signin');
})->name('signin');

Route::get('/success-3', function () {
    return view('success-3');
})->name('success-3');

Route::get('/success-2', function () {
    return view('success-2');
})->name('success-2');

Route::get('/success', function () {
    return view('success');
})->name('success');



Route::get('/security-settings', function () {
    return view('security-settings');
})->name('security-settings');

Route::get('/notification', function () {
    return view('notification');
})->name('notification');

Route::get('/connected-apps', function () {
    return view('connected-apps');
})->name('connected-apps');

Route::get('/system-settings', function () {
    return view('system-settings');
})->name('system-settings');

Route::get('/company-settings', function () {
    return view('company-settings');
})->name('company-settings');

Route::get('/localization-settings', function () {
    return view('localization-settings');
})->name('localization-settings');

Route::get('/prefixes', function () {
    return view('prefixes');
})->name('prefixes');

Route::get('/preference', function () {
    return view('preference');
})->name('preference');

Route::get('/appearance', function () {
    return view('appearance');
})->name('appearance');

Route::get('/social-authentication', function () {
    return view('social-authentication');
})->name('social-authentication');

Route::get('/language-settings', function () {
    return view('language-settings');
})->name('language-settings');

Route::get('/invoice-settings', function () {
    return view('invoice-settings');
})->name('invoice-settings');


Route::get('/department-list', function () {
    $departments = App\Models\Department::all();
    $hods = App\Models\User::whereIn('role', ['Manager', 'hods'])->get(); // Get the HODs
    return view('department-grid', compact('departments', 'hods'))->with('page', 'department-list');
})->name('department-list');


Route::get('/annual-report', function () {
    return view('annual-report');
})->name('annual-report');
Route::get('/layout-horizontal', function () {
    return view('layout-horizontal');
})->name('layout-horizontal');
Route::get('/layout-detached', function () {
    return view('layout-detached');
})->name('layout-detached');
Route::get('/layout-modern', function () {
    return view('layout-modern');
})->name('layout-modern');
Route::get('/layout-two-column', function () {
    return view('layout-two-column');
})->name('layout-two-column');
Route::get('/layout-hovered', function () {
    return view('layout-hovered');
})->name('layout-hovered');
Route::get('/layout-boxed', function () {
    return view('layout-boxed');
})->name('layout-boxed');
Route::get('/layout-rtl', function () {
    return view('layout-rtl');
})->name('layout-rtl');
Route::get('/layout-dark', function () {
    return view('layout-dark');
})->name('layout-dark');
Route::get('/income', function () {
    return view('income');
})->name('income');
Route::get('/income-category', function () {
    return view('income-category');
})->name('income-category');
Route::get('/account-list', function () {
    return view('account-list');
})->name('account-list');
Route::get('/money-transfer', function () {
    return view('money-transfer');
})->name('money-transfer');
Route::get('/balance-sheet', function () {
    return view('balance-sheet');
})->name('balance-sheet');
Route::get('/balance-sheet-v2', function () {
    return view('balance-sheet-v2');
})->name('balance-sheet-v2');
Route::get('/trial-balance', function () {
    return view('trial-balance');
})->name('trial-balance');
Route::get('/cash-flow', function () {
    return view('cash-flow');
})->name('cash-flow');
Route::get('/cash-flow-v2', function () {
    return view('cash-flow-v2');
})->name('cash-flow-v2');
Route::get('/account-statement', function () {
    return view('account-statement');
})->name('account-statement');
Route::get('/invoice-details', function () {
    return view('invoice-details');
})->name('invoice-details');
Route::get('/email-reply', function () {
    return view('email-reply');
})->name('email-reply');
Route::get('/sales-tax', function () {
    return view('sales-tax');
})->name('sales-tax');












Route::get('/pages', function () {
    return view('pages');
})->name('pages');

Route::get('/all-blog', function () {
    return view('all-blog');
})->name('all-blog');

Route::get('/blog-tag', function () {
    return view('blog-tag');
})->name('blog-tag');

Route::get('/blog-categories', function () {
    return view('blog-categories');
})->name('blog-categories');

Route::get('/blog-comments', function () {
    return view('blog-comments');
})->name('blog-comments');

Route::get('/cities', function () {
    return view('cities');
})->name('cities');

Route::get('/testimonials', function () {
    return view('testimonials');
})->name('testimonials');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');

Route::get('/email-templates', function () {
    return view('email-templates');
})->name('email-templates');

Route::get('/sms-settings', function () {
    return view('sms-settings');
})->name('sms-settings');

Route::get('/sms-templates', function () {
    return view('sms-templates');
})->name('sms-templates');

Route::get('/invoice-templates', function () {
    return view('invoice-templates');
})->name('invoice-templates');

Route::get('/signatures', function () {
    return view('signatures');
})->name('signatures');

Route::get('/billers', function () {
    return view('billers');
})->name('billers');

Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');

Route::get('/account-list', function () {
    return view('account-list');
})->name('account-list');

Route::get('/account-statement', function () {
    return view('account-statement');
})->name('account-statement');

Route::get('/balance-sheet-v2', function () {
    return view('balance-sheet-v2');
})->name('balance-sheet-v2');

Route::get('/balance-sheet', function () {
    return view('balance-sheet');
})->name('balance-sheet');

Route::get('/blog-details', function () {
    return view('blog-details');
})->name('blog-details');
