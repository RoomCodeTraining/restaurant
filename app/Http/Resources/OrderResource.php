<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'menu' => $this->menu->served_at->format('d/m/Y'),
            'dish' => new DishResource($this->dish),
            'type' => $this->is_for_the_evening ? 'Commande pour le soir' : 'Commande pour midi',
            'state' => $this->state->title()
        ];
    }
}