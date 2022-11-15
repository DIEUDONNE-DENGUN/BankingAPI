<?php


namespace App\Customer\Transfers\Services;


use App\Customer\Accounts\Exceptions\AccountNotFoundException;
use App\Customer\Accounts\Repositories\AccountRepository;
use App\Customer\Transfers\Constants\TransactionType;
use App\Customer\Transfers\Constants\Transfer_Type;
use App\Customer\Transfers\Events\TransactionCreated;
use App\Customer\Transfers\Exceptions\InsufficientBalanceException;
use App\Customer\Transfers\Repositories\TransferRepository;
use App\Customer\Transfers\Requests\TransactionTransferDTO;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @param $transferRepository
 * @param $accountRepository ;
 * @author Dieudonne Takougang
 * handle all business logic for the transfer domain
 */
class TransferService
{
    protected $transferRepository;
    protected $accountRepository;

    public function __construct(TransferRepository $repository, AccountRepository $accountRepository)
    {
        $this->transferRepository = $repository;
        $this->accountRepository = $accountRepository;
    }

    /**
     * Transaction funds from one account to another
     * @param array $transfer
     * @return boolean
     */
    public function transferBetweenAccounts(array $transfer)
    {
        $transferStatus = false;
        //put the entire process in a transaction for easy database rollback
        DB::transaction(function () use ($transfer,  &$transferStatus) {
            //get sender's account if exist
            $senderAccount = $this->getBankAccountById($transfer['sender_id']);
            //get receiver's account if exist
            $receiverAccount = $this->getBankAccountById((string)$transfer['receiver_id']);

            //check if the sender has sufficient funds to make the transfer
            $this->canTransferAmount($senderAccount, (double)$transfer['amount']);
            //set the transaction id and type to the transfer DTO
            $transfer['transaction_id'] = Str::uuid();
            $transfer['transfer_type'] = $this->getTransactionType($transfer['sender_id'], $receiverAccount);
            //save the transfer request
            $bankTransfer = $this->transferRepository->create($transfer);
            if (!empty($bankTransfer)) {
                //credit the receiver account and debit sender account
                $hasCreditedAccount = $this->creditBankAccount($transfer['receiver_id'], $receiverAccount->account_balance, (double)$transfer['amount']);
                if ($hasCreditedAccount) {
                    $this->debitBankAccount($transfer['sender_id'], $senderAccount->account_balance, (double)$transfer['amount']);
                    $transferStatus = true;
                }
            }
        });
        return $transferStatus;
    }

    /**
     * Get a paginated account transfer history
     * @param $accountId
     * @return \Illuminate\Support\Collection
     */
    public function getAccountTransferHistory($accountId,$pageSize)
    {
        //validate if bank account exist
        $this->getBankAccountById($accountId);
        //get transfer history
        return $this->transferRepository->transferHistory($accountId,$pageSize);
    }

    /**
     * verify if  user bank account balance with a given amount is eligible to make a transfer
     * @param Model $account
     * @param integer $amount
     */
    private function canTransferAmount(Model $account, $amount)
    {
        //throw exception is user account balance is less than the transfer amount
        if ($account->account_balance < $amount) throw new InsufficientBalanceException("Whoops! Insufficient account balance on sender's account to make transfer");
    }

    /**
     * get transfer type by verify if the sender is the owner of the receiver account or not
     * @param integer $senderId
     * @param Model $receiverAccount
     * @return string
     */
    private function getTransactionType($senderId, $receiverAccount)
    {
        $type = Transfer_Type::$FUND_BANK_USER_ACCOUNT;
        if ($this->accountRepository->customerHasAccount($senderId, $receiverAccount)) $type = Transfer_Type::$FUND_USER_OWN_ACCOUNT;
        return $type;
    }

    /**
     * get a user bank account
     * @param integer $accountId
     * @return Model
     */
    private function getBankAccountById($accountId)
    {
        $account = $this->accountRepository->findAccountById($accountId);
        if (empty($account) || is_null($account)) throw new  AccountNotFoundException("Bank account does not exist.Not found entity");
        return $account;
    }

    /**
     * credit a user bank account with a given amount
     * @param integer $accountId
     * @param integer $amount
     * @param integer $currentBalance
     * @return boolean
     */
    private function creditBankAccount($accountId, $currentBalance, $amount)
    {
        $status = false;
        $effectedBalance = (double)$amount + (double)$currentBalance;
        $updateBankAccountBalance = ['account_balance' => $effectedBalance];
        $creditAccount = $this->accountRepository->update($updateBankAccountBalance, $accountId);
        if ($creditAccount) {
            $status = true;
            //trigger transaction log event
            $this->triggerTransactionLog($accountId, $currentBalance, $effectedBalance, TransactionType::$CREDIT_TRANSACTION, $amount);
        }
        return $status;
    }


    /**
     * debit operation on a user bank account with a given amount
     * @param integer $accountId
     * @param integer $amount
     * @param integer $currentBalance
     * @return boolean
     */
    private function debitBankAccount($accountId, $currentBalance, $amount)
    {
        $status = false;
        $effectedBalance = (double)$currentBalance - (double)$amount;
        $updateBankAccountBalance = ['account_balance' => $effectedBalance];
        $creditAccount = $this->accountRepository->update($updateBankAccountBalance, $accountId);
        if ($creditAccount) {
            $status = true;
            //trigger transaction log event
            $this->triggerTransactionLog($accountId, $currentBalance, $effectedBalance, TransactionType::$DEBIT_TRANSACTION, $amount);
        }
        return $status;
    }

    /**
     * handle the logging of transactions done on the portal, decouple the logging from transferservice logic
     * @param $accountId
     * @param $previousBalance
     * @param $currentBalance
     * @param $type
     * @param $amount
     */

    private function triggerTransactionLog($accountId, $previousBalance, $currentBalance, $type, $amount)
    {
        //raise a transaction log event to save the transaction details done
        $transactionLogDTO = new TransactionTransferDTO($accountId, $amount, $previousBalance, $currentBalance, $type);
        event(new TransactionCreated($transactionLogDTO));
    }

}
