<?php

namespace App\Modules\Reconciliation\AcquirerConfig\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyAcquirerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'company' => $this->whenLoaded('company', fn($company) => [
                'uuid' => $company->uuid,
                'name' => $company->name,
            ]),
            'acquirer' => $this->whenLoaded('acquirer', fn($acquirer) => [
                'uuid' => $acquirer->uuid,
                'name' => $acquirer->name,
                'slug' => $acquirer->slug,
                'code' => $acquirer->code,
            ]),
            'merchant_id' => $this->merchant_id,
            'is_active' => $this->is_active,
            'rates'     => CompanyAcquirerRateResource::collection($this->whenLoaded('rates')),
            'anticipation' => new CompanyAnticipationResource($this->whenLoaded('anticipationConfig')),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
