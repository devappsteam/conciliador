<?php

namespace App\Modules\ERP\Contract\Models;

use App\Modules\Core\Company\Models\Company;
use App\Modules\ERP\Contract\Enums\BillingCycleEnum;
use App\Modules\ERP\Contract\Enums\ContractStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DevApps\LaravelModulesKit\Traits\Uuid;

class Contract extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;

    protected $table = 'contracts';

    protected $fillable = [
        'uuid',
        'company_id',
        'code',
        'status',
        'billing_cycle',
        'amount',
        'transaction_limit',
        'overage_fee',
        'start_date',
        'end_date',
        'notes',
    ];

    protected $hidden = [
        'id',
        'company_id',
    ];

    protected $casts = [
        'amount'            => 'decimal:2',
        'transaction_limit' => 'integer',
        'overage_fee'       => 'decimal:4',
        'start_date'        => 'date',
        'end_date'          => 'date',
        'status'            => ContractStatusEnum::class,
        'billing_cycle'     => BillingCycleEnum::class,
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function isActive(): bool
    {
        return $this->status === ContractStatusEnum::ACTIVE
            && $this->start_date->lte(now())
            && ($this->end_date === null || $this->end_date->gte(now()));
    }
}
