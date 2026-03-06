<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Http\Response;
use App\Support\ApiResponse;
use App\Http\Resources\ArticleResource;


class ArticleController extends Controller
{
    use ApiResponse;

    // GET /api/articles
    public function index() {
        $articles = Article::paginate(10);
        return $this->success(
            ArticleResource::collection($articles),
            'Articles list'
        );
    }
    // POST /api/articles
    public function store(StoreArticleRequest $request) {
        $article = Article::create($request->validated());

        return $this->created(
            new ArticleResource($article),
            'Article created'
        );
    }
    // GET /api/articles/{id}
    public function show(Article $article) {
        return $this->success(
            ArticleResource::collection($article),
            'Article detail'
        );
    }

    // PUT /api/articles/{id}
    public function update(UpdateArticleRequest $request, Article $article) {
        $article->update($request->validated());

        return $this->updated(
            new ArticleResource($article),
            'Article updated'
        );
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
