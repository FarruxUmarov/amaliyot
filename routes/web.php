<?php

use App\Http\Controllers\Frontend\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin', [AdminController::class, 'index']);

Route::post('/submit', [AdminController::class, 'store']);
