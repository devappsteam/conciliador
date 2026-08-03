<?php

namespace App\Modules\Core\User\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid'          => $this->uuid,
            'avatar_url'    => $this->avatar_url ?? null,
            'name'          => $this->name,
            'email'         => $this->email,
            'role'          => $this->role ?? null,
            'last_login_at' => $this->last_login_at ?? null,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
