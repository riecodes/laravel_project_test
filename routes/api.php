<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\Auth\Login;

Route::post('/register', [Register::class, 'register']);
Route::post('/login', [Login::class, 'login']);

// Register all CRUD API routes for ArticleController using Laravel's apiResource helper. 
// This creates index, store, show, update, and destroy endpoints automatically.

Route::middleware('auth:sanctum')->group(function(){
    
    Route::apiResource('articles', ArticleController::class);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

});
