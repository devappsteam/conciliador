<?php

namespace App\Modules\ERP\Employee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DevApps\LaravelModulesKit\Traits\Uuid;

class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;

    protected $table = 'employees';

    protected $fillable = [
        'uuid',
        'department_id',
        'position_id',
        'employee_code',
        'first_name',
        'last_name',
        'preferred_name',
        'birth_date',
        'gender',
        'cpf',
        'rg',
        'rg_issuer',
        'pis_pasep',
        'email',
        'personal_email',
        'phone',
        'mobile_phone',
        'zip_code',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'country',
        'hire_date',
        'termination_date',
        'status',
        'notes',
        'profile_picture',
    ];

    protected $hidden = [
        'id',
        'department_id',
        'position_id',
    ];

    protected $casts = [
        'birth_date'        => 'date',
        'hire_date'         => 'date',
        'termination_date'  => 'date',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => 'datetime',
    ];
}
