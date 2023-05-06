<?php

namespace App\Http\Controllers\Api\v1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\City\CityResource;
use App\Http\Resources\V1\Province\ProvinceResource;
use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceAndCityController extends Controller
{
    public function province(){
//        $province=Province::all()->orderBy('name', 'ASC')->get();
        $province=Province::all();
       return ProvinceResource::collection($province);
    }
    public function cities($id){
        $province = Province::whereId($id)->first();
        if (!$province) {
            return response([
                'message' => "یافت نشد",
                'status' => 'success'
            ], 400);
        }
        $cities=$province->cities;
       return CityResource::collection($cities);
    }
}
