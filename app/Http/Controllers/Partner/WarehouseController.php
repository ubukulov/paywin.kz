<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\PartnerWarehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = PartnerWarehouse::where(['partner_id' => Auth::id()])->get();
        return view('partner.warehouse.index', compact('warehouses'));
    }

    public function create()
    {
        $cities = City::all();
        return view('partner.warehouse.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['partner_id'] = Auth::id();
        PartnerWarehouse::create($data);
        return redirect()->route('partner.warehouse.index');
    }
}
