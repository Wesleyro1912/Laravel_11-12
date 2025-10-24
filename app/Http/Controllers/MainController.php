<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class MainController extends Controller
{
    
    public function index() {
        echo 'index';
    }

    public function about() {
        echo 'about';
    }

    public function ValorOpcional($value = null) {
        echo 'Valor: ' . $value;
    }

    public function ValoresOpcionais($value1, $value2 = null) {
        echo 'Valor: ' . $value1 .' e '. $value2;
    }

    public function mostrarPosts($user_id, $post_id = null) {
        echo 'Valor: ' . $user_id .' e '. $post_id;
        
    }

    public function home() {
        echo "Ola mundo";

    }

    public function home2() {
        echo "Ola mundo";

    }

    public function MIndex(): void {
        echo "<p/>IndexM<p>";
    }

    public function MAbout(): void {
        echo "<p/>MAbout<p>";
    }

    public function Mcontact(): void {
        echo "<p/>Mcontact<p>";
    }


}
