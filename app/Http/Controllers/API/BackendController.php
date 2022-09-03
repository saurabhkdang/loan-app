<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Emi;
use App\Models\User;
use App\Mail\LoanDetails;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Resources\Common;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\LoanUpdateRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\LoanDetailResource;

class BackendController extends Controller
{
    public function getLoanRequest() {
        $loans = Application::select('id','loan_amount','num_of_emis','user_id','status')->with('user:id,name,email')->get();
        $response = [
            'status' => true,
            'message' => '',
            'data' => LoanDetailResource::collection($loans)
        ];
        return Common::collection([collect($response)]);
    }

    public function updateLoanStatus(LoanUpdateRequest $request){
        $payload = $request->json()->all();

        /* $required = [
            'status' => ['required'],
            'loan_application_id' => ['required'],
        ];

        if($payload['status'] && $payload['status'] == 2){
            $required['reject_reason'] = 'required';
        }

        $validator = Validator::make($payload, $required);
 
        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->messages()
            ];
            return Common::collection([collect($response)]);
        } */

        try {
            $loanApplication = Application::select('id','loan_amount','num_of_emis','user_id')->where('id',$payload['loan_application_id'])->with('user:id,name,email')->first();
            
            $loanApplication->status = $payload['status'];
            if($payload['status'] == 2){
                $loanApplication->reject_reason = $payload['reject_reason'];
            }

            $loanApplication->save();
            $data = [];
            $data['emis'] = [];
            $data['name'] = $loanApplication->user->name;
            if($payload['status']==1) {
                $tenure = $loanApplication->num_of_emis;
                $loan_amount = $loanApplication->loan_amount;
                $interest = 10;
                $amountWithInterest = $loan_amount + (($loan_amount*$interest)/100);
                
                $emi = $loan_amount / $tenure;
                for ($i=1; $i <=$tenure ; $i++) { 
    
                    $interestAmount = ($emi / $interest);
                    $remaining = $amountWithInterest - $emi - $interestAmount;
                    $amountWithInterest = $remaining;
    
                    $emiDetails = [
                        'application_id' => $loanApplication->id,
                        'emi_number' => $i,
                        'emi_amount' => number_format($emi,2,'.',''),
                        'rate_of_interest' => number_format($interestAmount,2, '.',''),
                        'remaining_amount' => number_format($remaining,2,'.',''),
                        'status' => 0,
                    ];
   
                    Emi::insert($emiDetails);
                }
                
                $res = Emi::selectRaw('(remaining_amount+emi_amount+rate_of_interest) as outstanding')->where('application_id',$payload['loan_application_id'])->where('status',0)->orderby('id','ASC')->get()->first();
                $outstanding = $res->outstanding;

                $data['emis'] = [
                    'creation_date' => Carbon::parse($loanApplication->updated_at)->format('d M Y'),
                    'finish_date' => Carbon::parse($loanApplication->updated_at)->addMonths($loanApplication->num_of_emis)->format('d M Y'),
                    'total_emis' => $tenure,
                    'loan_amount' => number_format($loan_amount,2, '.',''),
                    'pending_emis' => Emi::where('application_id',$payload['loan_application_id'])->where('status',0)->count(),
                    'outstanding_amount' => number_format($outstanding,2, '.',''),
                    'monthly_emi_amount' => number_format($emi,2, '.','')
                ];
            } else {
                $data['reject_reason'] = $loanApplication->reject_reason;
            }
            
            Mail::to($loanApplication->user->email,$loanApplication->user->name)->queue(new LoanDetails($data));

            $response = [
                'status' => true,
                'message' => 'Loan Status updated successfully.',
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
}
