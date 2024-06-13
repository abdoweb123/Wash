<?php

namespace App\Http\Controllers\Api;

use App\Models\Slide;
use Illuminate\Http\Request;
use App\Functions\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\SlidesResource;
use App\Http\Resources\SocialResource;
use App\Models\Service;
use App\Models\Sidepage;
use App\Models\Social;
use App\Models\Token;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $slides = Slide::get();
        $services = Service::get();
        $data = [
            "slides" => SlidesResource::collection($slides),
            'services' => ServiceResource::collection($services)
        ];

        return ResponseHelper::make($data);
    }
    public function appLanguage(Request $request)
    {
        $request->validate([
            'device_token' => 'required',
            'device_type' => 'required'
        ]);
        $userType = Auth::user();
        // dd($admin);
        $lang = isset($request->lang) ? $request->lang : "ar";

        if ($userType instanceof \App\Models\User) {
            $token = Token::where('device_token', $request->device_token)->where("device_type", $request->device_type)->where('user_id', $userType->id)->first();
            $token->update([
                'lang' => $lang
            ]);
        } else {
            $token = Token::where('device_token', $request->device_token)->where("device_type", $request->device_type)->where('admin_id', $userType->id)->first();
            $token->update([
                'lang' => $lang
            ]);
        }
        $data = [
            'message' => [
                "language of application updated "
            ]
        ];
        return ResponseHelper::make($data);
    }
    public function about()
    {
        $data = [
            'about_us' => setting('about_paragraph_' . lang()),
        ];

        return ResponseHelper::make($data);
    }

    public function terms(Request $request)
    {

        $terms = Sidepage::where('key', 'terms')->where('user_type', $request->user_type)->first();

        $data = [
            'terms' => $terms['value_' . lang()] ?? null
        ];

        return ResponseHelper::make($data);
    }

    public function privacy(Request $request)
    {
        $privacy = Sidepage::where('key', 'privacy')->where('user_type', $request->user_type)->first();

        $data = [
            'privacy' => $privacy['value_' . lang()] ?? null
        ];

        return ResponseHelper::make($data);
    }

    public function version_checker(Request $request)
    {
        $request->validate([
            // 'device_token' => 'required',
            'device_version' => 'required',
            'device_type' => 'required'
        ]);
        // // dd(auth('sanctum')->user());
        // if(auth('sanctum')->check()){
        //     $token = Token::where('device_token', $request->device_token)->first();
        //     if(!$token){
        //         $token = new Token();
        //         $token->user_id = auth('sanctum')->id();
        //         $token->device_token = $request->device_token;
        //         $token->device_type = $request->device_type;
        //     }
        //     $token->device_version = $request->device_version;
        //     $token->save();    
        // }
        if ($request->device_type == "android") {
            $tokens = Token::where('device_type', "android")->get();
            if ($tokens->count() > 0) {
                foreach ($tokens as $token) {
                    $token->update([
                        'device_version' => $request->device_version
                    ]);
                }
            }
        } else {
            $tokens = Token::where('device_type', "ios")->get();
            if ($tokens->count() > 0) {
                foreach ($tokens as $token) {
                    $token->update([
                        'device_version' => $request->device_version
                    ]);
                }
            }
        }
        $data = [
            "app_lock" => setting('app_lock') ?? "0",
            "app_lock_msg" => __('dash.app_lock_msg'),
            "ios_version" => setting('ios_version') ?? "0",
            "android_version" => setting('android_version') ?? "0",
            "ios_link" => setting('apple_link') ?? "0",
            "android_link" => setting('google_link') ?? "0",
            "copyright_link" => "https://emcan-group.com/",
        ];

        ResponseHelper::make($data);
    }

    public function social_links(Request $request)
    {
        $socials = Social::where('user_type', $request->user_type)->get();
        $data = [
            'socials' => SocialResource::collection($socials)
        ];
        ResponseHelper::make($data);
    }
}
