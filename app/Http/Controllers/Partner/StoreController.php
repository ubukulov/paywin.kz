<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\PartnerWarehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function index()
    {
        $storePoints = PartnerWarehouse::where(['user_id' => Auth::id()])->get();
        return view('partner.store.index', compact('storePoints'));
    }

    public function create()
    {
        $cities = City::all();
        return view('partner.store.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::id();
        PartnerWarehouse::create($data);
        return redirect()->route('partner.store.index');
    }
}
