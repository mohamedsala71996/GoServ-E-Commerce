<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource  extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $product = $this->productColorSize->productColor->product;

        // Decode the photos JSON and get the first photo
             $photos = json_decode($this->productColorSize->productColor->photos, true);
             $firstPhoto = $photos[0] ?? null;

        return [
            'id' => $this->id,
            'product_name' => $product->getTranslation('name', app()->getLocale()),
            'product_price' =>  $this->productColorSize->price,
            'quantity' => $this->quantity,
            'color' => $this->productColorSize->productColor->color->getTranslation('name', app()->getLocale()),
            'size' => $this->productColorSize->size->size,
            'price' => $this->quantity * $this->productColorSize->price,
            'price_after_discount' => $this->quantity * $this->productColorSize->price_after_discount,
            'photo' => $firstPhoto,
        ];
    }

}
