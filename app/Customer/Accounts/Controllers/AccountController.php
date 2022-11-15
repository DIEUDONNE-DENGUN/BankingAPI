<?php


namespace App\Customer\Accounts\Controllers;

use App\Customer\Accounts\Requests\CreateAccountRequest;
use App\Customer\Accounts\Requests\UpdateAccountRequest;
use App\Customer\Accounts\Responses\AccountResponse;
use App\Customer\Accounts\Services\AccountService;
use \App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @param AccountService $accountService
 * @package App\Customer\Accounts\Controllers
 * @author Dieudonne Takougang
 * Handle controller account related business processing and delegation to core business service layer
 */
class AccountController extends Controller
{
    private $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * @param CreateAccountRequest $request
     *
     */
    public function createBankAccount($customerId,CreateAccountRequest $request)
    {
        //create bank account
        $accountDto=$request->getAccountDTO();
        $accountDto['customer_id']=$customerId;
        $account = $this->accountService->createCustomerBankAccount($accountDto);
        //map the created model to a response
        $accountResponse = new AccountResponse($account);
        // send back mapped response
        return response()->json($accountResponse, 201);
    }

    /**
     * get bank account details by id
     * @param $accountId
     * @return
     */
    public function getBankAccountById($accountId)
    {
        $account = $this->accountService->findCustomerBankAccountById($accountId);
        $accountResponse = new AccountResponse($account);
        return response()->json($accountResponse);
    }

    /**
     * get account balance for a specific account
     * @param integer $accountId
     */
    public function getBankAccountBalance($accountId, Request $request)
    {
        $account = $this->accountService->findCustomerBankAccountById($accountId);
        $balanceResponse = ['resource' => 'balance', 'resourceUrl' => $request->path(), 'data' => ['accountBalance' => number_format($account->account_balance,2)]];
        return response()->json($balanceResponse);
    }

    /**
     * update customer bank account details
     * @param integer $accountId
     */
    public function updateBankAccountDetails($accountId, UpdateAccountRequest $request)
    {
        $this->accountService->updateCustomerBankAccount($accountId, $request->getAccountDTO());
        return response()->json('', 204);
    }
}
