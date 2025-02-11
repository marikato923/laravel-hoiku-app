<?php

use Illuminate\Http\Request;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
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
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PushNotification;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file defines the web routes for your Laravel application.
|
*/

// Route::get('/', function () {
//     return view('home');
// });


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

    // 子供の情報
    Route::get('/children/create', [ChildController::class, 'create'])->name('children.create');
    Route::post('/children', [ChildController::class, 'store'])->name('children.store');
    Route::get('/children', [ChildController::class, 'show'])->name('children.show');
    Route::get('/children/{child}/edit', [ChildController::class, 'edit'])->name('children.edit');
    Route::put('/children/{child}', [ChildController::class, 'update'])->name('children.update');

    // 園の情報ページ
    Route::get('/kindergarten', [KindergartenController::class, 'show'])->name('kindergarten.show');

    // 利用規約ページ
    Route::get('/terms', [TermController::class, 'show'])->name('terms.show');

    // 通知
    Route::post('/user/subscribe', [UserController::class, 'subscribe'])->name('user.subscribe');
});

// 管理者側のページ
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::get('/home', [Admin\HomeController::class, 'index'])->name('home');
    Route::resource('/users', Admin\UserController::class)->only(['index', 'show']);

    // 子供情報の管理
    Route::resource('/children', Admin\ChildController::class);

    // 承認リクエストの管理
    Route::post('/children/{child}/approve', [Admin\ChildController::class, 'approve'])
        ->name('children.approve');
    Route::post('/children/{child}/reject', [Admin\ChildController::class, 'reject'])
        ->name('children.reject');

    Route::resource('/classrooms', Admin\ClassroomController::class)->except(['show']);
    Route::resource('/kindergarten', Admin\KindergartenController::class)->only(['index', 'edit', 'update']);
    Route::resource('/terms', Admin\TermController::class)->only(['index', 'edit', 'update']);
    Route::get('/attendance', [Admin\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/{childId}', [Admin\AttendanceController::class, 'show'])->name('attendance.show');
    Route::delete('/attendance/{id}', [Admin\AttendanceController::class, 'destroy'])->name('attendance.destroy');
    Route::get('/messages/{userId}', [MessageController::class, 'fetchMessagesForAdmin']);
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    // メール送信
    Route::get('/send-reminders', [Admin\NotificationController::class, 'sendPickupReminders']);
});
