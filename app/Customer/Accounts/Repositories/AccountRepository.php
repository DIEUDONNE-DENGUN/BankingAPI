<?php


namespace App\Customer\Accounts\Repositories;

use App\Customer\Accounts\Models\Account;
use \App\Customer\Commons\BaseRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * @param Model $model
 * @author Dieudonne Takougang
 *  AccountRepository to handle all data layer crud operations
 * @package App\Customer\Accounts\Repositories
 */
class AccountRepository extends BaseRepository
{
    public function __construct(Account $model)
    {
        parent::__construct($model);
    }

    /**
     * custom method to declare the associated models to account model to be returned during findById
     * @param integer $accountId
     * @return Model
     */
    public function findAccountById($accountId)
    {
        $relationships = ['customer'];
        return parent::findById($accountId, $relationships);
    }

    /**
     * find user bank account by account number
     * @param $accountNumber
     * @return Model
     */
    public function findAccountByNumber($accountNumber)
    {
        return $this->model->where('account_number', $accountNumber)->first();
    }

    /**
     * check if an account id belongs to a given customer or user
     * @param Model $account
     * @param integer $customerId
     * @return boolean
     */
    public function customerHasAccount($customerId, $account)
    {
        return $account->customer_id == $customerId;
    }
}
