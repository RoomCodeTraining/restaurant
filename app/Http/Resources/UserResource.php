<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return  [
            'id' => $this->id,
            'identifier' => $this->identifier,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'contact' => $this->contact,

            'department' => new DepartmentResource($this->whenLoaded('department')),
            'organization' => new OrganizationResource($this->whenLoaded('organization')),
            'accessCards' => new AccessCardResource($this->accessCard)
        ];
    }
}