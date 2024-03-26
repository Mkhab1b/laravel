<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::post('/categories', [CategoryController::class, 'create']);  // Create
Route::put('/categories/{category}', [CategoryController::class, 'update']);  // Update
Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);  // Destroy
Route::get('/categories/{category}', [CategoryController::class, 'show']);  // Get one category
Route::get('/categories', [CategoryController::class, 'paginate']);  // Get categories
