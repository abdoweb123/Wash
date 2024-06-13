<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyServiceResource extends JsonResource
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
            'standard_singular_title' => $this->standard['singular_title_'.lang()],
            'price' => $this->price,
            'company_id' => $this->company_id,
            'company_title' => $this->company['title_'.lang()],
            'company_disc' => $this->company['disc_'.lang()],
            'reviews_avg' => number_format($this->company->reviews_avg_star, 1),
            'reviews_count' => $this->company->reviews_count,
            'in_favorite' =>$this->favorates?->where('id', auth('sanctum')->id() ?? 'bla')->first() ? true : false,
        ];
    }
}
