<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        // $activeOffer =$this->offers()
        // ->where('is_active', true) // Ensure the offer is active
        // ->where('start_time', '<=', now()) // Offer has started
        // ->where('end_time', '>=', now()) // Offer has not ended
        // ->latest() // Order by start time in descending order
        // ->first(); // Get the latest offer
        // Calculate the price after discount
        // $priceAfterDiscount = $this->price;
        // if ($activeOffer) {
        //     $discountAmount = ($this->price * $activeOffer->discount_percentage) / 100;
        //     $priceAfterDiscount = $this->price - $discountAmount;
        // }
             // Decode the photos JSON and get the first photo
             $photos = json_decode($this->productColors[0]->photos, true);
             $firstPhoto = $photos[0] ?? null;
        return [
            'id' => $this->id,
            'name' => $this->getTranslation('name', app()->getLocale()),
            'description' => $this->getTranslation('description', app()->getLocale()),
            'category_name' => $this->category->getTranslation('name', app()->getLocale()),
            'brand_name' =>$this->brand ? $this->brand->getTranslation('name', app()->getLocale()) : null,
            'rating' => $this->getAverageRatingAttribute(),
            // 'price' => $this->price,
            // 'discount' => $activeOffer ? $activeOffer->discount_percentage : null, // Show discount if available
            // 'price_after_discount' => $this->price_after_discount, // Show price after discount
            // 'offer_start' => $activeOffer->start_time ?? null, // Show price after discount
            // 'offer_end' => $activeOffer->end_time ?? null, // Show price after discount
            'firstPhoto' => $firstPhoto,
            // 'main_color_quantity' => $this->main_color_quantity,
            // 'main_photos' => $photos,
            // 'main_color' => $this->color->getTranslation('name', app()->getLocale()),
            // 'hex_code' => $this->color->hex_code,
            'color_photos' => productColorResource::collection($this->productColors), // Add this line
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
