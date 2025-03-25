<?php

namespace App\Domains\Owner\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OwnerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'idcard' => $this->idcard,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
