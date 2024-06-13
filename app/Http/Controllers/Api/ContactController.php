<?php

namespace App\Http\Controllers\Api;

use PDO;
use App\Models\Contactus;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Functions\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index()
    {
        $points = setting('google_map_link') ? getMapPoint(setting('google_map_link')) : [];

        $data = [
            'phone' => setting('phone'),
            'fax' => setting('fax'),
            "lat" => $points["lat"] ?? '',
            "long" => $points["long"] ?? '',
        ];

        return ResponseHelper::make($data);
    }

    public function store(Request $request)
    {
        if (!auth('sanctum')->check()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'phone' => 'required',
                'subject' => 'required|max:255',
                'message' => 'required|max:1000|min:3'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'subject' => 'required|max:255',
                'message' => 'required|max:1000|min:3'
            ]);
        }
        
        if ($validator->fails()) {
            return ResponseHelper::make($validator->errors(), 'validation error', false, 200);
        }
        
        $user = auth('sanctum')->user();
        $contact = new Contactus();
        $contact->name = $user ? $user->name : $request->name;
        $contact->phone =  $user ? $user->name : $request->name;
        $contact->subject = $request->subject;
        $contact->message = $request->message;
        $contact->save();

        $notification = new Notification();
        $notification->title_ar = 'تواصل معنا';
        $notification->title_en = 'Contact us';
        $notification->from = $contact->name;
        $notification->body_ar = 'هناك رسالة جديدة قادمة من صفحة تواصل معنا';
        $notification->body_en = 'There is a new message coming from our contact page';
        $notification->link = route('dashboard.contacts');
        $notification->save();

        return ResponseHelper::make(null, __('dash.sended_successfully'));
    }
}
