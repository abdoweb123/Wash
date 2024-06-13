<?php

namespace App\Http\Controllers\Api;

use App\Functions\ResponseHelper;
use Carbon\Carbon;
use App\Models\WorkTime;
use App\Models\Cart;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WorktimeController extends Controller
{
    public function get_times(Request $request)
    {
         if ($this->isArabicDate($request->date)) {
            $englishDate = $this->convertArabicDateToEnglish($request->date);
        } else {
            $englishDate = $request->date; // Assuming it's already in the correct format
        }
       
        $request->merge(['date' => $englishDate]);
     
        $request->validate([
            'date' => 'required|date|date_format:Y-m-d',
        ]);

        
        $cart = Cart::where('user_id', auth('sanctum')->id())->first();
        if($cart){
            $company_id = $cart->company_service->company_id;
            $day_en = Carbon::parse($request->date)->format('l');
            $worktime = WorkTime::where('company_id', $company_id)->where('day', Str::upper($day_en))->first();
            if($worktime){
                $data = ['times' => getAvailableHours($worktime->from, $worktime->to)];
    
                return ResponseHelper::make($data);    
            }else{
                return ResponseHelper::make(null);    
            }

        }else{
            return ResponseHelper::make(null, 'cart is empty');    
        }
    }
        public function isArabicDate($date)
    {
        // Arabic-Indic numerals
        $arabicIndicNumerals = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

        // Check if the date contains any Arabic-Indic numerals
        foreach ($arabicIndicNumerals as $numeral) {
            if (strpos($date, $numeral) !== false) {
                return true;
            }
        }
        return false;
    }
      public function convertArabicDateToEnglish($arabicDate)
    {
        // Define Arabic-Indic numeral replacements
        $numerals = [
            '٠' => '0',
            '١' => '1',
            '٢' => '2',
            '٣' => '3',
            '٤' => '4',
            '٥' => '5',
            '٦' => '6',
            '٧' => '7',
            '٨' => '8',
            '٩' => '9'
        ];

        // Replace Arabic-Indic numerals with English numerals
        $englishDate = str_replace(array_keys($numerals), array_values($numerals), $arabicDate);

        // Parse the date using Carbon
        $date = Carbon::createFromFormat('Y-m-d', $englishDate);

        // Format the date in the desired format
        return $date->format('Y-m-d');
    }
}
