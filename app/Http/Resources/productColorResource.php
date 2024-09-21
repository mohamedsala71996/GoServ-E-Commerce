<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class productColorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product_color_id' => $this->id,
            // 'color_id' => $this->color->id,
            'color' => $this->color->getTranslation('name', app()->getLocale()),
            'hex_code' => $this->color->hex_code,
            // 'price' => $this->price,
            // 'quantity' => $this->quantity,
            'photos' => json_decode($this->photos, true), // Decode JSON to array
            'sizes' => productColorSizeResource::collection($this->productColorSizes),
            // 'sold_count' => $this->sold_count,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
}
