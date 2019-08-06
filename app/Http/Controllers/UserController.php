<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;


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

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');

            $path = $user->getAvatarPath();
            $name = Str::random() . '.' . $avatar->getClientOriginalExtension();

            $avatar->storePubliclyAs($path, $name);

            $image = Image::make($avatar);

            $sizes = [
                'md' => [
                    $image->getWidth() / 2,
                    $image->getHeight() / 2,
                ],
                'l' => [
                    $image->getWidth() / 4,
                    $image->getHeight() / 4,
                ]
            ];

            foreach ($sizes as $key => $size) {
                $image
                    ->resize(...$size)
                    ->save(storage_path('app/' . $path) . '/' . $key . '_' . $name);
            }

            $user->avatar = $name;
            Session::flash('message', 'Фото профиля еспешно обновлеоено');
            $user->save();
        }

        $user->save();
        return redirect()->route('cabinet.profile');
    }
}
