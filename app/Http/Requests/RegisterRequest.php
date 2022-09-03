<?php

namespace App\Http\Requests;

use App\Http\Resources\Common;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email','unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $response = [
            'status' => false,
            'message' => 'Validation Error',
            'data' => $validator->errors()
        ];
        throw new HttpResponseException(response()->json($response));
    }
}
