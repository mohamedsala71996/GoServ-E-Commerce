<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\StoreArticleRequest;
use App\Http\Requests\Api\Dashboard\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Admin;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
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
        $articles = Article::all();
        return ArticleResource::collection($articles);
    }

    /**
     * Store a newly created article in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        $validatedData = $request->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = $photo->store('articles', 'public');
            $validatedData['photo'] = $photoPath;
        }

        $article = Article::create($validatedData);
        return response()->json($article, 201);
    }

    /**
     * Display the specified article.
     */
    // public function show(Article $article)
    // {
    //     return new ArticleResource($article);
    // }

    // /**
    //  * Update the specified article in storage.
    //  */
    public function updateArticle(UpdateArticleRequest $request)
    {
        $article=Article::find($request->article_id);
        $validatedData = $request->validated();

        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            // Delete the old photo if it exists
            if ($article->photo ) {
                Storage::disk('public')->delete($article->photo);
            }

            $photo = $request->file('photo');
            $photoPath = $photo->store('articles', 'public');
            $validatedData['photo'] = $photoPath;
        }

        $article->update($validatedData);
        return response()->json($article);
    }

    // /**
    //  * Remove the specified article from storage.
    //  */
    public function destroy(Article $article)
    {
        // Delete the photo if it exists
        if ($article->photo) {
            Storage::disk('public')->delete($article->photo);
        }

        $article->delete();
        return response()->json(['message' => 'Article deleted successfully'], 200);
    }

}
