# Laravel Concepts Guide

This guide provides explanations and code snippets for key Laravel concepts: API Resources, Pagination, Logout (Sanctum), and Testing.

---

## 1. Eloquent API Resources
Eloquent Resources allow you to expressively and easily transform your models and model collections into JSON. This is crucial for maintaining a consistent API response structure.

### Example: `ArticleResource`
Located at `app/Http/Resources/ArticleResource.php`

```php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'content'    => $this->content,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
```

### Usage in Controller:
```php
public function index() {
    $articles = Article::all();
    return ArticleResource::collection($articles);
}
```

---

## 2. Pagination
Pagination is essential for handling large datasets by splitting them into smaller, manageable chunks (pages).

### Implementation:
In your controller, use the `paginate()` method instead of `get()` or `all()`.

```php
// app/Http/Controllers/ArticleController.php

public function index() {
    // Paginate with 10 items per page
    $articles = Article::paginate(10);
    
    return $this->success(
        ArticleResource::collection($articles),
        'Articles list'
    );
}
```

Laravel automatically adds pagination metadata (links, total, per_page, etc.) to the JSON response.

---

## 3. Logout (Sanctum)
When using Laravel Sanctum for API authentication, logout involves revoking the current access token.

### Example: `Logout` Controller
Located at `app/Http/Controllers/Auth/Logout.php`

```php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Logout extends Controller
{
    public function logout(Request $request) {
        // Delete the current access token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }
}
```

---

## 4. Testing in Laravel
Laravel provides a robust testing suite powered by PHPUnit. Tests are divided into **Feature** tests (for HTTP requests and overall behavior) and **Unit** tests (for isolated logic).

### Example Feature Test:
Located at `tests/Feature/ArticleTest.php` (Snippet for illustration)

```php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_articles()
    {
        $user = User::factory()->create();
        Article::factory()->count(3)->create();

        $response = $this->actingAs($user, 'sanctum')
                         ->getJson('/api/articles');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }
}
```

### Running Tests:
Run all tests using the Artisan command:
```bash
php artisan test
```
