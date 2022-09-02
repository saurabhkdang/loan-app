<?php

namespace App\Http\Resources;

use App\Enums\Status;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $status = Status::PENDING;
        if($this->status == 1)
        $status = Status::APPROVED;
        elseif($this->status == 2)
        $status = Status::REJECTED;

        return [
            'application_id' => $this->id,
            'loan_amount' => $this->loan_amount,
            'num_of_emis' => $this->num_of_emis,
            'user_id' => $this->num_of_emis,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
            'emis' => $this->emi,
            'status' => $status,
        ];
        //return parent::toArray($request);
    }
}
