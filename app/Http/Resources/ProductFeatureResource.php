<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductFeatureResource  extends JsonResource
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
            'name' => $this->getTranslation('name', app()->getLocale()),
            'description' => $this->getTranslation('description', app()->getLocale()),
            'product_id' => $this->product_id,
            'product_name' => $this->product->getTranslation('name', app()->getLocale()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
