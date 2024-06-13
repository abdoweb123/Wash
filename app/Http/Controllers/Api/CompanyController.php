<?php

namespace App\Http\Controllers\Api;

use App\Models\Token;
use App\Models\Admin;
use App\Models\Company;
use App\Functions\Upload;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Functions\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'country_code' => 'required|exists:countries,phone_code',
            'password' => 'required',
            'device_type' => 'required',
            'device_token' => 'required',
        ]);

        if ($validator->fails()) {
            $data = [
                'errors' => $validator->errors()
            ];
            return ResponseHelper::make($data, null, false);
        }
        $this->checkPhoneLength($request->country_code, $request->phone);

        $company = Admin::where('phone', $request->country_code . $request->phone)->where('company_id', '!=', null)->first();
        // dd($company);
        if (!$company || !Hash::check($request->password, $company->password)) {
            return ResponseHelper::make(null, __('auth.login_faild'), false, 200);
        }

        if ($company->is_approved == 0) {
            if (isset($request->lang)) {
                if ($request->lang == "ar") {
                    $data = [
                        'message' => [
                            "لم يتم الموافقة على طلبك بعد - سيتم تزويدك برسالة عبر الواتساب حال الموافقة على طلبك "
                        ]
                    ];
                } else {
                    $data = [
                        'message' => [
                            "After your request - you will be provided with a message via WhatsApp once your request is approved"
                        ]
                    ];
                }
            } else {
                $data = [
                    'message' => [
                        "لم يتم الموافقة على طلبك بعد - سيتم تزويدك برسالة عبر الواتساب حال الموافقة على طلبك "
                    ]
                ];
            }

            $admin_id = Admin::where('id', $company->id)->first()->id;

            $token = Token::where('device_token', $request->device_token)->where('admin_id', $admin_id)->first();
            if (!$token) {

                Token::create([
                    'admin_id' => $admin_id,
                    'device_type' => $request->device_type,
                    'device_version' => $request->device_version,
                    'device_token' => $request->device_token,
                    "lang" => isset($request->lang) ? $request->lang : "ar"
                ]);
            } else {
                $lang = isset($request->lang) ? $request->lang : "ar";
                if ($token->lang != $lang) {
                    $token->update([
                        'lang' => $lang
                    ]);
                }
            }
        } else {
            $data = [
                'company' => $company,
                'token' => $company->createToken("API TOKEN")->plainTextToken
            ];
            $admin_id = Admin::where('id', $company->id)->first()->id;
            $token = Token::where('device_token', $request->device_token)->where('admin_id', $admin_id)->first();
            if (!$token) {

                Token::create([
                    'admin_id' => $admin_id,
                    'device_type' => $request->device_type,
                    'device_version' => $request->device_version,
                    'device_token' => $request->device_token,
                    "lang" => isset($request->lang) ? $request->lang : "ar"
                ]);
            } else {
                $lang = isset($request->lang) ? $request->lang : "ar";
                if ($token->lang != $lang) {
                    $token->update([
                        'lang' => $lang
                    ]);
                }
            }
        }

        return ResponseHelper::make($data);
    }

    public function logout(Request $request) //auth
    {
        $request->validate([
            'device_token' => 'required'
        ]);
        $token = Token::where('device_token', $request->device_token)->where('user_id', null)->first();
        if ($token) {
            $token->delete();
        }
        auth('sanctum')->user()->currentAccessToken()->delete();

        return ResponseHelper::make(null, 'user loged out successfully', true, 200);
    }
    public function register(Request $request)
    {
        $request->request->add(['newphone' =>$request->country_code . $request->phone]);
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'title_ar' => 'required|min:3|max:255',
            'title_en' => 'required|min:3|max:255',
            'disc_ar' => 'required|min:3|max:5000',
            'disc_en' => 'required|min:3|max:5000',
            'logo' => 'required|image',
            'country_code' => 'required|exists:countries,phone_code',
            'google_map_link' => 'required',
            'email' => 'required|email|unique:admins,email',
            'newphone'=>"unique:admins,phone",
            'phone' => 'required',
            'password' => 'required',
        ]);
        // $validator->after(function ($validator) {
        //     if ($validator->errors()->has('newphone')) {
        //         $validator->errors()->add('phone', $validator->errors()->first('newphone'));
        //         $validator->errors()->forget('newphone');
        //     }
        // });
        if ($validator->fails()) {
            $data = [
                'errors' => $validator->errors()
            ];
            return ResponseHelper::make($data, null, false);
        }
        $company = new Company();
        $company->title_ar = $request->title_ar;
        $company->title_en = $request->title_en;
        $company->disc_ar = $request->disc_ar;
        $company->disc_en = $request->disc_en;
        $company->phone = $request->country_code . $request->phone;
        $company->google_map_link = $request->google_map_link;
        $company->company_name = $request->company_name;
        $company->iban_number = $request->iban_number;
        $company->bank_name = $request->bank_name;
        $company->beneficiary_name = $request->beneficiary_name;
        $company->logo = Upload::UploadFile($request->logo, 'companies');
        $company->save();

        $admin = new Admin();
        $admin->name = $request->title_en;
        $admin->phone = $request->country_code . $request->phone;
        $admin->email = $request->email;
        $admin->password = bcrypt($request->password);
        $admin->company_id = $company->id;
        $admin->save();

        if (isset($request->lang)) {
            if ($request->lang == "ar") {
                $data = [
                    'message' => [
                        "لم يتم الموافقة على طلبك بعد - سيتم تزويدك برسالة عبر الواتساب حال الموافقة على طلبك "
                    ]
                ];
            } else {
                $data = [
                    'message' => [
                        "After your request - you will be provided with a message via WhatsApp once your request is approved"
                    ]
                ];
            }
        } else {
            $data = [
                'message' => [
                    "لم يتم الموافقة على طلبك بعد - سيتم تزويدك برسالة عبر الواتساب حال الموافقة على طلبك "
                ]
            ];
        }
        Token::create([
            'admin_id' => $admin->id,
            'device_type' => $request->device_type,
            'device_version' => $request->device_version,
            'device_token' => $request->device_token,
            "lang" => isset($request->lang) ? $request->lang : "ar"
        ]);

        // dd($request->all());

        return ResponseHelper::make($data);
    }

    ////////////////////////////////////////////////////////////////////////////////////
    private static function checkPhoneLength($country_code, $phone)
    {
        $country = Country::where('phone_code', $country_code)->first();

        if ($phone && strlen($phone) != $country->length) {
            $data = [
                'phone' => ['The phone number must be ' . $country->length . ' digits only.']
            ];
            return ResponseHelper::make($data, 'Validation errors', false, 200);
        }

    }
    private static function saveTokens($admin_id, $device_type, $device_token)
    {
        $token = new Token();
        $token->admin_id = $admin_id;
        $token->device_type = $device_type;
        $token->device_token = $device_token;
        $token->save();
    }
}
