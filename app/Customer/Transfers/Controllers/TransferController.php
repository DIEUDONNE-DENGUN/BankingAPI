<?php


namespace App\Customer\Transfers\Controllers;

use App\Customer\Accounts\Services\AccountService;
use App\Customer\Transfers\Requests\TransferRequest;
use App\Customer\Transfers\Services\TransferService;
use \App\Http\Controllers\Controller;

/**
 * @param $transferService
 * @package App\Customer\Transfers\Controllers
 * @author Dieudonne Takougang
 * Class TransferController
 */
class TransferController extends Controller
{
    protected $transferService;

    public function __construct(TransferService $transferService)
    {
        $this->transferService = $transferService;
    }

    /**
     * Transfer money from a sender to a receiver
     * @param
     * @return
     */
    public function transferMoney($customerId, $accountId, TransferRequest $request, AccountService $accountService)
    {
        $transferDto = $request->getTransferDTO();
        $accountNumber = $transferDto['receiver_id'];
        //find the receiver account by account number
        $account = $accountService->findCustomerBankAccountByAccountNumber($transferDto['account_id']);
        $transferDto['receiver_id'] = $account->id;
        $transferDto['sender_id'] = $accountId;
        //make account to account transfer
        if ($this->transferService->transferBetweenAccounts($transferDto)) {
            $responseTransfer = ['resource' => 'transfer', 'resourceUrl' => $request->path(), 'message' => "You have successfully transferred " . $transferDto['amount'] . " to the bank account: " . $accountNumber];
            return response()->json($responseTransfer);
        }
        return response()->json(['resource' => 'transfer', 'resourceUrl' => $request->path(), 'message' => "Whoops!, An error was encountered while making transfer request. All transactions rollback"], 500);
    }

    /**
     * Get a paginated list of a customer given account transfer history
     *
     * @param $customerId
     * @param $accountId
     */
    public function getAccountTransferHistory($customerId, $accountId)
    {
        $transfers = $this->transferService->getAccountTransferHistory($accountId);
    }
}
