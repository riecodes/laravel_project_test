<?php

namespace Tests\Unit;

use App\Models\Article;
use Tests\TestCase;


class ArticleUnitTest extends TestCase
{
    public function test_articles_have_fillable_attributes() {
        $article = new Article();
        $this->assertEquals(['title', 'content'], $article->getFillable());
    }
}
