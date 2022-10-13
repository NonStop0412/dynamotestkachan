<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PaymentsController;
use App\Http\Controllers\API\UsersController;


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


Route::post('sign-up', [AuthController::class, 'register'])->name('sign-up');
Route::post('sign-in', [AuthController::class, 'login'])->name('sign-in');

//Route::get('/account', [UsersController::class, 'account']);
//Route::post('/payment', [PaymentsController::class, 'create']);

Route::middleware('auth:api')->group( function () {
    Route::get('account', [UsersController::class, 'account'])->name('account');
    Route::post('payment', [PaymentsController::class, 'create'])->name('payment');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
