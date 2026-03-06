<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_articles_with_pagination()
    {
        Sanctum::actingAs(User::factory()->create());

        Article::factory()->count(15)->create();

        $response = $this->getJson('api/articles');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data');
            // ->assertJsonStructure([
            //     'data' => [
            //         '*'=> [ 'id', 'title', 'content', 'created_at']
            //     ],
            //     'links',
            //     'meta'
            // ]);
    }
}
