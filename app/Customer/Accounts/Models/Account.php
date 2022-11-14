<?php


namespace App\Customer\Accounts\Models;


use App\Customer\Profile\Models\User;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['account_name', 'account_number', 'currency',
        'country', 'customer_id', 'account_type', 'account_balance', 'account_status'];

    public function customer()
    {
        return $this->belongsTo(User::class,'customer_id');
    }
}
