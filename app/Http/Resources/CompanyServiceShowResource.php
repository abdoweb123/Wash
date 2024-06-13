<?php

namespace App\Http\Resources;

use App\Models\Favorate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyServiceShowResource extends JsonResource
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
            'image' => asset($this->company->logo),
            'company_title' => $this->company['title_'.lang()],
            'company_id' => $this->company_id,
            'service_title' => $this->service['title_'.lang()],
            'standard_singular_title' => $this->standard['singular_title_'.lang()],
            'standard_plural_title' => $this->standard['plural_title_'.lang()],
            'price' => $this->price,
            'cleaning_materials_cost' => $this->cleaning_materials_cost,
            'disc' => $this['disc_'.lang()],
            'reviews_avg' => number_format($this->company->reviews_avg_star, 1),
            'reviews_count' => $this->company->reviews_count,
            'in_favorite' =>$this->favorates?->where('id', auth('sanctum')->id() ?? 'bla')->first() ? true : false,
        ];
    }
}
