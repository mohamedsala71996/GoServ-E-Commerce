<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource  extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_id' => $this->productColorSize->productColor->product->id,
            'product_color_size_id' => $this->product_color_size_id,
            'product_name' => $this->productColorSize->productColor->product->getTranslation('name', app()->getLocale()),
            'product_color' => $this->productColorSize->productColor->color->getTranslation('name', app()->getLocale()),
            'product_size' => $this->productColorSize->size->size,
            'discount_percentage' => $this->discount_percentage,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'is_active' => $this->is_active,
        ];
    }

}
