<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\API\PostController;

Auth::routes();

Route::get('/', [ProjectController::class, 'home'])->name('home');
Route::get('/language_settings', [ProjectController::class, 'languageSettings'])->name('language_settings');
Route::get('/show_directories', [ProjectController::class, 'showDirList'])->name('show_directories');
Route::post('/upload_file', [ProjectController::class, 'uploadFile'])->name('upload_file');
Route::post('/file_action', [ProjectController::class, 'fileAction'])->name('file_action');
Route::post('/update_phrase', [ProjectController::class, 'updatePhrase'])->name('update_phrase');
//ProjectController
Route::resource('project', ProjectController::class);


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/post', function () {
    return view('layouts.post');
});

Route::group(['prefix' => 'post'], function () {
    Route::post('store', [PostController::class, 'store']);
});

