<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MainController extends Controller
{
    private $app_data;

    public function __construct()
    {
        // load app_data.php
        $this->app_data = require(app_path('app_data.php'));
    }

    private function prepareQuiz($total_questions) {
        $questions = [];
        $total_countries = count($this->app_data);

        // create countries index
        $indexes = range(0, $total_countries - 1);
        shuffle($indexes);
        $indexes = array_slice($indexes, 0, $total_questions);

        // create array of questions 
        $question_number = 1;
        foreach($indexes as $index) {

            $question['question_number'] = $question_number++;
            $question['country'] = $this->app_data[$index]['country'];
            $question['correct_answer'] = $this->app_data[$index]['capital'];

            // wrong answer
            $other_capitals = array_column($this->app_data, 'capital');

            // remove correct
            $other_capitals = array_diff($other_capitals, [$question['correct_answer']]);

            // shuffle the wrong
            shuffle($other_capitals);
            $question['wrong_answers'] = array_slice($other_capitals, 0, 3);

            // store answer result
            $question['correct'] = null;

            $questions[] = $question;
        }

        return $questions;
    }

    public function startGame(): View {
        return view('home');
    }

    public function prepareGamer(Request $request) {
        
        // Validate
        $request->validate(
            [
                'total_questions' => 'required|integer|min:3|max:30',
            ],
            [
                'total_questions.required' => 'O número de questões é obrgatório',
                'total_questions.integer' => 'O número de questões tem que ser inteiro',
                'total_questions.min' => 'No mínimo :min questões',
                'total_questions.max' => 'No máximo :max questões',
            ]
        );

        // get total questions
        $total_questions = intval($request->input('total_questions'));

        // prepare all the quiz
        $quiz = $this->prepareQuiz($total_questions);

        dd($quiz);
    }
}
