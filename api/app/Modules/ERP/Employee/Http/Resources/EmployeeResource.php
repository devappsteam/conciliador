<?php

namespace App\Modules\ERP\Employee\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid'              => $this->uuid,
            'employee_code'     => $this->employee_code,
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            'preferred_name'    => $this->preferred_name,
            'birth_date'        => $this->birth_date?->toDateString(),
            'gender'            => $this->gender,
            'cpf'               => $this->cpf,
            'rg'                => $this->rg,
            'rg_issuer'         => $this->rg_issuer,
            'pis_pasep'         => $this->pis_pasep,
            'email'             => $this->email,
            'personal_email'    => $this->personal_email,
            'phone'             => $this->phone,
            'mobile_phone'      => $this->mobile_phone,
            'zip_code'          => $this->zip_code,
            'street'            => $this->street,
            'number'            => $this->number,
            'complement'        => $this->complement,
            'neighborhood'      => $this->neighborhood,
            'city'              => $this->city,
            'state'             => $this->state,
            'country'           => $this->country,
            'hire_date'         => $this->hire_date?->toDateString(),
            'termination_date'  => $this->termination_date?->toDateString(),
            'status'            => $this->status,
            'notes'             => $this->notes,
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
