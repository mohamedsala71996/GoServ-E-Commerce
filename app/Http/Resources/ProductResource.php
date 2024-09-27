<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $activeOffer =$this->productColors[0]->productColorSizes[0]->offers()
        ->first();

        $photos = json_decode($this->productColors[0]->photos, true);
             $firstPhoto = $photos[0] ?? null;
        return [
            'id' => $this->id,
            'name' => $this->getTranslation('name', app()->getLocale()),
            'description' => $this->getTranslation('description', app()->getLocale()),
            'details' => $this->getTranslation('details', app()->getLocale()) ?? null,
            'firstPhoto' => $firstPhoto,
            'Photos' =>  $photos,
            'category_name' => $this->category->getTranslation('name', app()->getLocale()),
            'brand_name' =>$this->brand ? $this->brand->getTranslation('name', app()->getLocale()) : null,
            // 'rating' => $this->rating,
            'weight' => $this->weight,
            'price' => $this->productColors[0]->productColorSizes[0]->price,
            'discount' => $activeOffer ? $activeOffer->discount_percentage : null, // Show discount if available
            'price_after_discount' => $this->productColors[0]->productColorSizes[0]->price_after_discount, // Show price after discount
            'offer_start' => $activeOffer->start_time ?? null, // Show price after discount
            'offer_end' => $activeOffer->end_time ?? null, // Show price after discount
            'reviews' => $this->reviews->map(function($review) {
                return [
                    'rating' => $review->average_rating,
                    'comment' => $review->comment,
                    'user_name' => $review->user->name,
                ];
            }),
            'features' => $this->features->map(function($feature) {
                return [
                    'name' => $feature->getTranslation('name', app()->getLocale()),
                    'description' => $feature->getTranslation('description', app()->getLocale()),
                ];
            }),
            'color_photos_quantity' => productColorResource::collection($this->productColors), // Add this line
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
