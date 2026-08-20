<?php

namespace App\Modules\Core\Company\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid'                      => $this->uuid,
            'document'                  => $this->document,
            'corporate_name'            => $this->corporate_name,
            'trade_name'                => $this->trade_name,
            'state_registration'        => $this->state_registration,
            'municipal_registration'    => $this->municipal_registration,
            'email'                     => $this->email,
            'phone'                     => $this->phone,
            'street'                    => $this->street,
            'number'                    => $this->number,
            'complement'                => $this->complement,
            'neighborhood'              => $this->neighborhood,
            'city'                      => $this->city,
            'state'                     => $this->state,
            'zip_code'                  => $this->zip_code,
            'country'                   => $this->country,
            'status'                    => $this->status?->value,
            'status_label'              => $this->status?->label(),
            'created_at'                => $this->created_at?->toIso8601String(),
            'updated_at'                => $this->updated_at?->toIso8601String(),
        ];
    }
}
