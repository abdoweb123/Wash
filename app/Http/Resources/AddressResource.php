<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'country' => $this->region->country['title_'.lang()],
            'region' => $this->region['title_'.lang()],
            'block' => $this->block,
            'road' => $this->road,
            'floor_no' => $this->floor_no,
            'appartment_no' => $this->appartment_no,
            'note' => $this->note,
            'lat' => $this->lat,
            'long' => $this->long,
            'is_active' => $this->is_active
        ];
    }
}
