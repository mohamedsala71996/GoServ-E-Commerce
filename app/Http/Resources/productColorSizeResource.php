<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class productColorSizeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
           $activeOffer =$this->offers()->first();
        return [
            'size_id' => $this->size_id,
            'size' => $this->size->size,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'cost' => $this->cost,
            'discount' => $activeOffer ? $activeOffer->discount_percentage : null, // Show discount if available
            'price_after_discount' => $this->price_after_discount, // Show price after discount
            'offer_start' => $activeOffer->start_time ?? null, // Show price after discount
            'offer_end' => $activeOffer->end_time ?? null, // Show price after discount

        ];
    }
}
