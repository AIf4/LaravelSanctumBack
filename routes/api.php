<?php

use App\Http\Controllers\ProyectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/user', [UserController::class, 'createUser'] );
Route::post('/login', [UserController::class, 'loginUser'] );

Route::middleware('auth:sanctum')->group( function(){

    // rutas de usuario
    Route::get('/users', [UserController::class, 'getAllUsers'] );
    Route::get('/user/{id}', [UserController::class, 'getUserById'] );
    Route::middleware('rol:admin')->put('/user/{id}', [UserController::class, 'updateUser'] );
    Route::middleware('rol:admin')->delete('/user/{id}', [UserController::class, 'deleteUser'] );

    // rutas de proyecto
    Route::middleware('rol:admin')->post('/proyect', [ProyectController::class, 'createProyect']);
    Route::get('/proyects', [ProyectController::class, 'getAllProyects']);
    Route::get('/proyect/{id}', [ProyectController::class, 'getProyectById']);
    Route::get('/proyect/{user_id}/byuser', [ProyectController::class, 'getAllProyectsByUserId']);
    Route::get('/proyect/{params}/params', [ProyectController::class, 'getByParam']);
    Route::middleware('rol:admin')->put('/proyect/{id}', [ProyectController::class, 'updateProyect']);
    Route::middleware('rol:admin')->delete('/proyect/{id}', [ProyectController::class, 'deleteProyect']);


    Route::middleware('rol:admin')->post('/task', [TaskController::class, 'createTask']);
    Route::get('/tasks', [TaskController::class, 'getAllTask']);
    Route::get('/task/{id}', [TaskController::class, 'getTaskById']);
    Route::get('/task/{proyect_id}/byproyect', [TaskController::class, 'getTaskByProyectId']);
    Route::middleware('rol:admin')->put('/task/{id}', [TaskController::class, 'updateTask']);
    Route::middleware('rol:admin')->delete('/task/{id}', [TaskController::class, 'deleteTask']);
    Route::post('/task/{task}/assign', [TaskController::class, 'assignUser']);
    Route::post('/task/{task}/unassign', [TaskController::class, 'unassignUser']);
});

