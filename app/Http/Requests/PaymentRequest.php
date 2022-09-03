<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaymentRequest extends FormRequest
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
            'application_id' => ['required'],
            'emi_id' => ['required'],
            'payment_status' => ['required'],
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
