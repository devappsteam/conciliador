<?php

namespace App\Modules\Reconciliation\AcquirerConfig\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DevApps\LaravelModulesKit\Traits\Uuid;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Modules\Reconciliation\AcquirerConfig\Models\CompanyAcquirerRate;
use App\Modules\Reconciliation\AcquirerConfig\Models\CompanyAnticipation;

class CompanyAcquirer extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;

    protected $table = 'company_acquirers';

    protected $fillable = [
        'uuid',
        'company_id',
        'acquirer_id',
        'merchant_id',
        'is_active',
    ];

    protected $hidden = [
        'id',
        'company_id',
        'acquirer_id',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function rates(): HasMany
    {
        return $this->hasMany(CompanyAcquirerRate::class);
    }

    public function anticipationConfig(): HasOne
    {
        return $this->hasOne(CompanyAnticipation::class);
    }
}
