<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
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
            'company_service_id' => $this->company_service_id,
            'service_title' => $this['title_'.lang()],
            'quantity' => $this->standard_quantity,
            'standard' => $this->standard['plural_title_'.lang()],
            'materials_cost' => costformat($this->need_materials == true ? $this->cleaning_materials_cost : 0),
            'price' => costformat($this->price),
            'total' => costformat(($this->price + ($this->need_materials == true ? $this->cleaning_materials_cost : 0)) * $this->standard_quantity),
            'note' => $this->note
        ];
    }
}
