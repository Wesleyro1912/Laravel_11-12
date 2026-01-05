<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
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

        // Store the quiz in session
        session()->put([
            'quiz' => $quiz,
            'total_questions' => $total_questions,
            'current_question' => 0,
            'correct_answers' => 0,
            'wrong_answers' => 0
        ]);

        return redirect()->route('game');
    }

    public function game(): View {

        $quiz = session('quiz');
        $total_questions = session('total_questions');

        // índice da questão atual
        $current_question_index = session('current_question');

        // questão atual
        $current_question = $quiz[$current_question_index];

        // prepara respostas
        $answers = $current_question['wrong_answers'];
        $answers[] = $current_question['correct_answer'];

        shuffle($answers);

        return view('game')->with([
            'country' => $current_question['country'],
            'totalQuestions' => $total_questions,
            'currentQuestion' => $current_question_index + 1,
            'answers' => $answers
        ]);
    }

    public function answer($enc_answer) {

        try {

           $answer = Crypt::decryptString($enc_answer);

        } catch (\Throwable $th) {

            return redirect()->route('game');

        }

        // game logic
        $quiz = session('quiz');
        $current_question = session('current_question');
        $correct_answer = $quiz[$current_question]['correct_answer'];
        $correct_answers = session('correct_answers');
        $wrong_answers = session('wrong_answers');

        if($answer == $correct_answer) {
            $correct_answers++;
            $quiz[$current_question]['correct'] = true;
        } else {
            $wrong_answers++;
            $quiz[$current_question]['correct'] = false;
        }

        // update session
        session()->put([
            'quiz' => $quiz,
            'correct_answers' => $correct_answers,
            'wrong_answers' => $wrong_answers
        ]);

        // prepare dat to show the correct answer
        $data = [
            'country' => $quiz[$current_question]['country'],
            'correct_answers' => $correct_answers,
            'choice_answers' => $answer,
            'currentQuestion' => $current_question,
            'totalQuestions' => session('total_questions')
        ];

        return view('answer_result')->with($data);
    }

    public function next_question() {

        $current_question = session('current_question');
        $total_questions = session('total_questions');

        // Check if the game is over
        if ($current_question < $total_questions) {

            $current_question++;
            session()->put('current_question', $current_question);
            return redirect()->route('game');

        } else {

            return redirect()->route('show_results');

        }
    }

    public function show_results() {

        $total_questions = session('total_questions');
        $correct_answer = session('correct_answer');

        return view('final_results')->with([
            'correct_answer' => session('correct_answer'),
            'wrong_answer' => session('wrong_answer'),
            'total_questions' => session('total_questions'),
            'percentage' => round( $correct_answer / $total_questions * 100 / 2)
        ]);
    }
}
