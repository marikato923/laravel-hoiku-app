<?php

use Illuminate\Support\Facades\Route;
use App\Events\MessageSent;
use App\Http\Middleware\RedirectIfNotAuthenticatedAsUserAndAdmin;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KindergartenController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\Admin;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

require __DIR__.'/auth.php';

// ユーザー側のページ
Route::group(['middleware' => ['auth', RedirectIfNotAuthenticatedAsUserAndAdmin::class]], function () {
    // トップページ
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // 会員情報ページ
    Route::get('/user/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/update', [UserController::class, 'update'])->name('user.update');
    Route::get('/user', [UserController::class, 'show'])->name('user.show');
    Route::get('/user/edit-password', [UserController::class, 'editPassword'])->name('user.edit-password');
    Route::post('/user/update-password', [UserController::class, 'updatePassword'])->name('user.update-password');

    // 出席履歴
    Route::get('/attendance/show', [AttendanceController::class, 'show'])->name('attendance.show');

    // 子供の基本情報
    Route::get('/children/{child}', [ChildController::class, 'show'])->name('children.show');

    // 園の情報ページ
    Route::get('/kindergarten', [KindergartenController::class, 'show'])->name('kindergarten.show');

    // 利用規約ページ
    Route::get('/terms', [TermController::class, 'show'])->name('terms.show');

    // チャット機能
    Route::get('/messages/{adminId}', [MessageController::class, 'fetchMessagesForUser']);
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
});

// 管理者側のページ
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::get('/home', [Admin\HomeController::class, 'index'])->name('home');
    Route::resource('/users', Admin\UserController::class)->only(['index', 'show']);
    Route::resource('/children', Admin\ChildController::class);
    Route::resource('/classrooms', Admin\ClassroomController::class)->except(['show']);
    Route::resource('/kindergarten', Admin\KindergartenController::class)->only(['index', 'edit', 'update']);
    Route::resource('/terms', Admin\TermController::class)->only(['index', 'edit', 'update']);
    Route::get('/attendance', [Admin\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/daily', [Admin\AttendanceController::class, 'dailyAttendance'])->name('attendance.daily');
    Route::get('/messages/{userId}', [MessageController::class, 'fetchMessagesForAdmin']);
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
});



