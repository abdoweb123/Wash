<?php

namespace App\Functions;

use App\Models\Token;

class PushNotification
{
    public static function send($message, $data, $user_id = null, $type = "client")
    {
        $headers = [];
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key=' . 'AAAAN1x2iNo:APA91bHG0BzkqNLTjkSOZN1nkE2jXDUn8_Av4YuIOfstn1DPo0J2TIhGPyx59f4M9r5vYvCfWuGZZxxhGCcOvzqBkuKdms47-l0WLIBszq_EHuUFn62qGJ_oCeCuQwrc8jPY_ci7Sy7s';
        if (is_array($message)) {
            $tokens = Token::query()->when($type == 'admin', function ($query) use ($user_id) {
                return $query->where('admin_id', $user_id);
            })->when($type == 'client', function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })->get();
            $meesageAr=$message['ar'];
            $meesageEn=$message['en'];
            foreach ($tokens as $data) {
                $message=$data->lang == "ar" ?$meesageAr: $meesageEn;
                $notification = [
                    'to' => $data->device_token,
                    'notification' => [
                        'title' => env('APP_NAME'),
                        'body' => $message,
                        'sound' => 'default',
                        'badge' => '1',
                    ],
                    'priority' => 'high',
                    "data" => $data,
                    "content_available" => true
                ];
               
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification));
                $response = curl_exec($ch);
                // \Illuminate\Support\Facades\Log::info($response);
                // \Illuminate\Support\Facades\Log::info(1);
                if ($response === FALSE) {
                    \Illuminate\Support\Facades\Log::debug('FCM Send Error: ' . curl_error($ch));
                    die('FCM Send Error: ' . curl_error($ch));
                }
                curl_close($ch);
            }
        }else{
            $tokens = Token::query()->when($type == 'admin', function ($query) use ($user_id) {
                return $query->where('admin_id', $user_id);
            })->when($type == 'client', function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })->pluck('device_token');
    
            foreach ($tokens as $token) {
                $notification = [
                    'to' => $token,
                    'notification' => [
                        'title' => env('APP_NAME'),
                        'body' => $message,
                        'sound' => 'default',
                        'badge' => '1',
                    ],
                    'priority' => 'high',
                    "data" => $data,
                    "content_available" => true
                ];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification));
                $response = curl_exec($ch);
                // \Illuminate\Support\Facades\Log::info($response);
                \Illuminate\Support\Facades\Log::info(2);
                if ($response === FALSE) {
                    \Illuminate\Support\Facades\Log::debug('FCM Send Error: ' . curl_error($ch));
                    die('FCM Send Error: ' . curl_error($ch));
                }
                curl_close($ch);
            }
        }
       
    }
}
