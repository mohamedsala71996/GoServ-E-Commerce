<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'title' => $this->getTranslation('title', $locale),
            'description' => $this->getTranslation('description', $locale),
            'link' => $this->link,
            'photo' => $this->photo,
        ];
    }

}
