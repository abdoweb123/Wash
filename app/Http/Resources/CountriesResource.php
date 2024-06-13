<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'country_code' => $this->phone_code,
            'image' => asset($this->image),
            'phone_length' => $this->length,
            'title' => $this['title_'.lang()]
        ];
    }
}
