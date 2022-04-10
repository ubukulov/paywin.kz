<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $partners = User::where(['user_type' => 'partner'])
            ->join('user_profile', 'user_profile.user_id', 'users.id')
            ->whereNotNull('user_profile.category_id')
            ->get();
        return view('category.partners', compact('partners', 'slug'));
    }

    public function showPartner($slug, $id)
    {
        $partner = User::findOrFail($id);
        $profile = $partner->profile;
        return view('category.partner', compact('partner', 'slug', 'id', 'profile'));
    }
}
