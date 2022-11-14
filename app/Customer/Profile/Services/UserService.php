<?php


namespace App\Customer\Profile\Services;

use App\Customer\Accounts\Constants\AccountStatus;
use App\Customer\Accounts\Exceptions\AccountNotFoundException;
use App\Customer\Profile\Exceptions\InvalidUsernamePasswordException;
use App\Customer\Profile\Exceptions\UserAccountDeactivated;
use App\Customer\Profile\Exceptions\UserEmailAlreadyExistException;
use App\Customer\Profile\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Ramsey\Collection\Collection;

/**
 * @param UserRepository
 * @author Dieudonne Takougang
 * handle all business related services for a user
 */
class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $repository)
    {
        $this->userRepository = $repository;
    }

    /**
     * create a new customer (user)
     * @param array $profile
     * @return array
     */
    public function createUserProfile(array $profile)
    {
        //check if a user with the specified email already exist in the system, if yes, throw exception
        if ($this->userRepository->hasEmailAddress($profile['email'])) throw new UserEmailAlreadyExistException("A customer already exist with the specified email address. Try another email address");
        //create user account
        $profile['password'] = bcrypt($profile['password']);
        $profile['status'] = AccountStatus::$ACCOUNT_ACTIVE;
        return $this->userRepository->create($profile);
    }

    /**
     * login user to get access token
     * @param array $userDTO
     */
    public function login(array $userDTO)
    {
        //check if user email and password matched, if not throw exception
        if (!Auth::attempt($userDTO)) throw  new InvalidUsernamePasswordException("Invalid email and password combination");
        //check if the user account is active or deactivated, if yes, throw exception
        if (!$this->userRepository->isAccountActive($userDTO['email'])) throw new UserAccountDeactivated("User account is currently inactive and suspended. Kindly contact system admin");
        //login successful, get user details
        $user = $this->userRepository->findUserByEmail($userDTO['email']);
        $accessToken = $user->createToken('authToken')->plainTextToken;
        return ['userId' => $user->id, 'accessToken' => $accessToken, 'TokenType' => 'Bearer'];
    }

    /**
     * get customer  or user bank accounts
     * @param integer $customerId
     * @return Collection
     */
    public function getCustomerBankAccounts($customerId)
    {
        //check if customer exist, if no, throw an exception
        $customerExist = $this->userRepository->findUserById($customerId);
        if (empty($customerExist)) throw new AccountNotFoundException("Customer profile information not found for the specified id");
        return $customerExist->accounts;
    }
}
