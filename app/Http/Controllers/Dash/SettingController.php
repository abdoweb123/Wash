<?php

namespace App\Http\Controllers\Dash;

use App\Functions\Upload;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::get();
        
        return view('admin.setting', compact('settings'));
    }

    public function store(Request $request)
    {
        foreach (Setting::get() as $setting) {
            $req_key = $setting->key;
            if($setting->type == 'image'){
                if($request[$req_key] != null){
                    $setting->value = Upload::UploadFile($request[$req_key], 'setting');
                }
            }else{
                $setting->value = $request[$req_key] ?? '';
            }
            $setting->save();
        }

        return back()->with('success', __('dash.alert_update'));
    }
}
