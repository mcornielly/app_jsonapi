<?php

namespace Tests\Feature\Article;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\MakesJsonApiRequests;
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
                    'title' => 'nuevo-articulo',
                    'slug' => 'nuevo-articulo',
                    'content' => 'Nuevo contenido del articulo',
                ]
            ]
        ]);


        $response->assertJsonApiValidationErrors('title');

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
                ]
            ]
        ]);

        $response->assertJsonApiValidationErrors('content');

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
                ]
            ]
        ]);

        $response->assertJsonApiValidationErrors('slug');

    }

    /**
    @test
     */
    public function content_must_be_at_least_5_characters()
    {

        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'Nuevo articulo',
                    'slug' => 'nuevo-articulo',
                    'content' => 'Nuev',
                ]
            ]
        ]);

        $response->assertJsonApiValidationErrors('content');

    }

}
