<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
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
            'contact_type' => $this->getTranslation('contact_type', app()->getLocale()),
            'contact_value' => $this->contact_value,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
