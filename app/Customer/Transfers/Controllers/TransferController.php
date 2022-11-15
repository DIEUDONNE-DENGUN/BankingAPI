<?php


namespace App\Customer\Transfers\Controllers;

use App\Customer\Accounts\Services\AccountService;
use App\Customer\Transfers\Requests\TransferRequest;
use App\Customer\Transfers\Responses\TransferResponse;
use App\Customer\Transfers\Services\TransferService;
use \App\Http\Controllers\Controller;
use http\Env\Response;
use Illuminate\Http\Request;

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
        $account = $accountService->findCustomerBankAccountByAccountNumber($accountNumber);
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
    public function getAccountTransferHistory($customerId, $accountId, Request $request)
    {
        //check if page size is part of the request
        $pageSize = !empty($request->get('pageSize')) ? $request->get('pageSize') : 10;
        $transfers = $this->transferService->getAccountTransferHistory($accountId, $pageSize);
        //check if any transfers exist
        if ($transfers->isEmpty()) return response()->json(['resource' => 'transfers', 'resourceUrl' => $request->path(), 'message' => "Bank account doesn't currently have any bank transfers yet", 'data' => []]);
        //transfer available, build and map response and send
        $response = ['data' => TransferResponse::collection($transfers)];
        return response()->json($response);
    }
}
