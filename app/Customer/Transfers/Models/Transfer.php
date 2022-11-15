<?php

namespace App\Customer\Transfers\Models;


use App\Customer\Accounts\Models\Account;

class Transfer extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'amount', 'transaction_id', 'description',
        'transfer_type'];

    public function account()
    {
        return $this->belongsTo(Account::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Account::class, 'receiver_id');
    }
}
