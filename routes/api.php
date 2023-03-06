<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\AuthController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//products routes
Route::prefix('/products')->group(function () {
    Route::get('/', [ProductController::class,'index']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/categories', [CategoryController::class,'getOnlyCategoriesName']); //get all categories name
        Route::get('/category/{category}', [ProductController::class,'getProductsByCategory']);
        Route::apiResource('/', ProductController::class)->except('index');
    });
});
//categories routes
Route::prefix('/categories')->group(function () {
    Route::get('/', [CategoryController::class,'index']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('/', CategoryController::class)->except('index');
    });
});

// //carts routes
Route::prefix('/carts')->middleware('auth:sactum')->group(function () {
    Route::get('/user/{userId}', [CartController::class,'getCartsByUser']);
    Route::apiResource('/', CartController::class);
});
// //users routes
Route::prefix('/users')->middleware('auth:sactum')->group(function () {
    Route::apiResource('/', UserController::class);
});

require('auth.php');
