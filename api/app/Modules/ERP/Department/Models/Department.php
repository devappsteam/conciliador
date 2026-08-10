<?php

namespace App\Modules\ERP\Department\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DevApps\LaravelModulesKit\Traits\Uuid;

class Department extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;

    protected $table = 'departments';

    protected $fillable = [
        'uuid',
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected $hidden = [
        'id',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
