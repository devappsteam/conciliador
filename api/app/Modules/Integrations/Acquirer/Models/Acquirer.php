<?php

namespace App\Modules\Integrations\Acquirer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DevApps\LaravelModulesKit\Traits\Uuid;

class Acquirer extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;

    protected $table = 'acquirers';

    protected $fillable = [
        'uuid',
        'name',
        'slug',
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
