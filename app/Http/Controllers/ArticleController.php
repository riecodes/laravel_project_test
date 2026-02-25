<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Http\Response;
use App\Support\ApiResponse;


class ArticleController extends Controller
{
    use ApiResponse;

    // GET /api/articles
    public function index() {
        $articles = Article::all();
        return $this->success($articles, 'Articles list');
    }
    // POST /api/articles
    public function store(StoreArticleRequest $request) {
        $article = Article::create($request->validated());
        return $this->created($article, 'Articles created');
    }
    // GET /api/articles/{id}
    public function show(Article $article) {
        return $this->success($article, 'Article detail');
    }

    // PUT /api/articles/{id}
    public function update(UpdateArticleRequest $request, $id) {
    $articles = Article::find($id);

    if (!$articles) {
        return response()->json(['message' => 'Article not found bro'], 404);
    }
    
    $articles->update($request->validated());
    return response()->json($articles, 200);
    }
    //  
    public function destroy($id) {
        $article = Article::find($id);

        if (!$article) {
            return $this->error( 'Article not found bro', 404);
        }

        $article->delete();
        return $this->deleted('Article deleted successfully bro');
    }

}
