<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $article variable transfers the data of the Article model and gets all data
        $articles = Article::all();

        // returning the response as a json file for the $article variable, with an http status 200 that means ok
        return response()->json($articles, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        // calling StoreArticleRequest to ensure if the data (title and content) is well validated
        $data = $request->validated();

        // Article::create a method to create data as well as uses the $data from the $fillable in Models/Article.php
        $article = Article::create($data);

        // returns the response as a json file for the $article variable, with an http status 201 that means created
        return response()->json($article, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // changed $data to $article for consistency
        $article = Article::select('id')->where('id', $id)->first();
        
                // returns the response as a json file for the $article variable, with an http status 200, updated
        return response()->json($article, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
