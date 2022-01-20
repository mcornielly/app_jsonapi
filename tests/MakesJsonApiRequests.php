<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Testing\TestResponse;
use PhpParser\Node\Stmt\TryCatch;
use PHPUnit\Framework\Assert as PHPUnit;
use Illuminate\Support\Str;


trait MakesJsonApiRequests
{
    protected function setUp(): void
    {
        parent::setUp();

        TestResponse::macro('assertJsonApiValidationErrors',

            $this->assertJsonApiValidationErrors()
        );
    }

    public function json($method, $uri, array $data = [], array $headers = [])
    {
        $headers['accept'] = 'application/vnd.api+json';

        $formattedData['data'] = $data;

        return parent::json($method, $uri, $formattedData, $headers);
    }

    public function postJson($uri, array $data = [], array $headers = [])
    {
        $headers['content-type'] = 'application/vnd.api+json';

        return parent::postJson($uri, $data, $headers);
    }

    public function patchJson($uri, array $data = [], array $headers = [])
    {
        $headers['content-type'] = 'application/vnd.api+json';

        return parent::patchJson($uri, $data, $headers);
    }

    public function assertJsonApiValidationErrors()
    {
        return function($attribute) {
            /** @var TestResponse $this */

            $pointer = Str::of($attribute)->startsWith('data') ? "/" . str_replace('.','/', $attribute)

            :  "/data/attributes/{$attribute}";

            try {

                $this->assertJsonFragment([
                    'source' => ['pointer' => $pointer]
                ]);
            } catch (\PHPUnit\Framework\ExpectationFailedException $e) {
                PHPUnit::fail("Failed to find a JSON:API validation error for key: '{$attribute}' \n"
                    .PHP_EOL.PHP_EOL.
                    $e->getMessage()
                );
            }

            try {

                $this->assertJsonStructure([
                    'errors' => [
                        ['title', 'detail', 'source' => ['pointer']]
                    ]
                ]);
            } catch (\PHPUnit\Framework\ExpectationFailedException $e) {
                PHPUnit::fail("Failed to find a valid JSON:API error response"
                    .PHP_EOL.PHP_EOL.
                    $e->getMessage()
                );
            }

            $this->assertHeader(
                'content-type', 'application/vnd.api+json'
            );

            $this->assertStatus(422);
        };
    }
}
