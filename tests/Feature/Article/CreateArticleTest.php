<?php

namespace Tests\Feature\Article;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateArticleTest extends TestCase
{
    use RefreshDatabase;

    /**
    @test
     */
    public function can_create_articles()
    {
        $this->withExceptionHandling();

        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'Nuevo articulo',
                    'slug' => 'nuevo-articulo',
                    'content' => 'Nuevo contenido del articulo',
                    'category_id' => 1,
                    'user_id' => 1,
                ]
            ]
        ]);

        $response->assertCreated();

        $article = Article::first();

        $response->assertHeader(
            'Location',
            route('api.v1.articles.show', $article)
        );

        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
                'attributes' => [
                    'title' => 'Nuevo articulo',
                    'slug' => 'nuevo-articulo',
                    'content' => 'Nuevo contenido del articulo',
                    // 'category_id' => 1,
                    // 'user_id' => 1,
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

        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'slug' => 'nuevo-articulo',
                    'content' => 'Nuevo contenido del articulo',
                    'category_id' => 1,
                    'user_id' => 1,
                ]
            ]
        ]);

        $response->assertJsonS

        // $response->assertJsonValidationErrors('data.attributes.title');

    }

    /**
    @test
     */
    public function title_is_at_least_4_characters()
    {

        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'Nue',
                    'slug' => 'nuevo-articulo',
                    'content' => 'Nuevo contenido del articulo',
                    'category_id' => 1,
                    'user_id' => 1,
                ]
            ]
        ]);

        $response->assertJsonValidationErrors('data.attributes.title');

    }

        /**
    @test
     */
    public function slug_is_required()
    {

        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'Nuevo articulo',
                    'content' => 'Nuevo contenido del articulo',
                    'category_id' => 1,
                    'user_id' => 1,
                ]
            ]
        ]);

        $response->assertJsonValidationErrors('data.attributes.slug');

    }

            /**
    @test
     */
    public function content_is_required()
    {

        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'Nuevo articulo',
                    'slug' => 'nuevo-articulo',
                    'category_id' => 1,
                    'user_id' => 1,
                ]
            ]
        ]);

        $response->assertJsonValidationErrors('data.attributes.content');

    }


}
