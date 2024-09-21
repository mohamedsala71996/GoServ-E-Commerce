<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllColorResource extends JsonResource
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
            'products_count' => $this->productColors()->count(), // Count the associated products
        ];
    }
}
