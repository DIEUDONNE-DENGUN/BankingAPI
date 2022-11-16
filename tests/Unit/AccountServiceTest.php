<?php

namespace Tests\Unit;

use App\Customer\Accounts\Constants\AccountType;
use App\Customer\Accounts\Exceptions\AccountNotFoundException;
use App\Customer\Accounts\Models\Account;
use App\Customer\Accounts\Repositories\AccountRepository;
use App\Customer\Accounts\Services\AccountService;
use Tests\TestCase;

class AccountServiceTest extends TestCase
{
    /**
     * Test the create bank account service function
     *
     * @return void
     */
    public function test_create_customer_bank_account()
    {
        //set up the service dependencies and the request dto
        $accountRepository = new AccountRepository(new Account());
        $accountService = new AccountService($accountRepository);
        $accountDTO = ['account_name' => 'Test Account', 'currency' => 'USD', 'country' => 'US',
            'account_type' => AccountType::$SAVING_ACCOUNT, 'account_balance' => 10000, 'customer_id' => 1];
        $addAccount = $accountService->createCustomerBankAccount($accountDTO);
        //check if the account was created
        $this->assertInstanceOf(Account::class, $addAccount);
        $this->assertEquals($accountDTO['account_name'], $addAccount->account_name);
        $this->assertEquals($accountDTO['account_balance'], $addAccount->account_balance);
    }

    /**
     * get customer account details by id when exist
     *
     */
    public function test_get_customer_bank_account_exist()
    {

        //set up the service dependencies and the request dto
        $accountRepository = new AccountRepository(new Account());
        $accountService = new AccountService($accountRepository);
        $accountId = 1;
        $accountExist = $accountService->findCustomerBankAccountById($accountId);
        //test the cases (check if account exist and if the account id is the same as that entered)
        $this->assertInstanceOf(Account::class, $accountExist);
        $this->assertEquals(1, $accountExist->id);
    }

    /**
     * get customer account details by id where not exist, expect AccountNotFoundException
     */

    public function test_get_customer_bank_account_not_found()
    {
        //set up the service dependencies and the request dto
        $accountRepository = new AccountRepository(new Account());
        $accountService = new AccountService($accountRepository);
        $accountId = 100;
        //test the cases (check if account exist and if the account id is the same as that entered)
        $this->expectException(AccountNotFoundException::class);
        $this->expectExceptionMessage("Bank Account not found for the specified account id");
        $accountService->findCustomerBankAccountById($accountId);
    }

    /**
     * test get customer account balance by account id
     */
    public function test_get_customer_account_balance()
    {
        //set up the service dependencies and the request dto
        $accountRepository = new AccountRepository(new Account());
        $accountService = new AccountService($accountRepository);
        $accountId = 1;
        $initialAccountDeposit = (double)10000;
        $account = $accountService->findCustomerBankAccountById($accountId);
        $this->assertEquals($initialAccountDeposit, $account->account_balance);
    }
}
