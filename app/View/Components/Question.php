<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Question extends Component
{
    /**
     * Create a new component instance.
     */
    public string $country;
    public string $currentQuestion;
    public string $totalQuestions;

    public function __construct($country, $currentQuestion, $totalQuestions)
    {
        $this->country = $country;
        $this->currentQuestion = $currentQuestion;
        $this->totalQuestions = $totalQuestions;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.question');
    }
}
