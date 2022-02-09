<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Metier\CandidatController;
use App\Http\Controllers\Metier\ElectionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth');
    Route::get('/me', [AuthController::class, 'me'])->name('auth.me')->middleware('auth');
});


Route::group(['prefix' => 'election'], function () {
    Route::get('/index', [ElectionController::class, 'index'])->name('election.index')->middleware('auth');
});

Route::group(['prefix' => 'candidat'], function () {
    Route::get('/index/{idElection}', [CandidatController::class, 'index'])->name('candidat.index')->middleware('auth');
});
