<?php

namespace App\Modules\Core\IAM\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid'          => $this->uuid,
            'name'          => $this->name,
            'slug'          => $this->slug,
            'guard_name'    => $this->guard_name,
        ];
    }
}
