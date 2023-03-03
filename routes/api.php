<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/products/categories', [CategoryController::class,'index']); //get all categories
Route::get('/products/category/{category}', [ProductController::class,'getProductsByCategory']);
Route::apiResource('products', ProductController::class);

Route::get('carts/user/{userId}', [CartController::class,'getCartsByUser']);
Route::apiResource('carts', CartController::class);

Route::apiResource('categories', CategoryController::class);

Route::apiResource('users', UserController::class);
