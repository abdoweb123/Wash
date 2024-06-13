<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddressRequest extends FormRequest
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
            'region_id' => 'required|exists:regions,id',
            'block' => 'required|max:255',
            'road' => 'required|max:255',
            'floor_no' => 'required|max:255',
            'appartment_no' => 'required|max:255',
            'note' => 'nullable|max:255',
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
