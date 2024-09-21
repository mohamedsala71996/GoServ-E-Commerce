<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\OfferRequest;
use App\Http\Requests\Api\Dashboard\StoreOfferRequest;
use App\Http\Resources\OfferResource;
use App\Models\Admin;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    // public function __construct()
    // {
    //     // Apply middleware to the constructor
    //     $this->middleware(function ($request, $next) {
    //         if ( !auth()->user() instanceof Admin) {
    //             return response()->json(['message' => 'You are not authenticated as an admin.'], 401);
    //         }
    //         return $next($request);
    //     });
    // }
    public function index()
    {
        $offers = Offer::all(); // Adjust if you want pagination or filtering

        return OfferResource::collection($offers); // Use a resource if needed
    }

    public function getOffersByProduct(Request $request,$product_id)
{


    $product = Product::with('productColors.productColorSizes.offers')->find($product_id);

    // Collect all offers related to the product's color sizes
    $offers = $product->productColors->flatMap(function($productColor) {
        return $productColor->productColorSizes->flatMap(function($productColorSize) {
            return $productColorSize->offers;
        });
    });

    return OfferResource::collection($offers);
}
    public function store(OfferRequest $request)
    {
        $data = $request->validated();

        $offer = Offer::create($data);

        return response()->json($offer, 201);
    }
    public function storeByProduct(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time',
            'is_active' => 'nullable|boolean',
        ]);

        $product = Product::find($data['product_id']);
        $productColors = $product->productColors;

        $offers = []; // Collect all created offers

        foreach ($productColors as $productColor) {
            foreach ($productColor->productColorSizes as $productColorSize) {
                $offer = Offer::create([
                    'product_color_size_id' => $productColorSize->id,
                    'discount_percentage' => $data['discount_percentage'],
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'is_active' => $data['is_active'] ?? 1,
                ]);

                $offers[] = $offer; // Add the created offer to the collection
            }
        }

        return response()->json($offers, 201); // Return all offers as a JSON response
    }

    public function update(OfferRequest $request, $id)
    {
        $offer = Offer::findOrFail($id);
        $data = $request->validated();

        $offer->update($data);

        return response()->json($offer);
    }

    public function updateByProduct(Request $request)
{
    $data = $request->validate([
        'product_id' => 'required|exists:products,id',
        'discount_percentage' => 'required|numeric|min:0|max:100',
        'start_time' => 'required|date',
        'end_time' => 'nullable|date|after:start_time',
        'is_active' => 'nullable|boolean',
    ]);

    $product = Product::find($data['product_id']);
    $productColors = $product->productColors;

    $updatedOffers = []; // Collect all updated or created offers

    foreach ($productColors as $productColor) {
        foreach ($productColor->productColorSizes as $productColorSize) {
            // Check if an offer already exists for this productColorSize
            $offer = Offer::where('product_color_size_id', $productColorSize->id)->first();

            if ($offer) {
                // If the offer exists, update it
                $offer->update([
                    'discount_percentage' => $data['discount_percentage'],
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'is_active' => $data['is_active'] ?? 1,
                ]);
            } else {
                // If no offer exists, create a new one
                $offer = Offer::create([
                    'product_color_size_id' => $productColorSize->id,
                    'discount_percentage' => $data['discount_percentage'],
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'is_active' => $data['is_active'] ?? 1,
                ]);
            }

            // Add the updated or created offer to the collection
            $updatedOffers[] = $offer;
        }
    }

    return response()->json($updatedOffers, 200); // Return updated offers as a JSON response
}

    /**
     * Remove the specified offer from storage.
     */
    public function destroy($id)
    {
        $offer = Offer::findOrFail($id);

        $offer->delete();

        return response()->json(['message' => 'Offer deleted successfully'], 200);
    }
    public function deleteByProduct(Request $request,$product_id)
    {
        $product = Product::find($product_id);
        $productColors = $product->productColors;


        foreach ($productColors as $productColor) {
            foreach ($productColor->productColorSizes as $productColorSize) {
                // Find and delete the offers for each productColorSize
                $offers = Offer::where('product_color_size_id', $productColorSize->id)->get();

                foreach ($offers as $offer) {
                    $offer->delete(); // Delete the offer
                }
            }
        }

        return response()->json([
            'message' => 'Offers deleted successfully',
        ], 200); // Return success message and deleted offer IDs
    }


//     public function applyOfferToAllProducts(Request $request)
// {
//     // Fetch all products
//     $products = Product::all();
//     // Loop through each product
//     foreach ($products as $product) {
//         Offer::create([
//             'product_id' => $product->id,
//             'discount_percentage' => $request->discount_percentage,
//             'start_time' => $request->start_time,
//             'end_time' => $request->end_time, // Offer valid for 30 days
//             'is_active' => $request->is_active,
//         ]);

//     }
//     return response()->json(['message' => 'Offer applied to all products successfully.']);
// }
}

