<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\DoctorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

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

Route::post('/order', [OrderController::class, 'store']);
Route::put('/order/update', [OrderController::class, 'update']);
Route::post('/doctor', [DoctorController::class, 'doctorApi']);
Route::post('/comment', [CommentController::class, 'store']);
