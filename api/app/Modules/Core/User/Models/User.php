<?php

namespace App\Modules\Core\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use DevApps\LaravelModulesKit\Traits\Uuid;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;
    use Notifiable;
    use HasApiTokens;

    protected $table = 'users';

    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'last_login_at',
    ];

    protected $hidden = [
        'id',
        'password',
        'remember_token',
        'mfa_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'password'          => 'hashed',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => 'datetime',
    ];
}
