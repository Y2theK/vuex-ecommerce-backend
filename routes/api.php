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
Route::group([], function () {
    Route::get('/products/category/{category}', [ProductController::class,'getProductsByCategory']);
    Route::get('/products/categories', [CategoryController::class,'getOnlyCategoriesName']); //get all categories name
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('/products', ProductController::class);
    });
    Route::get('/products', [ProductController::class,'index']);
    Route::get('/products/{product}', [ProductController::class,'show']);
});
//categories routes
Route::group([], function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('/categories', CategoryController::class);
    });
    Route::get('/categories', [CategoryController::class,'index']);
});

//carts routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/carts/user/{userId}', [CartController::class,'getCartsByUser']);
    Route::apiResource('/carts', CartController::class);
});
//users routes
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/users', UserController::class);
});

require('auth.php');
