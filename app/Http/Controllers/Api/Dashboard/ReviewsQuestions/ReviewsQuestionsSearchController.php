<?php

namespace App\Http\Controllers\Api\Dashboard\ReviewsQuestions;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductReviewResource;
use App\Http\Resources\QuestionResource;
use App\Models\ProductReview;
use App\Models\Question;
use Illuminate\Http\Request;

class ReviewsQuestionsSearchController extends Controller
{

    public function reviewSearch(Request $request)
    {
        $query = ProductReview::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by rating (exact match or range)
        if ($request->has('rating')) {
            $query->where('rating', $request->rating); // for exact rating
            // For range:
            // $query->whereBetween('rating', [$request->min_rating, $request->max_rating]);
        }
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by presence of replies
        if ($request->has('has_replies')) {
            if ($request->has_replies == true) {
                $query->has('replies');
            } elseif ($request->has_replies == false) {
                $query->doesntHave('replies');
            }
        }

        // Filter by date range
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        // Get the filtered results
        $reviews = $query->with(['user', 'product', 'replies'])->get();

        return ProductReviewResource::collection($reviews);
    }
    public function questionSearch(Request $request)
    {
        $query = Question::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('topic')) {
            $query->where('topic', $request->topic);
        }


        // Filter by presence of replies
        if ($request->has('has_replies')) {
            if ($request->has_replies == true) {
                $query->has('replies');
            } elseif ($request->has_replies == false) {
                $query->doesntHave('replies');
            }
        }

        // Filter by date range
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        // Get the filtered results
        $reviews = $query->with(['user', 'replies'])->get();

        return QuestionResource::collection($reviews);
    }


    public function search(Request $request)
    {
        // Search for ProductReviews
        $productReviewQuery = ProductReview::query();

        // Apply filters for ProductReview
        if ($request->has('status')) {
            $productReviewQuery->where('status', $request->status);
        }

        if ($request->has('rating')) {
            $productReviewQuery->where('rating', $request->rating);
        }

        if ($request->has('product_id')) {
            $productReviewQuery->where('product_id', $request->product_id);
        }

        if ($request->has('has_replies')) {
            if ($request->has_replies == true) {
                $productReviewQuery->has('replies');
            } else {
                $productReviewQuery->doesntHave('replies');
            }
        }

        if ($request->has('from_date') && $request->has('to_date')) {
            $productReviewQuery->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        // Get ProductReview results
        $productReviews = $productReviewQuery->with(['user', 'product', 'replies'])->get();
        $productReviewResults = ProductReviewResource::collection($productReviews);

        // Search for Questions
        $questionQuery = Question::query();

        // Apply filters for Questions
        if ($request->has('status')) {
            $questionQuery->where('status', $request->status);
        }

        if ($request->has('topic')) {
            $questionQuery->where('topic', $request->topic);
        }

        if ($request->has('has_replies')) {
            if ($request->has_replies == true) {
                $questionQuery->has('replies');
            } else {
                $questionQuery->doesntHave('replies');
            }
        }

        if ($request->has('from_date') && $request->has('to_date')) {
            $questionQuery->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        // Get Question results
        $questions = $questionQuery->with(['user', 'replies'])->get();
        $questionResults = QuestionResource::collection($questions);

        // Combine both results into a single response
        return response()->json([
            'product_reviews' => $productReviewResults,
            'questions' => $questionResults,
        ]);
    }
}
