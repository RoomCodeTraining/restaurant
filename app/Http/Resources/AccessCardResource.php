<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccessCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'identifier' => $this->identifier,
            'quota_breakfast' => $this->quota_breakfast,
            'quota_lunch' => $this->quota_lunch,
            'payment_method' => new PaymentMethodResource($this->paymentMethod),
            'user' => new UserResource($this->user),
        ];
    }
}
