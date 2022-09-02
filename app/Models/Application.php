<?php

namespace App\Models;

use App\Models\Emi;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'loan_amount',
        'num_of_emis'
    ];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function emi(){
        return $this->hasMany(Emi::class);
    }

    public function transaction(){
        return $this->hasOne(Transaction::class);
    }
}
