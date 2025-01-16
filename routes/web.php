<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('pet', [PetController::class, 'index']);
Route::post('pet', [PetController::class, 'add']);
Route::get('pet/list', [PetController::class, 'list']);
Route::get('pet/edit', [PetController::class, 'editView']);
Route::get('pet/delete', [PetController::class, 'deleteView']);
Route::get('pet/{pet:id}', [PetController::class, 'show']);
Route::put('pet/edit', [PetController::class, 'edit']);
Route::delete('pet/delete', [PetController::class, 'delete']);
