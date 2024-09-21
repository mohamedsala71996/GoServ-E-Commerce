<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    public function index()
    {
        $articles = Article::get();

        return ArticleResource::collection($articles);
    }

    public function show($id)
    {
        $article = Article::findOrFail($id);
        return new ArticleResource($article);
    }
}
