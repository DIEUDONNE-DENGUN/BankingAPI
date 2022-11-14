<?php


namespace App\Customer\Transfers\Listeners;


use App\Customer\Transfers\Events\TransactionCreated;
use App\Customer\Transfers\Repositories\TransferRepository;

class RecordTransactionLog
{
    protected $transferRepository;

    public function __construct(TransferRepository $transferRepository)
    {
        $this->transferRepository = $transferRepository;
    }

    /**
     * Handle the event.
     *
     * @param TransactionCreated $event
     * @return void
     */
    public function handle(TransactionCreated $event)
    {
        $data = $event->transactionDTO;
        $transactionLog = ['account_id' => $data->getAccountId(), 'previous_balance' => $data->getPreviousBalance(),
            'current_balance' => $data->getCurrentBalance(), 'amount' => $data->getAmount(), 'transaction_type' => $data->getTransactionType()];

        //persist data to the data layer
        $this->transferRepository->createTransactionLog($transactionLog);
    }
}
