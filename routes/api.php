<?php

use App\Http\Controllers\Api\ArticleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('articles/{article}', [ArticleController::class, 'show'])->name('api.v1.articles.show');
Route::patch('articles/{article}', [ArticleController::class, 'update'])->name('api.v1.articles.update');
Route::get('articles', [ArticleController::class, 'index'])->name('api.v1.articles.index');
Route::post('articles', [ArticleController::class, 'store'])->name('api.v1.articles.store');
