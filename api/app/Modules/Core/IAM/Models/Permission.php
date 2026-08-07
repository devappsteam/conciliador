<?php

namespace App\Modules\Core\IAM\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DevApps\LaravelModulesKit\Traits\Uuid;

class Permission extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;

    protected $table = 'permissions';

    protected $fillable = [
        'uuid',
        'name',
        'guard_name',
        'module',
    ];

    protected $hidden = [
        'id',
    ];

    protected $casts = [
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'deleted_at'    => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role', 'permission_id', 'role_id');
    }
}
