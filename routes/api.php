<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;


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

//department resources route.   
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('departments', DepartmentController::class);
});

//employee route.
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('employees', EmployeeController::class);
});

// Manager routes: only view and update employees in their own department (we'll filter in controller)
Route::middleware(['auth:sanctum', 'role:manager'])->group(function () {
    Route::get('/manager/employees', [EmployeeController::class, 'managerIndex']);
    Route::put('/manager/employees/{employee}', [EmployeeController::class, 'managerUpdate']);
});
    
// Employee: view own profile
Route::middleware(['auth:sanctum', 'role:employee'])->get('/employee/profile', [EmployeeController::class, 'profile']);

// Manager: View and Update Employees of own department
Route::middleware(['auth:sanctum', 'role:manager'])->group(function () {
    Route::get('/manager/employees', [EmployeeController::class, 'managerIndex']);
    Route::put('/manager/employees/{employee}', [EmployeeController::class, 'managerUpdate']);
});

Route::middleware(['auth:sanctum', 'role:employee'])->get('/employee/profile', [EmployeeController::class, 'profile']);

