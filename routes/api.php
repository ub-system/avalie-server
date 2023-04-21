<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::Post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/validate-token', [AuthController::class, 'validateToken']);
    Route::delete('/delete', [AuthController::class, 'destroy']);

    Route::post('/company', [CompanyController::class, 'store']);
    Route::get('/company', [CompanyController::class, 'index']);
    Route::get('/company/{name}', [CompanyController::class, 'showByName']);

    Route::post('/assessment', [AssessmentController::class, 'store']);
    Route::put('/assessment/{id}',[AssessmentController::class, 'update']);
});

// Route::get('/users', [AuthController::class, 'index']);

// Route::apiResource('companies', CompanyController::class);
