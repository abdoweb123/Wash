<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:255',
            'email' => 'nullable|min:3|max:255|email:rfc,dns|unique:users,email',
            'country_code' => 'required|exists:countries,phone_code',
            'phone' => 'required',
            'password' => 'required|confirmed',
            'device_type' => 'required',
            'device_token' => 'required',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'msg' => 'Validation errors',
            'statusCode' => 200,
            'success'   => false,
            'payload'      => $validator->errors()
        ], 200));
    }
}
