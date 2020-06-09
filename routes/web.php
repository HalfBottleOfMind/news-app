<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

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

Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::post('register', [RegisterController::class, 'register'])->name('register');
Route::get('auth/user', [AuthController::class, '__invoke'])->middleware('auth:sanctum')->name('auth.user');
Route::get('files/{name}', [FileController::class, '__invoke'])->middleware('auth:sanctum')->where('name', '.*');
Route::get('{any}', [IndexController::class, '__invoke'])->where('any', '.*');
