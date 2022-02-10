<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Metier\VoteController;
use App\Http\Controllers\Metier\CandidatController;
use App\Http\Controllers\Metier\ElectionController;

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
    Route::post('/store',  [ElectionController::class, 'store'])->name('election.store')->middleware('admin');
    Route::put('/update/{id}',  [ElectionController::class, 'update'])->name('election.update')->middleware('admin');
    Route::delete('/delete/{id}',  [ElectionController::class, 'delete'])->name('election.delete')->middleware('admin');
});

Route::group(['prefix' => 'candidat'], function () {
    Route::get('/indexAll', [CandidatController::class, 'indexAll'])->name('candidat.indexAll')->middleware('admin');
    Route::get('/index/{idElection}', [CandidatController::class, 'index'])->name('candidat.index')->middleware('auth');
    Route::post('/store',  [CandidatController::class, 'store'])->name('candidat.store')->middleware('admin');
    Route::put('/update/{id}',  [CandidatController::class, 'update'])->name('candidat.update')->middleware('admin');
    Route::delete('/delete/{id}',  [CandidatController::class, 'delete'])->name('candidat.delete')->middleware('admin');
});


Route::group(['prefix' => 'vote'], function () {
    Route::get('/voteCandidat/{idCandidat}', [VoteController::class, 'voteCandidat'])->name('vote.voteCandidat')->middleware('auth');
});


