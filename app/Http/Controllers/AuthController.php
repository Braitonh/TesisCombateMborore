<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
     // Mostrar formulario de login
     public function showLoginForm()
     {
         return view('auth.login');
     }
 
     // Procesar login
     public function login(Request $request)
     {
         $credentials = $request->validate([
             'email'    => ['required','email'],
             'password' => ['required'],
         ]);
 
         if (Auth::attempt($credentials, $request->boolean('remember'))) {
             $request->session()->regenerate();
             return redirect()->intended(route('productos.index'));
         }
 
         return back()
             ->withErrors(['email' => 'Las credenciales no coinciden.'])
             ->onlyInput('email');
     }
 
     // Logout
     public function logout(Request $request)
     {
         Auth::logout();
         $request->session()->invalidate();
         $request->session()->regenerateToken();
         return redirect()->route('login');
     }
}
