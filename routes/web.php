<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;


Route::controller(MainController::class)->group(function () {

    Route::get('/', 'home')->name('home');
    Route::post('/generate-exercises', 'generateExercises')->name('generateExercises');
    Route::get('/print-exercises', 'printExercises')->name('printExercises');
    Route::get('/export-exercises', 'exportExercises')->name('exportExercises');

});