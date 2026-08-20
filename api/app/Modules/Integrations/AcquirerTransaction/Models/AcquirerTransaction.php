<?php

namespace App\Modules\Integrations\AcquirerTransaction\Models;

use App\Modules\Core\Company\Models\Company;
use App\Modules\Integrations\AcquirerEDI\Models\EdiFileRow;
use App\Modules\Integrations\AcquirerTransaction\Enums\ProductTypeEnum;
use App\Modules\Integrations\AcquirerTransaction\Enums\ReconciliationStatusEnum;
use App\Modules\Integrations\AcquirerTransaction\Enums\TransactionStatusEnum;
use App\Modules\Reconciliation\AcquirerConfig\Models\CompanyAcquirer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DevApps\LaravelModulesKit\Traits\Uuid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcquirerTransaction extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;

    protected $table = 'acquirer_transactions';

    protected $fillable = [
        'uuid',
        'company_id',
        'company_acquirer_id',
        'edi_file_row_id',
        'establishment_code',
        'acquirer_nsu',
        'authorization_code',
        'tid',
        'sale_date_time',
        'payment_expected_date',
        'payment_real_date',
        'gross_amount',
        'net_amount',
        'fee_amount',
        'fee_percentage',
        'fixed_fee_amount',
        'installment_number',
        'total_installments',
        'card_brand',
        'card_number_masked',
        'product_type',
        'status',
        'reconciliation_status',
    ];

    protected $hidden = [
        'id',
        'company_id',
        'company_acquirer_id',
        'edi_file_row_id',
    ];

    protected $casts = [
        'sale_date_time'        => 'datetime',
        'payment_expected_date' => 'date',
        'payment_real_date'     => 'date',
        'gross_amount'          => 'decimal:2',
        'net_amount'            => 'decimal:2',
        'fee_amount'            => 'decimal:2',
        'fee_percentage'        => 'decimal:4',
        'fixed_fee_amount'      => 'decimal:2',
        'installment_number'    => 'integer',
        'total_installments'    => 'integer',
        'product_type'          => ProductTypeEnum::class,
        'status'                => TransactionStatusEnum::class,
        'reconciliation_status' => ReconciliationStatusEnum::class,
        'created_at'            => 'datetime',
        'updated_at'            => 'datetime',
        'deleted_at'            => 'datetime',
    ];


    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function companyAcquirer(): BelongsTo
    {
        return $this->belongsTo(CompanyAcquirer::class);
    }

    public function ediRow(): BelongsTo
    {
        return $this->belongsTo(EdiFileRow::class);
    }
}
