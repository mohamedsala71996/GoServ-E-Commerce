<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Website\StoreProductReviewRequest;
use App\Http\Resources\ProductReviewResource;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function index()
    {
        $reviews = ProductReview::with(['user', 'product'])->where('status','pending')->get();
        return ProductReviewResource::collection($reviews);
    }

    /**
     * Store a newly created review in storage.
     */
    public function setStatus(Request $request,$id)
    {
        $request->validate([
           'status' => ['required', 'in:approved,rejected']
        ]);
        $review = ProductReview::findOrFail($id);
        $review->status =  $request->status;
        $review->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Review updated successfully.',
            'review' => $review
        ], 200);
    }


}
