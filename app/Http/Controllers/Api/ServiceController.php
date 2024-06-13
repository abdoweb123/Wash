<?php

namespace App\Http\Controllers\Api;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\CompanyService;
use App\Functions\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\CompanyServiceResource;
use App\Http\Resources\CompanyServiceShowResource;

class ServiceController extends Controller
{
    public function index()
    {
        $data = [
            'services' => ServiceResource::collection(Service::get()),
        ];


        return ResponseHelper::make($data);
    }

    public function show($service_id)
    {
        $auth_id = auth('sanctum')->id() ?? null;
        $check = Service::find($service_id);
        if(!$check){
            return ResponseHelper::make(null, 'dev:id not found', false);
        }

        $companies = CompanyService::where('service_id', $service_id)->with('service', 'standard')
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

    public function CompanyServiceShow($company_service_id)
    {
        $auth_id = auth('sanctum')->id() ?? null;

        $c_service = CompanyService::where('id', $company_service_id)->with('service', 'standard')
        ->with('company', function($q){
            $q->withAvg('reviews', 'star')
            ->withCount('reviews');
        })
        ->first();
        if(!$c_service){
            return ResponseHelper::make(null, 'dev:id not found', false);
        }
        $data = [
            'companies' => CompanyServiceShowResource::make($c_service),
        ];

        return ResponseHelper::make($data);
    }
}
