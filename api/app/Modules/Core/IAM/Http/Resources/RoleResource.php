<?php

namespace App\Modules\Core\IAM\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid'          => $this->uuid,
            'name'          => $this->name,
            'guard_name'    => $this->guard_name,
            'description'   => $this->description,
            'permissions'   => $this->whenLoaded('permissions', function () {
                return $this->permissions->map(function ($permission) {
                    return [
                        'uuid' => $permission->uuid,
                        'name' => $permission->name,
                    ];
                });
            }),
        ];
    }
}
