<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            // 'order_id' => $this->order_id,
            // 'product_color_id' => $this->product_color_id,
            'product' => $this->productColorSize->productColor->product->getTranslation('name', app()->getLocale()),
            'color' => $this->productColorSize->productColor->color->getTranslation('name', app()->getLocale()),
            'size' => $this->productColorSize->size->size,
            'quantity' => $this->quantity,
            'price' => $this->price,
            // Include other necessary fields
        ];
    }
}
