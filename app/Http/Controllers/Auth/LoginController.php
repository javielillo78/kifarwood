<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'                => ['required','email'],
            'password'             => ['required'],
            'g-recaptcha-response' => ['required','captcha'],
        ]);
        if (Auth::attempt($request->only('email','password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->rol === 'admin') {
                return redirect()
                    ->route('admin.dashboard')
                    ->with('login_ok', 'Has iniciado sesión correctamente.');
            }
            return redirect()
                ->intended(route('public.cesta.checkout'));
        }
        return back()
            ->withErrors(['email' => 'Las credenciales no son válidas.'])
            ->onlyInput('email');
    }
}