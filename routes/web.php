<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'startGame'])->name('startgame');
Route::post('/prepare_name', [MainController::class, 'prepareGamer'])->name('prepare_name');
Route::get('/game', [MainController::class, 'game'])->name('game');
Route::get('/answer/{answer}', [MainController::class, 'answer'])->name('answer');
Route::get('/next_question', [MainController::class, 'next_question'])->name('next_question');
Route::get('/show_results', [MainController::class, 'show_results'])->name('show_results');


