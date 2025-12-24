<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;

class MainController extends Controller
{
    // === Página inicial ===
    public function home() :View{
        return view('home');
    }


    // === Formulário Post ===
    public function generateExercises(Request $request) :View {

        // form validation
        $request->validate([
            'check_sum' => 'required_without_all:check_subtraction,check_multiplication,check_division',
            'check_subtraction' => 'required_without_all:check_sum,check_multiplication,check_division',
            'check_multiplication' => 'required_without_all:check_subtraction,check_sum,check_division',
            'check_division' => 'required_without_all:check_subtraction,check_multiplication,check_sum',

            'number_one' => 'required|integer|min:0|max:999|lt:number_two',
            'number_two' => 'required|integer|min:0|max:999',
            'number_exercises' => 'required|integer|min:5|max:50',
        ]);

        // get selected operations
        $operations = [];
        if ($request->check_sum) { $operations [] = 'sum';}
        if ($request->check_subtraction) { $operations [] = 'subtraction';}
        if ($request->check_multiplication) { $operations [] = 'multiplication';}
        if ($request->check_division) { $operations [] = 'division';}

        // get numbers (min and max)
        $min = $request->number_one;
        $max = $request->number_two;

        // get number of exercises
        $numberExercises = $request->number_exercises;

        // generate exercises
        $exercises = [];

        for($index = 1; $index <= $numberExercises; $index++){
            $exercises [] = $this->generateExercise($index, $operations, $min, $max); 
        }

        // place exercises in session
        $request->session()->put('exercises', $exercises); 
        // ou
        // session(['exercises', $exercises]);

        return view('operations', ['exercises' => $exercises]);

    }


    // === Imprimir os execícios ===
    public function printExercises() {

        // check if exercises are in session
        if(!session()->has('exercises')){
            return redirect()->route('home');
        }

        $exercises = session('exercises');

        // Exercises
        echo '<pre>';
        echo '<h1>Exercícios de Matemática (' . config('app.name') . ')</h1>';
        echo '<hr>';
        foreach($exercises as $exercise) {
            echo '<h2><small>' . $exercise['exercise_number'] . ' >> </small> ' . $exercise['exercise'] . '</h2>';
        }

        // Sollutions
        echo '<hr>';
        echo '<small>Soluções</small><br>';
        foreach($exercises as $exercise) {
            echo '<small>' . $exercise['exercise_number'] . ' >> ' . $exercise['soluction'] . '</small><br>';
        }

    }


    // === Exportar os execicios ===
    public function exportExercises() {

        // check if exercises are in session
        if(!session()->has('exercises')){
            return redirect()->route('home');
        }

        $exercises = session('exercises');

        // Create file to download with exercises
        $fillename = 'exercises_' . config('app.name') . '_' . date('YmdHis') . 'txt';

        $content = '';
        $content .= "Exercicíos de Matemárica (" . config('app.name') .  ")\n\n";
        foreach ($exercises as $exercise) {
            $content .= $exercise['exercise_number'] . " > " . $exercise['exercise']. "\n";
        }

        // Sollutons
        $content .= "\n";
        $content .= "Soluções\n" . str_repeat('-', 20) . "\n";
        foreach ($exercises as $exercise) {
            $content .= $exercise['exercise_number'] . " > " . $exercise['soluction']. "\n";
        }

        return response($content)->header('Content-Type', 'text/plain')->header('Content-Disposition', 'attachment; filename="' . $fillename .'"');
        
    }


    private function generateExercise($index, $operations, $min, $max): array {

         $operation = $operations[array_rand($operations)];
         $number1 = rand($min, $max);
         $number2 = rand($min, $max);

        $exercise = '';
        $soluction = '';

        switch($operation) {
            case 'sum':
                $exercise = "$number1 + $number2 = ";
                $soluction = $number1 + $number2;
                break;

            case 'subtraction':
                $exercise = "$number1 - $number2 = ";
                $soluction = $number1 - $number2;
                break;

            case 'multiplication':
                $exercise = "$number1 x $number2 = ";
                $soluction = $number1 * $number2;
                break;

            case 'division':

                // avoid division by zero
                if($number2 == 0) {
                    $number2 = 1;
                }

                $exercise = "$number1 : $number2 = ";
                $soluction = $number1 / $number2;
                break;
        }

        // if $soluction is a float numer, round it to 2 decimal places
        if (is_float($soluction)) {
            $soluction = round($soluction, 2);
        }
    
        return  [
            'operation' => $operation,
            'exercise_number' => str_pad($index, 2, '0', STR_PAD_LEFT),
            'exercise' => $exercise,
            'soluction' => "$exercise $soluction",

        ];
    }
}
