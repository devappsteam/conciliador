<?php

namespace App\Modules\Integrations\Acquirer\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AcquirerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'slug' => $this->slug,
            'code' => $this->code,
            'logo' => $this->logo,
            'status' => (bool) $this->status,
        ];
    }
}
