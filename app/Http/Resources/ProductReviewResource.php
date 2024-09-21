<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductReviewResource extends JsonResource
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
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'status' => $this->status ?? 'pending',
            'user' => $this->user->name,
            'product' => $this->product->getTranslation('name', app()->getLocale()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
