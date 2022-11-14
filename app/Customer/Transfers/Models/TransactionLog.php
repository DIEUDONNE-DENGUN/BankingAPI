<?php


namespace App\Customer\Transfers\Models;


class TransactionLog extends \Illuminate\Database\Eloquent\Model
{
    protected $table = "transaction_logs";
    protected $fillable = ['account_id', 'previous_balance', 'current_balance', 'amount', 'transaction_type'];
}
