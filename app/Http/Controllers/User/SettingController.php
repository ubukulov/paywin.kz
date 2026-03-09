<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function profile()
    {
        $user_profile = Auth::user()->profile;
        return view('user.profile', compact('user_profile'));
    }

    public function profileUpdate(Request $request)
    {
        $this->userService->updateProfile(Auth::user(), $request->all());

        return redirect()->route('user.cabinet')->with('success', 'Успешно обновлено');
    }

    public function passwordChangeForm()
    {
        return view('user.change_password');
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        $this->userService->changePassword(Auth::user(), $request->new_password);

        return redirect()->route('user.settings')->with('success', 'Пароль изменен');
    }
}
