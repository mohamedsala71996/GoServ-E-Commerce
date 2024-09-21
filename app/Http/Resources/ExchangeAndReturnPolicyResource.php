<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExchangeAndReturnPolicyResource extends JsonResource
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
            'description' => $this->getTranslation('description', app()->getLocale()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
