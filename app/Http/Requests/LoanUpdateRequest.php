<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoanUpdateRequest extends FormRequest
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
        $rules = [
            'status' => ['required'],
            'loan_application_id' => ['required'],
        ];

        /* if (Request::input('status') == 2) {
            $rules['reject_reason'] = ['required'];
        } */
        
        if (request('status') == 2) {
            $rules['reject_reason'] = ['required'];
        }

        return $rules;
    }

    public function failedValidation(Validator $validator)
    {
        /* $validator->sometimes('reject_reason', 'required', function ($input, $item) {
            //dd($input->__get('status'));
            return $input->__get('status') == "2";
        }); */

        $response = [
            'status' => false,
            'message' => 'Validation Error',
            'data' => $validator->errors()
        ];
        throw new HttpResponseException(response()->json($response));
    }

    public function attributes(){
        return [
            'status',
            'loan_application_id'
        ];
    }
}
