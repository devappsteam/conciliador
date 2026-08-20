<?php

namespace App\Modules\Integrations\AcquirerEDI\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Integrations\AcquirerEDI\Models\EdiFileRow;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Modules\Integrations\AcquirerEDI\Enums\EDIStatusEnum;
use App\Modules\Integrations\AcquirerEDI\Enums\EDITypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EdiFile extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'acquirer_id',
        'company_id',
        'file_name',
        'file_type',
        'status',
        'file_hash',
        'processed_at',
    ];

    protected $hidden = [
        'id',
        'acquirer_id',
        'company_id',
    ];

    protected $casts = [
        'status'       => EDIStatusEnum::class,
        'file_type'    => EDITypeEnum::class,
        'processed_at' => 'datetime',
    ];

    public function rows(): HasMany
    {
        return $this->hasMany(EdiFileRow::class);
    }
}
