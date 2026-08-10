<?php

namespace App\Modules\ERP\Employee\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid'              => $this->uuid,
            'employee_code'     => $this->employee_code,
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            'cpf'               => $this->cpf,
            'email'             => $this->email,
            'status'            => $this->status,
            'profile_picture'   => Storage::url($this->profile_picture),
            'department'        => $this->whenLoaded('department', function () {
                return [
                    'uuid' => $this->department?->uuid,
                    'name' => $this->department?->name,
                ];
            }),
            'position'          => $this->whenLoaded('position', function () {
                return [
                    'uuid' => $this->position?->uuid,
                    'name' => $this->position?->name,
                ];
            }),
            'created_at'        => $this->created_at?->toIso8601String(),
            'updated_at'        => $this->updated_at?->toIso8601String(),
        ];
    }
}
