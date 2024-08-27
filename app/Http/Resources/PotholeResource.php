<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PotholeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'address' => $this->address,
            'locality' => $this->locality,
            'image' => $this->image,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'status' => $this->status,
            'user_id' => $this->user_id,
            'description' => $this->description,
            'solution_description' => $this->solution_description,
            'predictions' => json_encode($this->predictions),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
