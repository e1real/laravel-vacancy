<?php declare(strict_types=1);

use App\Http\Controllers\ResponseController;
use App\Http\Controllers\VacancyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserLikedVacanciesController;
use \App\Http\Controllers\UserLikedUsersController;

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


Route::resource('vacancy', VacancyController::class);
Route::resource('response', ResponseController::class);
Route::resource('user-liked-vacancies', UserLikedVacanciesController::class);
Route::resource('user-liked-users', UserLikedUsersController::class);
