<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'name' => $this->getTranslation('name', app()->getLocale()),
            'description' => $this->getTranslation('description', app()->getLocale()),
            'photo' => $this->photo,
            'product_count' => $this->products_count, // This will include the count of products
            'link_all_categories' => url('/website/categories'), // Generate the link for the categories
            'link_one_category' => url('/website/categories/' . $this->id ?? ''), // Generate the link for the category
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
