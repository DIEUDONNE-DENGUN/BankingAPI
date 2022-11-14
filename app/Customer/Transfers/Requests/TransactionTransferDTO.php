<?php


namespace App\Customer\Transfers\Requests;


class TransactionTransferDTO
{
    private $accountId;
    private $amount;
    private $previousBalance;
    private $currentBalance;
    private $transactionType;

    /**
     * TransactionTransferDTO constructor.
     * @param $accountId
     * @param $amount
     * @param $previousBalance
     * @param $currentBalance
     * @param $transactionType
     */
    public function __construct($accountId, $amount, $previousBalance, $currentBalance, $transactionType)
    {
        $this->accountId = $accountId;
        $this->amount = $amount;
        $this->previousBalance = $previousBalance;
        $this->currentBalance = $currentBalance;
        $this->transactionType = $transactionType;
    }

    /**
     * @return mixed
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function getPreviousBalance()
    {
        return $this->previousBalance;
    }

    /**
     * @return mixed
     */
    public function getCurrentBalance()
    {
        return $this->currentBalance;
    }

    /**
     * @return mixed
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }


}
