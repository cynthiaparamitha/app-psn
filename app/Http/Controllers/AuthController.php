<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        $users = [
            'admin'   => '12345',
            'cynthia' => 'cynthia123',
            'test' => 'test123',
        ];

        if (isset($users[$username]) && $users[$username] === $password) {

            session(['user' => $username]);

            return redirect('/drd');
        }

        return back()->with('error', 'Username atau password salah.');
    }

    public function logout()
    {
        session()->forget('user');
        return redirect('/login');
    }
}