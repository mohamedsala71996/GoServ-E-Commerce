<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PrivacyPolicyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->getTranslation('description', app()->getLocale()),
            'status' => $this->status,
        ];
    }
}
