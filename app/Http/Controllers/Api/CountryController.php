<?php

namespace App\Http\Controllers\Api;

use App\Functions\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CountriesResource;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::orderByRaw("FIELD(id, 2,1,3,4,5,6,7)")->select('id', 'phone_code', 'image', 'length', 'title_en', 'title_ar')->get();

        return ResponseHelper::make(CountriesResource::collection($countries));
    }
}
