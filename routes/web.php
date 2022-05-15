<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ProjectController::class, 'home'])->name('home');
Route::get('/language_settings', [ProjectController::class, 'languageSettings'])->name('language_settings');
Route::get('/show_directories', [ProjectController::class, 'showDirList'])->name('show_directories');
Route::post('/upload_file', [ProjectController::class, 'uploadFile'])->name('upload_file');
Route::post('/file_action', [ProjectController::class, 'fileAction'])->name('file_action');
Route::post('/update_phrase', [ProjectController::class, 'updatePhrase'])->name('update_phrase');
//ProjectController
Route::resource('project', ProjectController::class);