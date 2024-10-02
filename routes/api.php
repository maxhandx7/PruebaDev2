<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

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

// Rutas para usuarios
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:api')->get('/user', [AuthController::class, 'user']);

// Rutas para las notas protegidas por el middleware auth:api
Route::middleware('auth:api')->group(function () {
    Route::get('/notes', [NoteController::class, 'index']);
    Route::post('/notes', [NoteController::class, 'store']);
    Route::get('/notes/{note}', [NoteController::class, 'edit']);
    Route::put('/notes/{note}', [NoteController::class, 'update']);
    Route::delete('/notes/{note}', [NoteController::class, 'destroy']);
});

 // Rutas para etiquetas protegidas por el middleware auth:api
Route::middleware('auth:api')->group(function () {
   
    Route::get('/tags', [TagController::class, 'index']);    // Listar todas las etiquetas
    Route::post('/tags', [TagController::class, 'store']);   // Crear nueva etiqueta
    Route::delete('/tags/{id}', [TagController::class, 'destroy']); // Eliminar etiqueta
});



