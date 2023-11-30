<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function loginAction(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'username' => ['required'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                return redirect()->route('dashboard');
            }

            $error = 'The provided credentials are incorrect.';
        }
        return view('login', [
            'error' => $error ?? ''
        ]);
    }

    public function logoutAction()
    {
        if (Auth::check()) {
            Auth::logout();
        }
        return redirect()->route('login');
    }

}
