<?php

namespace App\Http\Resources;

use App\Models\Favorate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchResultResource extends JsonResource
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
            'service_title' => $this->service['title_'.lang()],
            'reviews_avg' => number_format($this->avg_rating, 1),
            'reviews_count' => $this->company->reviews->count(),
            'in_favorite' => Favorate::where('user_id', auth('sanctum')->id())->where('company_service_id', $this->id)->exists(),
        ];
    }
}
