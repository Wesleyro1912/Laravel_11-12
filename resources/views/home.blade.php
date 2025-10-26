<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>

    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/png">

    <!-- bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">

    <!-- main css -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
</head>

<body>

    <!-- logo -->
    <div class="text-center my-3">
        <img src="{{ asset('assets/images/logo.jpg') }}" alt="logo" class="img-fluid">
    </div>

    <h3 class="text-center text-secondary mb-5">
        Selecione as opções para gerar<br><span class="text-info">exercícios de matemática</span>.
    </h3>

    <!-- form -->
    <form action="{{ route('generateExercises') }}" method="post">
        @csrf
        <div class="container border border-primary rounded-3 p-5">
            <div class="row gap-5">

                <!-- 4 checkboxes -->
                <div class="col">
                    <p class="text-info">Operações:</p>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="check_sum" name="check_sum" checked>
                        <label class="form-check-label" for="check_sum">Soma</label>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="check_subtraction" name="check_subtraction" checked>
                        <label class="form-check-label" for="check_subtraction">Subtração</label>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="check_multiplication" name="check_multiplication" checked>
                        <label class="form-check-label" for="check_multiplication">Multiplicação</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="check_division" name="check_division" checked>
                        <label class="form-check-label" for="check_division">Divisão</label>
                    </div>
                </div>

                <!-- parcelas -->
                <div class="col">
                    <p class="text-info">Parcelas:</p>

                    <div class="mb-3">
                        <label for="number_one">Mínimo:</label>
                        <input type="number" class="form-control" id="number_one" name="number_one" min="0" max="999" value="0">
                    </div>

                    <div>
                        <label for="number_two">Máximo:</label>
                        <input type="number" class="form-control" id="number_two" name="number_two" min="0" max="999" value="100">
                    </div>
                </div>

                <!-- number of exercises and submit -->
                <div class="col">
                    <p class="text-info">Número de exercícios:</p>

                    <div class="mb-3">
                        <label for="number_exercises">Número:</label>
                        <input type="number" class="form-control" id="number_exercises" name="number_exercises" min="5" max="50" value="10">
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Gerar exercícios</button>
                    </div>
                </div>

            </div>
        </div>
    </form>

    {{-- Validation ERRO --}}
    @if ($errors->any())
        <div class="container">
            <div class="row">
                <div class="alert alert-danger text-center mt-3">
                    erro
                </div>
            </div>
        </div>
    @endif

    <!-- footer -->
    <footer class="text-center mt-5">
        <p class="text-secondary">MathX &copy; <span class="text-info">{{ date('Y') }}</span></p>
    </footer>

    <!-- bootstrap -->
    <script src="{{ asset('assets/bootstrap/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
