<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use App\Models\Region;
use App\Models\Address;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Functions\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Requests\AddressRequest;
use App\Http\Resources\RegionResource;
use App\Http\Resources\AddressResource;

class AddressController extends Controller
{
    public function getRegions()
    {
        $country = Country::find(2);
        if(!$country){
            return ResponseHelper::make(null, 'dev:id not found');
        }
        $data = [
            'regions' => RegionResource::collection(Region::where('country_id', $country->id)->get())
        ];

        return ResponseHelper::make($data);
    }

    public function getCities($region_id)
    {
        $region = Region::find($region_id);
        if(!$region){
            return ResponseHelper::make(null, 'dev:id not found');
        }
        $data = [
            'cities' => CityResource::collection(City::where('region_id', $region_id)->get())
        ];

        return ResponseHelper::make($data);
    }

    public function storeAddress(AddressRequest $request)
    {
        $addresses = Address::where('user_id', auth('sanctum')->id())->get();
        foreach ($addresses as $key => $address) {
            $address->is_active = false;
            $address->save();
        }

        $address = new Address();
        $address->user_id = auth('sanctum')->id();
        $address->region_id = $request->region_id;
        $address->block = $request->block;
        $address->road = $request->road;
        $address->floor_no = $request->floor_no;
        $address->appartment_no = $request->appartment_no;
        $address->note = $request->note;
        $address->lat = $request->lat;
        $address->long = $request->long;
        $address->is_active = true;
        $address->save();

        return ResponseHelper::make($address, __('dash.alert_add'));
    }

    public function getUserAddress()
    {
        $address = Address::where('user_id', auth('sanctum')->id())->get();

        $data = [
            'addresses' => AddressResource::collection($address)
        ];

        return ResponseHelper::make($data);
    }

    public function delete($address_id)
    {
        $address = Address::find($address_id);
        if(!$address){
            return ResponseHelper::make(null, 'dev: id not found', false);
        }
        $address->delete();
        return ResponseHelper::make(null, __('dash.alert_delete'));
    }

    public function active_address($address_id)
    {
        $main_address = Address::find($address_id);
        if(!$main_address){
            return ResponseHelper::make(null, 'dev: id not found', false);
        }

        $addresses = Address::where('user_id', auth('sanctum')->id())->get();
        foreach ($addresses as $key => $address) {
            $address->is_active = false;
            $address->save();
        }

        $main_address->is_active = true;
        $main_address->save();

        return ResponseHelper::make(null, __('dash.alert_update'));
    }

}
