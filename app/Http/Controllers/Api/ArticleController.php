<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveArticleRequest;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;

class ArticleController extends Controller
{
    public function index(): ArticleCollection
    {
        $articles = Article::all();

        return ArticleCollection::make($articles);
    }

    public function show(Article $article): ArticleResource
    {
        return ArticleResource::make($article);
    }

    public function store(SaveArticleRequest $request)
    {
        // dd($request->input('data.attributes'));
        // dd($request->all());
        // dd($request->input('data.attributes.title'));

        // $article = Article::create([
        //     'title' => $request->input('data.attributes.title'),
        //     'slug' => $request->input('data.attributes.slug'),
        //     'content' => $request->input('data.attributes.content'),
        // ]);

        $article = Article::create($request->validated());

        return ArticleResource::make($article);
    }

    public function update(Article $article, SaveArticleRequest $request)
    {

        $article->update($request->validated());

        return ArticleResource::make($article);
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return response()->noContent();
    }

}
