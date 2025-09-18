<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index($value) {
        // return view('main', ['value' => $value]);

        // return view('main')->with('value', $value);

        return view('main', compact('value'));
        
    }
}
