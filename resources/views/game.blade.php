<x-main-layout :pageTitle="config('app.name')">

    <div class="container">

        <x-question :country="$country" :currentQuestion="$currentQuestion" :totalQuestions="$totalQuestions" />

        <div class="row">
            @foreach ($answers as $answer)
                <x-answer :capital="$answer" />
            @endforeach
        </div>

        <!-- cancel game -->
        <div class="text-center mt-5">
            <a href="{{ route('startgame') }}" class="btn btn-outline-danger mt-3 px-5">CANCELAR JOGO</a>
        </div>
            
    </div>

</x-main-layout>