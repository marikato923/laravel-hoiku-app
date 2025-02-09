<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\NotificationController;


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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('/hello', function () {
    return response()->json(['message' => 'Hello World']);
});

// 登園、降園の記録
Route::post('/attendance/arrival', [AttendanceController::class, 'markArrival']);
Route::post('/attendance/departure', [AttendanceController::class, 'markDeparture']);


