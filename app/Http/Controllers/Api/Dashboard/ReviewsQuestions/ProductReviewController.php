<?php

namespace App\Http\Controllers\Api\Dashboard\ReviewsQuestions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Website\StoreProductReviewRequest;
use App\Http\Resources\ProductReviewResource;
use App\Models\ProductReview;
use App\Models\ReviewReply;
use App\Notifications\ReviewReplyNotification;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function index()
    {
        // $reviews = ProductReview::with(['user', 'product','replies'])->where('status','pending')->get();
        $reviews = ProductReview::with(['user', 'product','replies'])->get();
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
    public function storeReply(Request $request, $reviewId)
    {
        $request->validate([
            'reply' => 'required|string|max:2000',
        ]);

        // Check if the review exists
        $review = ProductReview::findOrFail($reviewId);

        // Create the reply
        $reply = ReviewReply::create([
            'review_id' => $review->id,
            'admin_id' => $request->user()->id, // Assuming authenticated user
            'reply' => $request->reply,
        ]);

        // Notify the user who wrote the review (only using database notifications)
        $review->user->notify(new ReviewReplyNotification($reply, $review));

        return response()->json([
            'status' => 'success',
            'message' => 'Reply added successfully.',
            'reply' => $reply
        ], 201);
    }
    public function destroy($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->replies()->delete();
        $review->delete();


        return response()->json([
            'status' => 'success',
            'message' => 'Review deleted successfully.'
        ], 200);
    }

}
