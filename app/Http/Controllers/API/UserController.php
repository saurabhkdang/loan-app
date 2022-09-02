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
            return Common::collection( json_encode($response));
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
            return Common::collection($response);
            //return response()->json(['status' => 200, "message" => "User has been registered successfully."]);
        } catch (\Illuminate\Database\QueryException $ex) {
            $response = [
                'success' => false,
                'message' => $ex->getMessage()
            ];
            return response()->json($response, 201);
        } 
    }

    public function login(Request $request) {
        $payload = $request->json()->all();

        $validator = Validator::make($payload, [
            'email' => ['required', 'email','exists:users'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 403, "data" => $validator->messages() ]);
        }

        if(Auth::attempt(['email' => $payload['email'], 'password' => $payload['password']])){
            $response = [
                'success' => true,
                'message' => "Use this token for further API calls",
                'token' => $request->user()->createToken('loan_app')->plainTextToken
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'success' => false,
                'message' => "Invalid credentials"
            ];
            return response()->json($response, 201);
        }
    }

    
}
