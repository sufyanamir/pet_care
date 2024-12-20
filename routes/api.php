<?php

use App\Http\Controllers\api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/addReminder', [ApiController::class, 'addReminder']);
    Route::get('/getReminder', [ApiController::class, 'getReminder']);
    Route::post('/deleteReminder', [ApiController::class, 'deleteReminder']);

    Route::post('/addFeed', [ApiController::class, 'addFeed']);
    Route::get('/getFeed', [ApiController::class, 'getFeed']);
    Route::post('/deleteFeed', [ApiController::class, 'deleteFeed']);
    Route::post('/likeFeed', [ApiController::class, 'likeFeed']);

    Route::get('/getAnimals', [ApiController::class, 'getAnimals']);
    Route::get('/getBreed/{id}', [ApiController::class, 'getBreed']);

    Route::post('/addPet', [ApiController::class, 'addPet']);
    Route::get('/getPets', [ApiController::class, 'getPets']);
    Route::post('/deletePet', [ApiController::class, 'deletePet']);
    Route::post('/addPetImages', [ApiController::class, 'addPetImages']);

    Route::get('/getPetDetails/{id}/{key?}', [ApiController::class, 'getPetDetails']);

    Route::get('/getUserDetails', [ApiController::class, 'getUserDetails']);
    Route::post('/updateUserDetails', [ApiController::class, 'updateUserDetails']);

    Route::get('/mostLikedFeed', [ApiController::class, 'mostLikedFeed']);
    Route::get('/upcomingReminders', [ApiController::class, 'upcomingReminders']);

    Route::match(['post', 'get'], '/logout', [ApiController::class, 'logout']);

});

Route::post('/login', [ApiController::class, 'login']);
Route::post('/register', [ApiController::class, 'register']);