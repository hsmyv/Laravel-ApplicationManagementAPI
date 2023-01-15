<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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





// Ilk once RolePermissionSeeder-den role yaradilmali, qeydiyyatdan kecen user-e role verilmelidir.
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/category',                 [AdminController::class, 'get_categories'])->middleware("role:Admin");
    Route::post('/create-category',         [AdminController::class, 'create_category'])->middleware("role:Admin");;
    Route::patch('/category/{category}',    [AdminController::class, 'update_category'])->middleware("role:Admin");;
    Route::delete('/category/{category}',   [AdminController::class, 'delete_category'])->middleware("role:Admin");;

    Route::get('/users',                    [AdminController::class, 'get_users'])->middleware("role:Admin|Moderator");
    Route::post('/create-user',             [AdminController::class, 'create_user'])->middleware("role:Admin|Moderator");
    Route::patch('/user/{user}',            [AdminController::class, 'update_user'])->middleware("role:Admin|Moderator");
    Route::delete('/user/{id}',           [AdminController::class, 'delete_user'])->middleware("role:Admin|Moderator");

    Route::get('applys',                    [AdminController::class, 'index'])->middleware("role:Admin|Moderator");
    Route::patch('apply/{apply}',           [AdminController::class, 'update_apply'])->middleware("role:Admin|Moderator");

    Route::get('templates',                 [AdminController::class, 'get_templates'])->middleware("role:Admin");
    Route::patch('template/{id}',           [AdminController::class, 'update_template'])->middleware("role:Admin");
    Route::post('create-apply',             [UserController::class, 'create_apply']);
});

Route::post('register', [UserController::class, 'register']);
Route::post('login',    [UserController::class, 'login']);
