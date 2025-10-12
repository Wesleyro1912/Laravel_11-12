<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    public function login() {

        return view('login');
        
    }

    public function loginSubmit(Request $request) {

        // Form Validation
        $request->validate(
            [
                'text_username' => 'required|email',
                'text_password' => 'required|min:6|max:16',
            ],

            // Messagens
            [
                'text_username.required' => 'Campo usuário é obrigatório',
                'text_username.email' => 'Campo usuário é email',
                'text_password.required' => 'Campo password é obrigatório',
                'text_password.min' => 'Campo password deve ter no mínimo :min caracteres',
                'text_password.max' => 'Campo password deve ter no máximo :max caracteres',
            ]
        );

        // Get user input
        $usename = $request->input('text_username');
        $password = $request->input('text_password');

        // Check Login
        $user = $this->UserModel->where('username', $usename)->where('deleted_at', NULL)->first();  
        
        if(!$user) {
            return redirect()->back()->withInput()->with('loginError', 'Username ou password incorreto!');
        }

        // check if password is correct
        if (!password_verify($password, $user->password)){
            return redirect()->back()->withInput()->with('loginError', 'Username ou password incorreto!');
        }

        // update last login
        $user->last_login = date('Y-m-d H:i:s');
        $user->save();
        
        // Login user
        session([
            'user' => [
                'id' => $user->id,
                'username' => $user->username
            ]
        ]);

        // Return Home
        return redirect()->to('/');
        
    }

    public function logout() {

        // Logout from the aplication
        session()->forget('user');
        return redirect()->to('/login'); 

    }


}
