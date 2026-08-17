<?php

namespace App\Modules\Reconciliation\AcquirerConfig\Models;

use App\Modules\Reconciliation\AcquirerConfig\Enums\AnticipationTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DevApps\LaravelModulesKit\Traits\Uuid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Reconciliation\AcquirerConfig\Models\CompanyAcquirer;

class CompanyAnticipation extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;

    protected $table = 'company_anticipations';

    protected $fillable = [
        'uuid',
        'company_acquirer_id',
        'anticipation_type',
        'rate_percentage_monthly',
        'rate_fixed_per_operation',
    ];

    protected $hidden = [
        'id',
        'company_acquirer_id',
    ];

    protected $casts = [
        'company_acquirer_id'       => 'integer',
        'anticipation_type'         => AnticipationTypeEnum::class,
        'rate_percentage_monthly'   => 'decimal:4',
        'rate_fixed_per_operation'  => 'decimal:4',
        'created_at'                => 'datetime',
        'updated_at'                => 'datetime',
        'deleted_at'                => 'datetime',
    ];

    public function companyAcquirer(): BelongsTo
    {
        return $this->belongsTo(CompanyAcquirer::class);
    }
}
