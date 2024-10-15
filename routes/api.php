<?php

use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\MonthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\YearController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/saveYear', [YearController::class, 'store']);
Route::get('/getYear', [YearController::class, 'index']);
Route::delete('/deleteYear/{year}', [YearController::class, 'destroy']);

Route::get('months/{year}', [MonthController::class, 'index']);
Route::post('months/{year}', [MonthController::class, 'store']);
Route::delete('/deleteMonth/{year}/{month}', [MonthController::class, 'destroy']);

Route::post('uploadPhoto', [ImageController::class, 'uploadPhoto']);
Route::delete('deletePhoto/{year}/{month}/{photoName}', action: [ImageController::class, 'deletePhoto']);
Route::get('photos/{year}/{month}/{letter}', [ImageController::class, 'getPhotos']);

