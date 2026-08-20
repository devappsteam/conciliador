<?php

namespace App\Modules\Integrations\Brand\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DevApps\LaravelModulesKit\Traits\Uuid;

class Brand extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;

    protected $table = 'brands';

    protected $fillable = [
        'uuid',
        'name',
        'code',
        'logo',
        'status',
    ];

    protected $hidden = [
        'id',
    ];

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
