<?php


namespace App\Customer\Accounts\Services;

use App\Customer\Accounts\Constants\AccountStatus;
use App\Customer\Accounts\Exceptions\AccountNotFoundException;
use App\Customer\Accounts\Repositories\AccountRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @param
 * @param AccountRepository $accountRepository
 * @author Dieudonne Takougang
 * manage all business logic for customer bank accounts like creating, editing and delete
 * @package App\Customer\Accounts\Services
 */
class AccountService
{
    protected $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * create customer bank account
     * @param array $account
     * @return array
     */
    public function createCustomerBankAccount(array $account)
    {
        $account['account_number'] = $this->generateCustomerBankAccountNumber();
        $account['account_status'] = AccountStatus::$ACCOUNT_ACTIVE;
        return $this->accountRepository->create($account);
    }

    /**
     * find customer account details by id
     * @param integer $accountId
     * @return Model
     */
    public function findCustomerBankAccountById($accountId)
    {
        $accountExist = $this->accountRepository->findAccountById($accountId);
        //if account does not exist, throw an account not found exception
        if (empty($accountExist) || is_null($accountExist)) throw new  AccountNotFoundException("Bank Account not found for the specified account id");
        //if not return the account details
        return $accountExist;
    }

    /**
     * find customer account details by id
     * @param string $accountNumber
     * @return Model
     */
    public function findCustomerBankAccountByAccountNumber($accountNumber)
    {
        $accountExist = $this->accountRepository->findAccountByNumber($accountNumber);
        if (empty($accountExist) || is_null($accountExist)) throw new  AccountNotFoundException("Bank Account not found for the specified account number");
        return $accountExist;
    }

    /**
     * update customer bank account details
     * @param integer accountId
     * @param array $account
     * @return integer
     */
    public function updateCustomerBankAccount($accountId, array $account)
    {
        //check if the specific account exist, if yes, update the resource
        if (!empty($this->findCustomerBankAccountById($accountId))) return $this->accountRepository->update($account, $accountId);
    }

    /**
     * generate a random bank account number for a account creation request
     * @return string
     */
    private function generateCustomerBankAccountNumber()
    {
        $currentYear = date('Y');
        $countryBankCode = Str::random(4);
        $branchId = rand(1000, 2000);
        return $currentYear . $branchId . strtoupper($countryBankCode);
    }
}
