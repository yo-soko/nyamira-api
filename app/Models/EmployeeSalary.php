<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    protected $fillable = [
        'employee_id', 'payment_date', 'basic_salary', 'payment_method', 'reference_code', 'notes', 'status',
        'allowance1', 'allowance2', 'allowance3', 'bonus', 'others1',
        'deduction1', 'deduction2', 'deduction3', 'deduction4', 'others',
        'total_deduction', 'net_salary',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

}
