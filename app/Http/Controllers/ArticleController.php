<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Http\Response;


class ArticleController extends Controller
{
    // GET /api/articles
    public function index() {
        $articles = Article::all();
        return response()->json($articles);
    }
    // POST /api/articles
    public function store(StoreArticleRequest $request) {
        $articles = Article::create($request->validated());
        return response()->json($articles, 201);
    }
    //GET /api/articles/{id}
    public function show($id) {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found bro'], 404);
        }

        return response()->json($article);
    }

    //
        public function update(UpdateArticleRequest $request, $id) {
        $articles = Article::find($id);

        if (!$articles) {
            return response()->json(['message' => 'Article not found bro'], 404);
        }
        
        $articles->update($request->validated());
        return response()->json($articles, 201);
    }

    public function destroy($id) {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found bro'], 404);
        }

        $article->delete();
        return response()->json(['message' => 'Article deleted successfully bro']);
    }
}
