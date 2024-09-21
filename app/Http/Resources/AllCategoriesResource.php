<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllCategoriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale(); // Get the current locale

        return [
            'name' => $this->getTranslation('name', $locale),
            'products_count' => $this->products_count,
        ];
    }
}
