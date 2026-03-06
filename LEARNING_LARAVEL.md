### Laravel Learning Guide for This Project

This project is your sandbox for learning Laravel by building a real JSON API with authentication. This guide walks through four topics you chose:

- API Resources
- Pagination
- Logout with Sanctum
- Testing with `php artisan test`

Each section explains the concept and ends with the **exact code currently in your project**, so you can refer back to it later.

---

## 1. API Resources

### 1.1 Concept

An **API Resource** in Laravel is a class that transforms your Eloquent models into a clean JSON structure for your API responses.

Without resources, you often return raw models:

```php
return response()->json($article);
```

This works, but:

- It exposes all columns by default.
- Any change to the database shape can accidentally change your API.
- You repeat the same transformations (date formatting, nested relations, etc.).

With a resource, you **centralize the JSON shape** in one place:

```php
return new ArticleResource($article);
```

Laravel then calls `toArray()` on the resource to decide exactly what fields to send.

### 1.2 Your `ArticleResource`

In this project you already have a resource that:

- Picks specific fields (`id`, `title`, `content`).
- Formats `created_at` as an ISO 8601 string (good for APIs).

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'content'       => $this->content,
            'created_at'    => $this->created_at->toIso8601String(),
        ];
    }
}
```

### 1.3 Using `ArticleResource` in `ArticleController`

- For **lists**, you use `ArticleResource::collection($articles)`.
- For a **single article**, you use `new ArticleResource($article)`.

Current controller code:

```php
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

    // DELETE /api/articles/{id}
    public function destroy($id) {
        $article = Article::find($id);

        if (! $article) {
            return $this->error('Article not found bro', 404);
        }

        $article->delete();
        return $this->deleted('Article deleted successfully bro');
    }
}
```

> Note: for a single article, you would typically use `new ArticleResource($article)` instead of `ArticleResource::collection($article)`, because `collection()` is meant for arrays/collections.

---

## 2. Pagination

### 2.1 Concept

**Pagination** prevents you from returning every row in the database at once. Instead of:

```php
$articles = Article::all();
```

you use:

```php
$articles = Article::paginate(10);
```

This:

- Adds an automatic `LIMIT/OFFSET` to your SQL.
- Reads `?page=1`, `?page=2` from the query string.
- Gives you metadata (total, current_page, last_page, etc.).

You can then return both the data and metadata to your frontend.

### 2.2 Your current pagination usage

Right now you use `paginate(10)` and wrap it directly in `ArticleResource::collection()`:

```php
public function index() {
    $articles = Article::paginate(10);
    return $this->success(
        ArticleResource::collection($articles),
        'Articles list'
    );
}
```

This works because `LengthAwarePaginator` is iterable and Laravel’s JSON resources know how to handle it.

### 2.3 Alternative: expose pagination meta explicitly

To practice a more explicit API design, you can (in the future) extract the items and meta separately:

```php
$paginated = Article::paginate(10);

return $this->success([
    'items' => ArticleResource::collection($paginated->items()),
    'meta' => [
        'current_page' => $paginated->currentPage(),
        'last_page'    => $paginated->lastPage(),
        'per_page'     => $paginated->perPage(),
        'total'        => $paginated->total(),
    ],
], 'Articles list');
```

This teaches you:

- How paginator methods work.
- How to design a stable API response format with `items` + `meta`.

---

## 3. Unified API Responses (`ApiResponse` trait)

### 3.1 Concept

Instead of writing `return response()->json([...], 200)` everywhere, you created an `ApiResponse` trait that:

- Standardizes the JSON shape.
- Provides helper methods like `success`, `created`, `updated`, `deleted`, `error`.

This is very similar to the “API responder” pattern you described:

- `this.api_response.success(data, message)` → 200
- `this.api_response.created(data, message)` → 201

### 3.2 Your `ApiResponse` implementation

```php
<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

trait ApiResponse {
    protected function success($data = null, string $message = "OK", int $status = 200): JsonResponse {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function created($data = null, string $message = "Created"): JsonResponse {
        return $this->success($data, $message, 201);
    }

    protected function updated($data = null, string $message = "Updated"): JsonResponse {
        return $this->success($data, $message, 200);
    }

    protected function deleted(string $message = "Deleted"): JsonResponse {
        return $this->success(null, $message, 200);
    }

    protected function error(string $message = 'Error', int $status = 400, $errors = null): JsonResponse {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}
```

You then **reuse** this in `ArticleController` via:

```php
use App\Support\ApiResponse;

class ArticleController extends Controller
{
    use ApiResponse;
    // ...
}
```

---

## 4. Logout (Sanctum)

### 4.1 Concept

With Laravel Sanctum:

- **Login**: `createToken('auth_token')->plainTextToken` stores a token in `personal_access_tokens`.
- **Every API request** with `Authorization: Bearer {token}` is matched to that record.
- **Logout** means: delete the token record so it can’t be used again.

Two common patterns:

1. Logout current device: `currentAccessToken()->delete()`
2. Logout everywhere: `tokens()->delete()`

### 4.2 Your current login implementation

Right now `Login` handles only login (no logout yet):

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Login extends Controller
{
    public function login(LoginRequest $request){

        // find user by email
        $user = User::where('email', $request->email)->first();

        // check if 
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'invalid credentials mo bows'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token'=> $token
        ], 200);
    }
}
```

Your routes currently define:

```php
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\Auth\Login;

Route::post('/register', [Register::class, 'register']);
Route::post('/login', [Login::class, 'login']);

// Register all CRUD API routes for ArticleController using Laravel's apiResource helper. 
// This creates index, store, show, update, and destroy endpoints automatically.

Route::middleware('auth:sanctum')->group(function(){
    
    Route::apiResource('articles', ArticleController::class);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

});
```

### 4.3 How you can add logout (concept)

Later, when you’re ready:

```php
public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();

    return $this->deleted('Logged out successfully');
}
```

and inside the `auth:sanctum` group:

```php
Route::post('/logout', [Login::class, 'logout']);
```

This would give you a complete login–logout–protected‑route flow to practice.

---

## 5. Testing (`php artisan test`)

### 5.1 Concept

Laravel’s test system lets you:

- Call your routes (`/api/register`, `/api/login`, `/api/articles`) in a fake environment.
- Assert status codes, JSON structures, and database state.

You haven’t added test files yet, but here’s the pattern you’ll use:

```php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['user', 'token']);
    }
}
```

Run tests with:

```bash
php artisan test
```

This is a perfect next step once you’re comfortable with your controllers and resources.

---

### Summary of What You’ve Built

- **API Resources**: `ArticleResource` to control JSON output.
- **Unified Responses**: `ApiResponse` trait providing `success`, `created`, `updated`, `deleted`, `error`.
- **Articles API**: `ArticleController` using Form Requests, Resources, and your response helpers.
- **Authentication**: Register + Login with Sanctum tokens; routes protected by `auth:sanctum`.
- **Pagination**: Using `paginate(10)` for article listing.

Each section above ends with the **current code from your project**, so you can copy it back if something breaks while you experiment.

