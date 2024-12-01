<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;

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

Route::apiResource('permissions', PermissionController::class);
Route::apiResource('users', UserController::class);

Route::get('/usuarios', [UserController::class, 'index']);
Route::get('/usuarios/{id}', [UserController::class, 'show']); 
Route::post('/cadastrar-usuario', [UserController::class, 'store']); 
Route::put('/editar-user/{id}', [UserController::class, 'update']); 
Route::delete('/excluir-user/{id}', [UserController::class, 'destroy']);
Route::post('/users/{id}/sync-permissions', [UserController::class, 'assignPermissions']);