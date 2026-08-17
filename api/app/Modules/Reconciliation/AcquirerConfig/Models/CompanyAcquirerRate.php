<?php

namespace App\Modules\Reconciliation\AcquirerConfig\Models;

use App\Modules\Reconciliation\AcquirerConfig\Enums\ProductTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DevApps\LaravelModulesKit\Traits\Uuid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Reconciliation\AcquirerConfig\Models\CompanyAcquirer;

class CompanyAcquirerRate extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;

    protected $table = 'company_acquirer_rates';

    protected $fillable = [
        'uuid',
        'company_acquirer_id',
        'product_type',
        'brand',
        'installment_min',
        'installment_max',
        'rate_percentage',
        'rate_fixed',
        'effective_date',
    ];

    protected $hidden = [
        'id',
        'company_acquirer_id',
    ];

    protected $casts = [
        'company_acquirer_id'   => 'integer',
        'product_type'          => ProductTypeEnum::class,
        'installment_min'       => 'integer',
        'installment_max'       => 'integer',
        'rate_percentage'       => 'decimal:4',
        'rate_fixed'            => 'decimal:4',
        'effective_date'        => 'datetime',
        'created_at'            => 'datetime',
        'updated_at'            => 'datetime',
        'deleted_at'            => 'datetime',
    ];

    public function companyAcquirer(): BelongsTo
    {
        return $this->belongsTo(CompanyAcquirer::class);
    }
}
