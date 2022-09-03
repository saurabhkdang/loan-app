<?php

namespace App\Http\Controllers\API;

use App\Models\Emi;
use App\Models\User;
use App\Models\Application;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Resources\Common;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PaymentRequest;
use App\Http\Requests\ApplicationRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\LoanDetailResource;
use App\Http\Resources\TransactionHistory;

class FrontController extends Controller
{

    public function getAllLoanRequest() {
        $applications = Auth::user()->application()->get();
        $response = [
            'status' => true,
            'message' => '',
            'data' => LoanDetailResource::collection($applications)
        ];
        return Common::collection([collect($response)]);
    }

    public function loan_request(ApplicationRequest $request) {
        $payload = $request->json()->all();

        try {
            $res = Application::create([
                'user_id' => request()->user()->id,
                'loan_amount' => $payload['loan_amount'],
                'num_of_emis' => $payload['num_of_emis']
            ]);
            $response = [
                'status' => true,
                'message' => 'Loan request has been submitted successfully.',
                'data' => $res
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

    public function payment(PaymentRequest $request){
        $payload = $request->json()->all();

        try {
            $emi = Emi::where('id', $payload['emi_id'])->first();
            $emi->status = $payload['payment_status'];
            $emi->update();
            //Emi::where('application_id', $payload['application_id'])->where('id', $payload['emi_id'])->update(['status'=> $payload['payment_status']]);
            $payload['amount'] = ($emi->emi_amount + $emi->rate_of_interest);
            Transaction::create($payload);

            $response = [
                'status' => true,
                'message' => ($payload['payment_status']?"Payment has been done successfully.":"Something went wrong. Please try again later."),
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

    public function transaction_history(){
        $applications = Auth::user()->application()->get();
        $response = [
            'status' => true,
            'message' => '',
            'data' => TransactionHistory::collection($applications)
        ];
        return Common::collection([collect($response)]);
    }
}
