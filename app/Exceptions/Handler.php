<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function invalidJson($request, ValidationException $exception)
    {
        $errors = [];
        $title = $exception->getMessage();

        // foreach ($exception->errors() as $field => $message) {

        //     $pointer = '/' . str_replace('.', '/', $field);

        //     $errors[] = [
        //         'title' => $title,
        //         'detail' => $message[0],
        //         'source' => [
        //             'pointer' => $pointer
        //         ]
        //     ];
        // }

       $errors = collect($exception->errors())
            ->map(function($message, $field) use ($title) {
                return [
                    'title' => $title,
                    'detail' => $message[0],
                    'source' => [
                        'pointer' => '/' . str_replace('.', '/', $field)
                    ]
                ];
            })->values();

        // dd($errors);

        return response()->json([
            'errors' => $errors
        ], 422, [
            'content-type' => 'application/vnd.api+json'
        ]);

    }
}
