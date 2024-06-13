<?php

namespace App\Http\Controllers\Api;

use App\Models\Favorate;
use Illuminate\Http\Request;
use App\Models\CompanyService;
use App\Functions\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyServiceResource;

class CompanyServicesController extends Controller
{
    public function index()
    {
        // dd(1);
        $auth_id = auth('sanctum')->id() ?? null;


        $companies = CompanyService::where("company_id", auth('sanctum')->user()->company_id)->select('id','service_id','standard_id','cleaning_materials_cost','price','disc_ar','disc_en')->with([
            
            'service' => function ($query) {
                $query->select('id', 'title_'.app()->getlocale().' as title');
            },
            'standard' => function ($query) {
                $query->select('id', 'singular_title_'.app()->getlocale().' as singular_title', 'plural_title_'.app()->getlocale().' as plural_title');
            },
        ])->get();


        return ResponseHelper::make($companies);
    }
    
    public function store(Request $request)
    {
        // dd(auth('sanctum')->user()->id);
        CompanyService::create([
            'company_id' =>  auth('sanctum')->user()->company_id,
        ] + $request->only('service_id','standard_id','cleaning_materials_cost','price','disc_ar','disc_en'));


        return ResponseHelper::make(NULL,__('dash.alert_add'));
    }
    
    
    public function show($id)
    {
        $auth_id = auth('sanctum')->id() ?? null;


        $companies = CompanyService::select('id','service_id','standard_id','cleaning_materials_cost','price','disc_ar','disc_en')->with([
          
            'service' => function ($query) {
                $query->select('id', 'title_'.app()->getlocale().' as title');
            },
            'standard' => function ($query) {
                $query->select('id', 'singular_title_'.app()->getlocale().' as singular_title', 'plural_title_'.app()->getlocale().' as plural_title');
            },
        ])->findorfail($id);


        return ResponseHelper::make($companies);
    }

    public function update($id , Request $request)
    {

        CompanyService::where('id',$id)->update($request->only('service_id','standard_id','cleaning_materials_cost','price','disc_ar','disc_en'));


        return ResponseHelper::make(NULL,__('dash.alert_update'));
    }
    
    public function destroy($id , Request $request)
    {

        CompanyService::where('id',$id)->delete();


        return ResponseHelper::make(NULL,__('dash.alert_delete'));
    }

}
