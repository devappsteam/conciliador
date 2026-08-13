<?php

namespace App\Modules\Integrations\Bank\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DevApps\LaravelModulesKit\Traits\Uuid;

class Bank extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;

    protected $table = 'banks';

    protected $fillable = [
        'uuid',
        'name',
        'code',
        'logo',
    ];

    protected $hidden = [
        'id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
