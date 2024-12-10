<?php

use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\ProductController;
use Illuminate\Support\Facades\Route;

Route::controller(CategoryController::class)->prefix('category')->group(function () {
    Route::get('/{id?}', [CategoryController::class, 'getCategories']);
    Route::post('/', [CategoryController::class, 'createCategory']);
    Route::patch('/{id}', [CategoryController::class, 'updateCategory']);
    Route::delete('{id}', [CategoryController::class, 'deleteCategory']);
});

Route::controller(ProductController::class)->prefix('products')->group(function () {
    Route::get('/{id?}', [ProductController::class, 'getProducts']);
    Route::post('/', [ProductController::class, 'createProduct']);
    Route::patch('/{id}', [ProductController::class, 'updateProduct']);
    Route::delete('{id}', [ProductController::class, 'deleteProduct']);
});

