<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'startGame'])->name('startgame');
Route::post('/prepare_name', [MainController::class, 'prepareGamer'])->name('prepare_name');



