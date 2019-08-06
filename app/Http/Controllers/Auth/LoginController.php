<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
//use Dotenv\Exception\ValidationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Console\Presets\React;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
//use Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;

class LoginController extends Controller
{

    use ThrottlesLogins;


    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'email';
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }


        $authenticate = Auth::attempt(
            $request->only(['email', 'password']),
            $request->filled('remember')
        );

        if ($authenticate)
        {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            $user = Auth::user();
            if ($user->isWait()) {
                Auth::logout();
                return back()->with('error', 'You need to confirm your account. Please check your email.');
            }
            return redirect()->intended(route('cabinet.profile'));
        }

        $this->incrementLoginAttempts($request);

        throw ValidationException::withMessages(['email','password' => [trans('Ошибка в арторизации:Проверьте данные')]]);
    }

    public function logout(Request $request)
    {
        Auth::guard()->logout();
        $request->session()->invalidate();
        return redirect()->route('home');
    }
}