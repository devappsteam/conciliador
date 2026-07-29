<?php

namespace App\Modules\Core\Company\Models;

use App\Modules\Core\Company\Database\Factories\CompanyFactory;
use App\Modules\Core\Company\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DevApps\LaravelModulesKit\Traits\Uuid;

class Company extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;

    protected $table = 'companies';

    protected $fillable = [
        'uuid',
        'document',
        'corporate_name',
        'trade_name',
        'state_registration',
        'municipal_registration',
        'email',
        'phone',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'zip_code',
        'country',
        'status'
    ];

    protected $hidden = [
        'id',
    ];

    protected $casts = [
        'status'        => Status::class,
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'deleted_at'    => 'datetime',
    ];

    protected static function newFactory()
    {
        return CompanyFactory::new();
    }
}
