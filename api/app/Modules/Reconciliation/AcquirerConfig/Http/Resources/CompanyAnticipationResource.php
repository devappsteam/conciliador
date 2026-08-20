<?php

namespace App\Modules\Reconciliation\AcquirerConfig\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyAnticipationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid'                      => $this->uuid,
            'anticipation_type'         => $this->anticipation_type->value,
            'rate_percentage_monthly'   => (float) $this->rate_percentage_monthly,
            'rate_fixed_per_operation'  => (float) $this->rate_fixed_per_operation,
        ];
    }
}
