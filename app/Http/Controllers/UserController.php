<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use App\User;
use Illuminate\Support\Facades\Session;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function profile()
    {
        return view('profile', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'nullable|min:4',
            'email' => 'nullable|string|email',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        /** @var User $user */
        $user = Auth::user();

        $user->update($request->only(['name', 'email']));

        if ($request->get('password')) {
            $user->password = bcrypt($request->get('password'));
            Session::flash('message', 'Пароль успеншно обновлен');
            $user->save();
        }

        if ($request->hasFile('avatar'))
        {
            $avatar = $request->file('avatar');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();
            Image::make($avatar)->resize(300, 300)->save(public_path('/uploads/avatars/' . $filename));

            $user->avatar = $filename;
            Session::flash('message', 'Фото профиля еспешно обновлеоено');
            $user->save();
        }

        $user->save();
        return redirect()->route('cabinet.profile');
    }
}
