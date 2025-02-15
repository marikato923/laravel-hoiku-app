<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\NotificationController;
use App\Http\Middleware\RedirectIfNotAuthenticatedAsUserAndAdmin;


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


Route::middleware(['auth:sanctum', 'verified', RedirectIfNotAuthenticatedAsUserAndAdmin::class])->group(function () {
    // 登園・降園の記録API
    Route::post('/attendance/arrival', [AttendanceController::class, 'markArrival']);
    Route::post('/attendance/departure', [AttendanceController::class, 'markDeparture']);
});


