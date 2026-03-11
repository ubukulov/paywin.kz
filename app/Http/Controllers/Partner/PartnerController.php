<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\PartnerProfile;
use App\Models\Payment;
use App\Models\PartnerAddress;
use App\Models\PartnerImage;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartnerController extends Controller
{
    public function cabinet()
    {
        $partner = Auth::user();
        $partnerProfile = Auth::user()->partnerProfile;
        return view('partner.home', compact('partnerProfile', 'partner'));
    }

    public function qr()
    {
        $partnerProfile = Auth::user()->partnerProfile;
        return view('partner.qr', compact('partnerProfile'));
    }

    public function clients()
    {
        $partnerId = auth()->id();

        // Получаем уникальных клиентов через таблицу транзакций
        $clients = User::whereIn('id', function($query) use ($partnerId) {
            $query->select('source_id')
                ->from('transactions')
                ->where('user_id', $partnerId)
                ->where('source_type', User::class); // Важно: только транзакции, связанные с юзерами
        })
            ->withCount(['transactions as total_spent' => function($query) use ($partnerId) {
                // Считаем общую сумму покупок этого клиента у этого партнера
                $query->where('user_id', $partnerId)
                    ->where('type', 'sale_income'); // Тип транзакции "Покупка"
            }])
            ->paginate(20);

        return view('partner.clients', compact('clients'));
    }

    public function edit()
    {
        $partnerProfile = Auth::user()->partnerProfile;
        $categories = Category::all();
        return view('partner.edit', compact('partnerProfile', 'categories'));
    }

    public function profileUpdate(Request $request)
    {
        $data = $request->all();
        $partnerProfile = PartnerProfile::where(['partner_id' => $data['partner_id']])->first();
        if ($partnerProfile) {

            if ($request->hasFile('logo')) {
                if(!empty($partnerProfile->logo) && file_exists(public_path() . $partnerProfile->logo)) {
                    unlink(public_path() . $partnerProfile->logo);
                }

                $request->validate([
                    'logo' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
                ]);

                $file = $request->file('logo');
                $ext = $file->getClientOriginalExtension();
                $name = md5(time()) . '.' . $ext;
                $path = '/upload/partners/';
                $dir = public_path() . $path;
                $file->move($dir, $name);
                $data['logo'] = $path.$name;
            }

            if ($request->hasFile('agreement')) {
                if(!empty($partnerProfile->agreement) && file_exists(public_path() . $partnerProfile->agreement)) {
                    unlink(public_path() . $partnerProfile->agreement);
                }

                $file = $request->file('agreement');
                $ext = $file->getClientOriginalExtension();
                $name = md5(time()) . '.' . $ext;
                $path = '/upload/partners/';
                $dir = public_path() . $path;
                $file->move($dir, $name);
                $data['agreement'] = $path.$name;
            }

            $partnerProfile->update($data);

            return redirect()->route('partner.cabinet');
        }
    }

    public function addressCreate()
    {
        return view('partner.address.create');
    }

    public function addressStore(Request $request)
    {
        $data = $request->all();
        $data['partner_id'] = Auth::user()->id;
        PartnerAddress::create($data);
        return redirect()->route('partner.cabinet');
    }

    public function imageCreate()
    {
        return view('partner.image');
    }

    public function imageStore(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();
        foreach($data['images'] as $img) {
            $ext = $img->getClientOriginalExtension();
            $name = md5(time()) . '.' . $ext;
            $path = '/upload/partners/images/';
            $dir = public_path() . $path;
            $img->move($dir, $name);
            $data['partner_id'] = $user->id;
            $data['image'] = $path.$name;
            PartnerImage::create($data);
        }

        return redirect()->route('partner.cabinet');
    }

    public function imageLists()
    {
        $images = PartnerImage::where(['user_id' => Auth::user()->id])->get();
        return view('partner.images', compact('images'));
    }

    public function imageDelete($id)
    {
        $image = PartnerImage::findOrFail($id);
        $image->delete();
        return redirect()->route('partner.cabinet');
    }
}
