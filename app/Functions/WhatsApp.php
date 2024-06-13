<?php

namespace App\Functions;

class WhatsApp
{
    public static function SendOTP($phone)
    {
        $otp = rand(100000, 999999);

        $body = '';
        $body .= '%0a *Wash* %0a';
        $body .= '%0a *Your Verification Code Is* ' . $otp . ' %0a';
        $body .= '%0a Powered By *Emcan Solutions*';

        self::SendWhatsApp($phone, $body);

        return $otp;
    }
    public static function SendMassege($phone, $approval)
    {

        if ($approval == 1) {
            $body = '';
            $body .= '%0a *Wash* %0a';
            $body .= '%0a *Your Account Is Approved* %0a';
            $body .= '%0a Powered By *Emcan Solutions*';
        } else {
            $body = '';
            $body .= '%0a *Wash* %0a';
            $body .= '%0a *Your Account Is Not Approved* %0a';
            $body .= '%0a Powered By *Emcan Solutions*';
        }


        self::SendWhatsApp($phone, $body);

    }

    public static function GetToken()
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://emcan.bh/api/UltraCredentials',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS => 'token=zuvzajw7goMh20q5YVu0&domain='.env('APP_URL'),
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => [
                'content-type: application/x-www-form-urlencoded',
            ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        return json_decode($response);
    }

    public static function SendWhatsApp($numbers, $message)
    {
        $EmcanWhats = self::GetToken();
        $instance = $EmcanWhats->instance;
        $token = $EmcanWhats->token;
        if ($EmcanWhats->active) {
            $numbers = is_array($numbers) ? $numbers : [$numbers];
            foreach ($numbers as $number) {
                $number = str_replace('++', '+', $number);
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => 'https://api.ultramsg.com/'.$instance.'/messages/chat',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => "token=$token&to=$number&body=$message&priority=1&referenceId=",
                    CURLOPT_HTTPHEADER => [
                        'content-type: application/x-www-form-urlencoded',
                    ],
                ]);
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
            }
        }
    }
}
