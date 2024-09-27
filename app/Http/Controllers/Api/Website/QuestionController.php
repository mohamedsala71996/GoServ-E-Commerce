<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Website\StoreQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{


    public function getTermsQuestions()
    {
        $questions = Question::with(['user','replies'])->where('status','approved')->where('topic','Terms and Conditions')->get();
        return QuestionResource::collection($questions);
    }

    public function getPrivacyPolicy()
    {
        $questions = Question::with(['user','replies'])->where('status','approved')->where('topic','Privacy Policy')->get();
        return QuestionResource::collection($questions);
    }
    public function getReturnAndExchangePolicy()
    {
        $questions = Question::with(['user','replies'])->where('status','approved')->where('topic','Return and Exchange Policy')->get();
        return QuestionResource::collection($questions);
    }

    public function store(StoreQuestionRequest $request)
    {
        $question = Question::create([
            'user_id' => $request->user()->id, // Assuming the user is authenticated
            'question' => $request->question,
            'status' => 'pending', // Default status
            'topic' => $request->topic,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Question sent successfully.',
            'question' => $question,
        ], 201);
    }
}
