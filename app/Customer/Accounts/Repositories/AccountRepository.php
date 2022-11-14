<?php


namespace App\Customer\Accounts\Repositories;

use App\Customer\Accounts\Models\Account;
use \App\Customer\Commons\BaseRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Dieudonne Takougang
 *  AccountRepository to handle all data layer crud operations
 * @param Model $model
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
}
