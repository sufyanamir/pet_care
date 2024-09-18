<?php

use App\Http\Controllers\AnimalController;
use App\Http\Controllers\BreedController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CustomAuthMiddleware;

Route::middleware(['custom_auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    });
    
    Route::get('/', function () {
        return view('dashboard');
    });
    
    Route::get('/animals', [AnimalController::class, 'getAnimals']);
    Route::post('/addAnimal', [AnimalController::class, 'addAnimal']);
    Route::match(['post', 'get'], '/delete/animal/{id}', [AnimalController::class, 'deleteAnimal']);
    Route::get('/getAnimalDetail/{id}', [AnimalController::class, 'getAnimalDetail']);

    Route::get('/breed', [BreedController::class, 'getBreeds']);
    Route::post('/addBreed', [BreedController::class, 'addBreed']);
    Route::match(['post', 'get'], '/delete/breed/{id}', [BreedController::class, 'deleteBreed']);
});


Route::get('/login', function () {
    return view('login');
});
Route::get('/', function () {
    return view('login');
});

Route::post('/Login', [UserController::class, 'login']);
Route::match(['get, post'], '/logout', [UserController::class, 'logout']);