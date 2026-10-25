<?php

namespace App\Services;

use App\Models\City;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Cache;
class CityService
{
    public function getCities()
    {
        return Cache::remember('app_cities', now()->addDay(), function () {
            return City::all();
        });
    }

    public function getCurrentCity()
    {
        $cities = $this->getCities();
        $cityId = Cookie::get('selected_city_id');

        return $cityId
            ? $cities->firstWhere('id', $cityId)
            : $cities->first();
    }
}
