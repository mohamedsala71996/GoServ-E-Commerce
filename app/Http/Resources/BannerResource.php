<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
            'name' => $this->name, // Assuming 'name' is not translatable
            'order' => $this->order,
            'items' => BannerItemResource::collection($this->whenLoaded('items')), // Load related BannerItems
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
      }
}
