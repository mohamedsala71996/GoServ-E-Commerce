<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PreferProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $photos = json_decode($this->product->main_photos, true);
        $firstPhoto = $photos[0] ?? null;

        return [
            'id' => $this->id,
            'title' => $this->getTranslation('title', app()->getLocale()),
            'product_name' => $this->product->getTranslation('name', app()->getLocale()),
            'photo' => $this->photo ?? $firstPhoto,
            'product_desc' => $this->product->getTranslation('description', app()->getLocale()),
            'product_link' => $this->product ? url('/products/' . $this->product->id) : null, // Generate URL for product details
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
