<?php

namespace Tests\Unit;

use App\Customer\Profile\Exceptions\UserEmailAlreadyExistException;
use App\Customer\Profile\Models\User;
use App\Customer\Profile\Repositories\UserRepository;
use App\Customer\Profile\Services\UserService;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class CustomerProfileServiceTest extends TestCase
{
    /**
     * test create a customer profile account
     *
     * @return void
     */
    public function test_create_customer_profile()
    {
        $userRepository = new UserRepository(new User());
        $userService = new UserService($userRepository);
        $createCustomerProfileRequest = ['name' => "Test User Profile", 'phone_number' => "23767308946", 'email' => 'test1@email.com',
            'password' => '12345678', 'customer_type' => "Individual"];
        $userAccount = $userService->createUserProfile($createCustomerProfileRequest);
        //test the cases if account was created
        $this->assertInstanceOf(User::class, $userAccount);
        $this->assertEquals($createCustomerProfileRequest['email'], $userAccount->email);
    }

    /**
     * test a user email already exist while creating a user account
     */
    public function test_create_customer_profile_email_exist_exception()
    {
        $userRepository = new UserRepository(new User());
        $userService = new UserService($userRepository);
        $createCustomerProfileRequest = ['name' => "Test User Profile", 'phone_number' => "23767308946", 'email' => 'test@email.com',
            'password' => '12345678', 'customer_type' => "Individual"];
        //test the cases
        $this->expectException(UserEmailAlreadyExistException::class);
        $this->expectExceptionMessage("A customer already exist with the specified email address. Try another email address");
        //run the query
        $userService->createUserProfile($createCustomerProfileRequest);
    }

    /**
     * test if a valid user created account can login with email and password combination
     */
    public function test_login_customer_profile()
    {
        $userRepository = new UserRepository(new User());
        $userService = new UserService($userRepository);
        $loginDTO = ['email' => 'test@email.com', 'password' => '12345678'];
        $accessToken = $userService->login($loginDTO);
        //test the cases if the email and password are correct
        $this->assertIsArray($accessToken);
        $this->assertEquals("Bearer", $accessToken['TokenType']);
    }

    /**
     * test get all customer (user) banks accounts created by user id
     */
    public function test_get_customer_bank_accounts()
    {
        $userRepository = new UserRepository(new User());
        $userService = new UserService($userRepository);
        $customerId=1;
        $bankAccounts = $userService->getCustomerBankAccounts($customerId);
        //test the cases
        $this->assertInstanceOf(Collection::class,$bankAccounts);
    }
}
