<?php

namespace App\Http\Controllers\Api;

use App\Models\Favorate;
use Illuminate\Http\Request;
use App\Models\CompanyService;
use App\Functions\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyServiceResource;

class FavorateController extends Controller
{
    public function index()
    {
        $auth_id = auth('sanctum')->id() ?? null;
        $favs_ids = Favorate::where('user_id', auth('sanctum')->id())->pluck('company_service_id');

        $companies = CompanyService::whereIn('id', $favs_ids)->with('service', 'standard')
        ->with('company', function($q){
            $q->withAvg('reviews', 'star')
            ->withCount('reviews');
        })
        ->with('favorate', function($q) use ($auth_id){
            $q->where('user_id', $auth_id);
        })

        ->paginate(10);
        $data = [
            'companies' => CompanyServiceResource::collection($companies)->response()->getData(true),
        ];

        return ResponseHelper::make($data);
    }

    public function storeOrRemove(Request $request)
    {
        $request->validate([
            'company_service_id' => 'required|exists:company_services,id'
        ]);

        $exist = Favorate::where('company_service_id', $request->company_service_id)
            ->where('user_id', auth('sanctum')->id())
            ->first();

        if($exist){
            $exist->delete();
            return ResponseHelper::make(null, __('dash.alert_delete'));
        }else{
            $fav = new Favorate();
            $fav->user_id = auth('sanctum')->id();
            $fav->company_service_id = $request->company_service_id;
            $fav->save();

            return ResponseHelper::make(null, __('dash.alert_add'));
        }
    }


}
