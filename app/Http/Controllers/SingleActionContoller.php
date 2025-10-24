<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SingleActionContoller extends Controller
{
   
    // Método publico que chamar métodos privados
    public function __invoke(Request $request) :void
    {
        echo 'Hello word';
        echo '<br>';
        echo $this->privatMethod();
    }

    private function privatMethod(): string{
        return "privatMethod";
    }
}
