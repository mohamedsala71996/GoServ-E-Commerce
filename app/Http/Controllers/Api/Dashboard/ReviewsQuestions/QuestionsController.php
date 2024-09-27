<?php

namespace App\Http\Controllers\Api\Dashboard\ReviewsQuestions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Website\StoreProductReviewRequest;
use App\Http\Resources\ProductReviewResource;
use App\Http\Resources\QuestionResource;
use App\Models\ProductReview;
use App\Models\Question;
use App\Models\QuestionReply;
use App\Models\ReviewReply;
use App\Notifications\QestionReplyNotification;
use App\Notifications\ReviewReplyNotification;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    public function index()
    {
        // $questions = ProductReview::with(['user', 'product','replies'])->where('status','pending')->get();
        $questions = Question::with(['user','replies'])->orderBy('topic')->get();
        return QuestionResource::collection($questions);
    }

    /**
     * Store a newly created question in storage.
     */
    public function setStatus(Request $request,$id)
    {
        $request->validate([
           'status' => ['required', 'in:approved,rejected']
        ]);
        $question = Question::findOrFail($id);
        $question->status =  $request->status;
        $question->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Question updated successfully.',
            'question' => $question
        ], 200);
    }
    public function storeReply(Request $request, $questionId)
    {
        $request->validate([
            'reply' => 'required|string|max:2000',
        ]);

        // Check if the question exists
        $question = Question::findOrFail($questionId);

        // Create the reply
        $reply = QuestionReply::create([
            'question_id' => $question->id,
            'admin_id' => $request->user()->id, // Assuming authenticated user
            'reply' => $request->reply,
        ]);

        // Notify the user who wrote the question (only using database notifications)
        $question->user->notify(new QestionReplyNotification($reply, $question));

        return response()->json([
            'status' => 'success',
            'message' => 'Reply added successfully.',
            'reply' => $reply
        ], 201);
    }
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->replies()->delete();
        $question->delete();


        return response()->json([
            'status' => 'success',
            'message' => 'question deleted successfully.'
        ], 200);
    }

}
