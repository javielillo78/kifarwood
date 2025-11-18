<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function login(\Illuminate\Http\Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
                // 'g-recaptcha-response' => ['required','captcha'],
            ]);

            if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
                $request->session()->regenerate();
                $user = Auth::user();

                return $user->rol === 'admin'
                    ? redirect()->route('admin.dashboard')
                    : redirect()->route('public.index');
            }

            return back()->withErrors([
                'email' => 'Las credenciales no son válidas.',
            ])->onlyInput('email');
        } catch (\Throwable $e) {
            // MOSTRAR INFO DEL ERROR EN EL NAVEGADOR
            dd($e->getMessage(), $e->getFile(), $e->getLine());
        }
    }

    protected function authenticated(Request $request, $user)
    {
        session()->flash('login_ok', 'Has iniciado sesión correctamente.');
    }
}
