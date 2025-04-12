<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;

//Route for sanctum.
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//register
Route::post('/register', [AuthController::class, 'register']);

//login
Route::post('/login',    [AuthController::class, 'login']);

//logout
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

//admin
Route::middleware(['auth:sanctum', 'role:admin'])->get('/admin', function () {
    return response()->json(['message' => 'Welcome Admin']);
});

//manager
Route::middleware(['auth:sanctum', 'role:manager'])->get('/manager', function () {
    return response()->json(['message' => 'Hello Manager']);
});
    
//assign role
Route::post('/assign-role', [AdminController::class, 'assignRole'])->middleware(['auth:sanctum', 'role:admin']);

