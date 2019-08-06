<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\Auth\VerifyMail;


class RegisterController extends Controller
{


    protected $redirectTo = '/home';


    public function __construct()
    {

        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $this->validate($request ,[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'verify_token' => Str::uuid(),
            'status' => User::STATUS_WAIT,
        ]);

        Mail::to($user->email)->send(new VerifyMail($user));
        event(new Registered($user));
        Session::flash('message', 'Вы успешно зарегестрировались! Вам на почту отправлено письмо, для авторизации вам необходимо подтвердить Ваш email ');
        return redirect()->route('login');
    }


    public function verify($token)
    {
        if (!$user = User::where('verify_token', $token)->first())
        {
            return redirect()
                ->route('login')
                ->with('error','извините ваш емаил не потверждун');
        }

        if ($user->status !== User::STATUS_WAIT)
        {
            return redirect()
                ->route('login')
                ->with('error','извините ваш емаил не потверждун');
        }

        $user->status = User::STATUS_ACTIVE;
        $user->verify_token = null;
        $user->save();

        return redirect()
            ->route('login')
            ->with('success','емаил верефицирован');
    }


}
