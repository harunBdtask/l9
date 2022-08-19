<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Auth;

Auth::routes();
Route::get('/', [ProjectController::class, 'home'])->name('home');
Route::get('/language_settings', [ProjectController::class, 'languageSettings'])->name('language_settings');
Route::get('/show_directories', [ProjectController::class, 'showDirList'])->name('show_directories');
Route::post('/upload_file', [ProjectController::class, 'uploadFile'])->name('upload_file');
Route::post('/file_action', [ProjectController::class, 'fileAction'])->name('file_action');
Route::post('/update_phrase', [ProjectController::class, 'updatePhrase'])->name('update_phrase');
Route::resource('project', ProjectController::class);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/posts', [App\Http\Controllers\API\PostController::class, 'index']);
Route::controller(NotificationController::class)->group(function () {
    Route::get('/notification', 'notificationPage');
    Route::get('/send-notification', 'sendNotification');
    Route::get('/show-notifications', 'showNotifications');
    Route::get('/get-notifications', 'getNotification');
    Route::get('/notification/{id}', 'readNotification');
});

