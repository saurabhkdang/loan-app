<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use App\Enums\PaymentStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionHistory extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $status = PaymentStatus::UNPAID;
        if($this->transaction->payment_status == 1)
        $status = PaymentStatus::PAID;
        elseif($this->transaction->payment_status == 2)
        $status = PaymentStatus::CANCELLED;

        return [
            'application_id' => $this->id,
            'amount_paid' => $this->transaction->amount,
            'payment_status' => $status,
            'paid_on' => Carbon::parse($this->transaction->created_at)->format('d M Y')
        ];
    }
}
