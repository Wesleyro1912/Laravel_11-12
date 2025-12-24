<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Types\Relations\Role;


Route::controller(MainController::class)->group(function () {

    Route::get('/', 'home')->name('home');
    Route::post('/generate-exercises', 'generateExercises')->name('generateExercises');
    Route::get('/print-exercises', 'printExercises')->name('printExercises');
    Route::get('/export-exercises', 'exportExercises')->name('exportExercises');

});

Route::match(['get', 'post'], '/match', function(Request $request){
    return 'Aceita mais de um método pré definido (no caso GET e POST).';
});

Route::any('/any', function(Request $request){
    return 'Aceita mais de um método sem definição.';
});

// Redirecionar para outra rota de forma temporaria
Route::redirect('/saltar', '/match');

// Redirecionar para outra rota de forma permanente
Route::permanentRedirect('/saltar2', '/any');

// Rota com valor opcional
Route::get('/opcional/{value?}', [MainController::class, 'ValorOpcional']);

// Rota com valores opcionais
Route::get('/opcionais/{value1}/{value2?}', [MainController::class, 'ValoresOpcionais']);

// Rota com paramentros diferentes
Route::get('/user/{user_id}/post/{post_id}', [MainController::class, 'mostrarPosts']);


// === Definindo os tipos de valores passados nas rotas ===
// Apenas números
Route::get('/exp1/{value}', function($value) {
    echo $value;
})->where('value', '[0-9]+');

// Apenas letras maisculas e minusculas
Route::get('/exp2/{value}', function($value) {
    echo $value;
})->where('value', '[A-Za-z]+');

// Apenas numeros, letras maisculas e minusculas
Route::get('/exp3/{value}', function($value) {
    echo $value;
})->where('value', '[A-Za-z0-9]+');

// Validação personalizada de 2 parametros
Route::get('/exp4/{value1}/{value2}', function($value) {
    echo $value;
})->where([
    'value1' => '[0-9]+',
    'value2' => '[A-Za-z0-9]+',
]);



// === Grupos de rotas + Grupos de Middleware ===
// Grupo por Pré-fixos
Route::prefix('admin')->group(function (){
    Route::get('/home', [MainController::class, 'home']);
});

// Grupo por middleware
Route::middleware([OnylAdmin::class])->group(function (){
    Route::get('admin/home2', [MainController::class, 'home2']);
});

// Grupo por Controllers
Route::controller(UserController::class)->group(function(){
    Route::get('user/new', 'new');
});

// Grupo misturando os 3 (pode variar de acordo com a necessidade)
Route::controller(UserController::class)->group(function(){
    Route::middleware([OnylAdmin::class])->group(function (){
        Route::prefix('user')->group(function (){
            Route::get('/new', 'new');
        });
    });
});

// Chamada do método invoke
Route::get('/single', SingleActionContoller::class);


// === Rotas não encontradas ===
Route::fallback(function () {
    echo "Rota não encontrada";
});

// === middleware estudos ===
Route::middleware([StartMiddleware::class, EndMiddleware::class])->controller(MainController::class)->group(function(){
    Route::get('/MIndex', 'MIndex')->name('middIndex');
    Route::get('/MAbout', 'MAbout')->name('middAbout');
    Route::get('/Mcontact', 'Mcontact')->name('middContact')->withoutMiddleware([EndMiddleware::class]);
});

