<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Http\Response;
use App\Support\ApiResponse;
use App\Http\Resources\ArticleResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ArticleController extends Controller
{
    use ApiResponse;

    // GET /api/articles
    public function index() {
        // Only get articles belonging to the authenticated user

        $user = Auth::user();

        $articles = $user->articles()->paginate(10);

        return $this->success(
            ArticleResource::collection($articles),
            'Articles list'
        );
    }
    // POST /api/articles
    public function store(StoreArticleRequest $request) {
        
        $user = Auth::user();

        $article = $user->articles()->create($request->validated());

        return $this->created(
            new ArticleResource($article),
            'Article created'
        );
    }
    // GET /api/articles/{id}
    public function show(Request $request, Article $article) {
        
        $user = Auth::user();

        if ($article->user_id !== $user->id) {
            return $this->error('Unauthorized to view this article', 403);
        }

        return $this->success(
            new ArticleResource($article),
            'Article detail'
        );
    }

    // PUT /api/articles/{id}
    public function update(UpdateArticleRequest $request, Article $article) {
        
        $user = Auth::user();

        // Check if the article belongs to the user
        if ($article->user_id !== $user->id) {
            return $this->error('Unauthorized to update this article', 403);
        }

        $article->update($request->validated());

        return $this->updated(
            new ArticleResource($article),
            'Article updated'
        );
    }
    // DELETE /api/articles/{id}
    public function destroy(Request $request, $id) {
        $article = $request->user()->articles()->find($id);

        if (!$article) {
            return $this->error('Article not found or unauthorized', 404);
        }

        $article->delete();
        return $this->deleted('Article deleted successfully bro');
    }

}
