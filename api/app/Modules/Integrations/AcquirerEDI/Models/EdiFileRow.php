<?php

namespace App\Modules\Integrations\AcquirerEDI\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Integrations\AcquirerEDI\Models\EdiFile;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Integrations\AcquirerEDI\Enums\EDIStatusEnum;

class EdiFileRow extends Model
{
    use HasUuids;

    protected $fillable = [
        'edi_file_id',
        'line_number',
        'raw_data',
        'status',
        'error_message',
    ];

    protected $casts = [
        'status' => EDIStatusEnum::class,
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(EdiFile::class, 'edi_file_id');
    }
}
