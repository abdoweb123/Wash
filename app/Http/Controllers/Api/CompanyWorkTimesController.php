<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\WorkTime;
use App\Models\Company;
use App\Models\Admin;
use App\Models\Day;
use App\Functions\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\WorkTimeResource;

class CompanyWorkTimesController extends Controller
{
    public function index()
    {
        $company_id=Admin::where('id',auth('sanctum')->id())->first()->company_id;
        // dd($company_id);
        $companies = WorkTime::select('id','company_id','day','day_num','from','to')->where('company_id',$company_id)->get();
        // dd($companies);
        // dd(auth('sanctum')->id());
        $days = Day::get();
        
        $success = [];
        
        foreach($days as $day){
            $times = [];
            $items = $companies->where('day_num', $day->day_num)->first();
            
            // foreach($items as $item){
            //     $times[] = ['from' => $item->from, 'to' => $item->to];
            // }
            
            $success[] = [
                'id' => $items?->id,
                'day' => $day->title_en,
                'hours' => ['from' => $items?->from, 'to' => $items?->to],
                'active' => $items ? true : false
            ];
        }
        
        return ResponseHelper::make($success);
    }
    
    public function store(Request $request)
    {
       
        if($request->from >= $request->to){
            if($request->lang == "en"){
                $data[] = [
                    'errors' => "End time must be grater than start time"
                ];
            }else{
                $data[] = [
                'errors' => "الوقت النهائي يجب ان يكون اكبر من الوقت الابتدائي"
               ];
            }
            
            return ResponseHelper::make($data, null, false);
        }else{
            if($request->time_id){
                // dd(1);
                $time = WorkTime::find($request->time_id);
            }else{
                $time = new WorkTime();
            }
            $company_id=Admin::where('id',auth('sanctum')->id())->first()->company_id;
    
            $time->company_id =$company_id;
            $time->day = $request->day;
            $time->day_num = getDayNum($request->day);
            $time->from = $request->from;
            $time->to = $request->to;
            $time->save();
            
            return ResponseHelper::make(NULL,__('dash.alert_add'));
        
        }
       

        
    }
    
    
    public function show($id)
    {
        $companies = WorkTime::select('id','day','day_num','from','to')->where('company_id',auth('sanctum')->id())->findorfail($id);

        return ResponseHelper::make($companies);
    }

    public function update($id , Request $request)
    {
        if($request->from >= $request->to){
            if($request->lang == "en"){
                $data[] = [
                    'errors' => "End time must be grater than start time"
                ];
            }else{
                $data[] = [
                'errors' => "الوقت النهائي يجب ان يكون اكبر من الوقت الابتدائي"
               ];
            }
            
            return ResponseHelper::make($data, null, false);
             
         }else{
                WorkTime::where('id',$id)->update($request->only('day','day_num','from','to'));
        
        
                return ResponseHelper::make(NULL,__('dash.alert_update'));
         }
      
    }
    
    public function destroy($id , Request $request)
    {
         

        WorkTime::where('id',$id)->delete();


        return ResponseHelper::make(NULL,__('dash.alert_delete'));
    }

}
