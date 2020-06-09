<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\v1;

use App\Http\Resources\Api\v1\RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\v1\PermissionResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => (int) $this->id,
            'login' => $this->login,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
            'created_at_date' => $this->created_at->format('d-m-Y'),
            'created_at_time' => $this->created_at->format('H:i:s'),
            'full_name' => $this->full_name,
            'access' => (array) $this->access,
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions'))
        ];
    }
}
