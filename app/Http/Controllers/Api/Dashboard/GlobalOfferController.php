<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\GlobalOfferRequest;
use App\Models\GlobalOffer;
use App\Models\Offer;
use App\Models\Product;
use App\Models\ProductColorSize;
use Illuminate\Http\Request;

class GlobalOfferController extends Controller
{
    public function index()
    {
        $offers = GlobalOffer::all();
        return response()->json($offers);
    }

    public function store(GlobalOfferRequest $request)
    {
        $data = $request->validated();
        $offer = GlobalOffer::create($data);
        $products = ProductColorSize::all();
        foreach ($products as $product) {
            Offer::create([
                'product_color_size_id' => $product->id,
                'discount_percentage' => $request->discount_percentage,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time, // Offer valid for 30 days
                'is_active' => $request->is_active ?? 1,
                'global_offer_id' => $offer->id,
            ]);

        }
        return response()->json($offer, 201);
    }



    public function update(GlobalOfferRequest $request, $id)
    {
        // Find the GlobalOffer by ID
        $globalOffer = GlobalOffer::findOrFail($id);

        // Validate and get request data
        $data = $request->validated();

        // Update the GlobalOffer details
        $globalOffer->update([
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'is_active' => $data['is_active'],
            'discount_percentage' => $data['discount_percentage'],
        ]);

        // Update related Offers for all products
        $offers = Offer::where('global_offer_id', $globalOffer->id)->get();
        foreach ($offers as $offer) {
            $offer->update([
                'discount_percentage' => $data['discount_percentage'],
                'start_time' => $data['start_time'], // Assuming you want to update start_time to match global offer
                'end_time' => $data['end_time'], // Assuming you want to update end_time to match global offer
                'is_active' => $data['is_active'],
            ]);
        }

        return response()->json($globalOffer, 200);
    }

    public function destroy($id)
    {
        $offer = GlobalOffer::findOrFail($id);
        $offer->offers()->delete(); // Delete related offers when global offer is deleted
        $offer->delete();
        return response()->json(['message' => 'Offer deleted successfully'], 200);
    }
}
