<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RecipeController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/index', [HomeController::class, 'index']);
Route::get('/recipes', [RecipeController::class, 'all']);
Route::get('/recipe/{id}', [RecipeController::class, 'getById']);
Route::post('/recipe/add', [RecipeController::class, 'store']);
Route::put('/recipe/modify/{id}', [RecipeController::class, 'update']);
Route::delete('/recipe/delete/{id}', [RecipeController::class, 'delete']);
