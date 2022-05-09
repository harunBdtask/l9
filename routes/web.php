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
Route::get('/create_directory', [ProjectController::class, 'createDirectory'])->name('create_directory');
Route::get('/show_directories', [ProjectController::class, 'showFiles'])->name('show_directories');
Route::get('/delete_directories', [ProjectController::class, 'renameDirectory'])->name('delete_directories');
//ProjectController
Route::resource('project', ProjectController::class);
