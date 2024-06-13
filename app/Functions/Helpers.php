<?php

use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\Setting;
use Illuminate\Support\Facades\File;
// use Modules\Country\Entities\Country;
use App\Models\Country;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

function lang($lang = null)
{
    if (isset($lang)) {
        return app()->islocale($lang);
    } else {
        return app()->getlocale();
    }
}

function RemoveFirstSlash($String)
{
    if (mb_substr($String, 0, 1) == '/') {
        return substr($String, 1);
    }else{
        return $String;
    }
}

function amount_format($amount)
{
    return number_format($amount, 3, '.', '');
}

function Admin($lang = null)
{
    if (! Config::get('Admin')) {
        Config::set('Admin', auth()->user());
    }

    return Config::get('Admin');
}

function Countries()
{
    if (!Config::get('Countries')) {
        Config::set('Countries', Country::where('status',1)->get());
    }

    return Config::get('Countries');
}

function Settings()
{
    Session::put('Settings', Setting::get());

    // if (! Session::get('Settings')) {
    //     Session::put('Settings', Setting::get());
    // }
    return Session::get('Settings');
}

function setting($key)
{
    return Settings()->where('key', $key)->first()->value ?? null;
}

function DT_Lang()
{
    if (lang('ar')) {
        return '//cdn.datatables.net/plug-ins/1.10.16/i18n/Arabic.json';
    } else {
        return '//cdn.datatables.net/plug-ins/1.10.16/i18n/English.json';
    }
}

function image_path($file)
{
    if ($file && file_exists(public_path($file))) {
        return $file;
    }

    return setting('logo');
}

function delete_files($path)
{
    $dirs = File::directories(public_path($path));
    foreach ($dirs as $dir) {
        $arr = [];
        $max = '';
        $files = File::files($dir);
        if (count($files) > 1) {
            foreach ($files as $string) {
                if (str_contains($string, '.png') || str_contains($string, '.jpg') || str_contains($string, '.jpeg') || str_contains($string, '.gif')) {
                    $arr[] = $string->getRelativePathname();
                }
            }
            $max = max($arr);
            foreach ($files as $string) {
                if (! str_contains($string, $max)) {
                    unlink($string);
                }
            }
        }
    }
    dd('Done');
}

function MergeArrays(array $input)
{
    $new_arr = [];
    $keys = array_keys($input);
    foreach ($input[$keys[count($keys) - 1]] as $key => $value) {
        foreach ($keys as $array_key => $value) {
            $new_arr[$key][$keys[$array_key]] = is_array($input[$keys[$array_key]]) ? $input[$keys[$array_key]][$key] : $input[$keys[$array_key]];
        }
    }

    return $new_arr;
}

function times()
{
    return  ["00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00"];
}

function costformat($cost)
{
//    return number_format($cost, 2);
    return number_format($cost);
}

function DayesNames()
{
    return ['SATURDAY', 'SUNDAY', 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY'];
}

function getAvailableHours(string $from, string $to)
{
    $fromTime = Carbon::parse($from);
    $toTime = Carbon::parse($to);

    $availableHours = [];

    while ($fromTime <= $toTime) {
        $availableHours[] = $fromTime->format('h:i A');

        $fromTime->addHour();
    }

    return $availableHours;
}


function getDayNum($day)
{
    foreach (DayesNames() as $key => $value) {
        if(strtolower($day) == strtolower($value)){
            return $key + 1;
        }
    }
}

function getMapPoint($link)
{
    $isLongLink = isLongLink($link);

    if(!$isLongLink){
        $client = new Client();

        $response = $client->head($link, [
            'allow_redirects' => [
                'track_redirects' => true
            ]
        ]);

        $link = $response->getHeaderLine('X-Guzzle-Redirect-History');

    }

        $pattern = '/@(-?\d+\.\d+),(-?\d+\.\d+)/';
        preg_match($pattern, $link, $matches);

        $latitude = $matches[1];
        $longitude = $matches[2];

        $coordinates = [
            'lat' => $latitude,
            'long' => $longitude
        ];


    return $coordinates;
}

function isLongLink($link)
{
    return strpos($link, '@') !== false;
}

function culcPercent($total, $percent)
{
    $discount = $total * ( $percent / 100);
    return $total - $discount;
}



