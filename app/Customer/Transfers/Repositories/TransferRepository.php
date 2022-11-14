<?php


namespace App\Customer\Transfers\Repositories;

use \App\Customer\Commons\BaseRepository;
use App\Customer\Transfers\Models\TransactionLog;
use App\Customer\Transfers\Models\Transfer;
use Illuminate\Database\Eloquent\Model;

class TransferRepository extends BaseRepository
{
    public function __construct(Transfer $model)
    {
        parent::__construct($model);
    }

    /**
     * custom method to declare the associated models to transfer model to be returned during findById
     * @param integer $transferId
     * @return Model
     */
    public function findAccountById($transferId)
    {
        $relationships = ['account'];
        return parent::findById($transferId, $relationships);
    }

    /**
     * save transaction log
     * @param array $transaction
     */
    public function createTransactionLog(array $transaction)
    {
        return TransactionLog::create($transaction);
    }

    /**
     * get a paginated list of user bank account transfer history
     */
    public function transferHistory($accountId)
    {
        $pageSize = 1;
        return $this->model->where('sender_id', $accountId)->paginate($pageSize);
    }
}
