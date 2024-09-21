<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OutOfStockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $photos = json_decode($this->productColor->photos, true);
        $firstPhoto = $photos[0] ?? null;

        return [
            'id' => $this->id,
            'product' => $this->productColor->product->getTranslation('name', app()->getLocale()),
            'color' => $this->productColor->color->getTranslation('name', app()->getLocale()),
            'size' => $this->size->size,
            'photo' => $firstPhoto,
            // 'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
