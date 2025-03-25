<?php

namespace App\Domains\Car\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'car_identifier' => $this->car_id,
            'owner_id' => $this->owner_id,
            'type' => $this->type,
            'registration_date' => $this->registered,
            'is_own_brand' => (bool)$this->ownbrand,
            'accident_count' => $this->accident,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
