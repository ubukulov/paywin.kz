<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Share;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;

class ShareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shares = Share::where(['user_id' => Auth::id()])
            ->whereDate('to_date', '>=', date('Y-m-d'))
            ->get();
        return view('partner.shares.index', compact('shares'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('partner.shares.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $data['from_date'] = date('Y-m-d H:i:s', strtotime($data['from_date']));
        $data['to_date'] = date('Y-m-d H:i:s', strtotime($data['to_date']));
        $data['promo'] = (isset($data['discount_or_money']) && $data['discount_or_money'] == 'on') ? 'money' : 'discount';
        $data['promo'] = ($data['type'] == 'promocode') ? $data['promo'] : 'none';
        Share::create($data);
        return redirect()->route('partner.my-shares.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $share = Share::findOrFail($id);
        return view('partner.shares.edit', compact('share'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $share = Share::findOrFail($id);
        $share->update($request->all());
        return redirect()->route('partner.my-shares.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
