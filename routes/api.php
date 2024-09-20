<?php

use App\Http\Controllers\api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/getAnimals', [ApiController::class, 'getAnimals']);
    Route::get('/getBreed/{id}', [ApiController::class, 'getBreed']);

    Route::post('/addPet', [ApiController::class, 'addPet']);
    Route::get('/getPets', [ApiController::class, 'getPets']);

    Route::get('/getPetDetails/{id}/{key?}', [ApiController::class, 'getPetDetails']);

    Route::get('/getUserDetails', [ApiController::class, 'getUserDetails']);

});

Route::post('/login', [ApiController::class, 'login']);
Route::post('/register', [ApiController::class, 'register']);