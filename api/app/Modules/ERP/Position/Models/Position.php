<?php

namespace App\Modules\ERP\Position\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DevApps\LaravelModulesKit\Traits\Uuid;

class Position extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;

    protected $table = 'positions';

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
