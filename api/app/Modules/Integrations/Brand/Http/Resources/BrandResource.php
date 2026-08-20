<?php

namespace App\Modules\Integrations\Brand\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'code' => $this->code,
            'logo' => $this->logo,
            'status' => (bool) $this->status,
        ];
    }
}
