<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApplicationRequest extends FormRequest
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
            'loan_amount' => ['required','gte:500000','lte:5000000'],
            'num_of_emis' => ['required', 'gte:24','lte:60'],
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
