<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Mail\WelcomeEmail;
use Illuminate\Http\Request;
use App\Http\Resources\Common;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function register(Request $request) {
        $payload = $request->json()->all();

        $validator = Validator::make($payload, [
            'name' => ['required'],
            'email' => ['required', 'email','unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);
 
        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->messages()
            ];
            return Common::collection([collect($response)]);
            //return response()->json(['status' => 403, "data" => $validator->messages() ]);
        }

        $payload['password'] = Hash::make($payload['password']);
        $payload['role'] = 2;
       
        try {
            $res = User::create($payload);
            $res->sendWelcomeEmail();
            $response = [
                'status' => true,
                'message' => 'User has been registered successfully.'
            ];
            return Common::collection([collect($response)]);
        } catch (\Illuminate\Database\QueryException $ex) {
            $response = [
                'status' => false,
                'message' => $ex->getMessage()
            ];
            return Common::collection([collect($response)]);
        } 
    }

    public function login(Request $request) {
        $payload = $request->json()->all();

        $validator = Validator::make($payload, [
            'email' => ['required', 'email','exists:users'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->messages()
            ];
            return Common::collection([collect($response)]);
        }

        if(Auth::attempt(['email' => $payload['email'], 'password' => $payload['password']])){
            $response = [
                'status' => true,
                'message' => 'Use this token for further API calls',
                'data' => ['token' => $request->user()->createToken('loan_app')->plainTextToken]
            ];
            return Common::collection([collect($response)]);
        }else{
            $response = [
                'success' => false,
                'message' => "Invalid credentials"
            ];
            return response()->json($response, 201);
            $response = [
                'status' => faslse,
                'message' => 'Invalid credentials',
            ];
            return Common::collection([collect($response)]);
        }
    }

    
}
