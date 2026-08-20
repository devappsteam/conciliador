<?php

namespace App\Modules\Reconciliation\AcquirerConfig\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyAcquirerRateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'product_type' => $this->product_type->value,
            'product_label' => $this->product_type->label(),
            'brand' => $this->brand ?? 'Todas',
            'installment_min' => $this->installment_min,
            'installment_max' => $this->installment_max,
            'rate_percentage' => (float) $this->rate_percentage,
            'rate_fixed' => (float) $this->rate_fixed,
            'effective_date' => $this->effective_date->format('Y-m-d'),
        ];
    }
}
