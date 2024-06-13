<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OldOrdersResource extends JsonResource
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
            'client_name' => $this->user ? $this->user->name : NULL,
            'company_title' => $this->company['title_'.lang()],
            'date' => Carbon::parse($this->date)->format('d F Y'),
            'time' => Carbon::parse($this->time)->format('H:i a'),
            'status' => $this->status ? __('dash.'.$this->status) : null,
            'total' => costformat($this->net_total),
        ];
    }
}