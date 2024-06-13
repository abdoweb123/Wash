<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Token;
use App\Models\Serial;
use App\Models\Country;
use App\Models\UserOtp;
use App\Functions\WhatsApp;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Functions\ResponseHelper;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePassRequest;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\SaveOtpRequest;
use App\Http\Requests\CheckOtpRequest;
use App\Http\Requests\CheckPhoneRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\RegistrationVerifyRequest;
use App\Http\Requests\sendWhatsappOtpRequest;
use App\Models\ChangePasswordToken;
use Illuminate\Auth\Notifications\ResetPassword;

class AuthUserController extends Controller
{

    public function login(LoginRequest $request)
    {
        $this->checkPhoneLength($request->country_code, $request->phone);

        if(Auth::attempt(['phone' => $request->country_code . $request->phone, 'password' => $request->password])){
            $user = auth('sanctum')->user();
            $token = Token::where('device_token', $request->device_token)->where('user_id',$user->id)->first();
            if(!$token){
                $this->saveTokens($user->id, $request->device_type, $request->device_token);
            }
            $data = [
                'user' => $user,
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ];
            return ResponseHelper::make($data);
        }

        $data = [
            'msg' => __('auth.login_faild'),
        ];

        return ResponseHelper::make($data, __('auth.login_faild'),false,200);
    }

    public function registration(RegisterRequest $request)
    {
        $this->checkPhoneUnique($request->country_code, $request->phone);
        $this->checkPhoneLength($request->country_code, $request->phone);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->country_code.$request->phone;
        $user->password = bcrypt($request->password);
        $user->is_verified = $request->is_verified;
        $user->save();

        $this->saveTokens($user->id, $request->device_type, $request->device_token);

        $data = [
            'user' => User::find($user->id),
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ];

        return ResponseHelper::make($data);
    }

    public function sendWhatsappOtp(CheckPhoneRequest $request)
    {
        $otp = WhatsApp::SendOTP($request->phone);

        $data = [
            'otp' => strval($otp)
        ];
        return ResponseHelper::make($data, __('auth.otp_sent'));
    }

    public function userVerified() //auth
    {
        $user = auth('sanctum')->user();
        $user->is_verified = true;
        $user->save();

        $data = [
            'user' => $user,
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ];
        return ResponseHelper::make($data);
    }

    public function deleteMyAccount() //auth
    {
        $user = auth('sanctum')->user();
        Token::where('user_id', $user->id)->delete();
        $user->delete();

        return ResponseHelper::make(null, 'user deleted successfully', true, 200);
    }

    public function logout(Request $request) //auth
    {
        $request->validate([
            'device_token' => 'required'
        ]);
        $token = Token::where('device_token', $request->device_token)->where('admin_id',null)->first();
        if($token){
            $token->delete();
        }
        auth('sanctum')->user()->currentAccessToken()->delete();

        return ResponseHelper::make(null, 'user loged out successfully', true, 200);
    }

    public function forgetPassword(CheckPhoneRequest $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if(!$user){
            $data = [
                'message' => __('auth.login_faild')
            ];
            return ResponseHelper::make($data, __('auth.login_faild'),false,200);
        }

        ChangePasswordToken::where('user_id', $user->id)->delete();

        $new_token = new ChangePasswordToken();
        $new_token->user_id = $user->id;
        $new_token->token = Str::random(16);
        $new_token->save();

        $data = [
            'reset_password_token' => $new_token->token,
        ];
        return ResponseHelper::make($data, "dev:phone exist",true,200);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $tokenModel = ChangePasswordToken::where('token', $request->reset_password_token)->first();

        if($tokenModel){
            $user = User::where('id', $tokenModel->user_id)->first();
            if(!$user){
                $data = [
                    'message' => __('auth.login_faild')
                ];
                return ResponseHelper::make($data, __('auth.login_faild'),false,200);
            }
            $user->password = bcrypt($request->password);
            $user->save();    

            $tokenModel->delete();

            $data = [
                'user' => $user,
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ];
            return ResponseHelper::make($data, __('auth.password_updated'));    
        }

        return ResponseHelper::make(null, 'invalid reset_password_token', false, 200);    
    }

    public function changePassword(ChangePasswordRequest $request) //auth
    {
        $user = auth('sanctum')->user();
        if ($user && Hash::check($request->input('old_password'), $user->password)) {
            $user->password = bcrypt($request->password);
            $user->save();
        } else {
            return ResponseHelper::make(NULL, __('auth.login_faild'),false,200);
        }

        $data = [
            'user' => $user,
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ];
        return ResponseHelper::make($data, __('auth.password_updated'));
    }

    public function userInformation()
    {
        $user = auth('sanctum')->user();
        if(!$user){
            $data = [
                'message' => 'dev:user not authorized!'
            ];
            return ResponseHelper::make($data, 'dev:user not authorized!',false,200);
        }

        $phoneNumber = $user->phone;
        $countryCodes = ['+973', '+966', '+968', '+971', '+974', '+965', '+20'];
        $foundCountryCode = '';
        $foundPhone = '';
        foreach ($countryCodes as $code) {
            if (strpos($phoneNumber, $code) === 0) {
                $foundCountryCode = $code;
                $foundPhone = str_replace($code, '', $phoneNumber);
                break;
            }
        }

        $data = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $foundPhone,
                'country_code' => $foundCountryCode,
                'country_code_image' => asset(Country::where('phone_code', $foundCountryCode)->first()?->image),
                'is_verified' => $user->is_verified,
            ],
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ];
        return ResponseHelper::make($data);
    }

    public function updateUser(UpdateUserRequest $request)
    {
        $user = auth()->user();
        $user->name = $request->name;
        $user->phone = $request->phone;
        // $user->email = $request->email;
        $user->save();

        $data = [
            'user' => $user,
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ];
        return ResponseHelper::make($data, __('dash.alert_update'));
    }

    ////////////////////////////////////////////////////////////////////////////////////
    private static function checkPhoneLength($country_code, $phone)
    {
        $country = Country::where('phone_code', $country_code)->first();

        if(strlen($phone) != $country->length){
            $data = [
                'phone' => ['The phone number must be '.$country->length.' digits only.']
            ];
            return ResponseHelper::make($data, 'Validation errors',false,200);
        }

    }

    private static function checkPhoneUnique($country_code, $phone)
    {
        $check = User::Where('phone', $country_code.$phone)->first();
        if($check){
            $data = [
                'phone' => [__('auth.uniqe_phone_country_code')]
            ];
            return ResponseHelper::make($data, 'Validation errors',false,200);
        }
    }

    private static function saveTokens($user_id, $device_type, $device_token)
    {
        $token = new Token();
        $token->user_id = $user_id;
        $token->device_type = $device_type;
        $token->device_token = $device_token;
        $token->save();
    }

}
