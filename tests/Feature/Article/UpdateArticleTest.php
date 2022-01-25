<?php

namespace Tests\Feature\Article;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_update_articles()
    {

        // $this->withExceptionHandling();

        $article = Article::factory()->create();

        $response = $this->patchJson(route('api.v1.articles.update', $article), [
                'title' => 'Update articulo',
                'slug' => 'update-articulo',
                'content' => 'Update contenido del articulo',
                // 'category_id' => 1,
                // 'user_id' => 1,
        ])->assertOk();

        $response->assertHeader(
            'Location',
            route('api.v1.articles.show', $article)
        );

        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
                'attributes' => [
                    'title' => 'Update articulo',
                    'slug' => 'update-articulo',
                    'content' => 'Update contenido del articulo',
                ],
                'links' => [
                    'self' => route('api.v1.articles.show', $article)
                ]
            ]
        ]);
    }

    /**
    @test
     */
    public function title_is_required()
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
                'slug' => 'update-articulo',
                'content' => 'Update contenido del articulo',
        ])->assertJsonApiValidationErrors('title');

    }


    /**
    @test
     */
    public function content_is_required()
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Update articulo',
            'slug' => 'update-articulo',
        ])->assertJsonApiValidationErrors('content');

    }

        /**
    @test
     */
    public function slug_is_required()
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Update articulo',
            'content' => 'Update contenido del articulo',
        ])->assertJsonApiValidationErrors('slug');

    }

    /**
    @test
     */
    public function content_must_be_at_least_5_characters()
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Update articulo',
            'slug' => 'update-articulo',
            'content' => 'Nuev',
        ])->assertJsonApiValidationErrors('content');

    }

}
