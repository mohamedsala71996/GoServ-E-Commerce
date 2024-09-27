<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Website\StoreProductReviewRequest;
use App\Http\Resources\ProductReviewResource;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function index()
    {
        $reviews = ProductReview::with(['user', 'product','replies'])->where('status','approved')->get();
        return ProductReviewResource::collection($reviews);
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(StoreProductReviewRequest $request)
    {
        $validatedData = $request->validated();
        $review = ProductReview::create($validatedData);
        return new ProductReviewResource($review);
    }

    public function getReviewsByProduct($productId)
    {
        $reviews = ProductReview::with(['user', 'product','replies'])
            ->where('product_id', $productId)
            ->where('status', 'approved')
            ->get();
        return ProductReviewResource::collection($reviews);
    }

    /**
     * Display the specified review.
     */
    // public function show($id)
    // {
    //     $review = ProductReview::with(['user', 'product'])->findOrFail($id);
    //     return new ProductReviewResource($review);
    // }

    /**
     * Update the specified review in storage.
     */
    // public function update(StoreProductReviewRequest $request, $id)
    // {
    //     $review = ProductReview::findOrFail($id);
    //     $validatedData = $request->validated();
    //     $review->update($validatedData);
    //     return new ProductReviewResource($review);
    // }

    // /**
    //  * Remove the specified review from storage.
    //  */
    // public function destroy($id)
    // {
    //     $review = ProductReview::findOrFail($id);
    //     $review->delete();
    //     return response()->json(['message' => 'Review deleted successfully'], 200);
    // }

}
