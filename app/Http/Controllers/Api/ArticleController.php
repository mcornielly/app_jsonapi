<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        // dd($request->input('data.attributes'));
        // dd($request->all());
        // dd($request->input('data.attributes.title'));

        $request->validate([
            'data.attributes.title' => ['required'],
            'data.attributes.slug' => ['required'],
            'data.attributes.content' => ['required', 'min:5'],
        ]);

        $article = Article::create([
            'title' => $request->input('data.attributes.title'),
            'slug' => $request->input('data.attributes.slug'),
            'content' => $request->input('data.attributes.content'),
        ]);


        return ArticleResource::make($article);
    }

}
