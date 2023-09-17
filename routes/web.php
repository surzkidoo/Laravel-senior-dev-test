<?php

use Illuminate\Support\Facades\Route;

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


Auth::routes();

Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->middleware(['auth'])->name('user.index');
Route::get('/user/delete/{id}', [App\Http\Controllers\UserController::class, 'delete'])->middleware(['auth'])->name('user.delete');
Route::get('/user/destroy/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->middleware(['auth'])->name('user.destroy');
Route::get('/user/trashed', [App\Http\Controllers\UserController::class, 'trashed'])->middleware(['auth'])->name('user.trashed');
Route::get('/user/create', [App\Http\Controllers\UserController::class, 'create'])->middleware(['auth'])->name('user.create');
Route::post('/user/store', [App\Http\Controllers\UserController::class, 'store'])->name('user.store');
Route::get('/user/edit/{id}', [App\Http\Controllers\UserController::class, 'edit'])->middleware(['auth'])->name('user.edit');
Route::post('/user/update/{id}', [App\Http\Controllers\UserController::class, 'update'])->middleware(['auth'])->name('user.update');
Route::get('/user/restore/{id}', [App\Http\Controllers\UserController::class, 'restore'])->middleware(['auth'])->name('user.restore');
Route::get('/user/{id}', [App\Http\Controllers\UserController::class, 'show'])->middleware(['auth'])->name('user.show');


