<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListingResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'city' => $this->city,
            'suburb' => $this->suburb,
            'pricing_type' => $this->pricing_type,
            'price_amount' => number_format((float)$this->price_amount, 2, '.', ''),
            'price_display' => '$' . number_format((float)$this->price_amount, 2) . '/' . $this->pricing_type,
            'status' => $this->status,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            
            // Nested relationships (only when loaded)
            'category' => new CategoryResource($this->whenLoaded('category')),
            'provider' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
