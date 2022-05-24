<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class SettingController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $user_profile = $user->profile;
        return view('user.profile', compact('user_profile'));
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();
        $user_profile = $user->profile;
        $user_profile->update($request->all());
        return redirect()->route('user.cabinet');
    }

    public function passwordChangeForm()
    {
        return view('user.change_password');
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'new_password' => 'min:4|required_with:confirm_new_password|same:confirm_new_password',
            'confirm_new_password' => 'required|min:4',
        ]);

        $user = Auth::user();
        $user->password = bcrypt($request->input('new_password'));
        $user->save();
        return redirect()->route('user.settings');
    }
}
