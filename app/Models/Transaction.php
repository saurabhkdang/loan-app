<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'emi_id',
        'amount',
        'payment_status'
    ];

    /* public function application(){
        return $this->hasOne(Application::class,'id','application_id');
    } */
}
